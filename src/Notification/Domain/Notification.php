<?php

declare(strict_types=1);

namespace App\Notification\Domain;

use App\Notification\Domain\Exception\InvalidArgumentException;
use App\Notification\Domain\Exception\NotFoundException;
use App\Notification\Domain\Provider\Provider;
use App\Notification\Domain\Retry\RetryStrategy;
use App\Notification\Domain\SettleFailture\ChangeOfProvider;
use App\Notification\Domain\SettleFailture\NotificationFailed;
use App\Notification\Domain\SettleFailture\NotificationToRetry;
use App\Notification\Domain\SettleFailture\SettleFailureResult;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Uid\Uuid;

class Notification
{
    /**
     * @var Collection<Call>
     */
    private Collection $calls;

    private Status $status = Status::NEW;
    /**
     * @var string[]
     */
    private array $channels;

    /**
     * @param Channel[] $channels
     */
    public function __construct(
        private Uuid $id,
        private Uuid $customerId,
        array $channels,
        private string $message,
    ) {
        if (! $channels) {
            throw new InvalidArgumentException('At least one channel is required');
        }

        $this->channels = array_map(fn (Channel $channel) => $channel->value, $channels);

        $this->calls = new ArrayCollection();
    }

    /**
     * @return CallData[]
     */
    public function getCalls(): array
    {
        return array_map(
            fn (Call $call) => new CallData($call->getId(), $call->getChannel(), $call->getProvider(), $this->message),
            $this->calls->toArray(),
        );
    }

    public function create(Channel $channel, Provider $provider, ?\DateTimeImmutable $scheduledAt = null): Uuid
    {
        if (! in_array($channel->value, $this->channels)) {
            throw new InvalidArgumentException(\sprintf('%s channel has not been initialised', $channel->value));
        }

        $this->calls[] = new Call($callId = Uuid::v4(), $this, $channel, $provider, $scheduledAt);
        return $callId;
    }

    /**
     * @return Channel[]
     */
    public function getChannels(): array
    {
        return array_map(fn (string $channel) => Channel::from($channel), $this->channels);
    }

    public function startDelivery(Uuid $callId): void
    {
        $this->getCall($callId)->start();
        if ($this->status === Status::NEW) {
            $this->status = Status::PENDING;
        }
    }

    public function getProvider(Uuid $callId): Provider
    {
        return $this->getCall($callId)->getProvider();
    }


    public function getChannel(Uuid $callId): Channel
    {
        return $this->getCall($callId)->getChannel();
    }

    public function complete(Uuid $callId): void
    {
        $this->getCall($callId)->complete();

        if (! array_filter($this->calls->toArray(), fn (Call $call) => $call->getStatus() !== CallStatus::COMPLETED)) {
            $this->status = Status::COMPLETED;
        }
    }

    public function getStatus(): Status
    {
        return $this->status;
    }

    /**
     * @param Provider[] $providers
     */
    public function settleFailure(Uuid $callId, array $providers, RetryStrategy $retryStrategy): SettleFailureResult
    {
        $call = $this->getCall($callId);
        $call->fail();

        if ($differentProvider = $this->findDifferentProvider($providers)) {
            return new ChangeOfProvider($this->create($call->getChannel(), $differentProvider));
        }

        if ($retryStrategy->retryAllowed($providers, $this->getCallsByChannel($call->getChannel()))) {
            $this->create($call->getChannel(), $call->getProvider(), $retryStrategy->getNextRetryDate());
            $this->status = Status::RETRY;
            return new NotificationToRetry();
        }

        $this->status = Status::FAILED;
        return new NotificationFailed();
    }

    public function findDifferentProvider(array $providers): ?Provider
    {
        $availableProviders = array_diff(
            array_map(fn (Provider $provider) => $provider->value, $providers),
            array_map(fn (Call $call) => $call->getProvider()->value, $this->calls->toArray()));

        return $availableProviders ? Provider::from(\reset($availableProviders)) : null;
    }
    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getCustomerId(): Uuid
    {
        return $this->customerId;
    }

    public function getCallData(Uuid $callId): CallData
    {
        $call = $this->getCall($callId);
        return new CallData($call->getId(), $call->getChannel(), $call->getProvider(), $this->message);
    }

    /**
     * @return CallData[]
     */
    private function getCallsByChannel(Channel $channel): array
    {
        return array_filter($this->getCalls(), fn (CallData $callData) => $callData->channel === $channel);
    }

    private function getCall(Uuid $callId): Call
    {
        $result = array_filter($this->calls->toArray(), fn (Call $call) => $call->getId() === $callId);
        return count($result) === 1
            ? current($result)
            : throw new NotFoundException(\sprintf('Call not found, id: %s', $callId));
    }
}

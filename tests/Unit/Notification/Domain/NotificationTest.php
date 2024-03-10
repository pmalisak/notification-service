<?php

declare(strict_types=1);

namespace Tests\App\Unit\Notification\Domain;

use App\Notification\Domain\Channel;
use App\Notification\Domain\Notification;
use App\Notification\Domain\Provider\Provider;
use App\Notification\Domain\Retry\RetryStrategy;
use App\Notification\Domain\SettleFailture\ChangeOfProvider;
use App\Notification\Domain\SettleFailture\NotificationFailed;
use App\Notification\Domain\SettleFailture\NotificationToRetry;
use App\Notification\Domain\Status;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

class NotificationTest extends TestCase
{
    private Notification $notification;

    protected function setUp(): void
    {
        $this->notification = new Notification(
            Uuid::v4(),
            Uuid::v4(),
            [Channel::SMS, Channel::EMAIL],
            'some message to send'
        );
    }
    public function testCreateNotification(): void
    {
        $this->assertEquals(Status::NEW, $this->notification->getStatus());
        $this->assertEquals(0, count($this->notification->getCalls()));
    }

    public function testPendingIfWeStartDelivery(): void
    {
        $callId = $this->notification->create(Channel::SMS, Provider::MOCKER);
        $this->notification->create(Channel::EMAIL, Provider::MOCKER);

        $this->notification->startDelivery($callId);

        $this->assertEquals(Status::PENDING, $this->notification->getStatus());
    }

    public function testCompleted(): void
    {
        $callId = $this->notification->create(Channel::SMS, Provider::MOCKER);

        $this->notification->startDelivery($callId);
        $this->notification->complete($callId);

        $this->assertEquals(Status::COMPLETED, $this->notification->getStatus());
    }

    public function testPendingIfOneIsCompletedAndTheOtherIsNot(): void
    {
        $callId = $this->notification->create(Channel::SMS, Provider::MOCKER);
        $this->notification->create(Channel::EMAIL, Provider::MOCKER);

        $this->notification->startDelivery($callId);
        $this->notification->complete($callId);

        $this->assertEquals(Status::PENDING, $this->notification->getStatus());
    }

    public function testRetry(): void
    {
        $callId = $this->notification->create(Channel::SMS, Provider::MOCKER);
        $this->notification->startDelivery($callId);
        $result = $this->notification->settleFailure(
            $callId,
            [Provider::MOCKER],
            new class implements RetryStrategy {
                public function retryAllowed(array $providers, array $calls): bool
                {
                    return true;
                }

                public function getNextRetryDate(): \DateTimeImmutable
                {
                    return new \DateTimeImmutable();
                }
            }
        );

        $this->assertInstanceOf(NotificationToRetry::class, $result);
        $this->assertEquals(Status::RETRY, $this->notification->getStatus());
    }

    public function testChangeProvider(): void
    {
        $callId = $this->notification->create(Channel::SMS, Provider::MOCKER);
        $this->notification->startDelivery($callId);
        $result = $this->notification->settleFailure(
            $callId,
            [Provider::MOCKER, Provider::TWILIO],
            new class implements RetryStrategy {
                public function retryAllowed(array $providers, array $calls): bool
                {
                    return true;
                }

                public function getNextRetryDate(): \DateTimeImmutable
                {
                    return new \DateTimeImmutable();
                }
            }
        );

        $this->assertInstanceOf(ChangeOfProvider::class, $result);
        $this->assertEquals(Status::PENDING, $this->notification->getStatus());
    }

    public function testFailed(): void
    {
        $callId = $this->notification->create(Channel::SMS, Provider::MOCKER);
        $this->notification->startDelivery($callId);
        $result = $this->notification->settleFailure(
            $callId,
            [Provider::MOCKER],
            new class implements RetryStrategy {
                public function retryAllowed(array $providers, array $calls): bool
                {
                    return false;
                }

                public function getNextRetryDate(): \DateTimeImmutable
                {
                    return new \DateTimeImmutable();
                }
            }
        );

        $this->assertInstanceOf(NotificationFailed::class, $result);
        $this->assertEquals(Status::FAILED, $this->notification->getStatus());
    }

    public function testFindDifferentProvider(): void
    {
        $this->notification->create(Channel::SMS, Provider::MOCKER);
        $this->assertEquals(Provider::TWILIO, $this->notification->findDifferentProvider([Provider::TWILIO]));
    }

    public function testFindDifferentProviderWillNotFind(): void
    {
        $this->notification->create(Channel::SMS, Provider::MOCKER);
        $this->assertNull($this->notification->findDifferentProvider([Provider::MOCKER]));
    }
}

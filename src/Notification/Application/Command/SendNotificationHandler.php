<?php

declare(strict_types=1);

namespace App\Notification\Application\Command;

use App\Notification\Application\Exception\LogicException;
use App\Notification\Application\Provider\SendAdapter;
use App\Notification\Application\Service\ProviderFinder;
use App\Notification\Domain\Repository\NotificationRepository;
use App\Notification\Domain\Retry\OneTimeRetryStrategy;
use App\Shared\Application\CQRS\CommandBus;
use App\Shared\Application\CQRS\CommandHandlerInterface;
use App\Shared\Application\Persistence\UnitOfWork;
use Psr\Log\LoggerInterface;

final readonly class SendNotificationHandler implements CommandHandlerInterface
{
    public function __construct(
        private SendAdapter $sendAdapter,
        private NotificationRepository $notificationRepository,
        private ProviderFinder $providerFinder,
        private CommandBus $commandBus,
        private UnitOfWork $unitOfWork,
        private LoggerInterface $logger,
    ) {
    }

    public function __invoke(SendNotificationCommand $command): void
    {
        $notification = $this->notificationRepository->get($command->notificationId);

        $notification->startDelivery($command->callId);
        $this->unitOfWork->commit();

        $result = $this->sendAdapter->send($notification, $command->callId);

        if ($result->success) {
            $notification->complete($command->callId);

            $this->unitOfWork->commit();
            return;
        }

        $providers = $this->providerFinder->getAvailableProviders($notification->getChannel($command->callId));

        $settleFailureResult = $notification->settleFailure($command->callId, $providers, new OneTimeRetryStrategy());

        $this->logger->error('Settle notification failure: notification {id}, call {callId}', [
            'id' => $notification->getId(),
            'callId' => $command->callId,
            'status' => $notification->getStatus()->value,
            'result' => get_class($settleFailureResult),
        ]);

        $this->unitOfWork->commit();

        if ($settleFailureResult->shouldBeSentImmediately()) {
            $this->commandBus->dispatch(new SendNotificationCommand(
                $command->notificationId,
                $settleFailureResult->getCallId() ?? throw new LogicException('Call id not set'),
            ));
        }
    }
}

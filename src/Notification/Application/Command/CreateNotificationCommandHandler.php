<?php

declare(strict_types=1);

namespace App\Notification\Application\Command;

use App\Notification\Application\Service\ScheduleNotification;
use App\Notification\Domain\Notification;
use App\Notification\Domain\Repository\NotificationRepository;
use App\Shared\Application\CQRS\CommandHandlerInterface;
use App\Shared\Application\Persistence\UnitOfWork;

final readonly class CreateNotificationCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private NotificationRepository $notificationRepository,
        private UnitOfWork $unitOfWork,
        private ScheduleNotification $scheduleNotification,
    ) {
    }

    public function __invoke(CreateNotificationCommand $command): void
    {
        $notification = new Notification(
            $command->id,
            $command->customerId,
            $command->channels,
            $command->message,
        );

        $this->notificationRepository->add($notification);

        $this->unitOfWork->commit();

        ($this->scheduleNotification)($notification);
    }
}

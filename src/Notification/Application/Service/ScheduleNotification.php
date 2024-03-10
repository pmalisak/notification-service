<?php

declare(strict_types=1);

namespace App\Notification\Application\Service;

use App\Notification\Application\Command\SendNotificationCommand;
use App\Notification\Domain\Exception\InvalidArgumentException;
use App\Notification\Domain\Notification;
use App\Shared\Application\CQRS\CommandBus;
use App\Shared\Application\Persistence\UnitOfWork;


final readonly class ScheduleNotification
{
    public function __construct(
        private ProviderFinder $providerFinder,
        private CommandBus $commandBus,
        private UnitOfWork $unitOfWork,
    ) {
    }

    public function __invoke(Notification $notification): void
    {
        $callIds = [];

        foreach ($notification->getChannels() as $channel) {
            $provider = $this->providerFinder->getAvailableProvider($channel);

            if (!$provider) {
                throw new InvalidArgumentException('No provider available');
            }

            $callIds[] = $notification->create($channel, $provider);
        }

        $this->unitOfWork->commit();

        foreach ($callIds as $callId) {
            $this->commandBus->dispatch(new SendNotificationCommand($notification->getId(), $callId));
        }
    }
}

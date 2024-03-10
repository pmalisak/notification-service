<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\CQRS;

use App\Shared\Application\CQRS\Command;
use App\Shared\Application\CQRS\CommandBus;
use Symfony\Component\Messenger\MessageBusInterface;

final readonly class MessengerCommandBus implements CommandBus
{
    public function __construct(private MessageBusInterface $messageBus)
    {
    }

    public function dispatch(Command $command): void
    {
        $this->messageBus->dispatch($command);
    }
}

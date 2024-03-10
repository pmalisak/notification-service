<?php

declare(strict_types=1);

namespace App\Notification\Application\Command;

use App\Notification\Domain\Channel;
use App\Shared\Application\CQRS\Command;
use Symfony\Component\Uid\Uuid;

final readonly class CreateNotificationCommand implements Command
{
    /**
     * @param Channel[] $channels
     */
    public function __construct(
        public Uuid $id,
        public Uuid $customerId,
        public array $channels,
        public string $message,
    ) {
    }
}

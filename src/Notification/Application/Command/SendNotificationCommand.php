<?php

declare(strict_types=1);

namespace App\Notification\Application\Command;

use App\Shared\Application\CQRS\Command;
use Symfony\Component\Uid\Uuid;

final readonly class SendNotificationCommand implements Command
{
    public function __construct(
        public Uuid $notificationId,
        public Uuid $callId,
    ) {
    }
}

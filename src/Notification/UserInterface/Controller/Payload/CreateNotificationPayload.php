<?php

declare(strict_types=1);

namespace App\Notification\UserInterface\Controller\Payload;

use Symfony\Component\Uid\Uuid;

final readonly class CreateNotificationPayload
{
    public function __construct(
        public Uuid $customerId,
        public array $channels,
        public string $message
    ) {
    }
}

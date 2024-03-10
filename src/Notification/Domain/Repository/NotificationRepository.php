<?php

declare(strict_types=1);

namespace App\Notification\Domain\Repository;

use App\Notification\Domain\Notification;
use Symfony\Component\Uid\Uuid;

interface NotificationRepository
{
    public function get(Uuid $notificationId): Notification;

    public function add(Notification $notification): void;
}

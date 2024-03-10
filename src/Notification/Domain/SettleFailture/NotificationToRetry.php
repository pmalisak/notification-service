<?php

declare(strict_types=1);

namespace App\Notification\Domain\SettleFailture;

final readonly class NotificationToRetry implements SettleFailureResult
{
    public function shouldBeSentImmediately(): false
    {
        return false;
    }

    public function getCallId(): null
    {
        return null;
    }
}

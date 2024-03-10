<?php

declare(strict_types=1);

namespace App\Notification\Domain\SettleFailture;

use Symfony\Component\Uid\Uuid;

final readonly class NotificationFailed implements SettleFailureResult
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

<?php

declare(strict_types=1);

namespace App\Notification\Domain\SettleFailture;

use Symfony\Component\Uid\Uuid;

final readonly class ChangeOfProvider implements SettleFailureResult
{
    public function __construct(private Uuid $callId)
    {
    }

    public function shouldBeSentImmediately(): true
    {
        return true;
    }

    public function getCallId(): ?Uuid
    {
        return $this->callId;
    }
}

<?php

declare(strict_types=1);

namespace App\Notification\Domain\SettleFailture;

use Symfony\Component\Uid\Uuid;

interface SettleFailureResult
{
    public function shouldBeSentImmediately(): bool;

    public function getCallId(): ?Uuid;
}

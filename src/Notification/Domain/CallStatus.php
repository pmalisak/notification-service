<?php

declare(strict_types=1);

namespace App\Notification\Domain;

enum CallStatus: string
{
    case NEW = 'new';
    case STARTED = 'started';
    case FAILED = 'failed';
    case COMPLETED = 'completed';

    public function isFinal(): bool
    {
        return match ($this) {
            self::FAILED, self::COMPLETED => true,
            default => false,
        };
    }
}

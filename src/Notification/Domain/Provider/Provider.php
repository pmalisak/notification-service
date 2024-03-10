<?php

declare(strict_types=1);

namespace App\Notification\Domain\Provider;

enum Provider: string
{
    case MOCKER = 'Mocker';
    case TWILIO = 'Twilio';

    public function equals(Provider $provider): bool
    {
        return $this === $provider;
    }
}

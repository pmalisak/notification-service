<?php

declare(strict_types=1);

namespace App\Notification\Domain;

enum Channel: string
{
    case EMAIL = 'email';
    case SMS = 'sms';
}

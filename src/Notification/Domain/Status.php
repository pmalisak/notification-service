<?php

declare(strict_types=1);

namespace App\Notification\Domain;

enum Status: string
{
    case NEW = 'new';
    case PENDING = 'pending';
    case RETRY = 'retry';
    case FAILED = 'failed';
    case COMPLETED = 'completed';
}

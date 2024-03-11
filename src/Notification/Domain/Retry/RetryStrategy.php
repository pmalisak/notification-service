<?php

declare(strict_types=1);

namespace App\Notification\Domain\Retry;

use App\Notification\Domain\CallData;
use App\Notification\Domain\Provider\Provider;

interface RetryStrategy
{
    /**
     * @param CallData[] $calls
     */
    public function retryAllowed(Provider $provider, array $calls): bool;

    public function getNextRetryDate(): \DateTimeImmutable;
}

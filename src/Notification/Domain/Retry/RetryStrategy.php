<?php

declare(strict_types=1);

namespace App\Notification\Domain\Retry;

use App\Notification\Domain\CallData;
use App\Notification\Domain\Provider\Provider;

interface RetryStrategy
{
    /**
     * @param Provider[] $providers
     * @param CallData[] $calls
     */
    public function retryAllowed(array $providers, array $calls): bool;

    public function getNextRetryDate(): \DateTimeImmutable;
}

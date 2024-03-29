<?php

declare(strict_types=1);

namespace App\Notification\Domain\Retry;

use App\Notification\Domain\CallData;
use App\Notification\Domain\Provider\Provider;

class OneTimeRetryStrategy implements RetryStrategy
{
    private const int ALLOWED_QUANTITY_OF_RETRY = 1;

    /**
     * @inheritdoc
     */
    public function retryAllowed(Provider $provider, array $calls): bool
    {
        $providerCount = array_count_values(array_map(fn (CallData $callData) => $callData->provider->value, $calls));

        if (! isset($providerCount[$provider->value])) {
            return true;
        }

        if ($providerCount[$provider->value] <= self::ALLOWED_QUANTITY_OF_RETRY) {
            return true;
        }

        return false;
    }

    public function getNextRetryDate(): \DateTimeImmutable
    {
        return new \DateTimeImmutable('+10 seconds');
    }
}

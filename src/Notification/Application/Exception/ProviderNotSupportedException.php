<?php

declare(strict_types=1);

namespace App\Notification\Application\Exception;

use App\Notification\Domain\Provider\Provider;

class ProviderNotSupportedException extends \Exception
{
    public function __construct(Provider $provider)
    {
        parent::__construct(\sprintf('Provider not supported: %s', $provider->value));
    }
}

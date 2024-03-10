<?php

namespace App\Notification\Application\Provider;

use App\Notification\Application\Provider\Params\SendParams;
use App\Notification\Application\Provider\Result\Result;
use App\Notification\Domain\Provider\Provider;

/**
 * @template T of SendParams
 */
interface ProviderGateway
{
    public function supports(Provider $provider): bool;

    /**
     * @param T $params
     */
    public function send(SendParams $params): Result;
}

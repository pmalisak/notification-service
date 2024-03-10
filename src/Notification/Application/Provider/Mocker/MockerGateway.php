<?php

declare(strict_types=1);

namespace App\Notification\Application\Provider\Mocker;

use App\Notification\Application\Provider\Params\SendParams;
use App\Notification\Application\Provider\ProviderGateway;
use App\Notification\Application\Provider\Result\Result;
use App\Notification\Domain\Channel;
use App\Notification\Domain\Provider\Provider;

/**
 * @implements ProviderGateway<MockerSendParams>
 */
class MockerGateway implements ProviderGateway
{
    private Provider $provider = Provider::MOCKER;

    public function supports(Provider $provider): bool
    {
        return $provider->equals($this->provider);
    }

    public function send(SendParams $params): Result
    {
        if ($params->channel === Channel::SMS) {
            return new Result(false);
        }

        return new Result(true);
    }
}

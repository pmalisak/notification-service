<?php

declare(strict_types=1);

namespace App\Notification\Application\Provider\Twilio;

use App\Notification\Application\Provider\Params\SendParams;
use App\Notification\Application\Provider\ProviderGateway;
use App\Notification\Application\Provider\Result\Result;
use App\Notification\Domain\Provider\Provider;
use App\Provider\Twilio\Public\TwilioFacade;

/**
 * @implements ProviderGateway<TwilioSendParams>
 */
class TwilioGateway implements ProviderGateway
{
    private Provider $provider = Provider::TWILIO;

    public function __construct(
        private TwilioFacade $facade,
    ) {
    }

    public function supports(Provider $provider): bool
    {
        return $provider->equals($this->provider);
    }

    public function send(SendParams $params): Result
    {
        return new Result(
            $this->facade->sendSms($params->to, $params->message),
        );
    }
}

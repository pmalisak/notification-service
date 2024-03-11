<?php

declare(strict_types=1);

namespace App\Notification\Application\Locator;

use App\Notification\Application\Exception\ProviderNotSupportedException;
use App\Notification\Application\Provider\Mocker\MockerGateway;
use App\Notification\Application\Provider\ProviderGateway;
use App\Notification\Application\Provider\Twilio\TwilioGateway;
use App\Notification\Domain\Provider\Provider;

final readonly class ProviderGatewayLocator
{
    public function __construct(
        private MockerGateway $mockerGateway,
        private TwilioGateway $twilioGateway,
    ) {
    }

    public function get(Provider $provider): ProviderGateway
    {
        foreach ((array) $this as $gateway) {
            if (! $gateway instanceof ProviderGateway) {
                continue;
            }

            if ($gateway->supports($provider)) {
                return $gateway;
            }
        }

        throw new ProviderNotSupportedException($provider);
    }
}

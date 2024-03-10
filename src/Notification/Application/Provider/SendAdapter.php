<?php

namespace App\Notification\Application\Provider;

use App\Notification\Application\Locator\ProviderGatewayLocator;
use App\Notification\Application\Provider\Params\SendParamsFactory;
use App\Notification\Application\Provider\Result\Result;
use App\Notification\Domain\Notification;
use Symfony\Component\Uid\Uuid;

final readonly class SendAdapter
{
    public function __construct(
        private ProviderGatewayLocator $locator,
        private SendParamsFactory $paramsFactory,
    ) {
    }

    public function send(Notification $notification, Uuid $callId): Result
    {
        $provider = $notification->getProvider($callId);

        $gateway = $this->locator->get($provider);
        $params = $this->paramsFactory->create($provider, $notification, $callId);

        return $gateway->send($params);
    }
}

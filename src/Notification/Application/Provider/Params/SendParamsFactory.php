<?php

declare(strict_types=1);

namespace App\Notification\Application\Provider\Params;

use App\Notification\Application\Provider\Mocker\MockerSendParams;
use App\Notification\Application\Provider\Twilio\TwilioSendParams;
use App\Notification\Domain\Notification;
use App\Notification\Domain\Provider\Provider;
use App\User\Public\CustomerFacade;
use Symfony\Component\Uid\Uuid;
use Twilio\Rest\Pricing;

class SendParamsFactory
{
    public function __construct(private CustomerFacade $customerFacade)
    {

    }

    public function create(Provider $provider, Notification $notification, Uuid $callId): SendParams
    {
        return match ($provider) {
            Provider::MOCKER => new MockerSendParams(
                $notification->getId(),
                $notification->getMessage(),
                $notification->getChannel($callId),
            ),
            Provider::TWILIO => $this->prepareTwilioParams($notification, $callId),
        };
    }

    private function prepareTwilioParams(Notification $notification, Uuid $callId): TwilioSendParams
    {
        $customerData = $this->customerFacade->getCustomer($notification->getCustomerId());

        return new TwilioSendParams(
            $notification->getId(),
            $notification->getMessage(),
            $notification->getChannel($callId),
            $customerData->phoneNumber,
        );
    }
}

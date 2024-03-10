<?php

declare(strict_types=1);

namespace App\Notification\Application\Provider\Twilio;

use App\Notification\Application\Provider\Params\SendParams;
use App\Notification\Domain\Channel;
use Symfony\Component\Uid\Uuid;

final readonly class TwilioSendParams implements SendParams
{
    public function __construct(
        public Uuid    $id,
        public string  $message,
        public Channel $channel,
        public string $to,
    ) {
    }
}

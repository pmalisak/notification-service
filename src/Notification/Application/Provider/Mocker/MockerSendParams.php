<?php

declare(strict_types=1);

namespace App\Notification\Application\Provider\Mocker;

use App\Notification\Application\Provider\Params\SendParams;
use App\Notification\Domain\Channel;
use Symfony\Component\Uid\Uuid;

final readonly class MockerSendParams implements SendParams
{
    public function __construct(
        public Uuid    $id,
        public string  $message,
        public Channel $channel,
    ) {
    }
}

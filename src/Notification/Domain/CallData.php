<?php

declare(strict_types=1);

namespace App\Notification\Domain;

use App\Notification\Domain\Provider\Provider;
use Symfony\Component\Uid\Uuid;

final readonly class CallData
{
    public function __construct(
        public Uuid    $id,
        public Channel $channel,
        public Provider $provider,
        public string  $data,
    ) {
    }
}

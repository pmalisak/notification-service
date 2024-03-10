<?php

declare(strict_types=1);

namespace App\Notification\Application\Command;

use App\Notification\Domain\Channel;
use App\Notification\Domain\Provider\Provider;
use App\Shared\Application\CQRS\Command;
use Symfony\Component\Uid\Uuid;

final readonly class UpdateProviderConfigurationCommand implements Command
{
    public function __construct(
        public Provider $provider,
        public Channel  $channel,
        public bool     $enabled,
    ) {
    }
}

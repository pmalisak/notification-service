<?php

declare(strict_types=1);

namespace App\Notification\UserInterface\Controller\Payload;

final readonly class UpdateProviderConfigurationPayload
{
    public function __construct(
        public bool $enabled,
    ) {
    }
}

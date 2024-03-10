<?php

declare(strict_types=1);

namespace App\Notification\Domain\Provider;

use App\Notification\Domain\Channel;
use Symfony\Component\Uid\Uuid;

class ProviderConfiguration
{
    public function __construct(
        private Uuid $id,
        private Provider $provider,
        private Channel $channel,
        private int $priority,
        private bool $enabled = false,
    ) {
    }

    public function getProvider(): Provider
    {
        return $this->provider;
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): void
    {
        $this->enabled = $enabled;
    }
}

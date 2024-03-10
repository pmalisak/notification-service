<?php

declare(strict_types=1);

namespace App\Notification\Domain\Provider;

use App\Notification\Domain\Channel;

interface ProviderConfigurationRepository
{
    /**
     * @return ProviderConfiguration[]
     */
    public function getAvailableProviders(Channel $channel): array;

    public function get(Provider $provider, Channel $channel): ProviderConfiguration;
}

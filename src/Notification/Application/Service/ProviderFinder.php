<?php

declare(strict_types=1);

namespace App\Notification\Application\Service;

use App\Notification\Domain\Channel;
use App\Notification\Domain\Provider\Provider;
use App\Notification\Domain\Provider\ProviderConfiguration;
use App\Notification\Domain\Provider\ProviderConfigurationRepository;

final readonly class ProviderFinder
{
    public function __construct(
        private ProviderConfigurationRepository $providerConfigurationRepository,
    ) {
    }

    /**
     * @return Provider[]
     */
    public function getAvailableProviders(Channel $channel): array
    {
        return array_map(
            fn (ProviderConfiguration $pc) => $pc->getProvider(),
            $this->providerConfigurationRepository->getAvailableProviders($channel),
        );
    }

    public function getAvailableProvider(Channel $channel): ?Provider
    {
        $providers = $this->getAvailableProviders($channel);
        return $providers ? \reset($providers) : null;
    }
}

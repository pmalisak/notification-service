<?php

declare(strict_types=1);

namespace App\Notification\Infrastructure\Doctrine\Repository;

use App\Notification\Domain\Channel;
use App\Notification\Domain\Provider\Provider;
use App\Notification\Domain\Provider\ProviderConfiguration;
use App\Notification\Domain\Provider\ProviderConfigurationRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class DoctrineProviderConfigurationRepository extends ServiceEntityRepository implements ProviderConfigurationRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProviderConfiguration::class);
    }

    public function getAvailableProviders(Channel $channel): array
    {
        return $this->findBy(['channel' => $channel, 'enabled' => true]);
    }

    public function get(Provider $provider, Channel $channel): ProviderConfiguration
    {
        return $this->findOneBy(['provider' => $provider, 'channel' => $channel])
            ?? throw new \Exception('Not found');
    }
}

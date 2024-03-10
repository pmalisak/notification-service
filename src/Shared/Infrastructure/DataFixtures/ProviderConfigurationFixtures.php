<?php

namespace App\Shared\Infrastructure\DataFixtures;

use App\Notification\Domain\Channel;
use App\Notification\Domain\Provider\Provider;
use App\Notification\Domain\Provider\ProviderConfiguration;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Uid\Uuid;

class ProviderConfigurationFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $manager->persist(
            new ProviderConfiguration(Uuid::fromString('63a25b87-4c5d-48f8-a39c-50a99f2eb76f'), Provider::MOCKER, Channel::EMAIL, 1, true)
        );
        $manager->persist(
            new ProviderConfiguration(Uuid::fromString('c3bdee30-18d1-4384-af2d-c1b824b51808'), Provider::MOCKER, Channel::SMS, 1, true)
        );
        $manager->persist(
            new ProviderConfiguration(Uuid::fromString('d96cf4ae-d41f-4abf-9c1a-23c1a391fe67'), Provider::TWILIO, Channel::SMS, 10, true)
        );

        $manager->flush();
    }
}

<?php

declare(strict_types=1);

namespace Tests\App\Unit\Notification\Application\Service;

use App\Notification\Application\Service\ProviderFinder;
use App\Notification\Application\Service\ScheduleNotification;
use App\Notification\Domain\Channel;
use App\Notification\Domain\Notification;
use App\Notification\Domain\Provider\Provider;
use App\Notification\Domain\Provider\ProviderConfiguration;
use App\Notification\Domain\Provider\ProviderConfigurationRepository;
use App\Shared\Application\CQRS\CommandBus;
use App\Shared\Application\Persistence\UnitOfWork;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

class ScheduleNotificationTest extends TestCase
{
    private ScheduleNotification $scheduleNotification;
    private ProviderConfigurationRepository&MockObject $providerConfigurationRepository;
    private Notification $notification;
    private CommandBus&MockObject $commandBus;
    private UnitOfWork&MockObject $unitOfWork;


    protected function setUp(): void
    {
        $this->scheduleNotification = new ScheduleNotification(
            new ProviderFinder(
                $this->providerConfigurationRepository = $this->createMock(ProviderConfigurationRepository::class),
            ),
            $this->commandBus = $this->createMock(CommandBus::class),
            $this->unitOfWork = $this->createMock(UnitOfWork::class)
        );

        $this->notification = new Notification(
            Uuid::v4(),
            Uuid::v4(),
            [Channel::SMS, Channel::EMAIL],
            'test message',
        );
    }

    public function testCallCommandBus(): void
    {
        $this->providerConfigurationRepository->method('getAvailableProviders')->willReturn(
            [new ProviderConfiguration(Uuid::v4(), Provider::MOCKER, Channel::EMAIL, 1)]
        );
        $this->unitOfWork->expects($this->once())->method('commit');
        $this->commandBus->expects($this->exactly(2))->method('dispatch');

        ($this->scheduleNotification)($this->notification);
    }
}

<?php

declare(strict_types=1);

namespace App\Notification\Application\Command;

use App\Notification\Application\Exception\ConflictException;
use App\Notification\Domain\Provider\ProviderConfigurationRepository;
use App\Shared\Application\CQRS\CommandHandlerInterface;
use App\Shared\Application\Persistence\UnitOfWork;

final readonly class UpdateProviderConfigurationHandler  implements CommandHandlerInterface
{
    public function __construct(
        private ProviderConfigurationRepository $providerConfigurationRepository,
        private UnitOfWork $unitOfWork,
    ) {
    }

    public function __invoke(UpdateProviderConfigurationCommand $command): void
    {
        $configuration = $this->providerConfigurationRepository->get($command->provider, $command->channel);
        if ($command->enabled === $configuration->isEnabled()) {
            throw new ConflictException(\sprintf('Channel already %s', $command->enabled ? 'enabled' : 'disabled'));
        }
        $configuration->setEnabled($command->enabled);

        $this->unitOfWork->commit();
    }
}

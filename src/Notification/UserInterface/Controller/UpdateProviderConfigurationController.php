<?php

declare(strict_types=1);

namespace App\Notification\UserInterface\Controller;

use App\Notification\Application\Command\UpdateProviderConfigurationCommand;
use App\Notification\Domain\Channel;
use App\Notification\Domain\Provider\Provider;
use App\Notification\UserInterface\Controller\Payload\UpdateProviderConfigurationPayload;
use App\Shared\Application\CQRS\CommandBus;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[
    AsController,
    Route(path: "/provider/{provider}/channel/{channel}/configuration", methods: ['PATCH'])
]
final readonly class UpdateProviderConfigurationController
{
    public function __construct(
        private CommandBus $commandBus,
    ){
    }

    public function __invoke(
        Provider                                                $provider,
        Channel                                                 $channel,
        #[MapRequestPayload] UpdateProviderConfigurationPayload $payload,
    ): JsonResponse
    {
        $this->commandBus->dispatch(new UpdateProviderConfigurationCommand(
            $provider,
            $channel,
            $payload->enabled,
        ));

        return new JsonResponse(['message' => 'The configuration has been updated']);
    }
}

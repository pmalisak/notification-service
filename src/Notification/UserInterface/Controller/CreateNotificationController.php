<?php

declare(strict_types=1);

namespace App\Notification\UserInterface\Controller;

use App\Notification\Application\Command\CreateNotificationCommand;
use App\Notification\Domain\Channel;
use App\Notification\UserInterface\Controller\Payload\CreateNotificationPayload;
use App\Shared\Application\CQRS\CommandBus;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Uid\Uuid;

#[
    AsController,
    Route(path: "/notification", methods: ['POST'])
]
class CreateNotificationController
{
    public function __construct(
        private CommandBus $commandBus,
    ){
    }

    public function __invoke(#[MapRequestPayload] CreateNotificationPayload $payload): JsonResponse
    {
        $this->commandBus->dispatch(new CreateNotificationCommand(
            $id = Uuid::v4(),
            $payload->customerId,
            \array_map(fn ($channel) => Channel::from($channel), $payload->channels),
            $payload->message,
        ));

        return new JsonResponse([
            'notificationId' => $id,
            'message' => 'Notification has been accepted'
        ]);
    }
}

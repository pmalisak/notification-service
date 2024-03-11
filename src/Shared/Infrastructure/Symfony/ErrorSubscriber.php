<?php

namespace App\Shared\Infrastructure\Symfony;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ErrorSubscriber implements EventSubscriberInterface
{
    /**
     * @return array<string, string>
     */
    public static function getSubscribedEvents(): array
    {
        return [KernelEvents::EXCEPTION => 'onKernelException'];
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        $response = new JsonResponse(
            [
                'error' => $exception->getPrevious() && $exception->getPrevious()->getMessage()
                    ? $exception->getPrevious()->getMessage()
                    : $exception->getMessage(),
            ],
            Response::HTTP_INTERNAL_SERVER_ERROR,
            [
                'Content-Type' => 'application/vnd.api+json',
            ]
        );

        $event->setResponse($response);
    }
}

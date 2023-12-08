<?php

declare(strict_types=1);

namespace Koddlo\Cqrs\Shared\Infrastructure\Symfony\Listener;

use Koddlo\Cqrs\Shared\Domain\NotFoundException;
use Koddlo\Cqrs\Shared\Infrastructure\Symfony\Request\Validator\ValidationError;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class ExceptionListener
{
    public function __construct(
        private string $environment
    ) {
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if ($exception instanceof ValidationError) {
            $code = Response::HTTP_BAD_REQUEST;
            $content['errors'] = $exception->getErrors();
        } elseif ($exception instanceof NotFoundException) {
            $code = Response::HTTP_NOT_FOUND;
        } elseif ($exception instanceof NotFoundHttpException) {
            $code = $exception->getStatusCode();
        } elseif ($exception instanceof MethodNotAllowedHttpException) {
            $code = $exception->getStatusCode();
        } else {
            $code = Response::HTTP_INTERNAL_SERVER_ERROR;
            if ($this->environment !== 'prod') {
                $content['errors'] = [
                    'server' => $exception::class . ' - ' . $event->getThrowable()->getMessage(),
                ];
            }
        }

        $event->setResponse(new JsonResponse($content ?? null, $code));
    }
}

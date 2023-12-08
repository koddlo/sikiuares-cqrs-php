<?php

declare(strict_types=1);

namespace Koddlo\Cqrs\Booking\Infrastructure\Symfony\Controller;

use Koddlo\Cqrs\Booking\Domain\CannotBeBooked;
use Koddlo\Cqrs\Booking\Infrastructure\Utils\Request\V1\BookAppointment as BookAppointmentV1;
use Koddlo\Cqrs\Booking\Infrastructure\Utils\Request\V2\BookAppointment as BookAppointmentV2;
use Koddlo\Cqrs\Shared\Application\Command\Async\CommandBus as AsyncCommandBus;
use Koddlo\Cqrs\Shared\Application\Command\Sync\CommandBus as SyncCommandBus;
use Koddlo\Cqrs\Shared\Infrastructure\Symfony\Request\Validator\RequestValidator;
use Koddlo\Cqrs\Shared\Infrastructure\Symfony\Request\Validator\ValidationError;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class BookingController extends AbstractController
{
    public function __construct(
        private SyncCommandBus $syncCommandBus,
        private AsyncCommandBus $asyncCommandBus,
        private RequestValidator $validator
    ) {
    }

    #[Route('v1/working-days/{workingDayId}/bookings', name: 'v2.working-days.bookings', methods: ['POST'])]
    public function createV1(string $workingDayId, BookAppointmentV1 $request): Response
    {
        $request->id = $workingDayId;
        $this->validator->validate($request);

        try {
            $this->syncCommandBus->dispatch($request->toCommand());
        } catch (CannotBeBooked) {
            throw new ValidationError([
                ValidationError::GENERAL => ['VALIDATION.CANNOT_BE_BOOKED'],
            ]);
        }

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    #[Route('v2/working-days/{workingDayId}/bookings', name: 'v1.working-days.bookings', methods: ['POST'])]
    public function createV2(string $workingDayId, BookAppointmentV2 $request): Response
    {
        $request->id = $workingDayId;
        $this->validator->validate($request);

        $this->asyncCommandBus->dispatch($request->toCommand());

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}

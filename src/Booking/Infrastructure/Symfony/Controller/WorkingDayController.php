<?php

declare(strict_types=1);

namespace Koddlo\Cqrs\Booking\Infrastructure\Symfony\Controller;

use Koddlo\Cqrs\Booking\Application\Query\GetWorkingDay;
use Koddlo\Cqrs\Shared\Domain\NotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

final class WorkingDayController extends AbstractController
{
    #[Route('/v1/working-days/{workingDayId}', name: 'working-days.get', methods: ['GET'])]
    public function read(string $workingDayId, GetWorkingDay $getWorkingDayQuery): JsonResponse
    {
        $workingDay = $getWorkingDayQuery->execute($workingDayId);
        if ($workingDay === null) {
            throw new NotFoundException();
        }

        return new JsonResponse($workingDay);
    }
}

<?php

declare(strict_types=1);

namespace Koddlo\Cqrs\Booking\Infrastructure\Doctrine\Query;

use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Koddlo\Cqrs\Booking\Application\Query\GetWorkingDay;
use Koddlo\Cqrs\Booking\Application\Query\Result\Booking as BookingDTO;
use Koddlo\Cqrs\Booking\Application\Query\Result\WorkingDay as WorkingDayDTO;
use Koddlo\Cqrs\Booking\Application\Query\Result\WorkingHour as WorkingHourDTO;
use Koddlo\Cqrs\Booking\Infrastructure\Doctrine\Entity\WorkingDay as WorkingDayEntity;

final class GetWorkingDayQuery implements GetWorkingDay
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
    }

    public function execute(string $id): ?WorkingDayDTO
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();

        $queryBuilder
            ->select(
                'wd.id',
                'wd.stafferId',
                'wd.date',
                'wd.workingHours',
                'b.id bookingId',
                'b.timeFrom bookingFrom',
                'b.timeTo bookingTo',
                'b.bookerFirstName',
                'b.bookerLastName',
                'b.bookerEmail'
            )
            ->from(WorkingDayEntity::class, 'wd')
            ->leftJoin('wd.bookings', 'b')
            ->andWhere('wd.id = :id')
            ->setParameter('id', $id)
            ->addOrderBy('bookingFrom')
            ->addOrderBy('bookingTo')
            ->addOrderBy('wd.id');

        /** @var array<int, array{
         *    id: string,
         *    stafferId: string,
         *    date: DateTimeImmutable,
         *    workingHours: array<int, array{from: string, to: string}>,
         *    bookingId: string|null,
         *    bookingFrom: DateTimeImmutable,
         *    bookingTo: DateTimeImmutable,
         *    bookerFirstName: string,
         *    bookerLastName: string,
         *    bookerEmail: string
         * }> $results */
        $results = $queryBuilder->getQuery()->getArrayResult();

        if (empty($results)) {
            return null;
        }

        $bookings = [];
        foreach ($results as $result) {
            if ($result['bookingId'] === null) {
                continue;
            }

            $bookings[] = new BookingDTO(
                $result['bookingFrom']->format('H:i'),
                $result['bookingTo']->format('H:i'),
                $result['bookerFirstName'],
                $result['bookerLastName'],
                $result['bookerEmail']
            );
        }

        $result = reset($results);

        $workingHours = [];
        foreach ($result['workingHours'] as $workingHour) {
            $workingHours[] = new WorkingHourDTO($workingHour['from'], $workingHour['to']);
        }

        return new WorkingDayDTO(
            $result['id'],
            $result['stafferId'],
            $result['date']->format('Y-m-d'),
            $workingHours,
            $bookings
        );
    }
}

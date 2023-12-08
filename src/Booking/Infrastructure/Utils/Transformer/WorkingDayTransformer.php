<?php

declare(strict_types=1);

namespace Koddlo\Cqrs\Booking\Infrastructure\Utils\Transformer;

use DateTimeImmutable;
use Koddlo\Cqrs\Booking\Domain\Booker;
use Koddlo\Cqrs\Booking\Domain\Booking;
use Koddlo\Cqrs\Booking\Domain\Date;
use Koddlo\Cqrs\Booking\Domain\Duration;
use Koddlo\Cqrs\Booking\Domain\StafferId;
use Koddlo\Cqrs\Booking\Domain\Time;
use Koddlo\Cqrs\Booking\Domain\WorkingDay;
use Koddlo\Cqrs\Booking\Domain\WorkingDayId;
use Koddlo\Cqrs\Booking\Infrastructure\Doctrine\Entity\Booking as BookingEntity;
use Koddlo\Cqrs\Booking\Infrastructure\Doctrine\Entity\WorkingDay as WorkingDayEntity;
use Koddlo\Cqrs\Booking\Infrastructure\Doctrine\Repository\WorkingDayRepository;
use Koddlo\Cqrs\Shared\Application\Service\IdGeneratorInterface;

final class WorkingDayTransformer
{
    public function __construct(
        private WorkingDayRepository $workingDayRepository,
        private IdGeneratorInterface $idGenerator
    ) {
    }

    public function fromDomain(WorkingDay $workingDay): WorkingDayEntity
    {
        /** @var WorkingDayEntity|null $workingDayEntity */
        $workingDayEntity = $this->workingDayRepository->find($workingDay->getId()->toString());
        if ($workingDayEntity === null) {
            /** @var DateTimeImmutable $date */
            $date = DateTimeImmutable::createFromFormat('!Y-m-d', $workingDay->getDate()->toString());

            $workingDayEntity = new WorkingDayEntity(
                $workingDay->getId()->toString(),
                $workingDay->getStafferId()->toString(),
                $date
            );
        }

        $workingHours = [];
        foreach ($workingDay->getWorkingHours() as $duration) {
            $workingHours[] = [
                'from' => $duration->getFrom()->toString(),
                'to' => $duration->getTo()->toString(),
            ];
        }
        $workingDayEntity->setWorkingHours($workingHours);

        foreach ($workingDay->getBookings() as $booking) {
            /** @var DateTimeImmutable $from */
            $from = DateTimeImmutable::createFromFormat('!H:i', $booking->getDuration()->getFrom()->toString());
            /** @var DateTimeImmutable $to */
            $to = DateTimeImmutable::createFromFormat('!H:i', $booking->getDuration()->getTo()->toString());

            if ($workingDayEntity->hasBooking($from, $to)) {
                continue;
            }

            new BookingEntity(
                $this->idGenerator->generate()->toString(),
                $booking->getBookerFirstName(),
                $booking->getBookerLastName(),
                $booking->getBookerEmail(),
                $from,
                $to,
                $workingDayEntity
            );
        }

        return $workingDayEntity;
    }

    public function toDomain(WorkingDayEntity $workingDayEntity): WorkingDay
    {
        $workingHours = [];
        foreach ($workingDayEntity->getWorkingHours() as $duration) {
            $workingHours[] = Duration::restore(Time::fromString($duration['from']), Time::fromString($duration['to']));
        }

        $bookings = [];
        foreach ($workingDayEntity->getBookings() as $booking) {
            $bookings[] = Booking::restore(
                Duration::restore(
                    Time::fromString($booking->getTimeFrom()->format('H:i')),
                    Time::fromString($booking->getTimeTo()->format('H:i'))
                ),
                Booker::restore(
                    $booking->getBookerFirstName(),
                    $booking->getBookerLastName(),
                    $booking->getBookerEmail()
                )
            );
        }

        return WorkingDay::restore(
            new WorkingDayId($workingDayEntity->getId()),
            new StafferId($workingDayEntity->getStafferId()),
            Date::fromString($workingDayEntity->getDate()->format('Y-m-d')),
            $workingHours,
            $bookings
        );
    }
}

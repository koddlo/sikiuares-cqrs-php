<?php

declare(strict_types=1);

namespace Koddlo\Cqrs\Booking\Application\Command\Sync;

use Koddlo\Cqrs\Booking\Domain\Booker;
use Koddlo\Cqrs\Booking\Domain\CannotBeBooked;
use Koddlo\Cqrs\Booking\Domain\ServiceType;
use Koddlo\Cqrs\Booking\Domain\Time;
use Koddlo\Cqrs\Booking\Domain\WorkingDayId;
use Koddlo\Cqrs\Booking\Domain\WorkingDayRepository;
use Koddlo\Cqrs\Shared\Application\Command\Sync\CommandHandler;

final class BookAppointmentHandler implements CommandHandler
{
    public function __construct(
        private WorkingDayRepository $repository
    ) {
    }

    /**
     * @throws CannotBeBooked
     */
    public function __invoke(BookAppointment $command): void
    {
        $stafferWorkingDay = $this->repository->get(new WorkingDayId($command->id));

        $stafferWorkingDay->book(
            ServiceType::from($command->service),
            Time::fromString($command->time),
            Booker::create(
                $command->bookerFirstName,
                $command->bookerLastName,
                $command->bookerEmail
            )
        );

        $this->repository->save($stafferWorkingDay);
    }
}

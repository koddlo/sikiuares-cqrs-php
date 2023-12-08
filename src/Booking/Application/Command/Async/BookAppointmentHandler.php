<?php

declare(strict_types=1);

namespace Koddlo\Cqrs\Booking\Application\Command\Async;

use Koddlo\Cqrs\Booking\Domain\Booker;
use Koddlo\Cqrs\Booking\Domain\CannotBeBooked;
use Koddlo\Cqrs\Booking\Domain\ServiceType;
use Koddlo\Cqrs\Booking\Domain\Time;
use Koddlo\Cqrs\Booking\Domain\WorkingDayId;
use Koddlo\Cqrs\Booking\Domain\WorkingDayRepository;
use Koddlo\Cqrs\Shared\Application\Command\Async\CommandHandler;
use Koddlo\Cqrs\Shared\Application\Service\Email;
use Koddlo\Cqrs\Shared\Application\Service\Mailer;

final class BookAppointmentHandler implements CommandHandler
{
    public function __construct(
        private WorkingDayRepository $repository,
        private Mailer $mailer
    ) {
    }

    /**
     * @throws CannotBeBooked
     */
    public function __invoke(BookAppointment $command): void
    {
        $stafferWorkingDay = $this->repository->get(new WorkingDayId($command->id));

        try {
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
        } catch (CannotBeBooked) {
            $this->mailer->send(
                new Email(
                    $command->bookerEmail,
                    'Failed to book an appointment',
                    'Someone else has already booked an appointment at the same time. Book an appointment again.'
                )
            );
        }
    }
}

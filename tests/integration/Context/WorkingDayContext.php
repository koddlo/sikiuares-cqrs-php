<?php

declare(strict_types=1);

namespace Koddlo\Cqrs\Tests\Integration\Context;

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use Doctrine\ORM\EntityManagerInterface;
use Koddlo\Cqrs\Booking\Application\Command\Async\BookAppointment;
use Koddlo\Cqrs\Booking\Domain\Date;
use Koddlo\Cqrs\Booking\Domain\Duration;
use Koddlo\Cqrs\Booking\Domain\StafferId;
use Koddlo\Cqrs\Booking\Domain\Time;
use Koddlo\Cqrs\Booking\Domain\WorkingDay;
use Koddlo\Cqrs\Booking\Domain\WorkingDayId;
use Koddlo\Cqrs\Booking\Domain\WorkingDayRepository;
use Koddlo\Cqrs\Shared\Application\Command\Async\CommandBus;

final class WorkingDayContext implements Context
{
    private array $workingDays = [];

    public function __construct(
        private WorkingDayRepository $workingDayRepository,
        private EntityManagerInterface $entityManager,
        private CommandBus $commandBus
    ) {
    }

    /**
     * @Given there are working days:
     */
    public function thereAreWorkingDays(TableNode $table): void
    {
        foreach ($table->getColumnsHash() as $row) {
            $this->workingDays[$row['id']] = $row;
        }
    }

    /**
     * @Given the working day :id has working hours:
     */
    public function theWorkingDayHasWorkingHours(string $id, TableNode $table): void
    {
        foreach ($table->getColumnsHash() as $row) {
            $this->workingDays[$id]['workingHours'][] = $row;
        }
    }

    /**
     * @Given the working day :id has bookings:
     */
    public function theWorkingDayHasBookings(string $id, TableNode $table): void
    {
        foreach ($table->getColumnsHash() as $row) {
            $this->commandBus->dispatch(
                new BookAppointment(
                    $id,
                    $row['time'],
                    $row['serviceType'],
                    $row['bookerFirstName'],
                    $row['bookerLastName'],
                    $row['bookerEmail']
                )
            );
        }
    }

    /**
     * @Given the working days have been saved
     */
    public function theWorkingDaysHaveBeenSaved(): void
    {
        foreach ($this->workingDays as $workingDayData) {
            $workingHours = [];
            foreach ($workingDayData['workingHours'] ?? [] as $durationData) {
                $workingHours[] = Duration::create(Time::fromString($durationData['from']), Time::fromString($durationData['to']));
            }

            $workingDay = WorkingDay::create(
                new WorkingDayId($workingDayData['id']),
                new StafferId($workingDayData['stafferId']),
                Date::fromString($workingDayData['date']),
                $workingHours
            );

            $this->workingDayRepository->save($workingDay);
        }

        $this->entityManager->flush();

        $this->workingDays = [];
    }
}

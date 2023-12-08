<?php

declare(strict_types=1);

namespace Koddlo\Cqrs\Tests\Unit\Booking\Domain;

use Koddlo\Cqrs\Booking\Domain\AppointmentBooked;
use Koddlo\Cqrs\Booking\Domain\Booker;
use Koddlo\Cqrs\Booking\Domain\Booking;
use Koddlo\Cqrs\Booking\Domain\CannotBeBooked;
use Koddlo\Cqrs\Booking\Domain\Date;
use Koddlo\Cqrs\Booking\Domain\Duration;
use Koddlo\Cqrs\Booking\Domain\ServiceType;
use Koddlo\Cqrs\Booking\Domain\StafferId;
use Koddlo\Cqrs\Booking\Domain\Time;
use Koddlo\Cqrs\Booking\Domain\WorkingDay;
use Koddlo\Cqrs\Booking\Domain\WorkingDayCreated;
use Koddlo\Cqrs\Booking\Domain\WorkingDayId;
use PHPUnit\Framework\TestCase;

final class WorkingDayTest extends TestCase
{
    public function testCreateWhenDataIsValidShouldCreateWorkingDay(): void
    {
        $id = new WorkingDayId('7fd1ad2c-05b1-4814-af37-d74d81a69f1d');
        $stafferId = new StafferId('e35b98e1-3cec-494c-8b88-e715201e8b75');
        $date = Date::fromString('2023-01-01');
        $workingHours = [Duration::create(Time::fromString('10:30'), Time::fromString('12:00'))];

        $SUT = WorkingDay::create($id, $stafferId, $date, $workingHours);
        $events = $SUT->pullEvents();

        self::assertEquals($id, $SUT->getId());
        self::assertEquals($stafferId, $SUT->getStafferId());
        self::assertEquals($date, $SUT->getDate());
        self::assertSame($workingHours, $SUT->getWorkingHours());
        self::assertCount(1, $events);
        self::assertInstanceOf(WorkingDayCreated::class, $events[0]);
    }

    public function testRestoreWhenDataIsValidShouldRestoreWorkingDay(): void
    {
        $id = new WorkingDayId('7fd1ad2c-05b1-4814-af37-d74d81a69f1d');
        $stafferId = new StafferId('e35b98e1-3cec-494c-8b88-e715201e8b75');
        $date = Date::fromString('2023-01-01');
        $workingHours = [Duration::restore(Time::fromString('10:30'), Time::fromString('12:00'))];
        $bookings = [
            Booking::restore(
                Duration::restore(
                    Time::fromString('10:30'),
                    Time::fromString('12:00')
                ),
                Booker::restore('John', 'Doe', 'john.doe@test.com')
            ),
        ];

        $SUT = WorkingDay::restore($id, $stafferId, $date, $workingHours, $bookings);

        self::assertEquals($id, $SUT->getId());
        self::assertEquals($stafferId, $SUT->getStafferId());
        self::assertEquals($date, $SUT->getDate());
        self::assertSame($workingHours, $SUT->getWorkingHours());
        self::assertSame($bookings, $SUT->getBookings());
        self::assertEmpty($SUT->pullEvents());
    }

    public function testBookWhenDataIsValidShouldBookAppointment(): void
    {
        $SUT = WorkingDay::restore(
            new WorkingDayId('7fd1ad2c-05b1-4814-af37-d74d81a69f1d'),
            new StafferId('e35b98e1-3cec-494c-8b88-e715201e8b75'),
            Date::fromString('2023-01-01'),
            [Duration::restore(Time::fromString('10:30'), Time::fromString('12:00'))],
            []
        );

        $SUT->book(
            ServiceType::COMBO,
            Time::fromString('10:30'),
            Booker::create('John', 'Doe', 'john.doe@test.com')
        );
        $events = $SUT->pullEvents();

        self::assertCount(1, $SUT->getBookings());
        self::assertCount(1, $events);
        self::assertInstanceOf(AppointmentBooked::class, $events[0]);
    }

    public function testBookWhenBookIsNotWithinWorkingHoursShouldThrowException(): void
    {
        $SUT = WorkingDay::restore(
            new WorkingDayId('7fd1ad2c-05b1-4814-af37-d74d81a69f1d'),
            new StafferId('e35b98e1-3cec-494c-8b88-e715201e8b75'),
            Date::fromString('2023-01-01'),
            [],
            []
        );

        self::expectException(CannotBeBooked::class);

        $SUT->book(
            ServiceType::COMBO,
            Time::fromString('10:30'),
            Booker::create('John', 'Doe', 'john.doe@test.com')
        );
    }

    public function testBookWhenTimeIsAlreadyBookedShouldThrowException(): void
    {
        $SUT = WorkingDay::restore(
            new WorkingDayId('7fd1ad2c-05b1-4814-af37-d74d81a69f1d'),
            new StafferId('e35b98e1-3cec-494c-8b88-e715201e8b75'),
            Date::fromString('2023-01-01'),
            [Duration::restore(Time::fromString('10:30'), Time::fromString('12:00'))],
            [
                Booking::restore(
                    Duration::restore(
                        Time::fromString('10:30'),
                        Time::fromString('12:00')
                    ),
                    Booker::restore('John', 'Doe', 'john.doe@test.com')
                ),
            ]
        );

        self::expectException(CannotBeBooked::class);

        $SUT->book(
            ServiceType::COMBO,
            Time::fromString('10:30'),
            Booker::create('John', 'Doe', 'john.doe@test.com')
        );
    }
}

<?php

declare(strict_types=1);

namespace Koddlo\Cqrs\Tests\Unit\Booking\Domain;

use Koddlo\Cqrs\Booking\Domain\AppointmentBooked;
use Koddlo\Cqrs\Booking\Domain\Booker;
use Koddlo\Cqrs\Booking\Domain\Duration;
use Koddlo\Cqrs\Booking\Domain\Time;
use Koddlo\Cqrs\Booking\Domain\WorkingDayId;
use PHPUnit\Framework\TestCase;

final class AppointmentBookedTest extends TestCase
{
    public function testConstructGivenDataIsValidShouldCreateEvent(): void
    {
        $id = new WorkingDayId('7fd1ad2c-05b1-4814-af37-d74d81a69f1d');
        $duration = Duration::restore(Time::fromString('10:30'), Time::fromString('12:00'));
        $booker = Booker::restore('John', 'Doe', 'john.doe@test.com');

        $SUT = AppointmentBooked::create($id, $duration, $booker);

        self::assertSame($id->toString(), $SUT->aggregateId);
        self::assertEquals([
            'from' => '10:30',
            'to' => '12:00',
        ], $SUT->duration);
        self::assertEquals([
            'firstName' => 'John',
            'lastName' => 'Doe',
            'email' => 'john.doe@test.com',
        ], $SUT->booker);
        self::assertSame(AppointmentBooked::EVENT_NAME, $SUT->name);
        self::assertSame(AppointmentBooked::EVENT_VERSION, $SUT->version);
    }
}

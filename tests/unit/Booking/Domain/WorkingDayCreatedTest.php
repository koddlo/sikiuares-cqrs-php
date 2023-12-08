<?php

declare(strict_types=1);

namespace Koddlo\Cqrs\Tests\Unit\Booking\Domain;

use Koddlo\Cqrs\Booking\Domain\Date;
use Koddlo\Cqrs\Booking\Domain\Duration;
use Koddlo\Cqrs\Booking\Domain\StafferId;
use Koddlo\Cqrs\Booking\Domain\Time;
use Koddlo\Cqrs\Booking\Domain\WorkingDayCreated;
use Koddlo\Cqrs\Booking\Domain\WorkingDayId;
use PHPUnit\Framework\TestCase;

final class WorkingDayCreatedTest extends TestCase
{
    public function testConstructGivenDataIsValidShouldCreateEvent(): void
    {
        $id = new WorkingDayId('7fd1ad2c-05b1-4814-af37-d74d81a69f1d');
        $stafferId = new StafferId('e35b98e1-3cec-494c-8b88-e715201e8b75');
        $date = Date::fromString('2023-01-01');
        $workingHours = [Duration::restore(Time::fromString('10:30'), Time::fromString('12:00'))];

        $SUT = WorkingDayCreated::create($id, $stafferId, $date, $workingHours);

        self::assertSame($id->toString(), $SUT->aggregateId);
        self::assertSame($stafferId->toString(), $SUT->stafferId);
        self::assertSame($date->toString(), $SUT->date);
        self::assertSame([[
            'from' => '10:30',
            'to' => '12:00',
        ]], $SUT->workingHours);
        self::assertSame(WorkingDayCreated::EVENT_NAME, $SUT->name);
        self::assertSame(WorkingDayCreated::EVENT_VERSION, $SUT->version);
    }
}

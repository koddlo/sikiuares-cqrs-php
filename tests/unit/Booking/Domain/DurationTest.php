<?php

declare(strict_types=1);

namespace Koddlo\Cqrs\Tests\Unit\Booking\Domain;

use Koddlo\Cqrs\Booking\Domain\Duration;
use Koddlo\Cqrs\Booking\Domain\InvalidDuration;
use Koddlo\Cqrs\Booking\Domain\Time;
use PHPUnit\Framework\TestCase;

final class DurationTest extends TestCase
{
    public function testCreateWhenDataIsValidShouldCreateDuration(): void
    {
        $timeFrom = Time::fromString('10:30');
        $timeTo = Time::fromString('12:00');

        $SUT = Duration::create($timeFrom, $timeTo);

        self::assertEquals($timeFrom, $SUT->getFrom());
        self::assertEquals($timeTo, $SUT->getTo());
    }

    public function testCreateWhenDataIsInvalidShouldThrowException(): void
    {
        $time = Time::fromString('10:30');

        self::expectException(InvalidDuration::class);

        Duration::create($time, $time);
    }

    public function testRestoreWhenDataIsValidShouldRestoreDuration(): void
    {
        $timeFrom = Time::fromString('10:30');
        $timeTo = Time::fromString('12:00');

        $SUT = Duration::create($timeFrom, $timeTo);

        self::assertEquals($timeFrom, $SUT->getFrom());
        self::assertEquals($timeTo, $SUT->getTo());
    }

    public function testRestoreWhenDataIsInvalidShouldRestoreDuration(): void
    {
        $time = Time::fromString('10:30');

        $SUT = Duration::restore($time, $time);

        self::assertEquals($time, $SUT->getFrom());
        self::assertEquals($time, $SUT->getTo());
    }

    public function testIsWithinWhenDurationIsWithinShouldReturnTrue(): void
    {
        $timeFrom = Time::fromString('10:30');
        $timeTo = Time::fromString('12:00');
        $SUT = Duration::restore($timeFrom, $timeTo);

        $result = $SUT->isWithin(Duration::restore($timeFrom, $timeTo));

        self::assertTrue($result);
    }

    public function testIsWithinWhenDurationIsNotWithinShouldReturnFalse(): void
    {
        $timeFrom = Time::fromString('10:30');
        $timeTo = Time::fromString('12:00');
        $SUT = Duration::restore($timeFrom, $timeTo);

        $result = $SUT->isWithin(Duration::restore($timeFrom, $timeTo->addMinutes(1)));

        self::assertFalse($result);
    }

    public function testIsOverlappingWhenDurationIsOverlappingShouldReturnTrue(): void
    {
        $timeFromOne = Time::fromString('10:30');
        $timeToOne = Time::fromString('12:00');
        $timeFromTwo = Time::fromString('10:00');
        $timeToTwo = Time::fromString('10:31');
        $SUT = Duration::restore($timeFromOne, $timeToOne);

        $result = $SUT->isOverlapping(Duration::restore($timeFromTwo, $timeToTwo));

        self::assertTrue($result);
    }

    public function testIsOverlappingWhenDurationIsNotOverlappingShouldReturnFalse(): void
    {
        $timeFromOne = Time::fromString('10:30');
        $timeToOne = Time::fromString('12:00');
        $timeFromTwo = Time::fromString('10:00');
        $timeToTwo = Time::fromString('10:30');
        $SUT = Duration::restore($timeFromOne, $timeToOne);

        $result = $SUT->isOverlapping(Duration::restore($timeFromTwo, $timeToTwo));

        self::assertFalse($result);
    }
}

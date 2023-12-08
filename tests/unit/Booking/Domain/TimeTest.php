<?php

declare(strict_types=1);

namespace Koddlo\Cqrs\Tests\Unit\Booking\Domain;

use Koddlo\Cqrs\Booking\Domain\InvalidTime;
use Koddlo\Cqrs\Booking\Domain\Time;
use PHPUnit\Framework\TestCase;

final class TimeTest extends TestCase
{
    public function testFromStringWhenDataIsValidShouldCreateTime(): void
    {
        $time = '10:30';

        $SUT = Time::fromString($time);

        self::assertSame($time, $SUT->toString());
    }

    public function testFromStringWhenDataIsInvalidShouldThrowException(): void
    {
        $invalidTimeFormat = '12:30:11';

        self::expectException(InvalidTime::class);

        Time::fromString($invalidTimeFormat);
    }

    public function testIsLaterThanOrEqualWhenTimeIsEqualShouldReturnTrue(): void
    {
        $time = '10:30';

        $SUT = Time::fromString($time);

        self::assertTrue($SUT->isLaterThanOrEqual(Time::fromString($time)));
    }

    public function testIsLaterThanOrEqualWhenTimeIsLaterShouldReturnTrue(): void
    {
        $earlierTime = '10:29';
        $time = '10:30';

        $SUT = Time::fromString($time);

        self::assertTrue($SUT->isLaterThanOrEqual(Time::fromString($earlierTime)));
    }

    public function testIsLaterThanOrEqualWhenTimeIsEarlierShouldReturnFalse(): void
    {
        $laterTime = '10:31';
        $time = '10:30';

        $SUT = Time::fromString($time);

        self::assertFalse($SUT->isLaterThanOrEqual(Time::fromString($laterTime)));
    }

    public function testAddMinutesWhenDataIsValidShouldAddMinutes(): void
    {
        $time = '10:30';
        $SUT = Time::fromString($time);

        $result = $SUT->addMinutes(30);

        self::assertSame('11:00', $result->toString());
    }
}

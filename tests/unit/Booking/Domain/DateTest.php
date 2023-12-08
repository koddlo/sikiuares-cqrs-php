<?php

declare(strict_types=1);

namespace Koddlo\Cqrs\Tests\Unit\Booking\Domain;

use Koddlo\Cqrs\Booking\Domain\Date;
use Koddlo\Cqrs\Booking\Domain\InvalidDate;
use PHPUnit\Framework\TestCase;

final class DateTest extends TestCase
{
    public function testFromStringWhenDataIsValidShouldCreateDate(): void
    {
        $date = '2023-01-01';

        $SUT = Date::fromString($date);

        self::assertSame($date, $SUT->toString());
    }

    public function testFromStringWhenDataIsInvalidShouldThrowException(): void
    {
        $invalidDateFormat = '01-01-2023';

        self::expectException(InvalidDate::class);

        Date::fromString($invalidDateFormat);
    }

    public function testEqualsWhenObjectsAreEqualShouldReturnTrue(): void
    {
        $date = '2023-01-01';

        $SUT = Date::fromString($date);

        self::assertTrue($SUT->equals(Date::fromString($date)));
    }

    public function testEqualsWhenObjectsAreNotEqualShouldReturnFalse(): void
    {
        $date = '2023-01-01';
        $otherDate = '2023-02-02';

        $SUT = Date::fromString($date);

        self::assertFalse($SUT->equals(Date::fromString($otherDate)));
    }
}

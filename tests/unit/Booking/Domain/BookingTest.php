<?php

declare(strict_types=1);

namespace Koddlo\Cqrs\Tests\Unit\Booking\Domain;

use Koddlo\Cqrs\Booking\Domain\Booker;
use Koddlo\Cqrs\Booking\Domain\Booking;
use Koddlo\Cqrs\Booking\Domain\Duration;
use Koddlo\Cqrs\Booking\Domain\Time;
use PHPUnit\Framework\TestCase;

final class BookingTest extends TestCase
{
    public function testCreateWhenDataIsValidShouldCreateBooking(): void
    {
        $firstName = 'John';
        $lastName = 'Doe';
        $email = 'john.doe@test.com';
        $duration = Duration::restore(Time::fromString('10:30'), Time::fromString('12:00'));

        $SUT = Booking::create($duration, Booker::restore($firstName, $lastName, $email));

        self::assertEquals($duration, $SUT->getDuration());
        self::assertSame($firstName, $SUT->getBookerFirstName());
        self::assertSame($lastName, $SUT->getBookerLastName());
        self::assertSame($email, $SUT->getBookerEmail());
    }

    public function testRestoreWhenDataIsValidShouldRestoreBooking(): void
    {
        $firstName = 'John';
        $lastName = 'Doe';
        $email = 'john.doe@test.com';
        $duration = Duration::restore(Time::fromString('10:30'), Time::fromString('12:00'));

        $SUT = Booking::restore($duration, Booker::restore($firstName, $lastName, $email));

        self::assertEquals($duration, $SUT->getDuration());
        self::assertSame($firstName, $SUT->getBookerFirstName());
        self::assertSame($lastName, $SUT->getBookerLastName());
        self::assertSame($email, $SUT->getBookerEmail());
    }
}

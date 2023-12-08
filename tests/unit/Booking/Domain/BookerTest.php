<?php

declare(strict_types=1);

namespace Koddlo\Cqrs\Tests\Unit\Booking\Domain;

use Koddlo\Cqrs\Booking\Domain\Booker;
use Koddlo\Cqrs\Booking\Domain\InvalidBooker;
use PHPUnit\Framework\TestCase;

final class BookerTest extends TestCase
{
    public function testCreateWhenDataIsValidShouldCreateBooker(): void
    {
        $firstName = 'John';
        $lastName = 'Doe';
        $email = 'john.doe@test.com';

        $SUT = Booker::create($firstName, $lastName, $email);

        self::assertSame($firstName, $SUT->getFirstName());
        self::assertSame($lastName, $SUT->getLastName());
        self::assertSame($email, $SUT->getEmail());
    }

    public function testCreateWhenFirstNameIsInvalidShouldThrowException(): void
    {
        $tooShortFirstName = 'Jo';
        $lastName = 'Doe';
        $email = 'john.doe@test.com';

        self::expectException(InvalidBooker::class);

        Booker::create($tooShortFirstName, $lastName, $email);
    }

    public function testCreateWhenLastNameIsInvalidShouldThrowException(): void
    {
        $firstName = 'John';
        $tooShortLastName = 'Do';
        $email = 'john.doe@test.com';

        self::expectException(InvalidBooker::class);

        Booker::create($firstName, $tooShortLastName, $email);
    }

    public function testCreateWhenEmailIsInvalidShouldThrowException(): void
    {
        $firstName = 'John';
        $lastName = 'Doe';
        $invalidEmail = 'john.doe.com';

        self::expectException(InvalidBooker::class);

        Booker::create($firstName, $lastName, $invalidEmail);
    }

    public function testRestoreWhenDataIsValidShouldRestoreBooker(): void
    {
        $firstName = 'John';
        $lastName = 'Doe';
        $email = 'john.doe@test.com';

        $SUT = Booker::restore($firstName, $lastName, $email);

        self::assertSame($firstName, $SUT->getFirstName());
        self::assertSame($lastName, $SUT->getLastName());
        self::assertSame($email, $SUT->getEmail());
    }

    public function testRestoreWhenDataIsInvalidShouldRestoreBooker(): void
    {
        $tooShortFirstName = 'Jo';
        $tooShortLastName = 'Do';
        $invalidEmail = 'john.doe.com';

        $SUT = Booker::restore($tooShortFirstName, $tooShortLastName, $invalidEmail);

        self::assertSame($tooShortFirstName, $SUT->getFirstName());
        self::assertSame($tooShortLastName, $SUT->getLastName());
        self::assertSame($invalidEmail, $SUT->getEmail());
    }
}

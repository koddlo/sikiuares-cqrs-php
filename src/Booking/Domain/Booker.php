<?php

declare(strict_types=1);

namespace Koddlo\Cqrs\Booking\Domain;

final readonly class Booker
{
    private const int FIRST_NAME_MIN_LENGTH = 3;

    private const int FIRST_NAME_MAX_LENGTH = 64;

    private const int LAST_NAME_MIN_LENGTH = 3;

    private const int LAST_NAME_MAX_LENGTH = 64;

    private const int EMAIL_MIN_LENGTH = 3;

    private const int EMAIL_MAX_LENGTH = 255;

    private function __construct(
        private string $firstName,
        private string $lastName,
        private string $email
    ) {
    }

    public static function create(
        string $firstName,
        string $lastName,
        string $email
    ): self {
        $booker = new self($firstName, $lastName, $email);
        $booker->guard();

        return $booker;
    }

    public static function restore(
        string $firstName,
        string $lastName,
        string $email
    ): self {
        return new self($firstName, $lastName, $email);
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    private function guard(): void
    {
        $firstNameLength = mb_strlen($this->firstName);
        if ($firstNameLength < self::FIRST_NAME_MIN_LENGTH || $firstNameLength > self::FIRST_NAME_MAX_LENGTH) {
            throw new InvalidBooker();
        }

        $lastNameLength = mb_strlen($this->lastName);
        if ($lastNameLength < self::LAST_NAME_MIN_LENGTH || $lastNameLength > self::LAST_NAME_MAX_LENGTH) {
            throw new InvalidBooker();
        }

        if (filter_var($this->email, FILTER_VALIDATE_EMAIL) === false) {
            throw new InvalidBooker();
        }

        $emailLength = mb_strlen($this->email);
        if ($emailLength < self::EMAIL_MIN_LENGTH || $emailLength > self::EMAIL_MAX_LENGTH) {
            throw new InvalidBooker();
        }
    }
}

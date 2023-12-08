<?php

declare(strict_types=1);

namespace Koddlo\Cqrs\Booking\Domain;

use DateTimeImmutable;

final readonly class Date
{
    private function __construct(
        private DateTimeImmutable $date
    ) {
    }

    public static function fromString(string $date): self
    {
        $date = DateTimeImmutable::createFromFormat('!Y-m-d', $date);

        if ($date === false) {
            throw new InvalidDate();
        }

        return new self($date);
    }

    public function toString(): string
    {
        return $this->date->format('Y-m-d');
    }

    public function equals(self $otherDate): bool
    {
        return $this->date->format('Y-m-d') === $otherDate->date->format('Y-m-d');
    }
}

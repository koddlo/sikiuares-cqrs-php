<?php

declare(strict_types=1);

namespace Koddlo\Cqrs\Booking\Domain;

use DateTimeImmutable;

final readonly class Time
{
    private function __construct(
        private DateTimeImmutable $time
    ) {
    }

    public static function fromString(string $time): self
    {
        $time = DateTimeImmutable::createFromFormat('!H:i', $time);

        if ($time === false) {
            throw new InvalidTime();
        }

        return new self($time);
    }

    public function addMinutes(int $minutes): self
    {
        return new self(
            $this->time->modify(
                sprintf('+ %u minutes', $minutes)
            )
        );
    }

    public function isLaterThanOrEqual(self $time): bool
    {
        return $this->time >= $time->time;
    }

    public function toString(): string
    {
        return $this->time->format('H:i');
    }
}

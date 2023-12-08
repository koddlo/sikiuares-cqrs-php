<?php

declare(strict_types=1);

namespace Koddlo\Cqrs\Booking\Domain;

final readonly class Duration
{
    private function __construct(
        private Time $timeFrom,
        private Time $timeTo
    ) {
    }

    public static function create(Time $timeFrom, Time $timeTo): self
    {
        $duration = new self($timeFrom, $timeTo);
        $duration->guard();

        return new self($timeFrom, $timeTo);
    }

    public static function restore(Time $timeFrom, Time $timeTo): self
    {
        return new self($timeFrom, $timeTo);
    }

    public function isWithin(self $otherDuration): bool
    {
        return $otherDuration->timeFrom->isLaterThanOrEqual($this->timeFrom)
            && $this->timeTo->isLaterThanOrEqual($otherDuration->timeTo);
    }

    public function isOverlapping(self $otherDuration): bool
    {
        return $this->timeTo > $otherDuration->timeFrom
            && $this->timeFrom < $otherDuration->timeTo;
    }

    public function getFrom(): Time
    {
        return $this->timeFrom;
    }

    public function getTo(): Time
    {
        return $this->timeTo;
    }

    private function guard(): void
    {
        if ($this->timeFrom->isLaterThanOrEqual($this->timeTo)) {
            throw new InvalidDuration();
        }
    }
}

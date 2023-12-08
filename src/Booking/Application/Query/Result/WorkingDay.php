<?php

declare(strict_types=1);

namespace Koddlo\Cqrs\Booking\Application\Query\Result;

final class WorkingDay
{
    /**
     * @param WorkingHour[] $workingHours
     * @param Booking[] $bookings
     */
    public function __construct(
        public readonly string $id,
        public readonly string $stafferId,
        public readonly string $date,
        public readonly array $workingHours,
        public readonly array $bookings
    ) {
    }
}

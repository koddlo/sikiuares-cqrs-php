<?php

declare(strict_types=1);

namespace Koddlo\Cqrs\Booking\Application\Query\Result;

final readonly class WorkingDay
{
    /**
     * @param WorkingHour[] $workingHours
     * @param Booking[] $bookings
     */
    public function __construct(
        public string $id,
        public string $stafferId,
        public string $date,
        public array $workingHours,
        public array $bookings
    ) {
    }
}

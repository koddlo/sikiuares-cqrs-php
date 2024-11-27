<?php

declare(strict_types=1);

namespace Koddlo\Cqrs\Booking\Application\Query\Result;

final readonly class WorkingHour
{
    public function __construct(
        public string $from,
        public string $to
    ) {
    }
}

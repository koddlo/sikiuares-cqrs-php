<?php

declare(strict_types=1);

namespace Koddlo\Cqrs\Booking\Application\Query\Result;

final class WorkingHour
{
    public function __construct(
        public readonly string $from,
        public readonly string $to
    ) {
    }
}

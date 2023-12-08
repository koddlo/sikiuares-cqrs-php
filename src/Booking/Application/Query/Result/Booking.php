<?php

declare(strict_types=1);

namespace Koddlo\Cqrs\Booking\Application\Query\Result;

final class Booking
{
    public function __construct(
        public readonly string $from,
        public readonly string $to,
        public readonly string $bookerFirstName,
        public readonly string $bookerLastName,
        public readonly string $bookerEmail
    ) {
    }
}

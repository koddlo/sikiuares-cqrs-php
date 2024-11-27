<?php

declare(strict_types=1);

namespace Koddlo\Cqrs\Booking\Application\Query\Result;

final readonly class Booking
{
    public function __construct(
        public string $from,
        public string $to,
        public string $bookerFirstName,
        public string $bookerLastName,
        public string $bookerEmail
    ) {
    }
}

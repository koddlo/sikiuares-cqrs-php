<?php

declare(strict_types=1);

namespace Koddlo\Cqrs\Booking\Application\Command\Async;

use Koddlo\Cqrs\Shared\Application\Command\Async\Command;

final readonly class BookAppointment implements Command
{
    public function __construct(
        public string $id,
        public string $time,
        public string $service,
        public string $bookerFirstName,
        public string $bookerLastName,
        public string $bookerEmail
    ) {
    }
}

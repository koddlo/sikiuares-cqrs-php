<?php

declare(strict_types=1);

namespace Koddlo\Cqrs\Booking\Application\Command\Sync;

use Koddlo\Cqrs\Shared\Application\Command\Sync\Command;

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

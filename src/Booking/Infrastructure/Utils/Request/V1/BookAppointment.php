<?php

declare(strict_types=1);

namespace Koddlo\Cqrs\Booking\Infrastructure\Utils\Request\V1;

use Koddlo\Cqrs\Booking\Application\Command\Sync\BookAppointment as Command;
use Koddlo\Cqrs\Shared\Infrastructure\Utils\Request\RequestInterface;

final class BookAppointment implements RequestInterface
{
    public function __construct(
        public mixed $id,
        public readonly mixed $time,
        public readonly mixed $service,
        public readonly mixed $bookerFirstName,
        public readonly mixed $bookerLastName,
        public readonly mixed $bookerEmail
    ) {
    }

    public function toCommand(): Command
    {
        return new Command(
            (string) $this->id,
            (string) $this->time,
            (string) $this->service,
            (string) $this->bookerFirstName,
            (string) $this->bookerLastName,
            (string) $this->bookerEmail
        );
    }
}

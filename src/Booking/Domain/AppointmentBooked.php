<?php

declare(strict_types=1);

namespace Koddlo\Cqrs\Booking\Domain;

use DateTimeImmutable;
use DateTimeInterface;
use Koddlo\Cqrs\Shared\Domain\DomainEvent;

final readonly class AppointmentBooked extends DomainEvent
{
    public const EVENT_VERSION = 1;

    public const EVENT_NAME = 'appointment_booked';

    /**
     * @var array{from: string, to: string}
     */
    public array $duration;

    /**
     * @var array{firstName: string, lastName: string, email: string}
     */
    public array $booker;

    /**
     * @param array{from: string, to: string}                           $duration
     * @param array{firstName: string, lastName: string, email: string} $booker
     */
    public function __construct(string $aggregateId, string $occurredAt, array $duration, array $booker)
    {
        $this->duration = $duration;
        $this->booker = $booker;

        parent::__construct($aggregateId, self::EVENT_NAME, self::EVENT_VERSION, $occurredAt);
    }

    public static function create(WorkingDayId $aggregateId, Duration $duration, Booker $booker): self
    {
        return new self(
            $aggregateId->toString(),
            (new DateTimeImmutable())->format(DateTimeInterface::ATOM),
            [
                'from' => $duration->getFrom()->toString(),
                'to' => $duration->getTo()->toString(),
            ],
            [
                'firstName' => $booker->getFirstName(),
                'lastName' => $booker->getLastName(),
                'email' => $booker->getEmail(),
            ]
        );
    }
}

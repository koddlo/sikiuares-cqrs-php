<?php

declare(strict_types=1);

namespace Koddlo\Cqrs\Booking\Domain;

use DateTimeImmutable;
use DateTimeInterface;
use Koddlo\Cqrs\Shared\Domain\DomainEvent;

final readonly class WorkingDayCreated extends DomainEvent
{
    public const EVENT_VERSION = 1;

    public const EVENT_NAME = 'working_day_created';

    public string $stafferId;

    public string $date;

    /**
     * @var array<int, array{from: string, to: string}>
     */
    public array $workingHours;

    /**
     * @param array<int, array{from: string, to: string}> $workingHours
     */
    public function __construct(
        string $aggregateId,
        string $occurredAt,
        string $stafferId,
        string $date,
        array $workingHours
    ) {
        $this->stafferId = $stafferId;
        $this->date = $date;
        $this->workingHours = $workingHours;

        parent::__construct($aggregateId, self::EVENT_NAME, self::EVENT_VERSION, $occurredAt);
    }

    /**
     * @param Duration[] $workingHours
     */
    public static function create(
        WorkingDayId $aggregateId,
        StafferId $stafferId,
        Date $date,
        array $workingHours
    ): self {
        $hours = [];
        foreach ($workingHours as $duration) {
            $hours[] = [
                'from' => $duration->getFrom()->toString(),
                'to' => $duration->getTo()->toString(),
            ];
        }

        return new self(
            $aggregateId->toString(),
            (new DateTimeImmutable())->format(DateTimeInterface::ATOM),
            $stafferId->toString(),
            $date->toString(),
            $hours
        );
    }
}

<?php

declare(strict_types=1);

namespace Koddlo\Cqrs\Booking\Infrastructure\Doctrine\Entity;

use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;

#[Entity]
class Booking
{
    #[Id]
    #[Column(type: Types::GUID)]
    private string $id;

    #[Column(type: Types::STRING, length: 64)]
    private string $bookerFirstName;

    #[Column(type: Types::STRING, length: 64)]
    private string $bookerLastName;

    #[Column(type: Types::STRING, length: 255)]
    private string $bookerEmail;

    #[Column(type: Types::TIME_IMMUTABLE)]
    private DateTimeImmutable $timeFrom;

    #[Column(type: Types::TIME_IMMUTABLE)]
    private DateTimeImmutable $timeTo;

    #[ManyToOne(targetEntity: WorkingDay::class, inversedBy: 'bookings')]
    #[JoinColumn(nullable: false)]
    private WorkingDay $workingDay;

    public function __construct(
        string $id,
        string $bookerFirstName,
        string $bookerLastName,
        string $bookerEmail,
        DateTimeImmutable $timeFrom,
        DateTimeImmutable $timeTo,
        WorkingDay $workingDay
    ) {
        $this->id = $id;
        $this->bookerFirstName = $bookerFirstName;
        $this->bookerLastName = $bookerLastName;
        $this->bookerEmail = $bookerEmail;
        $this->timeFrom = $timeFrom;
        $this->timeTo = $timeTo;
        $this->workingDay = $workingDay;
        $workingDay->addBooking($this);
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getBookerFirstName(): string
    {
        return $this->bookerFirstName;
    }

    public function getBookerLastName(): string
    {
        return $this->bookerLastName;
    }

    public function getBookerEmail(): string
    {
        return $this->bookerEmail;
    }

    public function getTimeFrom(): DateTimeImmutable
    {
        return $this->timeFrom;
    }

    public function getTimeTo(): DateTimeImmutable
    {
        return $this->timeTo;
    }

    public function getWorkingDay(): WorkingDay
    {
        return $this->workingDay;
    }
}

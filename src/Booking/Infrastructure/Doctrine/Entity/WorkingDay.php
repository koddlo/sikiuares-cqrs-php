<?php

declare(strict_types=1);

namespace Koddlo\Cqrs\Booking\Infrastructure\Doctrine\Entity;

use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\OneToMany;

#[Entity]
class WorkingDay
{
    #[Id]
    #[Column(type: Types::GUID)]
    private string $id;

    #[Column(type: Types::GUID)]
    private string $stafferId;

    #[Column(type: Types::DATE_IMMUTABLE)]
    private DateTimeImmutable $date;

    /**
     * @var array<int, array{from: string, to: string}>
     */
    #[Column(type: Types::JSON)]
    private array $workingHours;

    /**
     * @var Collection<int|string, Booking> $bookings
     */
    #[OneToMany(targetEntity: Booking::class, mappedBy: 'workingDay', cascade: ['persist'])]
    private Collection $bookings;

    public function __construct(
        string $id,
        string $stafferId,
        DateTimeImmutable $date,
    ) {
        $this->id = $id;
        $this->stafferId = $stafferId;
        $this->date = $date;
        $this->bookings = new ArrayCollection();
    }

    /**
     * @param array<int, array{from: string, to: string}> $workingHours
     */
    public function setWorkingHours(array $workingHours): void
    {
        $this->workingHours = $workingHours;
    }

    public function addBooking(Booking $booking): void
    {
        if ($this->hasBooking($booking->getTimeFrom(), $booking->getTimeTo())) {
            return;
        }

        $this->bookings->add($booking);
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getStafferId(): string
    {
        return $this->stafferId;
    }

    public function getDate(): DateTimeImmutable
    {
        return $this->date;
    }

    /**
     * @return array<int, array{from: string, to: string}>
     */
    public function getWorkingHours(): array
    {
        return $this->workingHours;
    }

    /**
     * @return Booking[]
     */
    public function getBookings(): array
    {
        return $this->bookings->toArray();
    }

    public function hasBooking(DateTimeImmutable $timeFrom, DateTimeImmutable $timeTo): bool
    {
        /** @var Booking $booking */
        foreach ($this->bookings as $booking) {
            if ($timeFrom->format('H:i') === $booking->getTimeFrom()->format('H:i')
                && $timeTo->format('H:i') === $booking->getTimeTo()->format('H:i')
            ) {
                return true;
            }
        }

        return false;
    }
}

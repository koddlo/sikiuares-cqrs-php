<?php

declare(strict_types=1);

namespace Koddlo\Cqrs\Booking\Domain;

use Koddlo\Cqrs\Shared\Domain\AggregateRoot;

final class WorkingDay extends AggregateRoot
{
    /**
     * @var Booking[]
     */
    private array $bookings;

    private function __construct(
        private WorkingDayId $id,
        private StafferId $stafferId,
        private Date $date,
        /** @var Duration[] $workingHours */
        private array $workingHours
    ) {
        $this->bookings = [];
    }

    /**
     * @param Duration[] $workingHours
     */
    public static function create(
        WorkingDayId $id,
        StafferId $stafferId,
        Date $date,
        array $workingHours
    ): self {
        $workingDay = new self($id, $stafferId, $date, $workingHours);
        $workingDay->raise(WorkingDayCreated::create($id, $stafferId, $date, $workingHours));

        return $workingDay;
    }

    /**
     * @param Duration[] $workingHours
     * @param Booking[] $bookings
     */
    public static function restore(
        WorkingDayId $id,
        StafferId $stafferId,
        Date $date,
        array $workingHours,
        array $bookings
    ): self {
        $workingDay = new self($id, $stafferId, $date, $workingHours);
        $workingDay->bookings = $bookings;

        return $workingDay;
    }

    /**
     * @throws CannotBeBooked
     */
    public function book(ServiceType $serviceType, Time $time, Booker $booker): void
    {
        $duration = Duration::create(
            $time,
            $time->addMinutes($serviceType->durationInMinutes())
        );

        if (! $this->isAvailable($duration)) {
            throw new CannotBeBooked();
        }

        $this->bookings[] = Booking::create($duration, $booker);
        $this->raise(AppointmentBooked::create($this->id, $duration, $booker));
    }

    public function getId(): WorkingDayId
    {
        return $this->id;
    }

    public function getStafferId(): StafferId
    {
        return $this->stafferId;
    }

    public function getDate(): Date
    {
        return $this->date;
    }

    /**
     * @return Duration[]
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
        return $this->bookings;
    }

    private function isAvailable(Duration $duration): bool
    {
        if (! $this->isDurationWithinWorkingHours($duration)) {
            return false;
        }

        if ($this->isAlreadyBooked($duration)) {
            return false;
        }

        return true;
    }

    private function isDurationWithinWorkingHours(Duration $duration): bool
    {
        return array_any(
            $this->workingHours,
            static fn (Duration $hourRange): bool => $hourRange->isWithin($duration)
        );
    }

    private function isAlreadyBooked(Duration $duration): bool
    {
        return array_any(
            $this->bookings,
            static fn (Booking $booking): bool => $duration->isOverlapping($booking->getDuration())
        );
    }
}

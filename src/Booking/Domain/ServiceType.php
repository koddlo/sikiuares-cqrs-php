<?php

declare(strict_types=1);

namespace Koddlo\Cqrs\Booking\Domain;

enum ServiceType: string
{
    case COMBO = 'combo';
    case MEN_HAIRCUT = 'men_haircut';
    case BOY_HAIRCUT = 'boy_haircut';
    case BEARD_GROOMING = 'beard_grooming';
    case SCALP_MASSAGE = 'scalp_massage';

    public function durationInMinutes(): int
    {
        return match ($this) {
            self::COMBO => 90,
            self::MEN_HAIRCUT => 60,
            self::BOY_HAIRCUT => 45,
            self::BEARD_GROOMING => 30,
            self::SCALP_MASSAGE => 15
        };
    }
}

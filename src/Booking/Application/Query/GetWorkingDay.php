<?php

declare(strict_types=1);

namespace Koddlo\Cqrs\Booking\Application\Query;

use Koddlo\Cqrs\Booking\Application\Query\Result\WorkingDay;

interface GetWorkingDay
{
    public function execute(string $id): ?WorkingDay;
}

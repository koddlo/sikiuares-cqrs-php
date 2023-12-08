<?php

declare(strict_types=1);

namespace Koddlo\Cqrs\Booking\Infrastructure\Utils\Repository;

use Koddlo\Cqrs\Booking\Domain\WorkingDay;
use Koddlo\Cqrs\Booking\Domain\WorkingDayId;
use Koddlo\Cqrs\Booking\Domain\WorkingDayNotFound;
use Koddlo\Cqrs\Booking\Domain\WorkingDayRepository as WorkingDayDomainRepository;
use Koddlo\Cqrs\Booking\Infrastructure\Doctrine\Repository\WorkingDayRepository as WorkingDayDoctrineRepository;
use Koddlo\Cqrs\Booking\Infrastructure\Utils\Transformer\WorkingDayTransformer;

final class WorkingDayRepository implements WorkingDayDomainRepository
{
    public function __construct(
        private WorkingDayDoctrineRepository $repository,
        private WorkingDayTransformer $transformer
    ) {
    }

    public function save(WorkingDay $workingDay): void
    {
        $this->repository->save(
            $this->transformer->fromDomain($workingDay)
        );
    }

    public function get(WorkingDayId $id): WorkingDay
    {
        $entity = $this->repository->find($id->toString());

        return $entity === null ? throw new WorkingDayNotFound() : $this->transformer->toDomain($entity);
    }
}

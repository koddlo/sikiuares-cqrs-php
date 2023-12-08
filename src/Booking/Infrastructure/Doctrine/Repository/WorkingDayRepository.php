<?php

declare(strict_types=1);

namespace Koddlo\Cqrs\Booking\Infrastructure\Doctrine\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Koddlo\Cqrs\Booking\Infrastructure\Doctrine\Entity\WorkingDay as WorkingDayEntity;

final class WorkingDayRepository
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
    }

    public function save(WorkingDayEntity $workingDay): void
    {
        $this->entityManager->persist($workingDay);
    }

    public function find(string $id): ?WorkingDayEntity
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();

        /** @var WorkingDayEntity|null $entity */
        $entity = $queryBuilder
            ->select('wd', 'b')
            ->from(WorkingDayEntity::class, 'wd')
            ->leftJoin('wd.bookings', 'b')
            ->andWhere('wd.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();

        return $entity;
    }
}

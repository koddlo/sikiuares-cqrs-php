<?php

declare(strict_types=1);

namespace Koddlo\Cqrs\Shared\Infrastructure\Ramsey;

use Koddlo\Cqrs\Shared\Application\Service\IdGeneratorInterface;
use Koddlo\Cqrs\Shared\Domain\Id;
use Ramsey\Uuid\Uuid;

final class IdGenerator implements IdGeneratorInterface
{
    public function generate(): Id
    {
        return new Id(Uuid::uuid7()->toString());
    }
}

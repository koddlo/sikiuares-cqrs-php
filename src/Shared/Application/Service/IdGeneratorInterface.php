<?php

declare(strict_types=1);

namespace Koddlo\Cqrs\Shared\Application\Service;

use Koddlo\Cqrs\Shared\Domain\Id;

interface IdGeneratorInterface
{
    public function generate(): Id;
}

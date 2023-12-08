<?php

declare(strict_types=1);

namespace Koddlo\Cqrs\Shared\Application\Command\Sync;

interface CommandBus
{
    public function dispatch(Command $command): void;
}

<?php

declare(strict_types=1);

namespace Koddlo\Cqrs\Shared\Domain;

abstract class AggregateRoot
{
    /**
     * @var DomainEvent[]
     */
    private array $events = [];

    /**
     * @return DomainEvent[]
     */
    final public function pullEvents(): array
    {
        $events = $this->events;
        $this->events = [];

        return $events;
    }

    final protected function raise(DomainEvent $event): void
    {
        $this->events[] = $event;
    }
}

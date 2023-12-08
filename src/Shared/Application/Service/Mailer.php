<?php

declare(strict_types=1);

namespace Koddlo\Cqrs\Shared\Application\Service;

interface Mailer
{
    public function send(Email $email): void;
}

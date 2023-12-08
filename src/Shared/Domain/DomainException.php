<?php

declare(strict_types=1);

namespace Koddlo\Cqrs\Shared\Domain;

use LogicException;

abstract class DomainException extends LogicException
{
}

<?php

declare(strict_types=1);

namespace Koddlo\Cqrs\Shared\Infrastructure\Symfony\Request\Validator;

use RuntimeException;

final class ValidationError extends RuntimeException
{
    public const GENERAL = 'general';

    /**
     * @var array<mixed>
     */
    private array $errors;

    /**
     * @param array<mixed> $errors
     */
    public function __construct(array $errors)
    {
        $this->errors = $errors;

        parent::__construct('Request is invalid.', 400);
    }

    /**
     * @return array<mixed>
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}

<?php

declare(strict_types=1);

namespace Koddlo\Cqrs\Shared\Infrastructure\Symfony\Request\Resolver;

use Generator;
use Koddlo\Cqrs\Shared\Infrastructure\Symfony\Request\Validator\ValidationError;
use Koddlo\Cqrs\Shared\Infrastructure\Utils\Request\RequestInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\PropertyAccess\Exception\RuntimeException;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Exception\UnexpectedValueException;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

final class JsonBodyResolver implements ValueResolverInterface
{
    private const FORMAT = 'json';

    public function __construct(
        private SerializerInterface $serializer
    ) {
    }

    /**
     * @throws ValidationError
     *
     * @return Generator
     */
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        if (! $this->supports($argument)) {
            return;
        }

        try {
            yield $this->serializer->deserialize(
                empty($request->getContent()) ? '{}' : $request->getContent(),
                $argument->getType() ?? '',
                self::FORMAT,
                [
                    AbstractObjectNormalizer::DISABLE_TYPE_ENFORCEMENT => true,
                ]
            );
        } catch (UnexpectedValueException|InvalidArgumentException|RuntimeException) {
            throw new ValidationError([
                ValidationError::GENERAL => 'VALIDATION.INVALID_PAYLOAD',
            ]);
        }
    }

    private function supports(ArgumentMetadata $argument): bool
    {
        $type = (string) $argument->getType();

        if (! class_exists($type)) {
            return false;
        }

        return \in_array(RequestInterface::class, class_implements($type), true);
    }
}

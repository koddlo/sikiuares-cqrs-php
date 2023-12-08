<?php

declare(strict_types=1);

namespace Koddlo\Cqrs\Shared\Infrastructure\Utils\Service;

use Koddlo\Cqrs\Shared\Application\Service\Email;
use Koddlo\Cqrs\Shared\Application\Service\Mailer;

/**
 * This is a fake mailer. In a real application, an email sending service would be implemented.
 */
final class InMemoryMailer implements Mailer
{
    /**
     * @var Email[]
     */
    private array $emails = [];

    public function send(Email $email): void
    {
        $this->emails[] = $email;
    }

    public function hasEmail(string $email): bool
    {
        foreach ($this->emails as $emailDTO) {
            if ($emailDTO->recipient === $email) {
                return true;
            }
        }

        return false;
    }
}

<?php

declare(strict_types=1);

namespace Koddlo\Cqrs\Tests\Integration\Context;

use Behat\Behat\Context\Context;
use Koddlo\Cqrs\Shared\Infrastructure\Utils\Service\InMemoryMailer;
use RuntimeException;

final class EmailContext implements Context
{
    public function __construct(
        private InMemoryMailer $mailer
    ) {
    }

    /**
     * @Then the email to :recipient has been sent
     */
    public function theEmailToRecipientHasBeenSent(string $recipient): void
    {
        if (! $this->mailer->hasEmail($recipient)) {
            throw new RuntimeException(
                sprintf('The email sent to the recipient %s could not be found.', $recipient)
            );
        }
    }
}

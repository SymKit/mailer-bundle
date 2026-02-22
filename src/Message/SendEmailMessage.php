<?php

declare(strict_types=1);

namespace Symkit\MailerBundle\Message;

final readonly class SendEmailMessage
{
    /**
     * @param array<string, mixed> $context
     */
    public function __construct(
        private string $slug,
        private string $recipient,
        private array $context = [],
    ) {
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function getRecipient(): string
    {
        return $this->recipient;
    }

    /**
     * @return array<string, mixed>
     */
    public function getContext(): array
    {
        return $this->context;
    }
}

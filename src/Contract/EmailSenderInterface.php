<?php

declare(strict_types=1);

namespace Symkit\MailerBundle\Contract;

/**
 * Public API for sending emails from template slugs.
 *
 * Type-hint this interface in the host application for BC-safe usage.
 */
interface EmailSenderInterface
{
    /**
     * @param array<string, mixed> $context
     */
    public function send(string $slug, string $recipient, array $context = []): void;
}

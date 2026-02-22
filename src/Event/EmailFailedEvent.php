<?php

declare(strict_types=1);

namespace Symkit\MailerBundle\Event;

use Symfony\Contracts\EventDispatcher\Event;

final class EmailFailedEvent extends Event
{
    public function __construct(
        private readonly string $messageId,
        private readonly string $error,
    ) {
    }

    public function getMessageId(): string
    {
        return $this->messageId;
    }

    public function getError(): string
    {
        return $this->error;
    }
}

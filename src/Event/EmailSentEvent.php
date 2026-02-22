<?php

declare(strict_types=1);

namespace Symkit\MailerBundle\Event;

use Symfony\Contracts\EventDispatcher\Event;

final class EmailSentEvent extends Event
{
    public function __construct(
        private readonly string $messageId,
        private readonly string $content,
    ) {
    }

    public function getMessageId(): string
    {
        return $this->messageId;
    }

    public function getContent(): string
    {
        return $this->content;
    }
}

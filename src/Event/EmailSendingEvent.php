<?php

declare(strict_types=1);

namespace Symkit\MailerBundle\Event;

use Symfony\Contracts\EventDispatcher\Event;

class EmailSendingEvent extends Event
{
    public function __construct(
        private readonly string $messageId,
        private readonly string $recipient,
        private readonly string $subject,
    ) {
    }

    public function getMessageId(): string
    {
        return $this->messageId;
    }

    public function getRecipient(): string
    {
        return $this->recipient;
    }

    public function getSubject(): string
    {
        return $this->subject;
    }
}

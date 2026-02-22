<?php

declare(strict_types=1);

namespace Symkit\MailerBundle\MessageHandler;

use Symkit\MailerBundle\Message\SendEmailMessage;
use Symkit\MailerBundle\Sender\EmailSender;

class SendEmailMessageHandler
{
    public function __construct(
        private readonly EmailSender $emailSender,
    ) {
    }

    public function __invoke(SendEmailMessage $message): void
    {
        $this->emailSender->send(
            $message->getSlug(),
            $message->getRecipient(),
            $message->getContext(),
        );
    }
}

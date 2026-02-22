<?php

declare(strict_types=1);

namespace Symkit\MailerBundle\MessageHandler;

use Symkit\MailerBundle\Contract\EmailSenderInterface;
use Symkit\MailerBundle\Message\SendEmailMessage;

final readonly class SendEmailMessageHandler
{
    public function __construct(
        private EmailSenderInterface $emailSender,
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

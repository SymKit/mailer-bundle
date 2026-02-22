<?php

declare(strict_types=1);

namespace Symkit\MailerBundle\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symkit\MailerBundle\Event\EmailFailedEvent;
use Symkit\MailerBundle\Event\EmailSendingEvent;
use Symkit\MailerBundle\Event\EmailSentEvent;
use Symkit\MailerBundle\Log\EmailLogger;

final readonly class EmailLogSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private EmailLogger $emailLogger,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            EmailSendingEvent::class => 'onEmailSending',
            EmailSentEvent::class => 'onEmailSent',
            EmailFailedEvent::class => 'onEmailFailed',
        ];
    }

    public function onEmailSending(EmailSendingEvent $event): void
    {
        $this->emailLogger->logSending(
            $event->getMessageId(),
            $event->getRecipient(),
            $event->getSubject(),
        );
    }

    public function onEmailSent(EmailSentEvent $event): void
    {
        $this->emailLogger->logSent(
            $event->getMessageId(),
            $event->getContent(),
        );
    }

    public function onEmailFailed(EmailFailedEvent $event): void
    {
        $this->emailLogger->logFailed(
            $event->getMessageId(),
            $event->getError(),
        );
    }
}

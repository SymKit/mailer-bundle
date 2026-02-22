<?php

declare(strict_types=1);

namespace Symkit\MailerBundle\Sender;

use InvalidArgumentException;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email as MimeEmail;
use Symkit\MailerBundle\Event\EmailFailedEvent;
use Symkit\MailerBundle\Event\EmailSendingEvent;
use Symkit\MailerBundle\Event\EmailSentEvent;
use Symkit\MailerBundle\Renderer\EmailRenderer;
use Symkit\MailerBundle\Repository\EmailRepository;
use Symkit\MailerBundle\Service\MessageIdGenerator;
use Throwable;

final readonly class EmailSender
{
    public function __construct(
        private EmailRepository $emailRepository,
        private EmailRenderer $emailRenderer,
        private MailerInterface $mailer,
        private EventDispatcherInterface $eventDispatcher,
        private MessageIdGenerator $messageIdGenerator,
    ) {
    }

    /**
     * @param array<string, mixed> $context
     */
    public function send(string $slug, string $recipient, array $context = []): void
    {
        $template = $this->emailRepository->getBySlug($slug);

        if (!$template) {
            throw new InvalidArgumentException(\sprintf('Email template with slug "%s" not found.', $slug));
        }

        $messageId = $this->messageIdGenerator->generate();
        $subject = $template->getSubject() ?? '';
        $senderEmail = $template->getSenderEmail() ?? '';

        $this->eventDispatcher->dispatch(new EmailSendingEvent(
            $messageId,
            $recipient,
            $subject,
        ));

        try {
            $htmlBody = $this->emailRenderer->render($template, $context);

            $email = (new MimeEmail())
                ->from(new Address(
                    $senderEmail,
                    $template->getSenderName() ?? '',
                ))
                ->to($recipient)
                ->subject($subject)
                ->html($htmlBody)
            ;

            $this->mailer->send($email);

            $this->eventDispatcher->dispatch(new EmailSentEvent(
                $messageId,
                $htmlBody,
            ));
        } catch (Throwable $e) {
            $this->eventDispatcher->dispatch(new EmailFailedEvent(
                $messageId,
                $e->getMessage(),
            ));

            throw $e;
        }
    }
}

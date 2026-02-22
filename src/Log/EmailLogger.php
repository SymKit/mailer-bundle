<?php

declare(strict_types=1);

namespace Symkit\MailerBundle\Log;

use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symkit\MailerBundle\Entity\EmailLog;
use Symkit\MailerBundle\Enum\EmailStatus;
use Symkit\MailerBundle\Repository\EmailLogRepository;

final readonly class EmailLogger
{
    /**
     * @param class-string<EmailLog> $emailLogClass
     */
    public function __construct(
        private EntityManagerInterface $entityManager,
        private EmailLogRepository $emailLogRepository,
        private string $emailLogClass = EmailLog::class,
    ) {
    }

    public function logSending(string $messageId, string $recipient, string $subject): void
    {
        $log = new ($this->emailLogClass)();
        $log->setMessageId($messageId);
        $log->setRecipient($recipient);
        $log->setSubject($subject);

        $this->entityManager->persist($log);
        $this->entityManager->flush();
    }

    public function logSent(string $messageId, string $content): void
    {
        $log = $this->emailLogRepository->findOneByMessageId($messageId);

        if ($log) {
            $log->setStatus(EmailStatus::SENT);
            $log->setContent($content);
            $log->setSentAt(new DateTimeImmutable());

            $this->entityManager->flush();
        }
    }

    public function logFailed(string $messageId, string $error): void
    {
        $log = $this->emailLogRepository->findOneByMessageId($messageId);

        if ($log) {
            $log->setStatus(EmailStatus::FAILED);
            $log->setError($error);

            $this->entityManager->flush();
        }
    }
}

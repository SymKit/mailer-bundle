<?php

declare(strict_types=1);

namespace Symkit\MailerBundle\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symkit\MailerBundle\Enum\EmailStatus;
use Symkit\MailerBundle\Repository\EmailLogRepository;
use Symkit\MailerBundle\Trait\TimestampableTrait;

#[ORM\Entity(repositoryClass: EmailLogRepository::class)]
#[ORM\HasLifecycleCallbacks]
class EmailLog
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'guid')]
    private ?string $messageId = null;

    #[ORM\Column(length: 255)]
    private ?string $recipient = null;

    #[ORM\Column(length: 255)]
    private ?string $subject = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $content = null;

    #[ORM\Column]
    private ?DateTimeImmutable $sentAt = null;

    #[ORM\Column(length: 50, enumType: EmailStatus::class)]
    private ?EmailStatus $status = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $error = null;

    public function __construct()
    {
        $this->sentAt = new DateTimeImmutable();
        $this->status = EmailStatus::PENDING;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMessageId(): ?string
    {
        return $this->messageId;
    }

    public function setMessageId(string $messageId): static
    {
        $this->messageId = $messageId;

        return $this;
    }

    public function getRecipient(): ?string
    {
        return $this->recipient;
    }

    public function setRecipient(string $recipient): static
    {
        $this->recipient = $recipient;

        return $this;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): static
    {
        $this->subject = $subject;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getSentAt(): ?DateTimeImmutable
    {
        return $this->sentAt;
    }

    public function setSentAt(DateTimeImmutable $sentAt): static
    {
        $this->sentAt = $sentAt;

        return $this;
    }

    public function getStatus(): ?EmailStatus
    {
        return $this->status;
    }

    public function setStatus(EmailStatus $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getError(): ?string
    {
        return $this->error;
    }

    public function setError(?string $error): static
    {
        $this->error = $error;

        return $this;
    }
}

<?php

declare(strict_types=1);

namespace Symkit\MailerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symkit\MailerBundle\Repository\EmailRepository;
use Symkit\MailerBundle\Trait\TimestampableTrait;

#[ORM\Entity(repositoryClass: EmailRepository::class)]
#[UniqueEntity('slug', groups: ['create', 'edit'])]
#[ORM\HasLifecycleCallbacks]
class Email
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Assert\NotBlank(groups: ['create', 'edit'])]
    #[Assert\Regex('/^[a-z0-9_]+$/', message: 'validation.slug.regex', groups: ['create', 'edit'])]
    private ?string $slug = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(groups: ['create', 'edit'])]
    private ?string $subject = null;

    #[ORM\Column(type: 'text')]
    #[Assert\NotBlank(groups: ['create', 'edit'])]
    private ?string $content = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(groups: ['create', 'edit'])]
    #[Assert\Email(groups: ['create', 'edit'])]
    private ?string $senderEmail = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $senderName = null;

    #[ORM\ManyToOne(targetEntity: Layout::class, inversedBy: 'emails')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Layout $layout = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

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

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getLayout(): ?Layout
    {
        return $this->layout;
    }

    public function setLayout(?Layout $layout): static
    {
        $this->layout = $layout;

        return $this;
    }

    public function getSenderEmail(): ?string
    {
        return $this->senderEmail;
    }

    public function setSenderEmail(string $senderEmail): static
    {
        $this->senderEmail = $senderEmail;

        return $this;
    }

    public function getSenderName(): ?string
    {
        return $this->senderName;
    }

    public function setSenderName(?string $senderName): static
    {
        $this->senderName = $senderName;

        return $this;
    }

    public function __toString(): string
    {
        return $this->subject ?? 'New Email';
    }
}

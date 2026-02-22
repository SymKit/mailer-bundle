<?php

declare(strict_types=1);

namespace Symkit\MailerBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symkit\MailerBundle\Repository\LayoutRepository;
use Symkit\MailerBundle\Trait\TimestampableTrait;

#[ORM\Entity(repositoryClass: LayoutRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Layout
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /** @var Collection<int, Email> */
    #[ORM\OneToMany(mappedBy: 'layout', targetEntity: Email::class)]
    private Collection $emails;

    public function __construct()
    {
        $this->emails = new ArrayCollection();
    }

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(groups: ['create', 'edit'])]
    #[Assert\Length(max: 255, groups: ['create', 'edit'])]
    private ?string $name = null;

    #[ORM\Column(type: 'text')]
    #[Assert\NotBlank(groups: ['create', 'edit'])]
    private ?string $content = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

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

    /** @return Collection<int, Email> */
    public function getEmails(): Collection
    {
        return $this->emails;
    }

    public function getEmailCount(): int
    {
        return $this->emails->count();
    }

    public function __toString(): string
    {
        return $this->name ?? 'New Layout';
    }
}

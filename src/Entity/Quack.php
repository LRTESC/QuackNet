<?php

namespace App\Entity;

use App\Repository\QuackRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuackRepository::class)]
class Quack
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'quacks')]
    private ?self $comment = null;

    #[ORM\OneToMany(mappedBy: 'comment', targetEntity: self::class)]
    private Collection $quacks;

    #[ORM\ManyToOne(inversedBy: 'quack')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Duck $duck = null;

    public function __construct()
    {
        $this->quacks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getComment(): ?self
    {
        return $this->comment;
    }

    public function setComment(?self $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getQuacks(): Collection
    {
        return $this->quacks;
    }

    public function addQuack(self $quack): self
    {
        if (!$this->quacks->contains($quack)) {
            $this->quacks->add($quack);
            $quack->setComment($this);
        }

        return $this;
    }

    public function removeQuack(self $quack): self
    {
        if ($this->quacks->removeElement($quack)) {
            // set the owning side to null (unless already changed)
            if ($quack->getComment() === $this) {
                $quack->setComment(null);
            }
        }

        return $this;
    }

    public function getDuck(): ?Duck
    {
        return $this->duck;
    }

    public function setDuck(?Duck $duck): self
    {
        $this->duck = $duck;

        return $this;
    }
}

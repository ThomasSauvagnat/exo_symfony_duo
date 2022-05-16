<?php

namespace App\Entity;

use App\Repository\MessageRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MessageRepository::class)]
class Message
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'datetime')]
    private $creadtedAt;

    #[ORM\Column(type: 'text')]
    private $content;

    #[ORM\ManyToOne(targetEntity: Topic::class, inversedBy: 'messages')]
    #[ORM\JoinColumn(nullable: false)]
    private $topic;

    #[ORM\ManyToOne(targetEntity: Account::class, inversedBy: 'messages')]
    #[ORM\JoinColumn(nullable: false)]
    private $createdBy;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreadtedAt(): ?\DateTimeInterface
    {
        return $this->creadtedAt;
    }

    public function setCreadtedAt(\DateTimeInterface $creadtedAt): self
    {
        $this->creadtedAt = $creadtedAt;

        return $this;
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

    public function getTopic(): ?Topic
    {
        return $this->topic;
    }

    public function setTopic(?Topic $topic): self
    {
        $this->topic = $topic;

        return $this;
    }

    public function getCreatedBy(): ?Account
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?Account $createdBy): self
    {
        $this->createdBy = $createdBy;

        return $this;
    }
}

<?php

namespace App\Entity;

use App\Repository\CommentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommentRepository::class)]
class Comment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'comment_id')]
    private ?int $id = null;

    #[ORM\Column(name: 'comment_date')]
    private ?\DateTime $date = null;

    #[ORM\Column(name: 'comment_content', type: Types::TEXT)]
    private ?string $content = null;

    #[ORM\ManyToOne(inversedBy: 'comment')]
    #[ORM\JoinColumn(name: 'hike_id', referencedColumnName: 'hike_id')]
    private ?Hike $hike = null;

    #[ORM\ManyToOne(inversedBy: 'comment')]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'user_id')]
    private ?User $userComment = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    public function setDate(\DateTime $date): static
    {
        $this->date = $date;

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

    public function getHike(): ?Hike
    {
        return $this->hike;
    }

    public function setHike(?Hike $hike): static
    {
        $this->hike = $hike;

        return $this;
    }

    public function getUserComment(): ?User
    {
        return $this->userComment;
    }

    public function setUserComment(?User $userComment): static
    {
        $this->userComment = $userComment;

        return $this;
    }
}

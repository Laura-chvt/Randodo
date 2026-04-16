<?php

namespace App\Entity;

use App\Repository\HikeDoneRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HikeDoneRepository::class)]
class HikeDone
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'hike')]
    #[ORM\JoinColumn(nullable: false, name: 'hiker_id', referencedColumnName: 'user_id')]
    private ?User $hiker = null;

    #[ORM\ManyToOne(inversedBy: 'hikesdone')]
    #[ORM\JoinColumn(nullable: false, name: 'hikesdone_id', referencedColumnName: 'hike_id')]
    private ?Hike $hike = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $doneAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHiker(): ?User
    {
        return $this->hiker;
    }

    public function setHiker(?User $hiker): static
    {
        $this->hiker = $hiker;

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

    public function getDoneAt(): ?\DateTime
    {
        return $this->doneAt;
    }

    public function setDoneAt(\DateTime $doneAt): static
    {
        $this->doneAt = $doneAt;

        return $this;
    }
}

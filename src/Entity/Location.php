<?php

namespace App\Entity;

use App\Repository\LocationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LocationRepository::class)]
class Location
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column (name: 'location_id')]
    private ?int $id = null;

    #[ORM\Column(name: 'location_name', length: 150)]
    private ?string $name = null;

    #[ORM\Column(name: 'location_gps', length: 150)]
    private ?string $gps = null;

    #[ORM\OneToMany(targetEntity: Hike::class, mappedBy: 'location')]
    private Collection $hikes;

    public function __construct()
    {
        $this->hikes = new ArrayCollection();
    }

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

    public function getGps(): ?string
    {
        return $this->gps;
    }

    public function setGps(string $gps): static
    {
        $this->gps = $gps;

        return $this;
    }
}

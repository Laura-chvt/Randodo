<?php

namespace App\Entity;

use App\Repository\HikeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HikeRepository::class)]
class Hike
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'hike_id')]
    private ?int $id = null;

    #[ORM\Column(name: 'hike_name', length: 255)]
    private ?string $name = null;

    #[ORM\Column(name: 'hike_height', nullable: true)]
    private ?int $height = null;

    #[ORM\Column (name: 'hike_time')]
    private ?int $time = null;

    #[ORM\Column(name: 'hike_leveel', length: 15)]
    private ?string $level = null;

    #[ORM\Column(name: 'hike_length')]
    private ?float $length = null;

    #[ORM\Column(name: 'hike_family')]
    private ?bool $family = null;

    #[ORM\Column(name: 'hike_description', type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(name: 'hike_thumbnail', length: 50, nullable: true)]
    private ?string $thumbnail = null;

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

    public function getHeight(): ?int
    {
        return $this->height;
    }

    public function setHeight(?int $height): static
    {
        $this->height = $height;

        return $this;
    }

    public function getTime(): ?int
    {
        return $this->time;
    }

    public function setTime(int $time): static
    {
        $this->time = $time;

        return $this;
    }

    public function getLevel(): ?string
    {
        return $this->level;
    }

    public function setLevel(string $level): static
    {
        $this->level = $level;

        return $this;
    }

    public function getLength(): ?float
    {
        return $this->length;
    }

    public function setLength(float $length): static
    {
        $this->length = $length;

        return $this;
    }

    public function isFamily(): ?bool
    {
        return $this->family;
    }

    public function setFamily(bool $family): static
    {
        $this->family = $family;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getThumbnail(): ?string
    {
        return $this->thumbnail;
    }

    public function setThumbnail(?string $thumbnail): static
    {
        $this->thumbnail = $thumbnail;

        return $this;
    }
}

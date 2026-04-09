<?php

namespace App\Entity;

use App\Repository\HikeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    #[ORM\Column(name: 'hike_level', length: 15)]
    private ?string $level = null;

    #[ORM\Column(name: 'hike_length')]
    private ?float $length = null;

    #[ORM\Column(name: 'hike_family')]
    private ?bool $family = null;

    #[ORM\Column(name: 'hike_description', type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(name: 'hike_thumbnail', length: 50, nullable: true)]
    private ?string $thumbnail = null;
    
    /**
     * @var Collection<int, Comment>
     */
    #[ORM\OneToMany(targetEntity: Comment::class, mappedBy: 'hike')]
    private Collection $comment;

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'favourite')]
    private Collection $favourite;

    #[ORM\ManyToOne(targetEntity: Location::class, inversedBy: 'hikes')]
    #[ORM\JoinColumn(name: 'location_id', referencedColumnName: 'location_id')]
    private ?Location $location = null;

    public function __construct()
    {
        $this->comment = new ArrayCollection();
        $this->favourite = new ArrayCollection();
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

    /**
     * @return Collection<int, Comment>
     */
    public function getComment(): Collection
    {
        return $this->comment;
    }

    public function addComment(Comment $comment): static
    {
        if (!$this->comment->contains($comment)) {
            $this->comment->add($comment);
            $comment->setHike($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): static
    {
        if ($this->comment->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getHike() === $this) {
                $comment->setHike(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getFavourite(): Collection
    {
        return $this->favourite;
    }

    public function addFavourite(User $favourite): static
    {
        if (!$this->favourite->contains($favourite)) {
            $this->favourite->add($favourite);
            $favourite->addFavourite($this);
        }

        return $this;
    }

    public function removeFavourite(User $favourite): static
    {
        if ($this->favourite->removeElement($favourite)) {
            $favourite->removeFavourite($this);
        }

        return $this;
    }

    public function getLocation(): ?Location
    {
        return $this->location;
    }

    public function setLocation(?Location $location): static
    {
        $this->location = $location;

        return $this;
    }
}

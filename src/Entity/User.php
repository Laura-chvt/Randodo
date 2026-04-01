<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name:'user_id')]
    private ?int $id = null;

    #[ORM\Column(name: 'user_name', length: 100)]
    private ?string $name = null;

    #[ORM\Column(name: 'user_firstname', length: 100)]
    private ?string $firstname = null;

    #[ORM\Column(name: 'user_image', length: 150)]
    private ?string $image = null;

    #[ORM\Column(name: 'user_mail', length: 100)]
    private ?string $mail = null;

    #[ORM\Column(name: 'user_password', length: 255)]
    private ?string $password = null;

    #[ORM\Column(name: 'user_description', length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column(name: 'user_role')]
    private array $role = [];

    /**
     * @var Collection<int, Comment>
     */
    #[ORM\OneToMany(targetEntity: Comment::class, mappedBy: 'userComment')]
    private Collection $comment;

    /**
     * @var Collection<int, Hike>
     */
    #[ORM\ManyToMany(targetEntity: Hike::class, inversedBy: 'favourite')]
    private Collection $favourite;

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

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): static
    {
        $this->mail = $mail;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

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

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getRole(): array
    {
        return $this->role;
    }

    public function setRole(array $role): static
    {
        $this->role = $role;

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
            $comment->setUserComment($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): static
    {
        if ($this->comment->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getUserComment() === $this) {
                $comment->setUserComment(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Hike>
     */
    public function getFavourite(): Collection
    {
        return $this->favourite;
    }

    public function addFavourite(Hike $favourite): static
    {
        if (!$this->favourite->contains($favourite)) {
            $this->favourite->add($favourite);
        }

        return $this;
    }

    public function removeFavourite(Hike $favourite): static
    {
        $this->favourite->removeElement($favourite);

        return $this;
    }
}

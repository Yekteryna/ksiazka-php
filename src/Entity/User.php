<?php

namespace App\Entity;

use App\Repository\UserRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
/**
 * Class User.
 */
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: 'user')]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * Primary key.
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * Role.
     */
    #[ORM\Column(nullable: true)]
    #[ORM\Column(type: 'json')]
    #[Assert\NotBlank]
    private array $role = [];

    /**
     * Nickname.
     */
    #[ORM\Column(length: 64)]
    #[Assert\Type('string')]
    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 64)]
    private ?string $nickname = null;

    /**
     * Email.
     */
    #[ORM\Column(length: 45)]
    #[Assert\NotBlank]
    #[Assert\Email]
    #[Assert\Type('string')]
    #[Assert\Length(min: 3, max: 45)]
    private ?string $email = null;

    /**
     * Password.
     */
    #[ORM\Column(length: 200)]
    #[Assert\Type('string')]
    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 200)]
    private ?string $password = null;


    /**
     * Status
     *
     */
    #[ORM\Column(type:"string", length:255, nullable:false)]
    #[Assert\NotBlank]
    private ?int $status = null;

    /**
     * CreatedAt.
     */
    #[ORM\Column(nullable: true)]
    #[ORM\Column]
    #[Assert\NotBlank]
    private ?DateTimeImmutable $created_at = null;

    /**
     * Recipes.
     */
    #[ORM\OneToMany(mappedBy: 'user_id', targetEntity: Recipe::class, orphanRemoval: true)]
    #[ORM\JoinColumn(nullable: false)]
    private Collection $recipes;

    public const STATUS_ACTIVE = 1;
    public const STATUS_DISABLED = 0;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->recipes = new ArrayCollection();
    }

    /**
     * Convert.
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->getEmail();
    }

    /**
     * Getter Id.
     *
     * @return int|null ID
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Getter NickName.
     *
     * @return string|null NickName
     */
    public function getNickname(): ?string
    {
        return $this->nickname;
    }

    /**
     * Nickname Setter.
     *
     * @param string $nickname Nickname
     *
     * @return self
     */
    public function setNickname(string $nickname): self
    {
        $this->nickname = $nickname;

        return $this;
    }

    /**
     * Getter CreatedAt.
     *
     * @return DateTimeImmutable|null CreatedAt
     */
    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->created_at;
    }

    /**
     * CreatedAt Setter.
     *
     * @param DateTimeImmutable $created_at No
     *
     * @return self
     */
    public function setCreatedAt(DateTimeImmutable $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    /**
     * Getter No.
     *
     * @return string|null No
     */
    public function getNo(): ?string
    {
        return $this->no;
    }

    /**
     * No Setter.
     *
     * @param string $no No
     *
     * @return self
     */
    public function setNo(string $no): self
    {
        $this->no = $no;

        return $this;
    }

    /**
     * Getter Recipes
     *
     * @return Collection<int, Recipe> Recipes
     */
    public function getRecipes(): Collection
    {
        return $this->recipes;
    }

    public function addRecipe(Recipe $recipe): self
    {
        if (!$this->recipes->contains($recipe)) {
            $this->recipes->add($recipe);
            $recipe->setUserId($this);
        }

        return $this;
    }

    public function removeRecipe(Recipe $recipe): self
    {
        if ($this->recipes->removeElement($recipe)) {
            // set the owning side to null (unless already changed)
            if ($recipe->getUserId() === $this) {
                $recipe->setUserId(null);
            }
        }

        return $this;
    }

    /**
     * Getter Role.
     *
     * @return array Role
     */
    public function getRole(): array
    {
        $roles = $this->role;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * Role Setter.
     *
     * @param array|null $role Role
     *
     * @return self
     */
    public function setRole(?array $role): self
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Getter Email.
     *
     * @return string|null Email
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * Email Setter.
     *
     * @param string $email Email
     *
     * @return self
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Getter Password.
     *
     * @return string|null Password
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * Password Setter.
     *
     * @param string $password Password
     *
     * @return self
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Getter Status.
     *
     * @return int|null Status
     */
    public function getStatus(): ?int
    {
        return $this->status;
    }

    /**
     * Status Setter.
     *
     * @param int $status Status
     *
     * @return self
     */
    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Getter Roles.
     *
     * @return array Roles
     */
    public function getRoles(): array
    {
        $roles = $this->role;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    /**
     * Getter UserIdentifier.
     *
     * @return string UserIdentifier
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }
}

<?php

namespace App\Entity;

use App\Repository\CommentRepository;
use Doctrine\ORM\Mapping as ORM;
/**
 * Class Comment.
 */
#[ORM\Entity(repositoryClass: CommentRepository::class)]
class Comment
{
    /**
     * Primary key.
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * Recipe.
     */
    #[ORM\ManyToOne(inversedBy: 'comments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Recipe $recipe = null;

    /**
     * NickName.
     */
    #[ORM\Column(length: 45)]
    private ?string $nickname = null;

    /**
     * Email.
     */
    #[ORM\Column(length: 45)]
    private ?string $email = null;

    /**
     * Message.
     */
    #[ORM\Column(length: 255)]
    private ?string $message = null;

    /**
     * Created at.
     */
    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $created_at = null;

    /**
     * Getter Id.
     *
     * @return int|null Id
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Getter Recipe.
     *
     * @return Recipe|null Recipe
     */
    public function getRecipe(): ?Recipe
    {
        return $this->recipe;
    }

    /**
     * Recipe Setter.
     *
     * @param Recipe|null $recipe Recipe
     *
     * @return self
     */
    public function setRecipe(?Recipe $recipe): self
    {
        $this->recipe = $recipe;

        return $this;
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
     * Getter Message.
     *
     * @return string|null Message
     */
    public function getMessage(): ?string
    {
        return $this->message;
    }

    /**
     * Message Setter.
     *
     * @param string $message Message
     *
     * @return self
     */
    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Getter CreatedAt.
     *
     * @return DateTimeImmutable|null CreatedAt
     */
    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    /**
     * CreatedAt Setter.
     *
     * @param DateTimeImmutable|null $created_at CreatedAt
     *
     * @return self
     */
    public function setCreatedAt(?\DateTimeImmutable $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }
}

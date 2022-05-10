<?php

namespace App\Entity;

use App\Repository\ForumCommentaryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass=ForumCommentaryRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class ForumCommentary
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="text")
     */
    private $commentary;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="forumCommentarys")
     *  @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=ForumSubject::class, inversedBy="forumCommentary")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $forumSubject;

    /**
     * @Gedmo\Slug(fields={"commentary"})
     * @ORM\Column(type="string", length=150, unique=true)
     */
    private $slug;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $userPseudo;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

       /**
     * @ORM\PrePersist
     */
    public function setCreatedAt(): self
    {
        $this->createdAt = new \DateTimeImmutable();

        return $this;
    }

    public function getCommentary(): ?string
    {
        return $this->commentary;
    }

    public function setCommentary(string $commentary): self
    {
        $this->commentary = $commentary;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getForumSubject(): ?ForumSubject
    {
        return $this->forumSubject;
    }

    public function setForumSubject(?ForumSubject $forumSubject): self
    {
        $this->forumSubject = $forumSubject;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function getUserPseudo(): ?string
    {
        return $this->userPseudo;
    }

    public function setUserPseudo(?string $userPseudo): self
    {
        $this->userPseudo = $userPseudo;

        return $this;
    }

    public function __toString()
    {
        // return $this->user->getPseudo();
        return $this->user->getPseudo();
    }

}

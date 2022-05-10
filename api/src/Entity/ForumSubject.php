<?php

namespace App\Entity;

use App\Repository\ForumSubjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass=ForumSubjectRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class ForumSubject
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
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @Gedmo\Slug(fields={"title"})
     * @ORM\Column(type="string", length=200, unique=true)
     */
    private $slug;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="forumSubjects")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=ForumCommentary::class, mappedBy="forumSubject", cascade={"persist"})
     */
    private $forumCommentary;

    /**
     * @ORM\ManyToMany(targetEntity=ForumCategory::class, inversedBy="forumSubjects")
     */
    private $forumCategory;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $userPseudo;

    public function __construct()
    {
        $this->forumCommentary = new ArrayCollection();
        $this->forumCategory = new ArrayCollection();
    }

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

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    /**
     * @ORM\PreUpdate
     */
    public function setUpdatedAt(): self
    {
        $this->updatedAt = new \DateTimeImmutable();

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

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

    /**
     * @return Collection|ForumCommentary[]
     */
    public function getForumCommentary(): Collection
    {
        return $this->forumCommentary;
    }

    public function addForumCommentary(ForumCommentary $forumCommentary): self
    {
        if (!$this->forumCommentary->contains($forumCommentary)) {
            $this->forumCommentary[] = $forumCommentary;
            $forumCommentary->setForumSubject($this);
        }

        return $this;
    }

    public function removeForumCommentary(ForumCommentary $forumCommentary): self
    {
        if ($this->forumCommentary->removeElement($forumCommentary)) {
            // set the owning side to null (unless already changed)
            if ($forumCommentary->getForumSubject() === $this) {
                $forumCommentary->setForumSubject(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ForumCategory[]
     */
    public function getForumCategory(): Collection
    {
        return $this->forumCategory;
    }

    // public function addForumCategory(ForumCategory $forumCategory): self
    // {
    //     if (!$this->forumCategory->contains($forumCategory)) {
    //         $this->forumCategory[] = $forumCategory;
    //     }

    //     return $this;
    // }

    // public function removeForumCategory(ForumCategory $forumCategory): self
    // {
    //     $this->forumCategory->removeElement($forumCategory);

    //     return $this;
    // }

    public function __toString()
    {
        return $this->title;
    }


}


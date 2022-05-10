<?php

namespace App\Entity;

use App\Repository\ForumCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass=ForumCategoryRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class ForumCategory
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
    private $name;

    /**
     * @Gedmo\Slug(fields={"name"})
     * @ORM\Column(type="string", length=150, unique=true)
     */
    private $slug;

    /**
     * @ORM\ManyToMany(targetEntity=ForumSubject::class, mappedBy="forumCategory")
     */
    private $forumSubjects;

    public function __construct()
    {
        $this->forumSubjects = new ArrayCollection();
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    /**
     * @return Collection|ForumSubject[]
     */
    public function getForumSubjects(): Collection
    {
        return $this->forumSubjects;
    }

    public function addForumSubject(ForumSubject $forumSubject): self
    {
        if (!$this->forumSubjects->contains($forumSubject)) {
            $this->forumSubjects[] = $forumSubject;
            $forumSubject->addForumCategory($this);
        }

        return $this;
    }

    public function removeForumSubject(ForumSubject $forumSubject): self
    {
        if ($this->forumSubjects->removeElement($forumSubject)) {
            $forumSubject->removeForumCategory($this);
        }

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }
}


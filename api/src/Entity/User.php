<?php

namespace App\Entity;

use Serializable;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\Common\Collections\ArrayCollection;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
// Téléchergement d'images
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;


#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ["email"], message: "Il y a déjà un compte avec cet email")]
#[ORM\HasLifecycleCallbacks()]
#[Vich\Uploadable]
class User implements UserInterface, PasswordAuthenticatedUserInterface, Serializable
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100, unique=true)
     * 
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isVerified = false;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $pseudo;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @var string
     */
    private $avatarFilename;

    /**
     * @Vich\UploadableField(mapping="user_images", fileNameProperty="avatarFilename")
     * @var File | null
     */
    private $avatarFile;

    /**
     * @Gedmo\Slug(fields={"pseudo"})
     * @ORM\Column(type="string", length=150, unique=true)
     */
    private $slug;

    /**
     * @ORM\OneToMany(targetEntity=ForumSubject::class, mappedBy="user", cascade={"persist"})
     */
    private $forumSubjects;

    /**
     * @ORM\OneToMany(targetEntity=ForumCommentary::class, mappedBy="user", cascade={"persist"})
     */
    private $forumCommentarys;


    public function __construct()
    {
        $this->forumSubjects = new ArrayCollection();
        $this->forumCommentarys = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @ORM\PrePersist
     */
    public function setCreatedAt(): self
    {
        $this->createdAt = new \DateTime();

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    /**
     * @ORM\PreUpdate
     */
    public function setUpdatedAt(): self
    {
        $this->updatedAt = new \DateTime();

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(?string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(?string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function getAvatarFilename(): ?string
    {
        return $this->avatarFilename;
    }

    public function setAvatarFilename($avatarFilename): self
    {
        $this->avatarFilename = $avatarFilename;

        return $this;
    }

    /**
     * @return File|null
     */
    public function getAvatarFile()
    {
        return $this->avatarFile;
    }

    public function setAvatarFile(File $avatarFile = null)
    {
        $this->avatarFile = $avatarFile;

        // VERY IMPORTANT:
        // It is required that at least one field changes if you are using Doctrine,
        // otherwise the event listeners won't be called and the file is lost
        if ($avatarFile) {
            // if 'updatedAt' is not defined in your entity, use another property
            $this->updatedAt = new \DateTime('now');
        }
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
            $forumSubject->setUser($this);
        }

        return $this;
    }

    public function removeForumSubject(ForumSubject $forumSubject): self
    {
        if ($this->forumSubjects->removeElement($forumSubject)) {
            // set the owning side to null (unless already changed)
            if ($forumSubject->getUser() === $this) {
                $forumSubject->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ForumCommentary[]
     */
    public function getForumCommentarys(): Collection
    {
        return $this->forumCommentarys;
    }

    public function addForumCommentary(ForumCommentary $forumCommentary): self
    {
        if (!$this->forumCommentarys->contains($forumCommentary)) {
            $this->forumCommentarys[] = $forumCommentary;
            $forumCommentary->setUser($this);
        }

        return $this;
    }

    public function removeForumCommentary(ForumCommentary $forumCommentary): self
    {
        if ($this->forumCommentarys->removeElement($forumCommentary)) {
            // set the owning side to null (unless already changed)
            if ($forumCommentary->getUser() === $this) {
                $forumCommentary->setUser(null);
            }
        }

        return $this;
    }

    public function serialize()
    {
        return serialize(array( 
            $this->id,
            $this->email,
            $this->roles,
            $this->password,
            $this->isVerified,
            $this->createdAt,
            $this->updatedAt,
            $this->lastname,
            $this->firstname,
            $this->pseudo,
            $this->avatarFilename,
            $this->slug,
            $this->forumSubjects,
            $this->forumCommentarys,

        ));
    }

    public function unserialize($serialized)
    {
        list(
            $this->id,
            $this->email,
            $this->roles,
            $this->password,
            $this->isVerified,
            $this->createdAt,
            $this->updatedAt,
            $this->lastname,
            $this->firstname,
            $this->pseudo,
            $this->avatarFilename,
            $this->slug,
            $this->forumSubjects,
            $this->forumCommentarys,
        ) = unserialize($serialized);
    }

    public function __toString()
    {
        return $this->pseudo;
    }


    
}

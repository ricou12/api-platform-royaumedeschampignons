<?php

namespace App\Entity;

use App\Repository\MediaRepository;
// API plateform
use ApiPlatform\Core\Annotation\ApiResource;
// Annotations pour gérer les appels REST (CRUD)
use Symfony\Component\Serializer\Annotation\Groups;
// Filtrer les requêtes ORM
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\DateFilter;
// validateurs de données entrantes
use Symfony\Component\Validator\Constraints as Assert;
// Générer les slugs
use Gedmo\Mapping\Annotation as Gedmo;
// Doctrine
use Doctrine\ORM\Mapping as ORM;
// gestion de l'upload des photos
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;


#[ORM\Entity(repositoryClass: MediaRepository::class)]
#[ORM\HasLifecycleCallbacks()]
#[Vich\Uploadable]
#[ApiResource(
    normalizationContext: ['groups' => ['api:read']],
    denormalizationContext: ['groups' => ['api:write']],
    order: ['createdAt' => 'DESC'],
)]
class Media
{
    #[ORM\Id, ORM\Column, ORM\GeneratedValue]
    private $id;

    #[ORM\Column(type: "string", length: 255)]
    private $path;

    #[Vich\UploadableField(mapping: "mushroom_images", fileNameProperty: "path")]
    private $imageFile;

    #[ORM\Column(type: "datetime_immutable")]
    private $createdAt;

    #[ORM\Column(type: "datetime_immutable", nullable: true)]
    private $updatedAt;

    #[ORM\Column(type: "string", length: 100, nullable: true)]
    private $name;

    #[ORM\ManyToOne(targetEntity: Mushroom::class, inversedBy: "medias")]
    #[ORM\JoinColumn(nullable: false)]
    private $mushroom;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    #[ORM\PrePersist]
    public function setCreatedAt(): self
    {
        $this->createdAt = new \DateTimeImmutable();

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    #[ORM\PreUpdate]
    public function setUpdatedAt(): self
    {
        $this->updatedAt = new \DateTimeImmutable();

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(?string $path): self
    {
        $this->path = $path;

        return $this;
    }

    public function getImageFile()
    {
        return $this->imageFile;
    }

    public function setImageFile(File $image = null)
    {
        $this->imageFile = $image;

        // VERY IMPORTANT:
        // It is required that at least one field changes if you are using Doctrine,
        // otherwise the event listeners won't be called and the file is lost
        if ($image) {
            // if 'updatedAt' is not defined in your entity, use another property
            $this->updatedAt = new \DateTime('now');
        }
    }

    public function setMushroom(?Mushroom $mushroom): self
    {
        $this->mushroom = $mushroom;

        return $this;
    }

    public function getMushroom(): ?Mushroom
    {
        return $this->mushroom;
    }

    public function __toString()
    {
        return $this->mushroom->getCommonname() . ' : ' . $this->path;
    }

}

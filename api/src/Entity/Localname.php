<?php

namespace App\Entity;

use App\Repository\LocalnameRepository;
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


#[ORM\Entity(repositoryClass: LocalnameRepository::class)]
#[ORM\HasLifecycleCallbacks()]
#[ApiResource(
    normalizationContext: ['groups' => ['api:read']],
    denormalizationContext: ['groups' => ['api:write']],
    order: ['createdAt' => 'DESC'],
)]
class Localname
{
    #[ORM\Id, ORM\Column, ORM\GeneratedValue]
    private $id;

    #[ORM\Column(type: "datetime_immutable")]
    private $createdAt;

    #[ORM\Column(type: "datetime_immutable", nullable: true)]
    private $updatedAt;

    #[ORM\Column(type: "string", length: 100, nullable: true)]
    private $name;

    #[Gedmo\Slug(fields: ["name"])]
    #[ORM\Column(type: "string", length: 150, unique: true)]
    private $slug;

    #[ORM\ManyToOne(targetEntity: Mushroom::class, inversedBy: "localname")]
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

    public function getMushroom(): ?Mushroom
    {
        return $this->mushroom;
    }

    public function setMushroom(?Mushroom $mushroom): self
    {
        $this->mushroom = $mushroom;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function __toString()
    {
        return $this->name;
    }
}


<?php

namespace App\Entity;

use App\Repository\EdibilityRepository;
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
//Doctrine
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;


#[ORM\Entity(repositoryClass: EdibilityRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['api:read']],
    denormalizationContext: ['groups' => ['api:write']],
)]
class Edibility
{
    #[ORM\Id, ORM\Column, ORM\GeneratedValue]
    private $id;

    #[ORM\Column(type: 'string', length: 100, nullable: true)]
    private $name;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $path;

    #[Gedmo\Slug(fields:['name'])]
    #[ORM\Column(type: 'string', length: 150, unique: true)]
    private $slug;

    #[ORM\OneToMany(targetEntity: Mushroom::class, mappedBy: 'edibility')]
    private $mushrooms;

    public function __construct()
    {
        $this->mushrooms = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    /**
     * @return Collection|Mushroom[]
     */
    public function getMushrooms(): Collection
    {
        return $this->mushrooms;
    }

    public function addMushroom(Mushroom $mushroom): self
    {
        if (!$this->mushrooms->contains($mushroom)) {
            $this->mushrooms[] = $mushroom;
            $mushroom->setEdibility($this);
        }

        return $this;
    }

    public function removeMushroom(Mushroom $mushroom): self
    {
        if ($this->mushrooms->removeElement($mushroom)) {
            // set the owning side to null (unless already changed)
            if ($mushroom->getEdibility() === $this) {
                $mushroom->setEdibility(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }
}


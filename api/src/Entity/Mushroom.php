<?php

namespace App\Entity;

use App\Repository\MushroomRepository;
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
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: MushroomRepository::class)]
#[ORM\HasLifecycleCallbacks()]
#[ApiResource(
    normalizationContext: ['groups' => ['mushroom_detail:read','mushroom_liste:read']],       // Sérialiser
    denormalizationContext: ['groups' => ['mushroom_detail:write','mushroom_liste:write']],    // Désérialiser
    order: ['createdAt' => 'DESC'],                         // Trier 
    attributes: ["security" => "is_granted('ROLE_USER')", "pagination_items_per_page" => 30]         // Modifier la pagination
    // collectionOperations: [
    //     "get",
    //     "post" => ["security" => "is_granted('ROLE_ADMIN')"],
    // ],
    // itemOperations: [
    //     "get",
    //     "put" => ["security" => "is_granted('ROLE_ADMIN') or object.owner == user"],
    // ],
)]
class Mushroom
{
    #[ORM\Id, ORM\Column, ORM\GeneratedValue]
    private $id;

    #[ORM\Column(type: "datetime", nullable: true)]
    #[Groups(["mushroom_detail:read"])]
    private $createdAt;

    #[ORM\Column(type: "datetime", nullable: true)]
    #[Groups(["mushroom_detail:read"])]
    private $updatedAt;

    #[ORM\Column(type: "boolean", nullable: true)]
    #[Groups(["mushroom_detail:read","mushroom_detail:write"])]
    private $visibility;

    #[ORM\Column(type: "string", length: 100)]
    #[Groups(["mushroom_detail:read","mushroom_detail:write", 'mushroom_liste:read', ])]
    private $commonname;

    #[ORM\Column(type: "string", length: 100, nullable: true)]
    private $latinname;

    #[ORM\Column(type: "text", nullable: true)]
    private $flesh;

    #[ORM\Column(type: "text", nullable: true)]
    private $hat;

    #[ORM\Column(type: "text", nullable: true)]
    private $lamella;

    #[ORM\Column(type: "text", nullable: true)]
    private $foot;

    #[ORM\Column(type: "text", nullable: true)]
    private $habitat;

    #[ORM\Column(type: "text", nullable: true)]
    private $comment;

    #[ORM\ManyToOne(targetEntity:Lamellatype::class, inversedBy: "mushrooms")]
    private $lamellatype;

    #[ORM\OneToMany(targetEntity: Localname::class, mappedBy: "mushroom", orphanRemoval: true,cascade: ["persist", "remove"])]
    private $localname;

    #[ORM\ManyToOne(targetEntity: Edibility::class, inversedBy: "mushrooms")]
    private $edibility;

    #[ORM\OneToMany(targetEntity: Media::class, mappedBy: "mushroom", orphanRemoval: true,cascade: ["persist", "remove"])]
    private $medias;

    #[Gedmo\Slug(fields: ["commonname"])]
    #[ORM\Column(type: "string", length: 150, unique: true)]
    private $slug;

    public function __construct()
    {
        $this->localname = new ArrayCollection();
        $this->medias = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getVisibility(): ?bool
    {
        return $this->visibility;
    }

    public function setVisibility(?bool $visibility): self
    {
        $this->visibility = $visibility;

        return $this;
    }

    public function getCommonname(): ?string
    {
        return $this->commonname;
    }

    public function setCommonname(?string $commonname): self
    {
        $this->commonname = $commonname;

        return $this;
    }

    public function getLatinname(): ?string
    {
        return $this->latinname;
    }

    public function setLatinname(?string $latinname): self
    {
        $this->latinname = $latinname;

        return $this;
    }

    public function getFlesh(): ?string
    {
        return $this->flesh;
    }

    public function setFlesh(?string $flesh): self
    {
        $this->flesh = $flesh;

        return $this;
    }

    public function getHat(): ?string
    {
        return $this->hat;
    }

    public function setHat(?string $hat): self
    {
        $this->hat = $hat;

        return $this;
    }

    public function getLamella(): ?string
    {
        return $this->lamella;
    }

    public function setLamella(?string $lamella): self
    {
        $this->lamella = $lamella;

        return $this;
    }

    public function getFoot(): ?string
    {
        return $this->foot;
    }

    public function setFoot(?string $foot): self
    {
        $this->foot = $foot;

        return $this;
    }

    public function getHabitat(): ?string
    {
        return $this->habitat;
    }

    public function setHabitat(?string $habitat): self
    {
        $this->habitat = $habitat;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getLamellatype(): ?Lamellatype
    {
        return $this->lamellatype;
    }

    public function setLamellatype(?Lamellatype $lamellatype): self
    {
        $this->lamellatype = $lamellatype;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    /**
     * @return Collection|Localname[]
     */
    public function getLocalname(): Collection
    {
        return $this->localname;
    }

    public function addLocalname(Localname $localname): self
    {
        if (!$this->localname->contains($localname)) {
            $this->localname[] = $localname;
            $localname->setMushroom($this);
        }

        return $this;
    }

    public function removeLocalname(Localname $localname): self
    {
        if ($this->localname->removeElement($localname)) {
            // set the owning side to null (unless already changed)
            if ($localname->getMushroom() === $this) {
                $localname->setMushroom(null);
            }
        }

        return $this;
    }

    public function getEdibility(): ?Edibility
    {
        return $this->edibility;
    }

    public function setEdibility(?Edibility $edibility): self
    {
        $this->edibility = $edibility;

        return $this;
    }

    /**
     * @return Collection|Media[]
     */
    public function getMedias(): Collection
    {
        return $this->medias;
    }

    public function addMedia(Media $media): self
    {
        if (!$this->medias->contains($media)) {
            $this->medias[] = $media;
            $media->setMushroom($this);
        }

        return $this;
    }

    public function removeMedia(Media $media): self
    {
        if ($this->medias->removeElement($media)) {
            // set the owning side to null (unless already changed)
            if ($media->getMushroom() === $this) {
                $media->setMushroom(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->commonname;
    }

}

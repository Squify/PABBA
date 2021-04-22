<?php

namespace App\Entity;

use App\Repository\TutorialRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TutorialRepository::class)
 */
class Tutorial
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="text", length=255)
     */
    private $description;

    /**
     * @ORM\Column(type="boolean")
     */
    private $disable;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $videoLink;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="tutorials")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=TutorialType::class, inversedBy="tutorials")
     */
    private $type;

    /**
     * @ORM\ManyToMany(targetEntity=TutorialType::class, inversedBy="tutorials")
     */
    private $supplies;

    public function __construct()
    {
        $this->supplies = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDisable(): ?bool
    {
        return $this->disable;
    }

    public function setDisable(bool $disable): self
    {
        $this->disable = $disable;

        return $this;
    }

    public function getVideoLink(): ?string
    {
        return $this->videoLink;
    }

    public function setVideoLink(?string $videoLink): self
    {
        $this->videoLink = $videoLink;

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

    public function getType(): ?TutorialType
    {
        return $this->type;
    }

    public function setType(?TutorialType $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection|TutorialType[]
     */
    public function getSupplies(): Collection
    {
        return $this->supplies;
    }

    public function addSupply(TutorialType $supply): self
    {
        if (!$this->supplies->contains($supply)) {
            $this->supplies[] = $supply;
        }

        return $this;
    }

    public function removeSupply(TutorialType $supply): self
    {
        $this->supplies->removeElement($supply);

        return $this;
    }
}

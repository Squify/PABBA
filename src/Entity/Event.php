<?php

namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EventRepository::class)
 */
class Event
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
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity=Place::class, inversedBy="events")
     */
    private $place;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $eventAt;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="events")
     */
    private $organisers;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isPublished;

    /**
     * @ORM\ManyToOne(targetEntity=EventType::class, inversedBy="events")
     */
    private $eventType;

    public function __construct()
    {
        $this->organisers = new ArrayCollection();
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

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPlace(): ?Place
    {
        return $this->place;
    }

    public function setPlace(?Place $place): self
    {
        $this->place = $place;

        return $this;
    }

    public function getEventAt(): ?\DateTimeInterface
    {
        return $this->eventAt;
    }

    public function setEventAt(?\DateTimeInterface $eventAt): self
    {
        $this->eventAt = $eventAt;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getOrganisers(): Collection
    {
        return $this->organisers;
    }

    public function addOrganiser(User $organiser): self
    {
        if (!$this->organisers->contains($organiser)) {
            $this->organisers[] = $organiser;
        }

        return $this;
    }

    public function removeOrganiser(User $organiser): self
    {
        $this->organisers->removeElement($organiser);

        return $this;
    }

    public function getIsPublished(): ?bool
    {
        return $this->isPublished;
    }

    public function setIsPublished(bool $isPublished): self
    {
        $this->isPublished = $isPublished;

        return $this;
    }

    public function getEventType(): ?EventType
    {
        return $this->eventType;
    }

    public function setEventType(?EventType $eventType): self
    {
        $this->eventType = $eventType;

        return $this;
    }
}

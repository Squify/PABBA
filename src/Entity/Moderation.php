<?php

namespace App\Entity;

use App\Repository\ModerationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ModerationRepository::class)
 */
class Moderation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="moderations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $moderator;

    /**
     * @ORM\OneToMany(targetEntity=ModerationMessage::class, mappedBy="moderation", orphanRemoval=true)
     */
    private $moderationMessages;

    /**
     * @ORM\OneToOne(targetEntity=Rent::class, inversedBy="moderation", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $rent;

    public function __construct()
    {
        $this->moderationMessages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getModerator(): ?User
    {
        return $this->moderator;
    }

    public function setModerator(?User $moderator): self
    {
        $this->moderator = $moderator;

        return $this;
    }

    /**
     * @return Collection|ModerationMessage[]
     */
    public function getModerationMessages(): Collection
    {
        return $this->moderationMessages;
    }

    public function addModerationMessage(ModerationMessage $moderationMessage): self
    {
        if (!$this->moderationMessages->contains($moderationMessage)) {
            $this->moderationMessages[] = $moderationMessage;
            $moderationMessage->setModeration($this);
        }

        return $this;
    }

    public function removeModerationMessage(ModerationMessage $moderationMessage): self
    {
        if ($this->moderationMessages->removeElement($moderationMessage)) {
            // set the owning side to null (unless already changed)
            if ($moderationMessage->getModeration() === $this) {
                $moderationMessage->setModeration(null);
            }
        }

        return $this;
    }

    public function getRent(): ?Rent
    {
        return $this->rent;
    }

    public function setRent(Rent $rent): self
    {
        $this->rent = $rent;

        return $this;
    }
}

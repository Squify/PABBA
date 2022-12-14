<?php

namespace App\Entity;

use App\Repository\RentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RentRepository::class)
 */
class Rent
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="myRents")
     */
    private $owner;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="rents")
     */
    private $renter;

    /**
     * @ORM\OneToOne(targetEntity=Moderation::class, mappedBy="rent", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $moderation;

    /**
     * @ORM\ManyToOne(targetEntity=Item::class, inversedBy="rents")
     */
    private $item;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $rentAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $returnAt;

    /**
     * @ORM\OneToMany(targetEntity=Render::class, mappedBy="rent")
     */
    private $renders;

    public function __construct()
    {
        $this->renders = new ArrayCollection();
    }

    public function __toString()
    {
        return 'Location de ' . $this->getItem() . ' par ' . $this->getRenter();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    public function getRenter(): ?User
    {
        return $this->renter;
    }

    public function setRenter(?User $renter): self
    {
        $this->renter = $renter;

        return $this;
    }

    public function getModeration(): ?Moderation
    {
        return $this->moderation;
    }

    public function setModeration(?Moderation $moderation): self
    {
        // set the owning side of the relation if necessary
        if ($moderation->getRent() !== $this) {
            $moderation->setRent($this);
        }

        $this->moderation = $moderation;

        return $this;
    }

    public function getItem(): ?Item
    {
        return $this->item;
    }

    public function setItem(?Item $item): self
    {
        $this->item = $item;

        return $this;
    }

    public function getRentAt(): ?\DateTimeInterface
    {
        return $this->rentAt;
    }

    public function setRentAt(\DateTimeInterface $rentAt): self
    {
        $this->rentAt = $rentAt;

        return $this;
    }

    public function getReturnAt(): ?\DateTimeInterface
    {
        return $this->returnAt;
    }

    public function setReturnAt(\DateTimeInterface $returnAt): self
    {
        $this->returnAt = $returnAt;

        return $this;
    }

    /**
     * @return Collection|Render[]
     */
    public function getRenders(): Collection
    {
        return $this->renders;
    }

    public function addRender(Render $render): self
    {
        if (!$this->renders->contains($render)) {
            $this->renders[] = $render;
            $render->setRent($this);
        }

        return $this;
    }

    public function removeRender(Render $render): self
    {
        if ($this->renders->removeElement($render)) {
            // set the owning side to null (unless already changed)
            if ($render->getRent() === $this) {
                $render->setRent(null);
            }
        }

        return $this;
    }
}

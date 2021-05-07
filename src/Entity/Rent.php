<?php

namespace App\Entity;

use App\Repository\RentRepository;
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

    public function __toString()
    {
        return 'Le nom de la vente TODO';
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
}

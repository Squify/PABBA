<?php

namespace App\Entity;

use App\Entity\Moderation;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user__user`")
 * @UniqueEntity(fields={"email"}, message="Ce compte existe déjà", entityClass=User::class)
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
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
     * @ORM\Column(type="text", nullable=true)
     */
    private $firstname;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $lastname;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $token;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $enable;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $lastLogin;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $phone;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $birthdate;

    /**
     * @ORM\OneToMany(targetEntity=Place::class, mappedBy="user")
     */
    private $places;

    /**
     * @ORM\OneToMany(targetEntity=Tutorial::class, mappedBy="user")
     */
    private $tutorials;

    /**
     * @ORM\OneToMany(targetEntity=CommentTutorial::class, mappedBy="auteur", orphanRemoval=true)
     */
    private $commentTutorials;

    /**
     * @ORM\OneToMany(targetEntity=Rent::class, mappedBy="renter")
     */
    private $rents;

    /**
     * @ORM\OneToMany(targetEntity=Rent::class, mappedBy="owner")
     */
    private $myRents;

    /**
     * @ORM\OneToMany(targetEntity=Moderation::class, mappedBy="moderator", orphanRemoval=true)
     */
    private $moderations;

    /**
     * @ORM\OneToMany(targetEntity=Item::class, mappedBy="owner", orphanRemoval=true)
     */
    private $items;

    /**
     * @ORM\ManyToMany(targetEntity=Event::class, mappedBy="organisers")
     */
    private $events;

    public function __construct()
    {
        $this->places = new ArrayCollection();
        $this->tutorials = new ArrayCollection();
        $this->commentTutorials = new ArrayCollection();
        $this->rents = new ArrayCollection();
        $this->myRents = new ArrayCollection();
        $this->moderations = new ArrayCollection();
        $this->items = new ArrayCollection();
        $this->events = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(){
        return $this->getFirstname() . ' ' . $this->getLastname();
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
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * @param mixed $firstname
     */
    public function setFirstname($firstname): void
    {
        $this->firstname = $firstname;
    }

    /**
     * @return mixed
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * @param mixed $lastname
     * @return User
     */
    public function setLastname($lastname): self
    {
        $this->lastname = $lastname;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param mixed $token
     * @return User
     */
    public function setToken($token): self
    {
        $this->token = $token;
        return  $this;
    }

    /**
     * @return mixed
     */
    public function getEnable()
    {
        return $this->enable;
    }

    /**
     * @param mixed $enable
     * @return User
     */
    public function setEnable($enable): self
    {
        $this->enable = $enable;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLastLogin()
    {
        return $this->lastLogin;
    }

    /**
     * @param mixed $lastLogin
     * @return User
     */
    public function setLastLogin($lastLogin): self
    {
        $this->lastLogin = $lastLogin;
        return $this;
    }

    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getBirthdate(): ?\DateTimeInterface
    {
        return $this->birthdate;
    }

    public function setBirthdate(?\DateTimeInterface $birthdate): self
    {
        $this->birthdate = $birthdate;

        return $this;
    }

    /**
     * @return Collection|Place[]
     */
    public function getPlaces(): Collection
    {
        return $this->places;
    }

    public function addPlace(Place $place): self
    {
        if (!$this->places->contains($place)) {
            $this->places[] = $place;
            $place->setUser($this);
        }

        return $this;
    }

    public function removePlace(Place $place): self
    {
        if ($this->places->removeElement($place)) {
            // set the owning side to null (unless already changed)
            if ($place->getUser() === $this) {
                $place->setUser(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->email;
    }

    /**
     * @return Collection|Tutorial[]
     */
    public function getTutorials(): Collection
    {
        return $this->tutorials;
    }

    public function addTutorial(Tutorial $tutorial): self
    {
        if (!$this->tutorials->contains($tutorial)) {
            $this->tutorials[] = $tutorial;
            $tutorial->setUser($this);
        }

        return $this;
    }

    public function removeTutorial(Tutorial $tutorial): self
    {
        if ($this->tutorials->removeElement($tutorial)) {
            // set the owning side to null (unless already changed)
            if ($tutorial->getUser() === $this) {
                $tutorial->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|CommentTutorial[]
     */
    public function getCommentTutorials(): Collection
    {
        return $this->commentTutorials;
    }

    public function addCommentTutorial(CommentTutorial $commentTutorial): self
    {
        if (!$this->commentTutorials->contains($commentTutorial)) {
            $this->commentTutorials[] = $commentTutorial;
            $commentTutorial->setAuteur($this);
        }

        return $this;
    }

    public function removeCommentTutorial(CommentTutorial $commentTutorial): self
    {
        if ($this->commentTutorials->removeElement($commentTutorial)) {
            // set the owning side to null (unless already changed)
            if ($commentTutorial->getAuteur() === $this) {
                $commentTutorial->setAuteur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Rent[]
     */
    public function getMyRents(): Collection
    {
        return $this->myRents;
    }

    public function addMyRent(Rent $rent): self
    {
        if (!$this->myRents->contains($rent)) {
            $this->myRents[] = $rent;
            $rent->setOwner($this);
        }

        return $this;
    }

    public function removeMyRent(Rent $rent): self
    {
        if ($this->myRents->removeElement($rent)) {
            // set the owning side to null (unless already changed)
            if ($rent->getOwner() === $this) {
                $rent->setOwner(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Rent[]
     */
    public function getRents(): Collection
    {
        return $this->rents;
    }

    public function addRent(Rent $rent): self
    {
        if (!$this->rents->contains($rent)) {
            $this->rents[] = $rent;
            $rent->setRenter($this);
        }

        return $this;
    }

    public function removeRent(Rent $rent): self
    {
        if ($this->rents->removeElement($rent)) {
            // set the owning side to null (unless already changed)
            if ($rent->getRenter() === $this) {
                $rent->setRenter(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Moderation[]
     */
    public function getModerations(): Collection
    {
        return $this->moderations;
    }

    public function addModeration(Moderation $moderation): self
    {
        if (!$this->moderations->contains($moderation)) {
            $this->moderations[] = $moderation;
            $moderation->setModerator($this);
        }

        return $this;
    }

    public function removeModeration(Moderation $moderation): self
    {
        if ($this->moderations->removeElement($moderation)) {
            // set the owning side to null (unless already changed)
            if ($moderation->getModerator() === $this) {
                $moderation->setModerator(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Item[]
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(Item $item): self
    {
        if (!$this->items->contains($item)) {
            $this->items[] = $item;
            $item->setOwner($this);
        }

        return $this;
    }

    public function removeItem(Item $item): self
    {
        if ($this->items->removeElement($item)) {
            // set the owning side to null (unless already changed)
            if ($item->getOwner() === $this) {
                $item->setOwner(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Event[]
     */
    public function getEvents(): Collection
    {
        return $this->events;
    }

    public function addEvent(Event $event): self
    {
        if (!$this->events->contains($event)) {
            $this->events[] = $event;
            $event->addOrganiser($this);
        }

        return $this;
    }

    public function removeEvent(Event $event): self
    {
        if ($this->events->removeElement($event)) {
            $event->removeOrganiser($this);
        }

        return $this;
    }


}

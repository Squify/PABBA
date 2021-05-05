<?php

namespace App\Entity;

use App\Repository\CommentTutorialRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CommentTutorialRepository::class)
 */
class CommentTutorial
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
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $notes = [];

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="commentTutorials")
     * @ORM\JoinColumn(nullable=false)
     */
    private $auteur;

    /**
     * @ORM\ManyToOne(targetEntity=Tutorial::class, inversedBy="commentTutorials")
     * @ORM\JoinColumn(nullable=false)
     */
    private $tutorial;

    /**
     * CommentTutorial constructor.
     * @param $tutorial
     */
    public function __construct($tutorial = null)
    {
        $this->tutorial = $tutorial;
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

    public function getNotes(): ?array
    {
        return $this->notes;
    }

    public function setNotes(array $notes): self
    {
        $this->notes = $notes;

        return $this;
    }

    public function getAuteur(): ?User
    {
        return $this->auteur;
    }

    public function setAuteur(?User $auteur): self
    {
        $this->auteur = $auteur;

        return $this;
    }

    public function getTutorial(): ?Tutorial
    {
        return $this->tutorial;
    }

    public function setTutorial(?Tutorial $tutorial): self
    {
        $this->tutorial = $tutorial;

        return $this;
    }
}

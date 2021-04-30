<?php

namespace App\Entity;

use App\Repository\TutorialRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=TutorialRepository::class)
 * @Vich\Uploadable
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
     * @Assert\NotBlank(message="Vous devez remplir ce champ")
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @Assert\NotBlank(message="Vous devez remplir ce champ")
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="boolean")
     */
    private $disable;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="tutorials")
     */
    private $user;

    /**
     * @Assert\NotBlank(message="Vous devez remplir ce champ")
     * @ORM\ManyToOne(targetEntity=TutorialType::class, inversedBy="tutorials")
     */
    private $type;

    /**
     * @ORM\ManyToMany(targetEntity=ToolType::class, inversedBy="tutorials")
     */
    private $tools;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $supplies;

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
    *
    * @Vich\UploadableField(mapping="tutorial_image", fileNameProperty="imageName")
    * @Assert\File(maxSize="5M", mimeTypes="image/*")
    * @var File|null
     */
    private $imageFile;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @var string|null
     */
    private $imageName;

    /**
    * @Vich\UploadableField(mapping="tutorial_video", fileNameProperty="videoName", size="50M")
    * @Assert\File(maxSize="50M", mimeTypes="video/*")
    * @var File|null
     */
    private $videoFile;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @var string|null
     */
    private $videoName;

    /**
     * @ORM\Column(type="datetime")
     *
     * @var \DateTimeInterface|null
     */
    private $updatedAt;

    /**
     * @ORM\OneToMany(targetEntity=CommentTutorial::class, mappedBy="tutorial", orphanRemoval=true)
     */
    private $commentTutorials;

    public function __construct()
    {
        $this->tools = new ArrayCollection();
        $this->commentTutorials = new ArrayCollection();
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
     * @return Collection|ToolType[]
     */
    public function getTools(): Collection
    {
        return $this->tools;
    }

    public function addTool(ToolType $tools): self
    {
        if (!$this->tools->contains($tools)) {
            $this->tools[] = $tools;
        }

        return $this;
    }

    public function removeTool(ToolType $tools): self
    {
        $this->tools->removeElement($tools);

        return $this;
    }

    public function getSupplies(): ?string
    {
        return $this->supplies;
    }

    public function setSupplies(string $supplies): self
    {
        $this->supplies = $supplies;

        return $this;
    }

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $imageFile
     */
    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageName(?string $imageName): void
    {
        $this->imageName = $imageName;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    /**
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $videoFile
     */
    public function setVideoFile(?File $videoFile = null): void
    {
        $this->videoFile = $videoFile;

        if (null !== $videoFile) {
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getVideoFile(): ?File
    {
        return $this->videoFile;
    }

    public function setVideoName(?string $videoName): void
    {
        $this->videoName = $videoName;
    }

    public function getVideoName(): ?string
    {
        return $this->videoName;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function setTools(?ToolType $tools): self
    {
        $this->tools = $tools;

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
            $commentTutorial->setTutorial($this);
        }

        return $this;
    }

    public function removeCommentTutorial(CommentTutorial $commentTutorial): self
    {
        if ($this->commentTutorials->removeElement($commentTutorial)) {
            // set the owning side to null (unless already changed)
            if ($commentTutorial->getTutorial() === $this) {
                $commentTutorial->setTutorial(null);
            }
        }

        return $this;
    }
}

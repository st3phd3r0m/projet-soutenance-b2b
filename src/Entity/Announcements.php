<?php

namespace App\Entity;

use App\Repository\AnnouncementsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Gedmo\Mapping\Annotation as Gedmo;


/**
 * @ORM\Entity(repositoryClass=AnnouncementsRepository::class)
 * @Vich\Uploadable
 */
class Announcements
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
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
     * @ORM\Column(type="string", length=255)
     */
    private $image;

    /**
     * @var File|null
     * @Vich\UploadableField(mapping="announcements_images", fileNameProperty="image")
     */
    private $imageFile;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $notes;

    /**
     * @ORM\Column(type="string", length=8)
     */
    private $ref;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $key_words = [];

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=5, nullable=true)
     */
    private $postal_code;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $gps_location = [];

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $price_range = [];

    /**
     * @ORM\ManyToOne(targetEntity=Categories::class, inversedBy="announcements")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    /**
     * @ORM\ManyToOne(targetEntity=Users::class, inversedBy="announcements")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $urgency;

    /**
     * @ORM\OneToMany(targetEntity=CategoriesPropertiesValues::class, mappedBy="announcement")
     */
    private $categoriesPropertiesValues;

    /**
     * @ORM\ManyToOne(targetEntity=Professions::class, inversedBy="announcements")
     * @ORM\JoinColumn(nullable=false)
     */
    private $profession;

    /**
     * @Gedmo\Slug(fields={"title"})
     * @ORM\Column(length=255, unique=true)
     */
    private $slug;

    public function __construct()
    {
        $this->categoriesPropertiesValues = new ArrayCollection();
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

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            $this->updatedAt = new \DateTimeImmutable();
        }
    }


    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(?string $notes): self
    {
        $this->notes = $notes;

        return $this;
    }

    public function getRef(): ?string
    {
        return $this->ref;
    }

    public function setRef(string $ref): self
    {
        $this->ref = $ref;

        return $this;
    }

    public function getKeyWords(): ?array
    {
        return $this->key_words;
    }

    public function setKeyWords(?array $key_words): self
    {
        $this->key_words = $key_words;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postal_code;
    }

    public function setPostalCode(?string $postal_code): self
    {
        $this->postal_code = $postal_code;

        return $this;
    }

    public function getGpsLocation(): ?array
    {
        return $this->gps_location;
    }

    public function setGpsLocation(?array $gps_location): self
    {
        $this->gps_location = $gps_location;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getPriceRange(): ?array
    {
        return $this->price_range;
    }

    public function setPriceRange(?array $price_range): self
    {
        $this->price_range = $price_range;

        return $this;
    }

    public function getCategory(): ?Categories
    {
        return $this->category;
    }

    public function setCategory(?Categories $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getUser(): ?Users
    {
        return $this->user;
    }

    public function setUser(?Users $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getUrgency(): ?int
    {
        return $this->urgency;
    }

    public function setUrgency(?int $urgency): self
    {
        $this->urgency = $urgency;

        return $this;
    }

    /**
     * @return Collection|CategoriesPropertiesValues[]
     */
    public function getCategoriesPropertiesValues(): Collection
    {
        return $this->categoriesPropertiesValues;
    }

    public function addCategoriesPropertiesValue(CategoriesPropertiesValues $categoriesPropertiesValue): self
    {
        if (!$this->categoriesPropertiesValues->contains($categoriesPropertiesValue)) {
            $this->categoriesPropertiesValues[] = $categoriesPropertiesValue;
            $categoriesPropertiesValue->setAnnouncement($this);
        }

        return $this;
    }

    public function removeCategoriesPropertiesValue(CategoriesPropertiesValues $categoriesPropertiesValue): self
    {
        if ($this->categoriesPropertiesValues->contains($categoriesPropertiesValue)) {
            $this->categoriesPropertiesValues->removeElement($categoriesPropertiesValue);
            // set the owning side to null (unless already changed)
            if ($categoriesPropertiesValue->getAnnouncement() === $this) {
                $categoriesPropertiesValue->setAnnouncement(null);
            }
        }

        return $this;
    }

    public function getProfession(): ?Professions
    {
        return $this->profession;
    }

    public function setProfession(?Professions $profession): self
    {
        $this->profession = $profession;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }
}

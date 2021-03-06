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
 * @ORM\Table(indexes={@ORM\Index(columns={"title", "description"}, flags={"fulltext"})})
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
     * @ORM\Column(type="string", nullable=true)
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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=5, nullable=true)
     */
    private $postal_code;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $departememt;

    /**
     * @ORM\Column(type="string", length=3)
     */
    private $dept_code;

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
     * @Gedmo\Slug(fields={"title"})
     * @ORM\Column(length=255, unique=true)
     */
    private $slug;

    /**
     * @ORM\ManyToOne(targetEntity=ActivitySector::class, inversedBy="announcements")
     */
    private $activity_sector;

    /**
     * @ORM\Column(type="integer")
     */
    private $unlock_count;

    /**
     * @ORM\OneToMany(targetEntity=UnlockedAnnouncements::class, mappedBy="announcements", cascade={"persist"})
     */
    private $unlockedAnnouncements;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=180)
     */
    private $email;

    /**
     * @ORM\ManyToMany(targetEntity=Users::class, inversedBy="favoris")
     */
    private $favoris;

    public function __construct()
    {
        $this->favoris = new ArrayCollection();
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

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getActivitySector(): ?ActivitySector
    {
        return $this->activity_sector;
    }

    public function setActivitySector(?ActivitySector $activity_sector): self
    {
        $this->activity_sector = $activity_sector;

        return $this;
    }

    public function getUnlockCount(): ?int
    {
        return $this->unlock_count;
    }

    public function setUnlockCount(int $unlock_count): self
    {
        $this->unlock_count = $unlock_count;

        return $this;
    }

    /**
     * @return Collection|UnlockedAnnouncements[]
     */
    public function getUnlockedAnnouncements(): Collection
    {
        return $this->unlockedAnnouncements;
    }

    public function addUnlockedAnnouncement(UnlockedAnnouncements $unlockedAnnouncement): self
    {
        if (!$this->unlockedAnnouncements->contains($unlockedAnnouncement)) {
            $this->unlockedAnnouncements[] = $unlockedAnnouncement;
            $unlockedAnnouncement->setAnnouncements($this);
        }

        return $this;
    }

    public function removeUnlockedAnnouncement(UnlockedAnnouncements $unlockedAnnouncement): self
    {
        if ($this->unlockedAnnouncements->contains($unlockedAnnouncement)) {
            $this->unlockedAnnouncements->removeElement($unlockedAnnouncement);
            // set the owning side to null (unless already changed)
            if ($unlockedAnnouncement->getAnnouncements() === $this) {
                $unlockedAnnouncement->setAnnouncements(null);
            }
        }

        return $this;
    }

    public function getDepartememt(): ?string
    {
        return $this->departememt;
    }

    public function setDepartememt(string $departememt): self
    {
        $this->departememt = $departememt;

        return $this;
    }

    public function getDeptCode(): ?string
    {
        return $this->dept_code;
    }

    public function setDeptCode(string $dept_code): self
    {
        $this->dept_code = $dept_code;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
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
     * @return Collection|Users[]
     */
    public function getFavoris(): Collection
    {
        return $this->favoris;
    }

    public function addFavori(Users $favori): self
    {
        if (!$this->favoris->contains($favori)) {
            $this->favoris[] = $favori;
        }

        return $this;
    }

    public function removeFavori(Users $favori): self
    {
        if ($this->favoris->contains($favori)) {
            $this->favoris->removeElement($favori);
        }

        return $this;
    }

   
}

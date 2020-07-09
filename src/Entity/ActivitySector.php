<?php

namespace App\Entity;

use App\Repository\ActivitySectorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass=ActivitySectorRepository::class)
 */
class ActivitySector
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $name;

    /**
     * @Gedmo\Slug(fields={"name"})
     * @ORM\Column(length=255, unique=true)
     */
    private $slug;

    /**
     * @ORM\OneToMany(targetEntity=Announcements::class, mappedBy="activity_sector")
     */
    private $announcements;

    public function __construct()
    {
        $this->professions = new ArrayCollection();
        $this->announcements = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|Professions[]
     */
    public function getProfessions(): Collection
    {
        return $this->professions;
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

    /**
     * @return Collection|Announcements[]
     */
    public function getAnnouncements(): Collection
    {
        return $this->announcements;
    }

    public function addAnnouncement(Announcements $announcement): self
    {
        if (!$this->announcements->contains($announcement)) {
            $this->announcements[] = $announcement;
            $announcement->setActivitySector($this);
        }

        return $this;
    }

    public function removeAnnouncement(Announcements $announcement): self
    {
        if ($this->announcements->contains($announcement)) {
            $this->announcements->removeElement($announcement);
            // set the owning side to null (unless already changed)
            if ($announcement->getActivitySector() === $this) {
                $announcement->setActivitySector(null);
            }
        }

        return $this;
    }
}

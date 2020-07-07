<?php

namespace App\Entity;

use App\Repository\ProfessionsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass=ProfessionsRepository::class)
 */
class Professions
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
     * @ORM\ManyToOne(targetEntity=ActivitySector::class, inversedBy="professions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $activitySector;

    /**
     * @ORM\OneToMany(targetEntity=Announcements::class, mappedBy="profession")
     */
    private $announcements;

    /**
     * @Gedmo\Slug(fields={"name"})
     * @ORM\Column(length=255, unique=true)
     */
    private $slug;

    public function __construct()
    {
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

    public function getActivitySector(): ?ActivitySector
    {
        return $this->activitySector;
    }

    public function setActivitySector(?ActivitySector $activitySector): self
    {
        $this->activitySector = $activitySector;

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
            $announcement->setProfession($this);
        }

        return $this;
    }

    public function removeAnnouncement(Announcements $announcement): self
    {
        if ($this->announcements->contains($announcement)) {
            $this->announcements->removeElement($announcement);
            // set the owning side to null (unless already changed)
            if ($announcement->getProfession() === $this) {
                $announcement->setProfession(null);
            }
        }

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

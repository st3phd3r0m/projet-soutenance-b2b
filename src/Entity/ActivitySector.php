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
     * @ORM\OneToMany(targetEntity=Professions::class, mappedBy="activitySector")
     */
    private $professions;

    /**
     * @Gedmo\Slug(fields={"name"})
     * @ORM\Column(length=255, unique=true)
     */
    private $slug;

    public function __construct()
    {
        $this->professions = new ArrayCollection();
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

    public function addProfession(Professions $profession): self
    {
        if (!$this->professions->contains($profession)) {
            $this->professions[] = $profession;
            $profession->setActivitySector($this);
        }

        return $this;
    }

    public function removeProfession(Professions $profession): self
    {
        if ($this->professions->contains($profession)) {
            $this->professions->removeElement($profession);
            // set the owning side to null (unless already changed)
            if ($profession->getActivitySector() === $this) {
                $profession->setActivitySector(null);
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

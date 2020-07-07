<?php

namespace App\Entity;

use App\Repository\ActivitySectorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

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
}

<?php

namespace App\Entity;

use App\Repository\CategoriesPropertiesValuesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CategoriesPropertiesValuesRepository::class)
 */
class CategoriesPropertiesValues
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
    private $value;

    /**
     * @ORM\ManyToOne(targetEntity=CategoriesProperties::class, inversedBy="categoriesPropertiesValues")
     * @ORM\JoinColumn(nullable=false)
     */
    private $categories_property;

    /**
     * @ORM\ManyToOne(targetEntity=Announcements::class, inversedBy="categoriesPropertiesValues")
     * @ORM\JoinColumn(nullable=false)
     */
    private $announcement;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getCategoriesProperty(): ?CategoriesProperties
    {
        return $this->categories_property;
    }

    public function setCategoriesProperty(?CategoriesProperties $categories_property): self
    {
        $this->categories_property = $categories_property;

        return $this;
    }

    public function getAnnouncement(): ?Announcements
    {
        return $this->announcement;
    }

    public function setAnnouncement(?Announcements $announcement): self
    {
        $this->announcement = $announcement;

        return $this;
    }
}

<?php

namespace App\Entity;

use App\Repository\CategoriesPropertiesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CategoriesPropertiesRepository::class)
 */
class CategoriesProperties
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
     * @ORM\Column(type="string", length=50)
     */
    private $type;

    /**
     * @ORM\ManyToMany(targetEntity=Categories::class, mappedBy="category_prop")
     */
    private $categories;

    /**
     * @ORM\OneToMany(targetEntity=CategoriesPropertiesValues::class, mappedBy="categories_property")
     */
    private $categoriesPropertiesValues;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->categoriesPropertiesValues = new ArrayCollection();
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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection|Categories[]
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Categories $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
            $category->addCategoryProp($this);
        }

        return $this;
    }

    public function removeCategory(Categories $category): self
    {
        if ($this->categories->contains($category)) {
            $this->categories->removeElement($category);
            $category->removeCategoryProp($this);
        }

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
            $categoriesPropertiesValue->setCategoriesProperty($this);
        }

        return $this;
    }

    public function removeCategoriesPropertiesValue(CategoriesPropertiesValues $categoriesPropertiesValue): self
    {
        if ($this->categoriesPropertiesValues->contains($categoriesPropertiesValue)) {
            $this->categoriesPropertiesValues->removeElement($categoriesPropertiesValue);
            // set the owning side to null (unless already changed)
            if ($categoriesPropertiesValue->getCategoriesProperty() === $this) {
                $categoriesPropertiesValue->setCategoriesProperty(null);
            }
        }

        return $this;
    }
}

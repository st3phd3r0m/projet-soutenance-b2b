<?php

namespace App\Entity;

use App\Repository\CategoriesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CategoriesRepository::class)
 */
class Categories
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $name;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_illimited;

    /**
     * @ORM\OneToMany(targetEntity=Announcements::class, mappedBy="category")
     */
    private $announcements;

    /**
     * @ORM\ManyToMany(targetEntity=CategoriesProperties::class, inversedBy="categories")
     */
    private $category_prop;

    public function __construct()
    {
        $this->announcements = new ArrayCollection();
        $this->category_prop = new ArrayCollection();
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

    public function getIsIllimited(): ?bool
    {
        return $this->is_illimited;
    }

    public function setIsIllimited(bool $is_illimited): self
    {
        $this->is_illimited = $is_illimited;

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
            $announcement->setCategory($this);
        }

        return $this;
    }

    public function removeAnnouncement(Announcements $announcement): self
    {
        if ($this->announcements->contains($announcement)) {
            $this->announcements->removeElement($announcement);
            // set the owning side to null (unless already changed)
            if ($announcement->getCategory() === $this) {
                $announcement->setCategory(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|CategoriesProperties[]
     */
    public function getCategoryProp(): Collection
    {
        return $this->category_prop;
    }

    public function addCategoryProp(CategoriesProperties $categoryProp): self
    {
        if (!$this->category_prop->contains($categoryProp)) {
            $this->category_prop[] = $categoryProp;
        }

        return $this;
    }

    public function removeCategoryProp(CategoriesProperties $categoryProp): self
    {
        if ($this->category_prop->contains($categoryProp)) {
            $this->category_prop->removeElement($categoryProp);
        }

        return $this;
    }
}

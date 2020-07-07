<?php

namespace App\Entity;

use App\Repository\CompagniesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CompagniesRepository::class)
 */
class Compagnies
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
     * @ORM\Column(type="string", length=14)
     */
    private $siret;

    /**
     * @ORM\ManyToOne(targetEntity=Users::class, inversedBy="compagnies")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=SubscriptionPurchases::class, mappedBy="compagny")
     */
    private $subscriptionPurchases;

    public function __construct()
    {
        $this->subscriptionPurchases = new ArrayCollection();
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

    public function getSiret(): ?string
    {
        return $this->siret;
    }

    public function setSiret(string $siret): self
    {
        $this->siret = $siret;

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

    /**
     * @return Collection|SubscriptionPurchases[]
     */
    public function getSubscriptionPurchases(): Collection
    {
        return $this->subscriptionPurchases;
    }

    public function addSubscriptionPurchase(SubscriptionPurchases $subscriptionPurchase): self
    {
        if (!$this->subscriptionPurchases->contains($subscriptionPurchase)) {
            $this->subscriptionPurchases[] = $subscriptionPurchase;
            $subscriptionPurchase->setCompagny($this);
        }

        return $this;
    }

    public function removeSubscriptionPurchase(SubscriptionPurchases $subscriptionPurchase): self
    {
        if ($this->subscriptionPurchases->contains($subscriptionPurchase)) {
            $this->subscriptionPurchases->removeElement($subscriptionPurchase);
            // set the owning side to null (unless already changed)
            if ($subscriptionPurchase->getCompagny() === $this) {
                $subscriptionPurchase->setCompagny(null);
            }
        }

        return $this;
    }
}

<?php

namespace App\Entity;

use App\Repository\SubscriptionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SubscriptionRepository::class)
 */
class Subscription
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
    private $name;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    private $price;

    /**
     * @ORM\OneToMany(targetEntity=SubscriptionPurchases::class, mappedBy="subscription")
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

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): self
    {
        $this->price = $price;

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
            $subscriptionPurchase->setSubscription($this);
        }

        return $this;
    }

    public function removeSubscriptionPurchase(SubscriptionPurchases $subscriptionPurchase): self
    {
        if ($this->subscriptionPurchases->contains($subscriptionPurchase)) {
            $this->subscriptionPurchases->removeElement($subscriptionPurchase);
            // set the owning side to null (unless already changed)
            if ($subscriptionPurchase->getSubscription() === $this) {
                $subscriptionPurchase->setSubscription(null);
            }
        }

        return $this;
    }
}

<?php

namespace App\Entity;

use App\Repository\UsersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Gedmo\Mapping\Annotation as Gedmo;
/**
 * @ORM\Entity(repositoryClass=UsersRepository::class)
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class Users implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $phone;

    /**
     * @ORM\OneToMany(targetEntity=Compagnies::class, mappedBy="user")
     */
    private $compagnies;

    /**
     * @ORM\OneToMany(targetEntity=Announcements::class, mappedBy="user")
     */
    private $announcements;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isVerified = false;

    /**
     * @Gedmo\Slug(fields={"firstname","lastname"})
     * @ORM\Column(length=255, unique=true)
     */
    private $slug;

    /**
     * @ORM\OneToMany(targetEntity=SubscriptionPurchases::class, mappedBy="User")
     */
    private $subscriptionPurchases;

    public function __construct()
    {
        $this->compagnies = new ArrayCollection();
        $this->announcements = new ArrayCollection();
        $this->subscriptionPurchases = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

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

    /**
     * @return Collection|Compagnies[]
     */
    public function getCompagnies(): Collection
    {
        return $this->compagnies;
    }

    public function addCompagny(Compagnies $compagny): self
    {
        if (!$this->compagnies->contains($compagny)) {
            $this->compagnies[] = $compagny;
            $compagny->setUser($this);
        }

        return $this;
    }

    public function removeCompagny(Compagnies $compagny): self
    {
        if ($this->compagnies->contains($compagny)) {
            $this->compagnies->removeElement($compagny);
            // set the owning side to null (unless already changed)
            if ($compagny->getUser() === $this) {
                $compagny->setUser(null);
            }
        }

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
            $announcement->setUser($this);
        }

        return $this;
    }

    public function removeAnnouncement(Announcements $announcement): self
    {
        if ($this->announcements->contains($announcement)) {
            $this->announcements->removeElement($announcement);
            // set the owning side to null (unless already changed)
            if ($announcement->getUser() === $this) {
                $announcement->setUser(null);
            }
        }

        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified)
    {
        $this->isVerified = $isVerified;
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
            $subscriptionPurchase->setUser($this);
        }

        return $this;
    }

    public function removeSubscriptionPurchase(SubscriptionPurchases $subscriptionPurchase): self
    {
        if ($this->subscriptionPurchases->contains($subscriptionPurchase)) {
            $this->subscriptionPurchases->removeElement($subscriptionPurchase);
            // set the owning side to null (unless already changed)
            if ($subscriptionPurchase->getUser() === $this) {
                $subscriptionPurchase->setUser(null);
            }
        }

        return $this;
    }
}

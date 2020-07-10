<?php

namespace App\Entity;

use App\Repository\UnlockedAnnouncementsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UnlockedAnnouncementsRepository::class)
 */
class UnlockedAnnouncements
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Users::class, inversedBy="unlockedAnnouncements")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Announcements::class, inversedBy="unlockedAnnouncements")
     * @ORM\JoinColumn(nullable=false)
     */
    private $announcements;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getAnnouncements(): ?Announcements
    {
        return $this->announcements;
    }

    public function setAnnouncements(?Announcements $announcements): self
    {
        $this->announcements = $announcements;

        return $this;
    }
}

<?php

namespace App\Entity;

use App\Repository\UserProfileRepository;
use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserProfileRepository::class)]
class UserProfile
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(length: 255, nullable: true)]
    private string|null $name = null;

    #[ORM\Column(length: 1024, nullable: true)]
    private string|null $bio = null;

    #[ORM\Column(length: 255, nullable: true)]
    private string|null $websiteUrl = null;

    #[ORM\Column(length: 255, nullable: true)]
    private string|null $twitterUsername = null;

    #[ORM\Column(length: 255, nullable: true)]
    private string|null $company = null;

    #[ORM\Column(length: 255, nullable: true)]
    private string|null $location = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private DateTime|null $dateOfBirth = null;

    #[ORM\OneToOne(inversedBy: 'userProfile', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private User|null $user = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string|null
    {
        return $this->name;
    }

    public function setName(string|null $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getBio(): string|null
    {
        return $this->bio;
    }

    public function setBio(string|null $bio): static
    {
        $this->bio = $bio;

        return $this;
    }

    public function getWebsiteUrl(): string|null
    {
        return $this->websiteUrl;
    }

    public function setWebsiteUrl(string|null $websiteUrl): static
    {
        $this->websiteUrl = $websiteUrl;

        return $this;
    }

    public function getTwitterUsername(): string|null
    {
        return $this->twitterUsername;
    }

    public function setTwitterUsername(string|null $twitterUsername): static
    {
        $this->twitterUsername = $twitterUsername;

        return $this;
    }

    public function getCompany(): string|null
    {
        return $this->company;
    }

    public function setCompany(string|null $company): static
    {
        $this->company = $company;

        return $this;
    }

    public function getLocation(): string|null
    {
        return $this->location;
    }

    public function setLocation(string|null $location): static
    {
        $this->location = $location;

        return $this;
    }

    public function getDateOfBirth(): DateTime|null
    {
        return $this->dateOfBirth;
    }

    public function setDateOfBirth(DateTime|null $dateOfBirth): static
    {
        $this->dateOfBirth = $dateOfBirth;

        return $this;
    }

    public function getUser(): User|null
    {
        return $this->user;
    }

    public function setUser(User|null $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
    }
}

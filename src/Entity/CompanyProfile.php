<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\User;

#[ORM\Entity]
class CompanyProfile
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'profile', targetEntity: User::class, cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;


    #[ORM\Column(length: 255, nullable: true)]
    private ?string $name = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $siren = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $country = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $apeCode = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $postalCode = null;

    // Getters & Setters
    public function getId(): ?int { return $this->id; }

    public function getUser(): ?User { return $this->user; }
    public function setUser(?User $user): self { $this->user = $user; return $this; }

    public function getName(): ?string { return $this->name; }
    public function setName(?string $name): self { $this->name = $name; return $this; }

    public function getSiren(): ?string { return $this->siren; }
    public function setSiren(?string $siren): self { $this->siren = $siren; return $this; }

    public function getCountry(): ?string { return $this->country; }
    public function setCountry(?string $country): self { $this->country = $country; return $this; }

    public function getApeCode(): ?string { return $this->apeCode; }
    public function setApeCode(?string $apeCode): self { $this->apeCode = $apeCode; return $this; }

    public function getPostalCode(): ?string { return $this->postalCode; }
    public function setPostalCode(?string $postalCode): self { $this->postalCode = $postalCode; return $this; }
}

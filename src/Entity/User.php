<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class User
{
    #[ORM\Id]
    #[ORM\Column(type: 'string', length: 36)]
    private string $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $name;

    #[ORM\Column(type: 'string', length: 20, nullable: true)]
    private ?string $siren = null;

    #[ORM\Column(type: 'string', length: 20, nullable: true)]
    private ?string $type = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: 'string', length: 50, nullable: true)]
    private ?string $status = null;

    // Getters
    public function getId(): string { return $this->id; }
    public function getName(): string { return $this->name; }
    public function getSiren(): ?string { return $this->siren; }
    public function getType(): ?string { return $this->type; }
    public function getCreatedAt(): ?\DateTimeInterface { return $this->createdAt; }
    public function getStatus(): ?string { return $this->status; }

    // Setters
    public function setId(string $id): self { $this->id = $id; return $this; }
    public function setName(string $name): self { $this->name = $name; return $this; }
    public function setSiren(?string $siren): self { $this->siren = $siren; return $this; }
    public function setType(?string $type): self { $this->type = $type; return $this; }
    public function setCreatedAt(?\DateTimeInterface $createdAt): self { $this->createdAt = $createdAt; return $this; }
    public function setStatus(?string $status): self { $this->status = $status; return $this; }
}

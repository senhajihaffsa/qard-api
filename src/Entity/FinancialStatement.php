<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\User;

#[ORM\Entity]
class FinancialStatement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'financials')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $year = null;

    #[ORM\Column(type: 'float', nullable: true)]
    private ?float $revenue = null;

    #[ORM\Column(type: 'float', nullable: true)]
    private ?float $netIncome = null;

    #[ORM\Column(type: 'float', nullable: true)]
    private ?float $ebitda = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $publishedAt = null;

    // Getters & Setters

    public function getId(): ?int { return $this->id; }

    public function getUser(): ?User { return $this->user; }
    public function setUser(?User $user): self { $this->user = $user; return $this; }

    public function getYear(): ?int { return $this->year; }
    public function setYear(?int $year): self { $this->year = $year; return $this; }

    public function getRevenue(): ?float { return $this->revenue; }
    public function setRevenue(?float $revenue): self { $this->revenue = $revenue; return $this; }

    public function getNetIncome(): ?float { return $this->netIncome; }
    public function setNetIncome(?float $netIncome): self { $this->netIncome = $netIncome; return $this; }

    public function getEbitda(): ?float { return $this->ebitda; }
    public function setEbitda(?float $ebitda): self { $this->ebitda = $ebitda; return $this; }

    public function getPublishedAt(): ?\DateTimeInterface { return $this->publishedAt; }
    public function setPublishedAt(?\DateTimeInterface $publishedAt): self { $this->publishedAt = $publishedAt; return $this; }
}

<?php

namespace App\Entity;

use App\Repository\AddHistoryRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AddHistoryRepository::class)]
class AddHistory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'addHistories')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Medocs $medocs = null;

    #[ORM\Column]
    private ?int $Qte = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $dateAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMedocs(): ?Medocs
    {
        return $this->medocs;
    }

    public function setMedocs(?Medocs $medocs): static
    {
        $this->medocs = $medocs;

        return $this;
    }

    public function getQte(): ?int
    {
        return $this->Qte;
    }

    public function setQte(int $Qte): static
    {
        $this->Qte = $Qte;

        return $this;
    }

    public function getDateAt(): ?\DateTimeImmutable
    {
        return $this->dateAt;
    }

    public function setDateAt(\DateTimeImmutable $dateAt): static
    {
        $this->dateAt = $dateAt;

        return $this;
    }
}

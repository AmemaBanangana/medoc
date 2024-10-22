<?php

namespace App\Entity;

use App\Repository\StockRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StockRepository::class)]
class Stock
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $quantite = null;

    #[ORM\Column]
    private ?int $seuil_critique = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $date_dernier_approvisionnementAt = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Medocs $medocs = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): static
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getSeuilCritique(): ?int
    {
        return $this->seuil_critique;
    }

    public function setSeuilCritique(int $seuil_critique): static
    {
        $this->seuil_critique = $seuil_critique;

        return $this;
    }

    public function getDateDernierApprovisionnementAt(): ?\DateTimeImmutable
    {
        return $this->date_dernier_approvisionnementAt;
    }

    public function setDateDernierApprovisionnementAt(\DateTimeImmutable $date_dernier_approvisionnementAt): static
    {
        $this->date_dernier_approvisionnementAt = $date_dernier_approvisionnementAt;

        return $this;
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
}

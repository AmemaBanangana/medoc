<?php

namespace App\Entity;

use App\Repository\OrdonanceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrdonanceRepository::class)]
class Ordonance
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'ordonances')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Patient $Patien = null;

    /**
     * @var Collection<int, Medocs>
     */
    #[ORM\ManyToMany(targetEntity: Medocs::class, inversedBy: 'ordonances')]
    private Collection $medicament;

    #[ORM\ManyToOne(inversedBy: 'ordonances')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Medecin $medecin = null;

    public function __construct()
    {
        
        $this->medicament = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPatien(): ?Patient
    {
        return $this->Patien;
    }

    public function setPatien(?Patient $Patien): static
    {
        $this->Patien = $Patien;

        return $this;
    }

    /**
     * @return Collection<int, Medocs>
     */
    public function getMedicament(): Collection
    {
        return $this->medicament;
    }

    public function addMedicament(Medocs $medicament): static
    {
        if (!$this->medicament->contains($medicament)) {
            $this->medicament->add($medicament);
        }

        return $this;
    }

    public function removeMedicament(Medocs $medicament): static
    {
        $this->medicament->removeElement($medicament);

        return $this;
    }

    public function getMedecin(): ?Medecin
    {
        return $this->medecin;
    }

    public function setMedecin(?Medecin $medecin): static
    {
        $this->medecin = $medecin;

        return $this;
    }
}

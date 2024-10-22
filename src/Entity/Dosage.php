<?php

namespace App\Entity;

use App\Repository\DosageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DosageRepository::class)]
class Dosage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $dosage = null;

    /**
     * @var Collection<int, Medocs>
     */
    #[ORM\ManyToMany(targetEntity: Medocs::class, mappedBy: 'dosage')]
    private Collection $medocs;

    public function __construct()
    {
        $this->medocs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDosage(): ?string
    {
        return $this->dosage;
    }

    public function setDosage(string $dosage): static
    {
        $this->dosage = $dosage;

        return $this;
    }

    /**
     * @return Collection<int, Medocs>
     */
    public function getMedocs(): Collection
    {
        return $this->medocs;
    }

    public function addMedoc(Medocs $medoc): static
    {
        if (!$this->medocs->contains($medoc)) {
            $this->medocs->add($medoc);
            $medoc->addDosage($this);
        }

        return $this;
    }

    public function removeMedoc(Medocs $medoc): static
    {
        if ($this->medocs->removeElement($medoc)) {
            $medoc->removeDosage($this);
        }

        return $this;
    }
}

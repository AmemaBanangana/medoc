<?php

namespace App\Entity;

use App\Repository\FormeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FormeRepository::class)]
class Forme
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $forme = null;

    /**
     * @var Collection<int, Medocs>
     */
    #[ORM\ManyToMany(targetEntity: Medocs::class, mappedBy: 'forme')]
    private Collection $medocs;

    public function __construct()
    {
        $this->medocs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getForme(): ?string
    {
        return $this->forme;
    }

    public function setForme(string $forme): static
    {
        $this->forme = $forme;

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
            $medoc->addForme($this);
        }

        return $this;
    }

    public function removeMedoc(Medocs $medoc): static
    {
        if ($this->medocs->removeElement($medoc)) {
            $medoc->removeForme($this);
        }

        return $this;
    }
}

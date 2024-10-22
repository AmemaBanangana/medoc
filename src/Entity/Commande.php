<?php

namespace App\Entity;

use App\Repository\CommandeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommandeRepository::class)]
class Commande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

   

    #[ORM\Column(length: 50)]
    private ?string $statut = null;

    /**
     * @var Collection<int, CommandeMedicament>
     */
    #[ORM\OneToMany(targetEntity: CommandeMedicament::class, mappedBy: 'commande', orphanRemoval: true)]
    private Collection $commandeMedicaments;

    public function __construct()
    {
        $this->commandeMedicaments = new ArrayCollection();
    }

    /**
     * @var Collection<int, Medocs>
     */


    public function getId(): ?int
    {
        return $this->id;
    }


   
    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): static
    {
        $this->statut = $statut;

        return $this;
    }

    /**
     * @return Collection<int, CommandeMedicament>
     */
    public function getCommandeMedicaments(): Collection
    {
        return $this->commandeMedicaments;
    }

    public function addCommandeMedicament(CommandeMedicament $commandeMedicament): static
    {
        if (!$this->commandeMedicaments->contains($commandeMedicament)) {
            $this->commandeMedicaments->add($commandeMedicament);
            $commandeMedicament->setCommande($this);
        }

        return $this;
    }

    public function removeCommandeMedicament(CommandeMedicament $commandeMedicament): static
    {
        if ($this->commandeMedicaments->removeElement($commandeMedicament)) {
            // set the owning side to null (unless already changed)
            if ($commandeMedicament->getCommande() === $this) {
                $commandeMedicament->setCommande(null);
            }
        }

        return $this;
    }

   
}

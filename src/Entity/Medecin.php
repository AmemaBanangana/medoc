<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\MedecinRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: MedecinRepository::class)]
class Medecin
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $prenom = null;

    #[ORM\Column(length: 255)]
    private ?string $adresse = null;

    #[ORM\Column(length: 255)]
    private ?string $phone = null;

    

    /**
     * @var Collection<int, Ordonance>
     */
    #[ORM\OneToMany(targetEntity: Ordonance::class, mappedBy: 'medecin')]
    private Collection $ordonances;

   

   

    public function __construct()
    {
        $this->ordonances = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }


    /**
     * @return Collection<int, Ordonance>
     */
    public function getOrdonances(): Collection
    {
        return $this->ordonances;
    }

    public function addOrdonance(Ordonance $ordonance): static
    {
        if (!$this->ordonances->contains($ordonance)) {
            $this->ordonances->add($ordonance);
            $ordonance->setMedecin($this);
        }

        return $this;
    }

    public function removeOrdonance(Ordonance $ordonance): static
    {
        if ($this->ordonances->removeElement($ordonance)) {
            // set the owning side to null (unless already changed)
            if ($ordonance->getMedecin() === $this) {
                $ordonance->setMedecin(null);
            }
        }

        return $this;
    }




  

}

<?php

namespace App\Entity;

use App\Repository\MedocsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MedocsRepository::class)]
class Medocs
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique:true)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    /**
     * @var Collection<int, Forme>
     */
    #[ORM\ManyToMany(targetEntity: Forme::class, inversedBy: 'medocs')]
    private Collection $forme;

    /**
     * @var Collection<int, Dosage>
     */
    #[ORM\ManyToMany(targetEntity: Dosage::class, inversedBy: 'medocs')]
    private Collection $dosage;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_exp = null;

    /**
     * @var Collection<int, Ordonance>
     */
    #[ORM\ManyToMany(targetEntity: Ordonance::class, mappedBy: 'medicament')]
    private Collection $ordonances;

    /**
     * @var Collection<int, Commande>
     */
    #[ORM\ManyToMany(targetEntity: Commande::class, mappedBy: 'medocs')]
    private Collection $commandes;

    /**
     * @var Collection<int, AddHistory>
     */
    #[ORM\OneToMany(targetEntity: AddHistory::class, mappedBy: 'medocs')]
    private Collection $addHistories;

    #[ORM\Column]
    private ?int $stock = null;

    #[ORM\Column(length: 255)]
    private ?string $photo = null;

    /**
     * @var Collection<int, CommandeMedicament>
     */
    #[ORM\OneToMany(targetEntity: CommandeMedicament::class, mappedBy: 'medicament')]
    private Collection $commandeMedicaments;

    public function __construct()
    {
        $this->forme = new ArrayCollection();
        $this->dosage = new ArrayCollection();
        $this->ordonances = new ArrayCollection();
        $this->commandes = new ArrayCollection();
        $this->addHistories = new ArrayCollection();
        $this->commandeMedicaments = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, Forme>
     */
    public function getForme(): Collection
    {
        return $this->forme;
    }

    public function addForme(Forme $forme): static
    {
        if (!$this->forme->contains($forme)) {
            $this->forme->add($forme);
        }

        return $this;
    }

    public function removeForme(Forme $forme): static
    {
        $this->forme->removeElement($forme);

        return $this;
    }

    /**
     * @return Collection<int, Dosage>
     */
    public function getDosage(): Collection
    {
        return $this->dosage;
    }

    public function addDosage(Dosage $dosage): static
    {
        if (!$this->dosage->contains($dosage)) {
            $this->dosage->add($dosage);
        }

        return $this;
    }

    public function removeDosage(Dosage $dosage): static
    {
        $this->dosage->removeElement($dosage);

        return $this;
    }

    public function getDateExp(): ?\DateTimeInterface
    {
        return $this->date_exp;
    }

    public function setDateExp(\DateTimeInterface $date_exp): static
    {
        $this->date_exp = $date_exp;

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
            $ordonance->addMedicament($this);
        }

        return $this;
    }

    public function removeOrdonance(Ordonance $ordonance): static
    {
        if ($this->ordonances->removeElement($ordonance)) {
            $ordonance->removeMedicament($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, AddHistory>
     */
    public function getAddHistories(): Collection
    {
        return $this->addHistories;
    }

    public function addAddHistory(AddHistory $addHistory): static
    {
        if (!$this->addHistories->contains($addHistory)) {
            $this->addHistories->add($addHistory);
            $addHistory->setMedocs($this);
        }

        return $this;
    }

    public function removeAddHistory(AddHistory $addHistory): static
    {
        if ($this->addHistories->removeElement($addHistory)) {
            // set the owning side to null (unless already changed)
            if ($addHistory->getMedocs() === $this) {
                $addHistory->setMedocs(null);
            }
        }

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(int $stock): static
    {
        $this->stock = $stock;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(string $photo): static
    {
        $this->photo = $photo;

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
            $commandeMedicament->setMedicament($this);
        }

        return $this;
    }

    public function removeCommandeMedicament(CommandeMedicament $commandeMedicament): static
    {
        if ($this->commandeMedicaments->removeElement($commandeMedicament)) {
            // set the owning side to null (unless already changed)
            if ($commandeMedicament->getMedicament() === $this) {
                $commandeMedicament->setMedicament(null);
            }
        }

        return $this;
    }
}

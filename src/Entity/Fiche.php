<?php

namespace App\Entity;

use App\Repository\FicheRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=FicheRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Fiche
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity=Proposition::class, mappedBy="fiche",cascade={"persist"})
     * @Assert\Valid
     */
    private $propositions;

    /**
     * @ORM\Column(type="date")
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $numero;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isValidee;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isSoumis;



    /**
     * @ORM\ManyToOne(targetEntity=Enseignant::class, inversedBy="fiches")
     * @ORM\JoinColumn(nullable=false)
     */
    private $directeurPropose;

    /**
     * @ORM\ManyToOne(targetEntity=Enseignant::class, inversedBy="fichesRetenues")
     */
    private $directeurRetenu;

    /**
     * @ORM\ManyToOne(targetEntity=Enseignant::class, inversedBy="fichesCodirections")
     */
    private $codirecteur;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $observation;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $etatFiche;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isFeuxVert;

    /**
     * @ORM\OneToMany(targetEntity=Depot::class, mappedBy="fiche", orphanRemoval=true)
     */
    private $depots;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateAffectation;

    /**
     * @ORM\OneToOne(targetEntity=Finaliste::class, inversedBy="fiche", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $finaliste;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $sujetRetenu;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $dateValidation;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isPlanifiee;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateDefense;

    /**
     * @ORM\ManyToOne(targetEntity=Promotion::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $promotion;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $dateSoumission;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateFeuxVert;

    /**
     * @ORM\ManyToOne(targetEntity=Faculte::class, inversedBy="fiches")
     * @ORM\JoinColumn(nullable=false)
     */
    private $faculte;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isPaiementDepot;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isDefendue;

    /**
     * @ORM\ManyToOne(targetEntity=AnneeAcademique::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $anneeAcademique;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isRejete;


    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatePromotion(){
        $this->setPromotion($this->getFinaliste()->getPromotion());
        $this->setFaculte($this->getPromotion()->getDepartement()->getFaculte());
        $this->setAnneeAcademique($this->getPromotion()->getAnneeAcademique());
    }

    public function __construct(Finaliste $finaliste)
    {
        $this->setFinaliste($finaliste);
        $this->propositions = new ArrayCollection();
        $this->isValidee=false;
        $this->isSoumis=false;
        $this->isDefendue=false;
        $this->isFeuxVert=false;
        $this->isPaiementDepot=false;
        $this->isRejete=false;
        $this->setIsDefendue(false);
        $this->setIsPlanifiee(false);
        $this->setNumero(strtoupper(uniqid('Fic-')));
        $this->date=new \DateTime();
        $this->setEtatFiche(1);
        $this->depots = new ArrayCollection();
        $this->dateValidation=new \DateTime();
        $this->dateAffectation=new \DateTime();
        
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|Proposition[]
     */
    public function getPropositions(): Collection
    {
        return $this->propositions;
    }

    public function addProposition(Proposition $proposition): self
    {
        if (!$this->propositions->contains($proposition)) {
            $this->propositions[] = $proposition;
            $proposition->setFiche($this);
        }

        return $this;
    }

    public function ajoutProposition(Proposition $proposition): self
    {
        if (!$this->propositions->contains($proposition)) {
            $this->propositions[] = $proposition;
            //$proposition->setFiche($this);
        }

        return $this;
    }

    public function removeProposition(Proposition $proposition): self
    {
        if ($this->propositions->removeElement($proposition)) {
            // set the owning side to null (unless already changed)
            if ($proposition->getFiche() === $this) {
                $proposition->setFiche(null);
            }
        }

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getNumero(): ?string
    {
        return $this->numero;
    }

    public function setNumero(string $numero): self
    {
        $this->numero = $numero;

        return $this;
    }

    public function getIsValidee(): ?bool
    {
        return $this->isValidee;
    }

    public function setIsValidee(bool $isValidee): self
    {
        $this->isValidee = $isValidee;

        return $this;
    }

    public function getIsSoumis(): ?bool
    {
        return $this->isSoumis;
    }

    public function setIsSoumis(bool $isSoumis): self
    {
        $this->isSoumis = $isSoumis;

        return $this;
    }

    public function getFinaliste(): ?Finaliste
    {
        return $this->finaliste;
    }

    public function setFinaliste(?Finaliste $finaliste): self
    {
        $this->finaliste = $finaliste;

        return $this;
    }

    public function getDirecteurPropose(): ?Enseignant
    {
        return $this->directeurPropose;
    }

    public function setDirecteurPropose(?Enseignant $directeurPropose): self
    {
        $this->directeurPropose = $directeurPropose;

        return $this;
    }

    public function getDirecteurRetenu(): ?Enseignant
    {
        return $this->directeurRetenu;
    }

    public function setDirecteurRetenu(?Enseignant $directeurRetenu): self
    {
        $this->directeurRetenu = $directeurRetenu;

        return $this;
    }

    public function getCodirecteur(): ?Enseignant
    {
        return $this->codirecteur;
    }

    public function setCodirecteur(?Enseignant $codirecteur): self
    {
        $this->codirecteur = $codirecteur;

        return $this;
    }

    public function getObservation(): ?string
    {
        return $this->observation;
    }

    public function setObservation(?string $observation): self
    {
        $this->observation = $observation;

        return $this;
    }

    public function getEtatFiche(): ?int
    {
        return $this->etatFiche;
    }

    public function setEtatFiche(int $etatFiche): self
    {
        $this->etatFiche = $etatFiche;

        return $this;
    }

    public function getIsFeuxVert(): ?bool
    {
        return $this->isFeuxVert;
    }

    public function setIsFeuxVert(?bool $isFeuxVert): self
    {
        $this->isFeuxVert = $isFeuxVert;

        return $this;
    }

    /**
     * @return Collection|Depot[]
     */
    public function getDepots(): Collection
    {
        return $this->depots;
    }

    public function addDepot(Depot $depot): self
    {
        if (!$this->depots->contains($depot)) {
            $this->depots[] = $depot;
            $depot->setFiche($this);
        }

        return $this;
    }

    public function removeDepot(Depot $depot): self
    {
        if ($this->depots->removeElement($depot)) {
            // set the owning side to null (unless already changed)
            if ($depot->getFiche() === $this) {
                $depot->setFiche(null);
            }
        }

        return $this;
    }

    public function getDateAffectation(): ?\DateTimeInterface
    {
        return $this->dateAffectation;
    }

    public function setDateAffectation(?\DateTimeInterface $dateAffectation): self
    {
        $this->dateAffectation = $dateAffectation;

        return $this;
    }

    public function getSujetRetenu(): ?string
    {
        return $this->sujetRetenu;
    }

    public function setSujetRetenu(?string $sujetRetenu): self
    {
        $this->sujetRetenu = $sujetRetenu;

        return $this;
    }

    public function getDateValidation(): ?\DateTimeInterface
    {
        return $this->dateValidation;
    }

    public function setDateValidation(?\DateTimeInterface $dateValidation): self
    {
        $this->dateValidation = $dateValidation;

        return $this;
    }

    public function getIsPlanifiee(): ?bool
    {
        return $this->isPlanifiee;
    }

    public function setIsPlanifiee(bool $isPlanifiee): self
    {
        $this->isPlanifiee = $isPlanifiee;

        return $this;
    }


    public function getDateDefense(): ?\DateTimeInterface
    {
        return $this->dateDefense;
    }

    public function setDateDefense(?\DateTimeInterface $dateDefense): self
    {
        $this->dateDefense = $dateDefense;

        return $this;
    }

    public function getPromotion(): ?Promotion
    {
        return $this->promotion;
    }

    public function setPromotion(?Promotion $promotion): self
    {
        $this->promotion = $promotion;

        return $this;
    }

    public function getDateSoumission(): ?\DateTimeInterface
    {
        return $this->dateSoumission;
    }

    public function setDateSoumission(?\DateTimeInterface $dateSoumission): self
    {
        $this->dateSoumission = $dateSoumission;

        return $this;
    }

    public function getDateFeuxVert(): ?\DateTimeInterface
    {
        return $this->dateFeuxVert;
    }

    public function setDateFeuxVert(?\DateTimeInterface $dateFeuxVert): self
    {
        $this->dateFeuxVert = $dateFeuxVert;

        return $this;
    }

    public function getFaculte(): ?Faculte
    {
        return $this->faculte;
    }

    public function setFaculte(?Faculte $faculte): self
    {
        $this->faculte = $faculte;

        return $this;
    }

    public function getIsPaiementDepot(): ?bool
    {
        return $this->isPaiementDepot;
    }

    public function setIsPaiementDepot(bool $isPaiementDepot): self
    {
        $this->isPaiementDepot = $isPaiementDepot;

        return $this;
    }

    public function getIsDefendue(): ?bool
    {
        return $this->isDefendue;
    }

    public function setIsDefendue(bool $isDefendue): self
    {
        $this->isDefendue = $isDefendue;

        return $this;
    }

    public function getAnneeAcademique(): ?AnneeAcademique
    {
        return $this->anneeAcademique;
    }

    public function setAnneeAcademique(?AnneeAcademique $anneeAcademique): self
    {
        $this->anneeAcademique = $anneeAcademique;

        return $this;
    }

    public function getIsRejete(): ?bool
    {
        return $this->isRejete;
    }

    public function setIsRejete(bool $isRejete): self
    {
        $this->isRejete = $isRejete;

        return $this;
    }





    
}

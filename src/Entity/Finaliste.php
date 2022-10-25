<?php

namespace App\Entity;

use App\Repository\FinalisteRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=FinalisteRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Finaliste
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=Identite::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     * @Assert\Valid
     */
    private $identite;

    /**
     * @ORM\OneToOne(targetEntity=Fiche::class, mappedBy="finaliste", cascade={"persist", "remove"})
     */
    private $fiche;

    /**
     * @ORM\OneToOne(targetEntity=User::class, mappedBy="finaliste", cascade={"persist", "remove"})
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Promotion::class, inversedBy="finalistes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $promotion;

    /**
     * @ORM\Column(type="integer")
     */
    private $nombreCorrectionDirecteur;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $civilite;

    /**
     * @ORM\ManyToOne(targetEntity=Faculte::class, inversedBy="finalistes")
     * @ORM\JoinColumn(nullable=true)
     */
    private $faculte;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $matricule;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $compteur;

    /**
     * @ORM\PreUpdate
     * @ORM\PostLoad
     */
    public function avantEnregistrementEtChargement(){
        if(!$this->getFaculte()){
            return 0;
        }
        $this->faculte=$this->departement->getFaculte();
        $idFaculte=$this->getFaculte()->getId();
        $idDepartement=$this->getPromotion()->getDepartement()->getId();
        $idPromotion=$this->getPromotion()->getId();
        $annee=$this->getPromotion()->getAnneeAcademique()->getDebut();
        $id=$this->getId();
        if(strlen($idFaculte)<2){
            $idFaculte='0'.$idFaculte;
        }
        if(strlen($idDepartement)<2){
            $idDepartement='0'.$idDepartement;
        }
        if(strlen($idPromotion)<2){
            $idPromotion='0'.$idPromotion;
        }
        if(strlen($id)<2){
            $id='0'.$id;
        }
        $this->matricule=$annee.$idFaculte.$idDepartement.$idPromotion.$id;
    }

    public function __construct()
    {
        $this->setNombreCorrectionDirecteur(0);
    }

    public function __toString()
    {
        return $this->getNomComplet();
    }

    public function getNomComplet()
    {
        return $this->getIdentite()->getNom() . ' ' . $this->getIdentite()->getPostnom() . ' ' . $this->getIdentite()->getPrenom();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdentite(): ?Identite
    {
        return $this->identite;
    }

    public function setIdentite(Identite $identite): self
    {
        $this->identite = $identite;

        return $this;
    }

    public function getFiche(): ?Fiche
    {
        return $this->fiche;
    }

    public function setFiche(Fiche $fiche): self
    {
        // set the owning side of the relation if necessary
        if ($fiche->getFinaliste() !== $this) {
            $fiche->setFinaliste($this);
        }

        $this->fiche = $fiche;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        // unset the owning side of the relation if necessary
        if ($user === null && $this->user !== null) {
            $this->user->setFinaliste(null);
        }

        // set the owning side of the relation if necessary
        if ($user !== null && $user->getFinaliste() !== $this) {
            $user->setFinaliste($this);
        }

        $this->user = $user;

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

    public function getNombreCorrectionDirecteur(): ?int
    {
        return $this->nombreCorrectionDirecteur;
    }

    public function setNombreCorrectionDirecteur(int $nombreCorrectionDirecteur): self
    {
        $this->nombreCorrectionDirecteur = $nombreCorrectionDirecteur;

        return $this;
    }

    public function getCivilite(): ?string
    {
        return $this->civilite;
    }

    public function setCivilite(string $civilite): self
    {
        $this->civilite = $civilite;

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

    public function getMatricule(): ?string
    {
        return $this->matricule;
    }

    public function setMatricule(string $matricule): self
    {
        $this->matricule = $matricule;

        return $this;
    }

    public function getCompteur(): ?string
    {
        return $this->compteur;
    }

    public function setCompteur(?string $compteur): self
    {
        $this->compteur = $compteur;

        return $this;
    }
}

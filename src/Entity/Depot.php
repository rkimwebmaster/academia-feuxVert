<?php

namespace App\Entity;

use App\Repository\DepotRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=DepotRepository::class)
    * @ORM\HasLifecycleCallbacks()
 */
class Depot
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
    * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $fichier;

    /**
     * @ORM\Column(type="text")
     */
    private $noteEtudiant;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $noteDirecteur;

    /**
     * @ORM\ManyToOne(targetEntity=Fiche::class, inversedBy="depots")
     * @ORM\JoinColumn(nullable=false)
     */
    private $fiche;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isCorrige;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $fichierCorrigeDirecteur;

    /**
     * @ORM\Column(type="date")
     */
    private $dateDepot;

    /**
     * @ORM\Column(type="date", nullable=true )
     */
    private $dateCorrection;

    /**
     * @ORM\Column(type="boolean")
     */
    private $demandezRendezVous;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateRendezVous;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $titre;


    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function incrementerDepotEnseignant(){
        $directeur=$this->getFiche()->getDirecteurRetenu();
        $nbreDepotEnseignant=$directeur->getNombreNouveauDepot();
        if($nbreDepotEnseignant>=0){
            $directeur->setNombreNouveauDepot($directeur->getNombreNouveauDepot()+1);
        }
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function correctionDirecteur(){
        if($this->getNoteDirecteur()){
            $this->setIsCorrige(true);
        }
    }

    public function __construct(Fiche $fiche)
    {
        $this->setFiche($fiche);
        $this->date= new \DateTime();
        $this->isCorrige=false;
        $this->dateDepot= new \DateTime();
        // $this->dateCorrection=new \DateTime();
        $this->dateRendezVous=new \DateTime();

    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getFichier(): ?string
    {
        return $this->fichier;
    }

    public function setFichier(string $fichier): self
    {
        $this->fichier = $fichier;

        return $this;
    }

    public function getNoteEtudiant(): ?string
    {
        return $this->noteEtudiant;
    }

    public function setNoteEtudiant(string $noteEtudiant): self
    {
        $this->noteEtudiant = $noteEtudiant;

        return $this;
    }

    public function getNoteDirecteur(): ?string
    {
        return $this->noteDirecteur;
    }

    public function setNoteDirecteur(?string $noteDirecteur): self
    {
        $this->noteDirecteur = $noteDirecteur;

        return $this;
    }

    public function getFiche(): ?Fiche
    {
        return $this->fiche;
    }

    public function setFiche(?Fiche $fiche): self
    {
        $this->fiche = $fiche;

        return $this;
    }

    public function getIsCorrige(): ?bool
    {
        return $this->isCorrige;
    }

    public function setIsCorrige(bool $isCorrige): self
    {
        $this->isCorrige = $isCorrige;

        return $this;
    }

    public function getFichierCorrigeDirecteur(): ?string
    {
        return $this->fichierCorrigeDirecteur;
    }

    public function setFichierCorrigeDirecteur(?string $fichierCorrigeDirecteur): self
    {
        $this->fichierCorrigeDirecteur = $fichierCorrigeDirecteur;

        return $this;
    }

    public function getDateDepot(): ?\DateTimeInterface
    {
        return $this->dateDepot;
    }

    public function setDateDepot(\DateTimeInterface $dateDepot): self
    {
        $this->dateDepot = $dateDepot;

        return $this;
    }

    public function getDateCorrection(): ?\DateTimeInterface
    {
        return $this->dateCorrection;
    }

    public function setDateCorrection(\DateTimeInterface $dateCorrection): self
    {
        $this->dateCorrection = $dateCorrection;

        return $this;
    }

    public function getDemandezRendezVous(): ?bool
    {
        return $this->demandezRendezVous;
    }

    public function setDemandezRendezVous(bool $demandezRendezVous): self
    {
        $this->demandezRendezVous = $demandezRendezVous;

        return $this;
    }

    public function getDateRendezVous(): ?\DateTimeInterface
    {
        return $this->dateRendezVous;
    }

    public function setDateRendezVous(\DateTimeInterface $dateRendezVous): self
    {
        $this->dateRendezVous = $dateRendezVous;

        return $this;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }
}

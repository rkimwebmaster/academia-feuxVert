<?php

namespace App\Entity;

use App\Repository\EntrepriseRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=EntrepriseRepository::class)
 */
class Entreprise
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom='Grande université nationale ';

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $adresse;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $ville;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $pays='République Démocratique du Congo';

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
          * @Assert\Length(min=12, max=13)
     */
    private $telephone1;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
         * @Assert\Length(min=12, max=13)
     */
    private $telephone2;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $website;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isPublique;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $sigle;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $logo;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $devise='science sans conscience n\'est que ruine de l\'âme. ';

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $boitePostale='0000';

    /**
     * @ORM\ManyToOne(targetEntity=AnneeAcademique::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $anneeAcademiqueCourante;

    /**
     * @ORM\Column(type="date", nullable=false)
     */
    private $dateFinPropositionSujet;

    /**
     * @ORM\Column(type="date", nullable=false)
     */
    private $dateDebutDefenseSession1;

    /**
     * @ORM\Column(type="date", nullable=false)
     */
    private $dateDebutDefenseSession2;

    /**
     * @ORM\Column(type="date", nullable=false)
     */
    private $dateCollationGrade;

    /**
     * @ORM\Column(type="float")
     */
    private $prixDepot;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $monaie;


    public function __construct()
    {
        $this->dateFinPropositionSujet= new \DateTime();
        $this->dateDebutDefenseSession1= new \DateTime();
        $this->dateCollationGrade= new \DateTime();
        $this->dateDebutDefenseSession2= new \DateTime();

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    public function getPays(): ?string
    {
        return $this->pays;
    }

    public function setPays(string $pays): self
    {
        $this->pays = $pays;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getTelephone1(): ?string
    {
        return $this->telephone1;
    }

    public function setTelephone1(string $telephone1): self
    {
        $this->telephone1 = $telephone1;

        return $this;
    }

    public function getTelephone2(): ?string
    {
        return $this->telephone2;
    }

    public function setTelephone2(?string $telephone2): self
    {
        $this->telephone2 = $telephone2;

        return $this;
    }

    public function getWebsite(): ?string
    {
        return $this->website;
    }

    public function setWebsite(string $website): self
    {
        $this->website = $website;

        return $this;
    }

    public function getIsPublique(): ?bool
    {
        return $this->isPublique;
    }

    public function setIsPublique(bool $isPublique): self
    {
        $this->isPublique = $isPublique;

        return $this;
    }

    public function getSigle(): ?string
    {
        return $this->sigle;
    }

    public function setSigle(string $sigle): self
    {
        $this->sigle = $sigle;

        return $this;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(string $logo): self
    {
        $this->logo = $logo;

        return $this;
    }

    public function getDevise(): ?string
    {
        return $this->devise;
    }

    public function setDevise(string $devise): self
    {
        $this->devise = $devise;

        return $this;
    }

    public function getBoitePostale(): ?string
    {
        return $this->boitePostale;
    }

    public function setBoitePostale(?string $boitePostale): self
    {
        $this->boitePostale = $boitePostale;

        return $this;
    }

    public function getAnneeAcademiqueCourante(): ?AnneeAcademique
    {
        return $this->anneeAcademiqueCourante;
    }

    public function setAnneeAcademiqueCourante(?AnneeAcademique $anneeAcademiqueCourante): self
    {
        $this->anneeAcademiqueCourante = $anneeAcademiqueCourante;

        return $this;
    }

    public function getDateFinPropositionSujet(): ?\DateTimeInterface
    {
        return $this->dateFinPropositionSujet;
    }

    public function setDateFinPropositionSujet(?\DateTimeInterface $dateFinPropositionSujet): self
    {
        $this->dateFinPropositionSujet = $dateFinPropositionSujet;

        return $this;
    }

    public function getDateDebutDefenseSession1(): ?\DateTimeInterface
    {
        return $this->dateDebutDefenseSession1;
    }

    public function setDateDebutDefenseSession1(?\DateTimeInterface $dateDebutDefenseSession1): self
    {
        $this->dateDebutDefenseSession1 = $dateDebutDefenseSession1;

        return $this;
    }

    public function getDateDebutDefenseSession2(): ?\DateTimeInterface
    {
        return $this->dateDebutDefenseSession2;
    }

    public function setDateDebutDefenseSession2(?\DateTimeInterface $dateDebutDefenseSession2): self
    {
        $this->dateDebutDefenseSession2 = $dateDebutDefenseSession2;

        return $this;
    }

    public function getDateCollationGrade(): ?\DateTimeInterface
    {
        return $this->dateCollationGrade;
    }

    public function setDateCollationGrade(?\DateTimeInterface $dateCollationGrade): self
    {
        $this->dateCollationGrade = $dateCollationGrade;

        return $this;
    }

    public function getPrixDepot(): ?float
    {
        return $this->prixDepot;
    }

    public function setPrixDepot(float $prixDepot): self
    {
        $this->prixDepot = $prixDepot;

        return $this;
    }

    public function getMonaie(): ?string
    {
        return $this->monaie;
    }

    public function setMonaie(string $monaie): self
    {
        $this->monaie = $monaie;

        return $this;
    }

}

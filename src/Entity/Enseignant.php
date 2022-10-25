<?php

namespace App\Entity;

use App\Repository\EnseignantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass=EnseignantRepository::class)
 */
class Enseignant
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isCordirecteur;

    /**
     * @ORM\OneToOne(targetEntity=Identite::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     * @Assert\Valid
     */
    private $identite;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $nombreDirection;

    /**
     * @ORM\Column(type="integer", nullable=true )
     */
    private $nombreFeuxVert;

    /**
     * @ORM\OneToMany(targetEntity=Fiche::class, mappedBy="directeurPropose")
     */
    private $fiches;

    /**
     * @ORM\OneToMany(targetEntity=Fiche::class, mappedBy="directeurRetenu")
     */
    private $fichesRetenues;

    /**
     * @ORM\OneToMany(targetEntity=Fiche::class, mappedBy="codirecteur")
     */
    private $fichesCodirections;

    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    private $nombreNouveauDepot;

    /**
     * @ORM\OneToOne(targetEntity=User::class, mappedBy="enseignant", cascade={"persist", "remove"})
     */
    private $user;

    /**
     * @ORM\ManyToMany(targetEntity=LignePlanification::class, mappedBy="lecteurs")
     */
    private $lectures;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $adresseBureau;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $grade;

    public function getNombreFicheRetnues()
    {
        return count($this->fichesRetenues);
    }

    public function __construct()
    {
        // $user->setEnseignant($this);
        $this->fiches = new ArrayCollection();
        $this->fichesRetenues = new ArrayCollection();
        $this->fichesCodirections = new ArrayCollection();
        $this->setNombreNouveauDepot(0);
        $this->lectures = new ArrayCollection();
        $this->isCordirecteur = false;
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

    public function getIsCordirecteur(): ?bool
    {
        return $this->isCordirecteur;
    }

    public function setIsCordirecteur(bool $isCordirecteur): self
    {
        $this->isCordirecteur = $isCordirecteur;

        return $this;
    }

    public function getNombreDirection(): ?int
    {
        return $this->nombreDirection;
    }

    public function setNombreDirection(?int $nombreDirection): self
    {
        $this->nombreDirection = $nombreDirection;

        return $this;
    }

    public function getNombreFeuxVert(): ?int
    {
        return $this->nombreFeuxVert;
    }

    public function setNombreFeuxVert(?int $nombreFeuxVert): self
    {
        $this->nombreFeuxVert = $nombreFeuxVert;

        return $this;
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

    /**
     * @return Collection|Fiche[]
     */
    public function getFiches(): Collection
    {
        return $this->fiches;
    }

    public function addFich(Fiche $fich): self
    {
        if (!$this->fiches->contains($fich)) {
            $this->fiches[] = $fich;
            $fich->setDirecteurPropose($this);
        }

        return $this;
    }

    public function removeFich(Fiche $fich): self
    {
        if ($this->fiches->removeElement($fich)) {
            // set the owning side to null (unless already changed)
            if ($fich->getDirecteurPropose() === $this) {
                $fich->setDirecteurPropose(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Fiche[]
     */
    public function getFichesRetenues(): Collection
    {
        return $this->fichesRetenues;
    }

    public function addFichesRetenue(Fiche $fichesRetenue): self
    {
        if (!$this->fichesRetenues->contains($fichesRetenue)) {
            $this->fichesRetenues[] = $fichesRetenue;
            $fichesRetenue->setDirecteurRetenu($this);
        }

        return $this;
    }

    public function removeFichesRetenue(Fiche $fichesRetenue): self
    {
        if ($this->fichesRetenues->removeElement($fichesRetenue)) {
            // set the owning side to null (unless already changed)
            if ($fichesRetenue->getDirecteurRetenu() === $this) {
                $fichesRetenue->setDirecteurRetenu(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Fiche[]
     */
    public function getFichesCodirections(): Collection
    {
        return $this->fichesCodirections;
    }

    public function addFichesCodirection(Fiche $fichesCodirection): self
    {
        if (!$this->fichesCodirections->contains($fichesCodirection)) {
            $this->fichesCodirections[] = $fichesCodirection;
            $fichesCodirection->setCodirecteur($this);
        }

        return $this;
    }

    public function removeFichesCodirection(Fiche $fichesCodirection): self
    {
        if ($this->fichesCodirections->removeElement($fichesCodirection)) {
            // set the owning side to null (unless already changed)
            if ($fichesCodirection->getCodirecteur() === $this) {
                $fichesCodirection->setCodirecteur(null);
            }
        }

        return $this;
    }

    public function getNombreNouveauDepot(): ?int
    {
        return $this->nombreNouveauDepot;
    }

    public function setNombreNouveauDepot(?int $nombreNouveauDepot): self
    {
        $this->nombreNouveauDepot = $nombreNouveauDepot;

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
            $this->user->setEnseignant(null);
        }

        // set the owning side of the relation if necessary
        if ($user !== null && $user->getEnseignant() !== $this) {
            $user->setEnseignant($this);
        }

        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection|LignePlanification[]
     */
    public function getLectures(): Collection
    {
        return $this->lectures;
    }

    public function addLecture(LignePlanification $lecture): self
    {
        if (!$this->lectures->contains($lecture)) {
            $this->lectures[] = $lecture;
            $lecture->addLecteur($this);
        }

        return $this;
    }

    public function removeLecture(LignePlanification $lecture): self
    {
        if ($this->lectures->removeElement($lecture)) {
            $lecture->removeLecteur($this);
        }

        return $this;
    }

    public function getAdresseBureau(): ?string
    {
        return $this->adresseBureau;
    }

    public function setAdresseBureau(string $adresseBureau): self
    {
        $this->adresseBureau = $adresseBureau;

        return $this;
    }

    public function getGrade(): ?string
    {
        return $this->grade;
    }

    public function setGrade(string $grade): self
    {
        $this->grade = $grade;

        return $this;
    }
}

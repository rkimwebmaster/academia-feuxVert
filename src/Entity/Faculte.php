<?php

namespace App\Entity;

use App\Repository\AnneeAcademiqueRepository;
use App\Repository\FaculteRepository;
use App\Service\AnneeCourante;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=FaculteRepository::class)
    * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(
 *     fields={"designation"},
 *     errorPath="designation",
 *     message="Cette valeur est déjà utilisé et ne peut exister en double."
 * )
 */
class Faculte
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
    private $designation;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity=Departement::class, mappedBy="faculte", orphanRemoval=true,cascade={"persist"})
     */
    private $departements;

    /**
     * @ORM\Column(type="integer",nullable=false)
     */
    private $nombreFicheATraiter;

    /**
     * @ORM\Column(type="integer")
     */
    private $nombreFicheCree;

    /**
     * @ORM\Column(type="integer")
     */
    private $nombreFicheSoumis;

    /**
     * @ORM\Column(type="integer")
     */
    private $nombreFicheValidee;

    /**
     * @ORM\Column(type="integer")
     */
    private $nombreFicheFeuxVert;

    /**
     * @ORM\Column(type="integer")
     */
    private $nombreFicheAlignee;

    /**
     * @ORM\Column(type="integer")
     */
    private $nombreFicheDefendu;

    /**
     * @ORM\OneToMany(targetEntity=Fiche::class, mappedBy="faculte")
     */
    private $fiches;

    /**
     * @ORM\OneToMany(targetEntity=Promotion::class, mappedBy="faculte")
     */
    private $promotions;

    /**
     * @ORM\OneToMany(targetEntity=Finaliste::class, mappedBy="faculte")
     */
    private $finalistes;

 
    public function __construct()
    {
        $this->departements = new ArrayCollection();
        //$this->nombreFicheATraiter=0;
        $this->nombreFicheAlignee=0;
        $this->nombreFicheCree=0;
        $this->nombreFicheDefendu=0;
        $this->nombreFicheFeuxVert=0;
        $this->nombreFicheSoumis=0;
        $this->nombreFicheValidee=0;
        $this->nombreFicheATraiter=0;
        $this->fiches = new ArrayCollection();
        $this->promotions = new ArrayCollection();
        $this->finalistes = new ArrayCollection();
    }

    public function __toString()
    {
        return (string) $this->getDesignation();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDesignation(): ?string
    {
        return $this->designation;
    }

    public function setDesignation(string $designation): self
    {
        $this->designation = $designation;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|Departement[]
     */
    public function getDepartements(): Collection
    {
        // dd('sddgsgsg');
        // $departements=$this->departements;
        // foreach($departements as $departement){
        //     $anneeCourante=new AnneeAcademique();
        //     $anneeCourante->setDebut(2002);
        //    // $departement->s
        // }
        return $this->departements;
    }

    public function addDepartement(Departement $departement): self
    {
        if (!$this->departements->contains($departement)) {
            $this->departements[] = $departement;
            $departement->setFaculte($this);
        }

        return $this;
    }

    public function removeDepartement(Departement $departement): self
    {
        if ($this->departements->removeElement($departement)) {
            // set the owning side to null (unless already changed)
            if ($departement->getFaculte() === $this) {
                $departement->setFaculte(null);
            }
        }

        return $this;
    }

    public function getNombreFicheATraiter(): ?int
    {
        return $this->nombreFicheATraiter;
    }

    public function setNombreFicheATraiter(int $nombreFicheATraiter): self
    {
        $this->nombreFicheATraiter = $nombreFicheATraiter;

        return $this;
    }

    public function getNombreFicheCree(): ?int
    {
        return $this->nombreFicheCree;
    }

    public function setNombreFicheCree(int $nombreFicheCree): self
    {
        $this->nombreFicheCree = $nombreFicheCree;

        return $this;
    }

    public function getNombreFicheSoumis(): ?int
    {
        return $this->nombreFicheSoumis;
    }

    public function setNombreFicheSoumis(int $nombreFicheSoumis): self
    {
        $this->nombreFicheSoumis = $nombreFicheSoumis;

        return $this;
    }

    public function getNombreFicheValidee(): ?int
    {
        return $this->nombreFicheValidee;
    }

    public function setNombreFicheValidee(int $nombreFicheValidee): self
    {
        $this->nombreFicheValidee = $nombreFicheValidee;

        return $this;
    }

    public function getNombreFicheFeuxVert(): ?int
    {
        return $this->nombreFicheFeuxVert;
    }

    public function setNombreFicheFeuxVert(int $nombreFicheFeuxVert): self
    {
        $this->nombreFicheFeuxVert = $nombreFicheFeuxVert;

        return $this;
    }

    public function getNombreFicheAlignee(): ?int
    {
        return $this->nombreFicheAlignee;
    }

    public function setNombreFicheAlignee(int $nombreFicheAlignee): self
    {
        $this->nombreFicheAlignee = $nombreFicheAlignee;

        return $this;
    }

    public function getNombreFicheDefendu(): ?int
    {
        return $this->nombreFicheDefendu;
    }

    public function setNombreFicheDefendu(int $nombreFicheDefendu): self
    {
        $this->nombreFicheDefendu = $nombreFicheDefendu;

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
            $fich->setFaculte($this);
        }

        return $this;
    }

    public function removeFich(Fiche $fich): self
    {
        if ($this->fiches->removeElement($fich)) {
            // set the owning side to null (unless already changed)
            if ($fich->getFaculte() === $this) {
                $fich->setFaculte(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Promotion[]
     */
    public function getPromotions(): Collection
    {
        return $this->promotions;
    }

    public function addPromotion(Promotion $promotion): self
    {
        if (!$this->promotions->contains($promotion)) {
            $this->promotions[] = $promotion;
            $promotion->setFaculte($this);
        }

        return $this;
    }

    public function removePromotion(Promotion $promotion): self
    {
        if ($this->promotions->removeElement($promotion)) {
            // set the owning side to null (unless already changed)
            if ($promotion->getFaculte() === $this) {
                $promotion->setFaculte(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Finaliste[]
     */
    public function getFinalistes(): Collection
    {
        return $this->finalistes;
    }

    public function addFinaliste(Finaliste $finaliste): self
    {
        if (!$this->finalistes->contains($finaliste)) {
            $this->finalistes[] = $finaliste;
            $finaliste->setFaculte($this);
        }

        return $this;
    }

    public function removeFinaliste(Finaliste $finaliste): self
    {
        if ($this->finalistes->removeElement($finaliste)) {
            // set the owning side to null (unless already changed)
            if ($finaliste->getFaculte() === $this) {
                $finaliste->setFaculte(null);
            }
        }

        return $this;
    }
}

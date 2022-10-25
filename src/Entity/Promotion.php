<?php

namespace App\Entity;

use App\Repository\AnneeAcademiqueRepository;
use App\Repository\PromotionRepository;
use App\Service\AnneeCourante;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=PromotionRepository::class)
     * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(
 *     fields={"designation","departement"},
 *     errorPath="designation",
 *     message="Cette valeur est déjà utilisé et ne peut exister en double pour le même département."
 * )

 */
class Promotion
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
     * @ORM\ManyToOne(targetEntity=Departement::class, inversedBy="promotions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $departement;

    /**
     * @ORM\ManyToOne(targetEntity=AnneeAcademique::class,cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $anneeAcademique;

    private $anneeCourante;

    /**
     * @ORM\OneToMany(targetEntity=Finaliste::class, mappedBy="promotion")
     */
    private $finalistes;

    /**
     * @ORM\ManyToOne(targetEntity=Faculte::class, inversedBy="promotions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $faculte;

    function __construct()
    {
        $this->finalistes = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getDesignation().'-'.$this->getDepartement();
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function avantEnregistrement(){
        $this->faculte=$this->departement->getFaculte();
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

    public function getDepartement(): ?Departement
    {
        return $this->departement;
    }

    public function setDepartement(?Departement $departement): self
    {
        $this->departement = $departement;

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
            $finaliste->setPromotion($this);
        }

        return $this;
    }

    public function removeFinaliste(Finaliste $finaliste): self
    {
        if ($this->finalistes->removeElement($finaliste)) {
            // set the owning side to null (unless already changed)
            if ($finaliste->getPromotion() === $this) {
                $finaliste->setPromotion(null);
            }
        }

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
}

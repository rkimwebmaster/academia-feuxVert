<?php

namespace App\Entity;

use App\Repository\DepartementRepository;
use App\Repository\AnneeAcademiqueRepository;
use App\Service\CreateurPromotion;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=DepartementRepository::class)
  * @UniqueEntity(
 *     fields={"designation"},
 *     errorPath="designation",
 *     message="Cette valeur est déjà utilisé et ne peut exister en double."
 * )

   * @ORM\HasLifecycleCallbacks()
 */
class Departement
{


    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $designation;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;


    /**
     * @ORM\OneToMany(targetEntity=Promotion::class, mappedBy="departement", cascade={"persist"})
     */
    private $promotions;

    /**
     * @ORM\ManyToOne(targetEntity=Faculte::class, inversedBy="departements")
     * @ORM\JoinColumn(nullable=false)
     */
    private $faculte;
    

    public function __toString()
    {
        return $this->getDesignation();
    }

    public function __construct()
    {
        $this->promotions = new ArrayCollection();
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

    public function setDescription(?string $description): self
    {
        $this->description = $description;

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
            $promotion->setDepartement($this);
        }

        return $this;
    }

    public function removePromotion(Promotion $promotion): self
    {
        if ($this->promotions->removeElement($promotion)) {
            // set the owning side to null (unless already changed)
            if ($promotion->getDepartement() === $this) {
                $promotion->setDepartement(null);
            }
        }

        return $this;
    }
}

<?php

namespace App\Entity;

use App\Repository\AnneeAcademiqueRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=AnneeAcademiqueRepository::class)
  * @ORM\HasLifecycleCallbacks()
  * @UniqueEntity(
 *     fields={"debut"},
 *     errorPath="debut",
 *     message="Cette valeur est déjà utilisé et ne peut exister en double."
 * )
 * @UniqueEntity(
 *     fields={"fin"},
 *     errorPath="fin",
 *     message="Cette valeur est déjà utilisé et ne peut exister en double."
 * )
 */
class AnneeAcademique
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", unique=true)
     */
    private $debut;

    /**
     * @ORM\Column(type="integer", unique=true)
     */
    private $fin;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isCurrent;

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function miseAJour(){
        $this->setFin($this->getDebut()+1);
    }

    public function __tostring(){
        return $this->debut.'-'.$this->fin;
    }

    public function __construct()
    {
        $this->setCreatedAt(new \DateTime());
        $this->setUpdatedAt(new \DateTime());
        $this->setIsCurrent(false);
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDebut(): ?int
    {
        return $this->debut;
    }

    public function setDebut(int $debut): self
    {
        $this->debut = $debut;

        return $this;
    }

    public function getFin(): ?int
    {
        return $this->fin;
    }

    public function setFin(int $fin): self
    {
        $this->fin = $fin;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getIsCurrent(): ?bool
    {
        return $this->isCurrent;
    }

    public function setIsCurrent(?bool $isCurrent): self
    {
        $this->isCurrent = $isCurrent;

        return $this;
    }
}

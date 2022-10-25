<?php

namespace App\Entity;

use App\Repository\PlanificationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=PlanificationRepository::class)
 */
class Planification
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
     * @ORM\Column(type="boolean")
     */
    private $isValidee;

    /**
     * @ORM\OneToMany(targetEntity=LignePlanification::class, mappedBy="planification", orphanRemoval=true,cascade={"persist"})
     */
    private $lignePlanifications;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $observation;

    /**
     * @ORM\Column(type="integer")
     */
    private $minutesDefense=15;

    /**
     * @ORM\Column(type="integer")
     */
    private $minutesPause=3;

    /**
     * @ORM\ManyToOne(targetEntity=Salle::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $salle;

    public function __construct()
    {
        $this->lignePlanifications = new ArrayCollection();
        $this->tempsDefense= new \DateTime('+10 min');
        $this->date=new \DateTime(' + 10 sec ');
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

    public function getTempsDefense(): ?\DateTimeInterface
    {
        return $this->tempsDefense;
    }

    public function setTempsDefense(\DateTimeInterface $tempsDefense): self
    {
        $this->tempsDefense = $tempsDefense;

        return $this;
    }

    public function getTempsPause(): ?\DateTimeInterface
    {
        return $this->tempsPause;
    }

    public function setTempsPause(\DateTimeInterface $tempsPause): self
    {
        $this->tempsPause = $tempsPause;

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

    /**
     * @return Collection|LignePlanification[]
     */
    public function getLignePlanifications(): Collection
    {
        return $this->lignePlanifications;
    }

    public function addLignePlanification(LignePlanification $lignePlanification): self
    {
        if (!$this->lignePlanifications->contains($lignePlanification)) {
            $this->lignePlanifications[] = $lignePlanification;
            $lignePlanification->setPlanification($this);
        }

        return $this;
    }

    public function ajoutLignePlanification(LignePlanification $lignePlanification): self
    {
        if (!$this->lignePlanifications->contains($lignePlanification)) {
            $this->lignePlanifications[] = $lignePlanification;
            $lignePlanification->setPlanification(null);
        }

        return $this;
    }

    public function removeLignePlanification(LignePlanification $lignePlanification): self
    {
        if ($this->lignePlanifications->removeElement($lignePlanification)) {
            // set the owning side to null (unless already changed)
            if ($lignePlanification->getPlanification() === $this) {
                $lignePlanification->setPlanification(null);
            }
        }

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

    public function getMinutesDefense(): ?int
    {
        return $this->minutesDefense;
    }

    public function setMinutesDefense(int $minutesDefense): self
    {
        $this->minutesDefense = $minutesDefense;

        return $this;
    }

    public function getMinutesPause(): ?int
    {
        return $this->minutesPause;
    }

    public function setMinutesPause(int $minutesPause): self
    {
        $this->minutesPause = $minutesPause;

        return $this;
    }

    public function getSalle(): ?Salle
    {
        return $this->salle;
    }

    public function setSalle(?Salle $salle): self
    {
        $this->salle = $salle;

        return $this;
    }
}

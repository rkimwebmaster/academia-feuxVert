<?php

namespace App\Service;

use App\Repository\AnneeAcademiqueRepository;
use App\Entity\AnneeAcademique;

class AnneeCourante
{

    private $anneeAcademiqueRepository;
    public function __construct(AnneeAcademiqueRepository $anneeAcademiqueRepository)
    {
        $this->anneeAcademiqueRepository=$anneeAcademiqueRepository;
    }

    public function getAnneeCourante():AnneeAcademique{
        $anneeAcademiqueCourante=$this->anneeAcademiqueRepository->findOneBy(['isCurrent'=>true]);
        return $anneeAcademiqueCourante;
    }    
}

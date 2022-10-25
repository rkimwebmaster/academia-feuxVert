<?php

namespace App\Service;

use App\Repository\AnneeAcademiqueRepository;
use App\Entity\AnneeAcademique;
use App\Entity\Departement;
use App\Entity\Promotion;
use Doctrine\Persistence\ObjectManager;

class CreateurPromotion
{

    private $anneeAcademiqueRepository;
    private $departement;
    private $objectManager;
    public function __construct(ObjectManager $objectManager, AnneeAcademiqueRepository $anneeAcademiqueRepository, Departement $departement)
    {
        $this->objectManager=$objectManager;
        $this->departement=$departement;
        $this->anneeAcademiqueRepository=$anneeAcademiqueRepository;
    }

    public function getAnneeCourante():AnneeAcademique{
        $anneeAcademiqueCourante=$this->anneeAcademiqueRepository->findOneBy(['isCurrent'=>true]);
        return $anneeAcademiqueCourante;
    }
    public function creerPromotion(){

        $anneeAcademiqueCourante=$this->anneeAcademiqueRepository->findOneBy(['isCurrent'=>true]);

        $objectManager=$this->objectManager;
        if(! $anneeAcademiqueCourante){
            return ;
        }

        $designation1='PREMIER BACHELIER';
        $designation2='DEUXIEME BACHELIER';
        $designation3='TROISIEME BACHELIER';
        $designation4='LICENCE 1 ';
        $designation5='LICENCE 2 ';

        $promotion1= new Promotion($anneeAcademiqueCourante);
        $promotion1->setDesignation($designation1);
        $promotion1->setDepartement($this->departement);
        $this->departement->addPromotion($promotion1);
        $objectManager->persist($promotion1);

        $promotion2= new Promotion($anneeAcademiqueCourante);
        $promotion2->setDesignation($designation2);
        $promotion2->setDepartement($this->departement);
        $this->departement->addPromotion($promotion2);
        $objectManager->persist($promotion2);

        $promotion3= new Promotion($anneeAcademiqueCourante);
        $promotion3->setDesignation($designation3);
        $promotion3->setDepartement($this->departement);
        $this->departement->addPromotion($promotion3);
        $objectManager->persist($promotion3);

        
    }
    
}

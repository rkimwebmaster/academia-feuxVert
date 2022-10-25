<?php

namespace App\Repository;

use App\Entity\Depot;
use App\Entity\Fiche;
use App\Entity\Enseignant;
use App\Entity\Finaliste;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Depot|null find($id, $lockMode = null, $lockVersion = null)
 * @method Depot|null findOneBy(array $criteria, array $orderBy = null)
 * @method Depot[]    findAll()
 * @method Depot[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DepotRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Depot::class);
    }

    /**
     * @return Depot[] Returns an array of Depot objects
    */
    public function getDepotDirecteur(Enseignant $directeur, FicheRepository $ficheRepository)
    {
        $fichesDirecteurs=$ficheRepository->findByDirecteurRetenu($directeur);
        $depotsRetours=array();
        foreach($fichesDirecteurs as $fiche){
            $depots=$fiche->getDepots();
            foreach($depots as $depot){
                $depotsRetours[]=$depot;
            }
        }
        return $depotsRetours;
        
    }

    /**
     * @return Depot[] Returns an array of Depot objects
    */
    public function getDepotNonCorrigeDirecteur(Enseignant $directeur, FicheRepository $ficheRepository)
    {
        $fichesDirecteurs=$ficheRepository->findBy(['directeurRetenu'=>$directeur]);
        $depotsRetours=array();
        foreach($fichesDirecteurs as $fiche){
            $depots=$fiche->getDepots();
            foreach($depots as $depot){
                if($depot->getIsCorrige()==false){
                    $depotsRetours[]=$depot;
                }
            }
        }
        return $depotsRetours;
        
    }

        /**
     * @return Depot[] Returns an array of Depot objects
    */
    public function getDepotEtudiant(Finaliste $finaliste, FicheRepository $ficheRepository)
    {
        $ficheFinaliste=$ficheRepository->findOneByFinaliste($finaliste);
        $depots=array();
        if($ficheFinaliste){
            $depots=$ficheFinaliste->getDepots();

            return $depots;
        }else{
            return null;
        }
        
        // return $this->createQueryBuilder('d')
        //     ->andWhere('d.fiche = :val')
        //     ->setParameter('val', $value)
        //     ->orderBy('d.id', 'ASC')
        //     ->setMaxResults(10)
        //     ->getQuery()
        //     ->getResult()
        // ;
    }
    

    /*
    public function findOneBySomeField($value): ?Depot
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

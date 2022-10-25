<?php

namespace App\Repository;

use App\Entity\Fiche;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Fiche|null find($id, $lockMode = null, $lockVersion = null)
 * @method Fiche|null findOneBy(array $criteria, array $orderBy = null)
 * @method Fiche[]    findAll()
 * @method Fiche[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FicheRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Fiche::class);
    }

    // /**
    //  * @return Fiche[] Returns an array of Fiche objects
    //  */
    public function getByCodeFiche($numero)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.numero = :val')
            ->setParameter('val', '%'.$numero.'%')
            ->orderBy('f.id', 'ASC')
            //->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

        // /**
    //  * @return Fiche[] Returns an array of Fiche objects
    //  */
    public function getByFeuxVert()
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.isFeuxVert = :val')
            ->setParameter('val', true)
            ->orderBy('f.id', 'ASC')
            //->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    

    /*
    public function findOneBySomeField($value): ?Fiche
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

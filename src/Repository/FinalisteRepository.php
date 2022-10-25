<?php

namespace App\Repository;

use App\Entity\Finaliste;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Finaliste|null find($id, $lockMode = null, $lockVersion = null)
 * @method Finaliste|null findOneBy(array $criteria, array $orderBy = null)
 * @method Finaliste[]    findAll()
 * @method Finaliste[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FinalisteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Finaliste::class);
    }

    // /**
    //  * @return Finaliste[] Returns an array of Finaliste objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Finaliste
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

<?php

namespace App\Repository;

use App\Entity\LignePlanification;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LignePlanification|null find($id, $lockMode = null, $lockVersion = null)
 * @method LignePlanification|null findOneBy(array $criteria, array $orderBy = null)
 * @method LignePlanification[]    findAll()
 * @method LignePlanification[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LignePlanificationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LignePlanification::class);
    }

    // /**
    //  * @return LignePlanification[] Returns an array of LignePlanification objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?LignePlanification
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

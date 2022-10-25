<?php

namespace App\Repository;

use App\Entity\Identite;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Identite|null find($id, $lockMode = null, $lockVersion = null)
 * @method Identite|null findOneBy(array $criteria, array $orderBy = null)
 * @method Identite[]    findAll()
 * @method Identite[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IdentiteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Identite::class);
    }

    // /**
    //  * @return Identite[] Returns an array of Identite objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Identite
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

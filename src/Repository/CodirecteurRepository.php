<?php

namespace App\Repository;

use App\Entity\Codirecteur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Codirecteur|null find($id, $lockMode = null, $lockVersion = null)
 * @method Codirecteur|null findOneBy(array $criteria, array $orderBy = null)
 * @method Codirecteur[]    findAll()
 * @method Codirecteur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CodirecteurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Codirecteur::class);
    }

    // /**
    //  * @return Codirecteur[] Returns an array of Codirecteur objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Codirecteur
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

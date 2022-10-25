<?php

namespace App\Repository;

use App\Entity\Directeur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Directeur|null find($id, $lockMode = null, $lockVersion = null)
 * @method Directeur|null findOneBy(array $criteria, array $orderBy = null)
 * @method Directeur[]    findAll()
 * @method Directeur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DirecteurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Directeur::class);
    }

    // /**
    //  * @return Directeur[] Returns an array of Directeur objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Directeur
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

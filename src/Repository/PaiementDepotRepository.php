<?php

namespace App\Repository;

use App\Entity\PaiementDepot;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PaiementDepot|null find($id, $lockMode = null, $lockVersion = null)
 * @method PaiementDepot|null findOneBy(array $criteria, array $orderBy = null)
 * @method PaiementDepot[]    findAll()
 * @method PaiementDepot[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PaiementDepotRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PaiementDepot::class);
    }

    // /**
    //  * @return PaiementDepot[] Returns an array of PaiementDepot objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PaiementDepot
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

<?php

namespace App\Repository;

use App\Entity\BroadcastMessage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BroadcastMessage|null find($id, $lockMode = null, $lockVersion = null)
 * @method BroadcastMessage|null findOneBy(array $criteria, array $orderBy = null)
 * @method BroadcastMessage[]    findAll()
 * @method BroadcastMessage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BroadcastMessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BroadcastMessage::class);
    }

    // /**
    //  * @return BroadcastMessage[] Returns an array of BroadcastMessage objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?BroadcastMessage
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

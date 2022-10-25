<?php

namespace App\Repository;

use App\Entity\LigneCloture;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LigneCloture|null find($id, $lockMode = null, $lockVersion = null)
 * @method LigneCloture|null findOneBy(array $criteria, array $orderBy = null)
 * @method LigneCloture[]    findAll()
 * @method LigneCloture[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LigneClotureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LigneCloture::class);
    }

    // /**
    //  * @return LigneCloture[] Returns an array of LigneCloture objects
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
    public function findOneBySomeField($value): ?LigneCloture
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

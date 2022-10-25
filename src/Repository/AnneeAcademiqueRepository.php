<?php

namespace App\Repository;

use App\Entity\AnneeAcademique;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AnneeAcademique|null find($id, $lockMode = null, $lockVersion = null)
 * @method AnneeAcademique|null findOneBy(array $criteria, array $orderBy = null)
 * @method AnneeAcademique[]    findAll()
 * @method AnneeAcademique[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnneeAcademiqueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AnneeAcademique::class);
    }

    // /**
    //  * @return AnneeAcademique[] Returns an array of AnneeAcademique objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AnneeAcademique
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

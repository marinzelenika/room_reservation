<?php

namespace App\Repository;

use App\Entity\Soba;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Soba|null find($id, $lockMode = null, $lockVersion = null)
 * @method Soba|null findOneBy(array $criteria, array $orderBy = null)
 * @method Soba[]    findAll()
 * @method Soba[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SobaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Soba::class);
    }

    // /**
    //  * @return Soba[] Returns an array of Soba objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Soba
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

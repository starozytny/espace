<?php

namespace App\Repository\Prev;

use App\Entity\Prev\PrGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PrGroup|null find($id, $lockMode = null, $lockVersion = null)
 * @method PrGroup|null findOneBy(array $criteria, array $orderBy = null)
 * @method PrGroup[]    findAll()
 * @method PrGroup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PrGroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PrGroup::class);
    }

    // /**
    //  * @return PrGroup[] Returns an array of PrGroup objects
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
    public function findOneBySomeField($value): ?PrGroup
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

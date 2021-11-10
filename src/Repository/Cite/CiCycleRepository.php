<?php

namespace App\Repository\Cite;

use App\Entity\Cite\CiCycle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CiCycle|null find($id, $lockMode = null, $lockVersion = null)
 * @method CiCycle|null findOneBy(array $criteria, array $orderBy = null)
 * @method CiCycle[]    findAll()
 * @method CiCycle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CiCycleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CiCycle::class);
    }

    // /**
    //  * @return CiCycle[] Returns an array of CiCycle objects
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
    public function findOneBySomeField($value): ?CiCycle
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

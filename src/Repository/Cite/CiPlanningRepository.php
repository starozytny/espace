<?php

namespace App\Repository\Cite;

use App\Entity\Cite\CiPlanning;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CiPlanning|null find($id, $lockMode = null, $lockVersion = null)
 * @method CiPlanning|null findOneBy(array $criteria, array $orderBy = null)
 * @method CiPlanning[]    findAll()
 * @method CiPlanning[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CiPlanningRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CiPlanning::class);
    }

    // /**
    //  * @return CiPlanning[] Returns an array of CiPlanning objects
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
    public function findOneBySomeField($value): ?CiPlanning
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

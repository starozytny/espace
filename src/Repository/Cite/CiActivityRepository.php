<?php

namespace App\Repository\Cite;

use App\Entity\Cite\CiActivity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CiActivity|null find($id, $lockMode = null, $lockVersion = null)
 * @method CiActivity|null findOneBy(array $criteria, array $orderBy = null)
 * @method CiActivity[]    findAll()
 * @method CiActivity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CiActivityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CiActivity::class);
    }

    // /**
    //  * @return CiActivity[] Returns an array of CiActivity objects
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
    public function findOneBySomeField($value): ?CiActivity
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

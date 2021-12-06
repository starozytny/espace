<?php

namespace App\Repository\Cite;

use App\Entity\Cite\CiGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CiGroup|null find($id, $lockMode = null, $lockVersion = null)
 * @method CiGroup|null findOneBy(array $criteria, array $orderBy = null)
 * @method CiGroup[]    findAll()
 * @method CiGroup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CiGroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CiGroup::class);
    }

    // /**
    //  * @return CiGroup[] Returns an array of CiGroup objects
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
    public function findOneBySomeField($value): ?CiGroup
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

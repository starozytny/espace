<?php

namespace App\Repository\Visio;

use App\Entity\Visio\ViRoom;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ViRoom|null find($id, $lockMode = null, $lockVersion = null)
 * @method ViRoom|null findOneBy(array $criteria, array $orderBy = null)
 * @method ViRoom[]    findAll()
 * @method ViRoom[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ViRoomRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ViRoom::class);
    }

    // /**
    //  * @return ViRoom[] Returns an array of ViRoom objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('v.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ViRoom
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

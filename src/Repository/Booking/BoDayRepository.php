<?php

namespace App\Repository\Booking;

use App\Entity\Booking\BoDay;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BoDay|null find($id, $lockMode = null, $lockVersion = null)
 * @method BoDay|null findOneBy(array $criteria, array $orderBy = null)
 * @method BoDay[]    findAll()
 * @method BoDay[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BoDayRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BoDay::class);
    }

    // /**
    //  * @return Day[] Returns an array of Day objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Day
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

<?php

namespace App\Repository\Booking;

use App\Entity\Booking\BoSlot;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BoSlot|null find($id, $lockMode = null, $lockVersion = null)
 * @method BoSlot|null findOneBy(array $criteria, array $orderBy = null)
 * @method BoSlot[]    findAll()
 * @method BoSlot[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BoSlotRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BoSlot::class);
    }

    // /**
    //  * @return ReSlot[] Returns an array of ReSlot objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ReSlot
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

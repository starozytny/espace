<?php

namespace App\Repository\Booking;

use App\Entity\Booking\BoResponsable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BoResponsable|null find($id, $lockMode = null, $lockVersion = null)
 * @method BoResponsable|null findOneBy(array $criteria, array $orderBy = null)
 * @method BoResponsable[]    findAll()
 * @method BoResponsable[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BoResponsableRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BoResponsable::class);
    }

    // /**
    //  * @return BoResponsable[] Returns an array of BoResponsable objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?BoResponsable
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

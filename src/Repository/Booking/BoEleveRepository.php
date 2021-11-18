<?php

namespace App\Repository\Booking;

use App\Entity\Booking\BoEleve;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BoEleve|null find($id, $lockMode = null, $lockVersion = null)
 * @method BoEleve|null findOneBy(array $criteria, array $orderBy = null)
 * @method BoEleve[]    findAll()
 * @method BoEleve[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BoEleveRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BoEleve::class);
    }

    // /**
    //  * @return BoEleve[] Returns an array of BoEleve objects
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
    public function findOneBySomeField($value): ?BoEleve
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

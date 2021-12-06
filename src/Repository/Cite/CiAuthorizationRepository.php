<?php

namespace App\Repository\Cite;

use App\Entity\Cite\CiAuthorization;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CiAuthorization|null find($id, $lockMode = null, $lockVersion = null)
 * @method CiAuthorization|null findOneBy(array $criteria, array $orderBy = null)
 * @method CiAuthorization[]    findAll()
 * @method CiAuthorization[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CiAuthorizationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CiAuthorization::class);
    }

    // /**
    //  * @return CiAuthorization[] Returns an array of CiAuthorization objects
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
    public function findOneBySomeField($value): ?CiAuthorization
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

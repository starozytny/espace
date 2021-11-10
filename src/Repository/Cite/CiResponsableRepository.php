<?php

namespace App\Repository\Cite;

use App\Entity\Cite\CiResponsable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CiResponsable|null find($id, $lockMode = null, $lockVersion = null)
 * @method CiResponsable|null findOneBy(array $criteria, array $orderBy = null)
 * @method CiResponsable[]    findAll()
 * @method CiResponsable[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CiResponsableRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CiResponsable::class);
    }

    // /**
    //  * @return CiResponsable[] Returns an array of CiResponsable objects
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
    public function findOneBySomeField($value): ?CiResponsable
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

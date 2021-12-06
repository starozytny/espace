<?php

namespace App\Repository\Cite;

use App\Entity\Cite\CiAssignation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CiAssignation|null find($id, $lockMode = null, $lockVersion = null)
 * @method CiAssignation|null findOneBy(array $criteria, array $orderBy = null)
 * @method CiAssignation[]    findAll()
 * @method CiAssignation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CiAssignationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CiAssignation::class);
    }

    // /**
    //  * @return CiAssignation[] Returns an array of CiAssignation objects
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
    public function findOneBySomeField($value): ?CiAssignation
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

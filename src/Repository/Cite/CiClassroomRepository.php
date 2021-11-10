<?php

namespace App\Repository\Cite;

use App\Entity\Cite\CiClassroom;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CiClassroom|null find($id, $lockMode = null, $lockVersion = null)
 * @method CiClassroom|null findOneBy(array $criteria, array $orderBy = null)
 * @method CiClassroom[]    findAll()
 * @method CiClassroom[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CiClassroomRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CiClassroom::class);
    }

    // /**
    //  * @return CiClassroom[] Returns an array of CiClassroom objects
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
    public function findOneBySomeField($value): ?CiClassroom
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

<?php

namespace App\Repository\Cite;

use App\Entity\Cite\CiTeacher;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CiTeacher|null find($id, $lockMode = null, $lockVersion = null)
 * @method CiTeacher|null findOneBy(array $criteria, array $orderBy = null)
 * @method CiTeacher[]    findAll()
 * @method CiTeacher[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CiTeacherRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CiTeacher::class);
    }

    // /**
    //  * @return CiTeacher[] Returns an array of CiTeacher objects
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
    public function findOneBySomeField($value): ?CiTeacher
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

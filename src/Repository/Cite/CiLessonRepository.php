<?php

namespace App\Repository\Cite;

use App\Entity\Cite\CiLesson;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CiLesson|null find($id, $lockMode = null, $lockVersion = null)
 * @method CiLesson|null findOneBy(array $criteria, array $orderBy = null)
 * @method CiLesson[]    findAll()
 * @method CiLesson[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CiLessonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CiLesson::class);
    }

    // /**
    //  * @return CiLesson[] Returns an array of CiLesson objects
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
    public function findOneBySomeField($value): ?CiLesson
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

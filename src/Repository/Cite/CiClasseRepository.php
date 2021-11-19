<?php

namespace App\Repository\Cite;

use App\Entity\Cite\CiClasse;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CiClasse|null find($id, $lockMode = null, $lockVersion = null)
 * @method CiClasse|null findOneBy(array $criteria, array $orderBy = null)
 * @method CiClasse[]    findAll()
 * @method CiClasse[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CiClasseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CiClasse::class);
    }

    // /**
    //  * @return CiClasse[] Returns an array of CiClasse objects
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
    public function findOneBySomeField($value): ?CiClasse
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

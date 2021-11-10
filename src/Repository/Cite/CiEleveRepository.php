<?php

namespace App\Repository\Cite;

use App\Entity\Cite\CiEleve;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CiEleve|null find($id, $lockMode = null, $lockVersion = null)
 * @method CiEleve|null findOneBy(array $criteria, array $orderBy = null)
 * @method CiEleve[]    findAll()
 * @method CiEleve[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CiEleveRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CiEleve::class);
    }

    // /**
    //  * @return CiEleve[] Returns an array of CiEleve objects
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
    public function findOneBySomeField($value): ?CiEleve
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

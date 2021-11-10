<?php


namespace App\Service;


use Doctrine\DBAL\DBALException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Console\Style\SymfonyStyle;

class DatabaseService
{
    private $em;
    private $emWindev;

    public function __construct(ManagerRegistry $registry)
    {
        $this->em = $registry->getManager('default');
        $this->emWindev = $registry->getManager('windev');
    }

    public function resetTable(SymfonyStyle $io, $list)
    {
        foreach ($list as $item) {
            $objs = $this->em->getRepository($item)->findAll();
            foreach($objs as $obj){
                $this->em->remove($obj);
            }

            $this->em->flush();
        }
        $io->text('Reset [OK]');
    }

    public function getEntityManagerWindev(): ObjectManager
    {
        return $this->emWindev;
    }

    public function getEntityManager(): ObjectManager
    {
        return $this->em;
    }
}
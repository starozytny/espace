<?php

namespace App\Command;

use App\Entity\Cite\CiAuthorization;
use App\Service\DatabaseService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CiteInitAuthCommand extends Command
{
    protected static $defaultName = 'cite:init:auth';
    protected $em;
    private $databaseService;

    public function __construct(EntityManagerInterface $entityManager, DatabaseService $databaseService)
    {
        parent::__construct();

        $this->em = $entityManager;
        $this->databaseService = $databaseService;
    }

    protected function configure()
{
        $this
            ->setDescription('Init rules authorization.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('Reset des tables');
        $this->databaseService->resetTable($io, [CiAuthorization::class]);

        $io->title('Initialisation des données');

        $renew = (new CiAuthorization())
            ->setRank(0)
            ->setIsOpenLevel(false)
        ;

        $this->em->persist($renew);
        $this->em->flush();

        $io->newLine();
        $io->comment('--- [FIN DE LA COMMANDE] ---');
        return Command::SUCCESS;
    }
}

<?php

namespace App\Command;

use App\Service\DatabaseService;
use App\Service\Synchro\SyncCenter;
use App\Windev\WindevCentre;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CiteSynchroDataCommand extends Command
{
    protected static $defaultName = 'cite:synchro:data';
    protected static $defaultDescription = 'Synchronise data';
    protected $emWindev;
    protected $syncCenter;

    public function __construct(DatabaseService $databaseService, SyncCenter $syncCenter)
    {
        parent::__construct();

        $this->emWindev = $databaseService->getEmWindev();
        $this->syncCenter = $syncCenter;
    }

    protected function getData($io, $windevClass, $title): array
    {
        $io->title("Synchronisation des " . $title);
        return $this->emWindev->getRepository($windevClass)->findAll();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $data = $this->getData($io, WindevCentre::class, "centres");
        $this->syncCenter->synchronize($io, $data);


        return Command::SUCCESS;
    }
}

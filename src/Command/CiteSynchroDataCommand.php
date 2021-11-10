<?php

namespace App\Command;

use App\Service\DatabaseService;
use App\Service\Synchro\SyncData;
use App\Windev\WindevCentre;
use App\Windev\WindevPersonne;
use App\Windev\WindevProfs;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CiteSynchroDataCommand extends Command
{
    protected static $defaultName = 'cite:synchro:data';
    protected static $defaultDescription = 'Synchronise data';
    protected $emWindev;
    protected $syncData;

    public function __construct(DatabaseService $databaseService, SyncData $syncData)
    {
        parent::__construct();

        $this->emWindev = $databaseService->getEmWindev();
        $this->syncData = $syncData;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $this->syncData->synchroData($output, $io, $this->getData($io, WindevCentre::class, "centres"), "centres");
        $this->syncData->synchroData($output, $io, $this->getData($io, WindevProfs::class, "professeurs"), "professeurs");
        $this->syncData->synchroData($output, $io, $this->getData($io, WindevPersonne::class, "responsables"), "responsables");

        return Command::SUCCESS;
    }

    protected function getData($io, $windevClass, $title): array
    {
        $io->title("Synchronisation des " . $title);
        return $this->emWindev->getRepository($windevClass)->findAll();
    }
}

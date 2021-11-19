<?php

namespace App\Command;

use App\Entity\Cite\CiSlot;
use App\Service\DatabaseService;
use App\Service\Synchro\SyncData;
use App\Windev\WindevActivite;
use App\Windev\WindevAdherent;
use App\Windev\WindevAncien;
use App\Windev\WindevCentre;
use App\Windev\WindevCycle;
use App\Windev\WindevEmpltps;
use App\Windev\WindevNiveau;
use App\Windev\WindevPersonne;
use App\Windev\WindevProfs;
use App\Windev\WindevSalle;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CiteSynchroSuiteCommand extends Command
{
    protected static $defaultName = 'cite:synchro:suite';
    protected static $defaultDescription = 'Synchronise data apres la création des plannings';
    protected $em;
    protected $emWindev;
    protected $syncData;

    public function __construct(DatabaseService $databaseService, SyncData $syncData)
    {
        parent::__construct();

        $this->em = $databaseService->getEm();
        $this->emWindev = $databaseService->getEmWindev();
        $this->syncData = $syncData;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $this->syncData->synchroData($output, $io, $this->getDataEm($io, CiSlot::class,"classesSlots"),"classesSlots");

        return Command::SUCCESS;
    }

    protected function getData($io, $windevClass, $title): array
    {
        $io->title("Synchronisation des " . $title);
        return $this->emWindev->getRepository($windevClass)->findAll();
    }

    protected function getDataEm($io, $class, $title): array
    {
        $io->title("Synchronisation des " . $title);
        return $this->em->getRepository($class)->findAll();
    }

    protected function getDataPrevisionnel($io, $windevClass, $title): array
    {
        $io->title("Synchronisation des " . $title);
        return $this->emWindev->getRepository($windevClass)->findBy(['previsionnel' => 1]);
    }
}

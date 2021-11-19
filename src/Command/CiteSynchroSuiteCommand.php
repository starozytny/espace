<?php

namespace App\Command;

use App\Entity\Cite\CiClasse;
use App\Entity\Cite\CiSlot;
use App\Service\DatabaseService;
use App\Service\Synchro\SyncData;
use App\Windev\WindevCours;
use Exception;
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

    /**
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $used = $this->syncData->synchroSpecial($output, $io, $this->getDataEm($io, CiSlot::class,"classesSlots"),"classesSlots");
        $this->syncData->synchroSpecial($output, $io, $this->getData($io, WindevCours::class,"classes"),"classes", [], $used);
        $this->syncData->synchroSpecial($output, $io, $this->getDataEm($io, CiClasse::class,"classesSemi"),"classesSemi");

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
}

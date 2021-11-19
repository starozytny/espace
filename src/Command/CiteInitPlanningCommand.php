<?php

namespace App\Command;

use App\Entity\Cite\CiPlanning;
use App\Entity\Cite\CiSlot;
use App\Service\DatabaseService;
use App\Service\Synchro\SyncData;
use App\Windev\WindevAdhact;
use App\Windev\WindevEmpltps;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CiteInitPlanningCommand extends Command
{
    protected static $defaultName = 'cite:init:planning';
    protected static $defaultDescription = 'Create planning prev and actual';
    protected $syncData;
    protected $databaseService;
    protected $emWindev;
    protected $em;

    public function __construct(DatabaseService $databaseService, SyncData $syncData)
    {
        parent::__construct();

        $this->syncData = $syncData;
        $this->databaseService = $databaseService;

        $this->em = $databaseService->getEm();
        $this->emWindev = $databaseService->getEmWindev();
    }

    protected function configure()
    {
        $this
            ->addArgument('year', InputArgument::REQUIRED, 'année du planning n+1')
        ;
    }

    /**
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('Reset des tables');
        $list = [
            CiSlot::class, CiPlanning::class
        ];
        $this->databaseService->resetTable($io, $list);

        $plannings = $this->createPlanning($io, $input->getArgument('year'));
        $usedSlot = $this->syncData->synchroSlots($output, $io, $this->getData($io, WindevEmpltps::class,"slots"),"slots", $plannings);
        $usedAdhAct = $this->syncData->synchroSlots($output, $io, $this->getData($io, WindevAdhact::class,"slotsMissing"),"slotsMissing", $plannings, $usedSlot);
//        $this->syncData->synchroSlots($output, $io, $this->getData($io, WindevAdhact::class,"slotsDelete"),"slotsDelete", $plannings, $usedSlot, $usedAdhAct);

        return Command::SUCCESS;
    }

    protected function getData($io, $windevClass, $title): array
    {
        $io->title("Synchronisation des " . $title);
        return $this->emWindev->getRepository($windevClass)->findAll();
    }

    /**
     * @param $io
     * @param $year
     * @return array
     */
    protected function createPlanning($io, $year): array
    {
        $io->title("Création des plannings");

        $prev = ($year - 1) . '/' . $year;
        $actuel = ($year - 2) . '/' . ($year - 1);

        $planningPrevisionnel = (new CiPlanning())
            ->setName($prev)
            ->setYear($year)
            ->setIsActual(false)
        ;

        $planningActual = (new CiPlanning())
            ->setName($actuel)
            ->setYear($year - 1)
            ->setIsActual(true)
        ;

        $this->em->persist($planningPrevisionnel);
        $this->em->persist($planningActual);
        $this->em->flush();

        $io->text("Actuel : " . $actuel);
        $io->text("Prévisionnel : " . $prev);

        return [$planningPrevisionnel, $planningActual];
    }
}

<?php

namespace App\Service\Synchro;

use App\Entity\Cite\CiActivity;
use App\Entity\Cite\CiCenter;
use App\Entity\Cite\CiClassroom;
use App\Entity\Cite\CiCycle;
use App\Entity\Cite\CiEleve;
use App\Entity\Cite\CiLevel;
use App\Entity\Cite\CiResponsable;
use App\Entity\Cite\CiTeacher;
use App\Service\DatabaseService;
use App\Service\Synchro\Table\SyncActivity;
use App\Service\Synchro\Table\SyncCenter;
use App\Service\Synchro\Table\SyncClassroom;
use App\Service\Synchro\Table\SyncCycle;
use App\Service\Synchro\Table\SyncEleve;
use App\Service\Synchro\Table\SyncLevel;
use App\Service\Synchro\Table\SyncResponsable;
use App\Service\Synchro\Table\SyncTeacher;
use App\Windev\WindevPersonne;
use Symfony\Component\Console\Helper\ProgressBar;

class SyncData
{
    private $em;
    private $emWindev;
    private $sync;

    private $syncCenter;
    private $syncTeacher;
    private $syncResponsable;
    private $syncEleve;
    private $syncActivity;
    private $syncCycle;
    private $syncLevel;
    private $syncClassroom;

    public function __construct(DatabaseService $databaseService, Sync $sync,
                                SyncCenter $syncCenter, SyncTeacher $syncTeacher, SyncResponsable $syncResponsable,
                                SyncEleve $syncEleve, SyncActivity $syncActivity, SyncCycle $syncCycle,
                                SyncLevel $syncLevel, SyncClassroom $syncClassroom)
    {
        $this->em = $databaseService->getEm();
        $this->emWindev = $databaseService->getEmWindev();
        $this->sync = $sync;

        $this->syncCenter = $syncCenter;
        $this->syncTeacher = $syncTeacher;
        $this->syncResponsable = $syncResponsable;
        $this->syncEleve = $syncEleve;
        $this->syncActivity = $syncActivity;
        $this->syncCycle = $syncCycle;
        $this->syncLevel = $syncLevel;
        $this->syncClassroom = $syncClassroom;
    }

    public function synchroSlots($output, $io, $items, $name, $plannings)
    {
        if($this->sync->haveData($io, $items)){
            $errors = []; $updatedArray = [];
            $total = 0; $created = 0; $notUsed = 0; $updated = 0; $noUpdated = 0;

            $progressBar = new ProgressBar($output, count($items));
            $progressBar->start();

            $data1 = [];
            $isAncien = false;
            switch ($name){
                case "salles":
                    $syncFunction = $this->syncClassroom;
                    break;
                default:
                    return;
            }

            foreach($items as $item){
                $progressBar->advance();

                $result = $syncFunction->synchronize($item, $isAncien, $data0, $data1);

                if($result['code'] == 1){
                    $total++;

                    switch ($result['status']){
                        case 3:
                            $noUpdated++;
                            break;
                        case 2:
                            $updated++;
                            array_push($updatedArray, $result['data']);
                            break;
                        case 1:
                            $created++;
                            break;
                        case 0:
                            array_push($errors, $result['data']);
                            break;
                        default:
                            break;
                    }
                }else{
                    $notUsed++;
                }
            }

            $progressBar->finish();

            $this->em->flush();

            $io->newLine();
            $this->sync->displayDataArray($io, $errors);
            $io->comment(sprintf("%d %s non utilisés.", $notUsed, $name));
            $io->comment(sprintf("%d %s mis à jour.", $updated, $name));
            $this->sync->displayDataArray($io, $updatedArray);
            $io->comment(sprintf("%d %s inchangés.", $noUpdated, $name));
            $io->comment(sprintf("%d / %d %s créés.", $created, $total, $name));
        }
    }

    public function synchroData($output, $io, $items, $name)
    {
        if($this->sync->haveData($io, $items)){
            $errors = []; $updatedArray = [];
            $total = 0; $created = 0; $notUsed = 0; $updated = 0; $noUpdated = 0;

            $progressBar = new ProgressBar($output, count($items));
            $progressBar->start();

            $data1 = [];
            $isAncien = false;
            switch ($name){
                case "salles":
                    $data1 = $this->em->getRepository(CiCenter::class)->findAll();
                    $data0 = $this->em->getRepository(CiClassroom::class)->findAll();
                    $syncFunction = $this->syncClassroom;
                    break;
                case "niveaux":
                    $data0 = $this->em->getRepository(CiLevel::class)->findAll();
                    $syncFunction = $this->syncLevel;
                    break;
                case "cycles":
                    $data0 = $this->em->getRepository(CiCycle::class)->findAll();
                    $syncFunction = $this->syncCycle;
                    break;
                case "activites":
                    $data0 = $this->em->getRepository(CiActivity::class)->findAll();
                    $syncFunction = $this->syncActivity;
                    break;
                case "anciens":
                    $isAncien = true;
                    $data1 = $this->em->getRepository(CiResponsable::class)->findAll();
                    $data0 = $this->em->getRepository(CiEleve::class)->findAll();
                    $syncFunction = $this->syncEleve;
                    break;
                case "eleves":
                    $data1 = $this->em->getRepository(CiResponsable::class)->findAll();
                    $data0 = $this->em->getRepository(CiEleve::class)->findAll();
                    $syncFunction = $this->syncEleve;
                    break;
                case "responsables":
                    $data0 = $this->em->getRepository(CiResponsable::class)->findAll();
                    $syncFunction = $this->syncResponsable;
                    break;
                case "professeurs":
                    //Récupération des données des professeurs contenues dans la table windev personne
                    $data1 = $this->getPersonnes($items); //Windev[]
                    $data0 = $this->em->getRepository(CiTeacher::class)->findAll();
                    $syncFunction = $this->syncTeacher;
                    break;
                case "centres":
                    $data0 = $this->em->getRepository(CiCenter::class)->findAll();
                    $syncFunction = $this->syncCenter;
                    break;
                default:
                    return;
            }

            foreach($items as $item){
                $progressBar->advance();

                $result = $syncFunction->synchronize($item, $isAncien, $data0, $data1);

                if($result['code'] == 1){
                    $total++;

                    switch ($result['status']){
                        case 3:
                            $noUpdated++;
                            break;
                        case 2:
                            $updated++;
                            array_push($updatedArray, $result['data']);
                            break;
                        case 1:
                            $created++;
                            break;
                        case 0:
                            array_push($errors, $result['data']);
                            break;
                        default:
                            break;
                    }
                }else{
                    $notUsed++;
                }
            }

            $progressBar->finish();

            $this->em->flush();

            $io->newLine();
            $this->sync->displayDataArray($io, $errors);
            $io->comment(sprintf("%d %s non utilisés.", $notUsed, $name));
            $io->comment(sprintf("%d %s mis à jour.", $updated, $name));
            $this->sync->displayDataArray($io, $updatedArray);
            $io->comment(sprintf("%d %s inchangés.", $noUpdated, $name));
            $io->comment(sprintf("%d / %d %s créés.", $created, $total, $name));
        }
    }

    /**
     * Récupération des données des professeurs contenues dans la table windev personne
     *
     * @param $items
     * @return array
     */
    private function getPersonnes($items): array
    {
        $ids = [];
        foreach($items as $item){
            array_push($ids, $item->getPecleunik());
        }
        return $this->emWindev->getRepository(WindevPersonne::class)->findBy(['id' => $ids]);
    }

}
<?php

namespace App\Service\Synchro;

use App\Entity\Cite\CiActivity;
use App\Entity\Cite\CiAssignation;
use App\Entity\Cite\CiCenter;
use App\Entity\Cite\CiClasse;
use App\Entity\Cite\CiClassroom;
use App\Entity\Cite\CiCycle;
use App\Entity\Cite\CiEleve;
use App\Entity\Cite\CiGroup;
use App\Entity\Cite\CiLesson;
use App\Entity\Cite\CiLevel;
use App\Entity\Cite\CiResponsable;
use App\Entity\Cite\CiSlot;
use App\Entity\Cite\CiTeacher;
use App\Service\DatabaseService;
use App\Service\Synchro\Table\SyncActivity;
use App\Service\Synchro\Table\SyncCenter;
use App\Service\Synchro\Table\SyncClasse;
use App\Service\Synchro\Table\SyncClasseSemi;
use App\Service\Synchro\Table\SyncClasseSlot;
use App\Service\Synchro\Table\SyncClassroom;
use App\Service\Synchro\Table\SyncCycle;
use App\Service\Synchro\Table\SyncEleve;
use App\Service\Synchro\Table\SyncGroup;
use App\Service\Synchro\Table\SyncLesson;
use App\Service\Synchro\Table\SyncLessonFm;
use App\Service\Synchro\Table\SyncLevel;
use App\Service\Synchro\Table\SyncLevelUpFm;
use App\Service\Synchro\Table\SyncResponsable;
use App\Service\Synchro\Table\SyncSlot;
use App\Service\Synchro\Table\SyncSlotMissing;
use App\Service\Synchro\Table\SyncTeacher;
use App\Windev\WindevAdhact;
use App\Windev\WindevCours;
use App\Windev\WindevPersonne;
use Exception;
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
    private $syncSlot;
    private $syncSlotMissing;
    private $syncClasse;
    private $syncClasseSlot;
    private $syncClasseSemi;
    private $syncLesson;
    private $syncGroup;
    private $syncLessonFm;
    private $syncLevelUpFm;

    public function __construct(DatabaseService $databaseService, Sync $sync,
                                SyncCenter $syncCenter, SyncTeacher $syncTeacher, SyncResponsable $syncResponsable,
                                SyncEleve $syncEleve, SyncActivity $syncActivity, SyncCycle $syncCycle,
                                SyncLevel $syncLevel, SyncClassroom $syncClassroom, SyncSlot $syncSlot,
                                SyncSlotMissing $syncSlotMissing, SyncClasse $syncClasse, SyncClasseSemi $syncClasseSemi,
                                SyncClasseSlot $syncClasseSlot, SyncLesson $syncLesson, SyncLessonFm $syncLessonFm,
                                SyncGroup $syncGroup, SyncLevelUpFm $syncLevelUpFm)
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
        $this->syncSlot = $syncSlot;
        $this->syncSlotMissing = $syncSlotMissing;
        $this->syncClasse = $syncClasse;
        $this->syncClasseSlot = $syncClasseSlot;
        $this->syncClasseSemi = $syncClasseSemi;
        $this->syncLesson = $syncLesson;
        $this->syncGroup = $syncGroup;
        $this->syncLessonFm = $syncLessonFm;
        $this->syncLevelUpFm = $syncLevelUpFm;
    }

    /**
     * @throws Exception
     */
    public function synchroSpecial($output, $io, $items, $name, $plannings = [], $usedData = [], $usedAdhAct = []): array
    {
        $used = [];
        if($this->sync->haveData($io, $items)){
            $errors = []; $updatedArray = []; $noDuplication = [];
            $total = 0; $created = 0; $notUsed = 0; $updated = 0; $noUpdated = 0;

            $progressBar = new ProgressBar($output, count($items));
            $progressBar->start();

            $data8 = $usedAdhAct;
            $data7 = $usedData;
            $data6 = $this->em->getRepository(CiClassroom::class)->findAll();
            $data5 = $this->em->getRepository(CiLevel::class)->findAll();
            $data4 = $this->em->getRepository(CiCycle::class)->findAll();
            $data3 = $this->em->getRepository(CiActivity::class)->findAll();
            $data2 = $this->em->getRepository(CiCenter::class)->findAll();
            $data1 = $this->em->getRepository(CiTeacher::class)->findAll();
            $data0 = $this->em->getRepository(CiSlot::class)->findAll();
            $windevData = $items;
            switch ($name){
                case "levelUpFm":
                    $data1 = $this->em->getRepository(CiLesson::class)->findAll();
                    $data0 = $this->em->getRepository(CiAssignation::class)->findAll();
                    $windevData = $this->emWindev->getRepository(WindevCours::class)->findAll();
                    $syncFunction = $this->syncLevelUpFm;
                    break;
                case "groups":
                    $data6 = $this->em->getRepository(CiSlot::class)->findBy(
                        ['planning' => $plannings[0]], ['start' => 'ASC', 'end' => 'DESC']
                    );
                    $data3 = $this->em->getRepository(CiEleve::class)->findAll();
                    $data2 = $this->em->getRepository(CiGroup::class)->findAll();
                    $data1 = $this->em->getRepository(CiClasse::class)->findAll();
                    $windevData = $this->emWindev->getRepository(WindevCours::class)->findAll();
                    $syncFunction = $this->syncGroup;
                    break;
                case "lessonsFM":
                    $data2 = $this->em->getRepository(CiLesson::class)->findAll();
                    $data1 = $this->em->getRepository(CiClasse::class)->findAll();
                    $syncFunction = $this->syncLessonFm;
                    break;
                case "lessonsMissing":
                case "lessons":
                    $data4 = $this->em->getRepository(CiAssignation::class)->findAll();
                    $data3 = $this->em->getRepository(CiEleve::class)->findAll();
                    $data2 = $this->em->getRepository(CiLesson::class)->findAll();
                    $data1 = $this->em->getRepository(CiClasse::class)->findAll();
                    $windevData = $this->emWindev->getRepository(WindevCours::class)->findAll();
                    $syncFunction = $this->syncLesson;
                    break;
                case "classesSemi":
                    $cycle = $this->em->getRepository(CiCycle::class)->findOneBy(['oldId' => 1]);
                    $cycleSemi = $this->em->getRepository(CiCycle::class)->findOneBy(['oldId' => 5]);
                    $items = $this->em->getRepository(CiClasse::class)->findBy(['cycle' => $cycle]);
                    $data1 = $this->em->getRepository(CiClasse::class)->findAll();
                    $data0 = $this->em->getRepository(CiClasse::class)->findBy(['cycle' => $cycleSemi]);
                    $syncFunction = $this->syncClasseSemi;
                    break;
                case "classes":
                    $data0 = $this->em->getRepository(CiClasse::class)->findAll();
                    $windevData = $this->emWindev->getRepository(WindevAdhact::class)->findAll();
                    $noDuplication = $usedData;
                    $syncFunction = $this->syncClasse;
                    break;
                case "classesSlots":
                    $data0 = $this->em->getRepository(CiClasse::class)->findAll();
                    $syncFunction = $this->syncClasseSlot;
                    break;
                case "slotsDelete":
                    $syncFunction = $this->syncSlotMissing;
                    break;
                case "slotsMissing":
                    $windevData = $this->emWindev->getRepository(WindevCours::class)->findAll();
                    $syncFunction = $this->syncSlotMissing;
                    break;
                case "slots":
                    $syncFunction = $this->syncSlot;
                    break;
                default:
                    return $used;
            }

            foreach($items as $item){
                $progressBar->advance();

                $letters = $name == "slots" ? ["", "A", "B", "C", "D"] : [""];

                for($i = 0 ; $i < count($letters) ; $i++){
                    $result = $syncFunction->synchronize($letters[$i], $item, $windevData, $plannings, $noDuplication,
                        $data0, $data1, $data2, $data3, $data4, $data5, $data6, $data7, $data8);

                    if($result['code'] == 1){
                        $total++;

                        switch ($result['status']){
                            case 4:
                                $total   = $result['total'] > 1 ? $total + $result['total'] : $total;
                                $created = $created + $result['created'];
                                $updated = $updated + $result['updated'];
                                $noDuplication = $result['data'];
                                break;
                            case 3:
                                $noUpdated++;
                                break;
                            case 2:
                                $updated++;
                                array_push($used, $result['data']);
                                array_push($updatedArray, $result['data']);

                                if($name != "slots"){
                                    $noDuplication = $result['data'];
                                }

                                break;
                            case 1:
                                $created++;
                                if($name != "slots"){
                                    $noDuplication = $result['data'];
                                }
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
            }

            $progressBar->finish();

            $this->em->flush();

            $io->newLine();
            $this->sync->displayDataArray($io, $errors);
            $io->comment(sprintf("%d %s non utilis??s.", $notUsed, $name));
            $io->comment(sprintf("%d %s mis ?? jour.", $updated, $name));
            if($name == "slots"){
                $this->sync->displayDataArray($io, $updatedArray);
            }
            $io->comment(sprintf("%d %s inchang??s.", $noUpdated, $name));
            $io->comment(sprintf("%d / %d %s cr????s.", $created, $total, $name));
        }

        return $used;
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
                    //R??cup??ration des donn??es des professeurs contenues dans la table windev personne
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
            $io->comment(sprintf("%d %s non utilis??s.", $notUsed, $name));
            $io->comment(sprintf("%d %s mis ?? jour.", $updated, $name));
            $this->sync->displayDataArray($io, $updatedArray);
            $io->comment(sprintf("%d %s inchang??s.", $noUpdated, $name));
            $io->comment(sprintf("%d / %d %s cr????s.", $created, $total, $name));
        }
    }

    /**
     * R??cup??ration des donn??es des professeurs contenues dans la table windev personne
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
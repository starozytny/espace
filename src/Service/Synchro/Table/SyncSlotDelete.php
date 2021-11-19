<?php

namespace App\Service\Synchro\Table;

use App\Entity\Cite\CiActivity;
use App\Entity\Cite\CiCenter;
use App\Entity\Cite\CiClassroom;
use App\Entity\Cite\CiCycle;
use App\Entity\Cite\CiLevel;
use App\Entity\Cite\CiPlanning;
use App\Entity\Cite\CiSlot;
use App\Entity\Cite\CiTeacher;
use App\Service\Synchro\Sync;
use App\Windev\WindevActivite;
use App\Windev\WindevAdhact;
use App\Windev\WindevCours;
use App\Windev\WindevEmpltps;
use Exception;

class SyncSlotDelete extends Sync
{
    /**
     * @param $letter
     * @param WindevAdhact $item
     * @param WindevCours[] $items
     * @param array $plannings - 0 = prev | 1 = actual
     * @param CiSlot[] $slots
     * @param CiTeacher[] $teachers
     * @param CiCenter[] $centers
     * @param CiActivity[] $activities
     * @param CiCycle[] $cycles
     * @param CiLevel[] $levels
     * @param CiClassroom[] $classrooms
     * @param array $noDuplication
     * @param array $usedSlot
     * @param array $usedAdhact
     * @return array
     */
    public function synchronize($letter, WindevAdhact $item, array $items, array $plannings, array $slots,
                                array $teachers, array $centers, array $activities, array $cycles, array $levels, array $classrooms,
                                array $noDuplication, array $usedSlot, array $usedAdhact): array
    {
//        $notUsed = [];
//        // delete all slots qui ne sont pas passé par la boucle
//        foreach($slots as $s){
//            if(!in_array($s->getOldId(), $usedSlot) && !in_array($s->getOldAdhactId(), $usedAdhact)){
//                array_push($notUsed, $s);
//            }
//        }
//
//        $errors = [];
//        $countDelete = 0;
//        foreach($notUsed as $not){
//            if(count($not->getLessons()) == 0){
//                $this->em->remove($not);
//                $countDelete++;
//            }else{
//
//                $canDelete = false;
//                if($toClean){
//                    $canDelete = true;
//                }else{
//                    foreach($not->getLessons() as $lesson){
//                        if($this->helper->canDeleteLesson($lesson)){
//                            $canDelete = true;
//                        }
//                    }
//                }
//
//                if($canDelete){
//                    foreach($not->getLessons() as $lesson){
//                        foreach($lesson->getAssignations() as $assignation){
//                            $this->em->remove($assignation);
//                        }
//                        foreach($lesson->getAssignationRefuses() as $assignationRefus){
//                            $this->em->remove($assignationRefus);
//                        }
//                        $this->em->remove($lesson);
//                    }
//
//                    $this->em->remove($not);
//                    $countDelete++;
//                }else{
//                    array_push($errors, sprintf('Impossible de supprimer car ce slot à des lessons = %d' , $not->getId()));
//
//                }
//            }
//        }

        return ['code' => 0];
    }
}
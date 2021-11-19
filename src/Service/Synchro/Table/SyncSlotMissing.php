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

class SyncSlotMissing extends Sync
{
    private function getExisteFromOldAdhactId($data, $oldId)
    {
        foreach($data as $el){
            if($el->getOldAdhactId() == $oldId){
                return $el;
            }
        }

        return null;
    }

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
     * @return array
     */
    public function synchronize($letter, WindevAdhact $item, array $items, array $plannings, array $noDuplication, array $slots,
                                array $teachers, array $centers, array $activities, array $cycles, array $levels, array $classrooms): array
    {
        $oldId = $item->getId();

        /** @var WindevCours $cours */
        $cours = $this->getExisteFromId($items, $item->getCocleunik());
        $planning = $plannings[1];
        
        // Check des slots déjà créés par adhact existe
        /** @var CiSlot $slot */
        $slot = $this->getExisteFromOldAdhactId($slots, $oldId);

        if($cours && $cours->getJour() != -1){
            //si existe, on met à jours les infos
            if($slot){
                $slot = $this->createSlotIfNoExiste($oldId, $slot, $slot->getIdentifiant(), $cours, $planning, $slots,
                    $teachers, $centers, $activities, $cycles, $levels, $classrooms);

                if($slot){
                    return ['code' => 1, 'status' => 2, 'data' => $oldId];
                }
            }else{
                // s'il existe on check s'il y a un slot pour ce cours
                $find = $this->helper->getExisteSlotForCours($item, $cours, $slots);

                // si n'existe pas on va
                if(!$find instanceof CiSlot){
                    if($find == 1){ // pas trouvé de slot existant pour ce cours
                        $unicite = [$cours->getPrcleunik(), $cours->getCecleunik(), $cours->getAccleunik(), $cours->getCycleunik()];
                        if(!in_array($unicite, $noDuplication)){
                            array_push($noDuplication, $unicite);

                            $slot = $this->createSlotIfNoExiste($oldId, new CiSlot(), uniqid(), $cours, $planning, $slots,
                                $teachers, $centers, $activities, $cycles, $levels, $classrooms);

                            if($slot){
                                $this->em->persist($slot);
                                return ['code' => 1, 'status' => 1, 'data' => $noDuplication];
                            }
                        }
                    }
                }
            }
        }

        return ['code' => 0];
    }

    /**
     * @param $oldId
     * @param CiSlot $slot
     * @param $identifiant
     * @param WindevCours $cours
     * @param CiPlanning $planning
     * @param CiSlot[] $slots
     * @param CiTeacher[] $teachers
     * @param CiCenter[] $centers
     * @param CiActivity[] $activities
     * @param CiCycle[] $cycles
     * @param CiLevel[] $levels
     * @param CiClassroom[] $classrooms
     * @return CiSlot|null
     */
    public function createSlotIfNoExiste($oldId, CiSlot $slot, $identifiant, WindevCours $cours, CiPlanning $planning, array $slots,
                                         array $teachers, array $centers, array $activities, array $cycles, array $levels, array $classrooms): ?CiSlot
    {
        $teacher    = $this->getExisteFromOldId($teachers,      $cours->getPrcleunik());
        $center     = $this->getExisteFromOldId($centers,       $cours->getCecleunik());
        $activity   = $this->getExisteFromOldId($activities,    $cours->getAccleunik());
        $cycle      = $this->getExisteFromOldId($cycles,        $cours->getCycleunik());
        $level      = $this->getExisteFromOldId($levels,        $cours->getNicleunik());
        $classroom  = $this->getExisteFromOldId($classrooms,    $cours->getSacleunik());

        if($teacher && $center && $activity && $cycle){
            $start = $this->helper->createTime($cours->getHeuredeb());
            $end = $this->helper->createTime($cours->getHeurefin());
            $duration = $this->helper->createTime($cours->getDuree());

            $slot = $this->createSlotEntity($slot, $cours, $start, $end, $duration, $identifiant,
                $planning, $teacher, $center, $activity, $cycle, $level, $classroom);
            $slot->setOldAdhactId($oldId);

            return $slot;
        }

        return null;
    }
}
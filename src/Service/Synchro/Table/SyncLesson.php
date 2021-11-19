<?php

namespace App\Service\Synchro\Table;

use App\Entity\Cite\CiActivity;
use App\Entity\Cite\CiCenter;
use App\Entity\Cite\CiClassroom;
use App\Entity\Cite\CiCycle;
use App\Entity\Cite\CiLevel;
use App\Entity\Cite\CiSlot;
use App\Entity\Cite\CiTeacher;
use App\Service\Synchro\Sync;
use App\Windev\WindevAdhact;
use App\Windev\WindevCours;

class SyncLesson extends Sync
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
     * @return array
     */
    public function synchronize($letter, WindevAdhact $item, array $items, array $plannings, array $noDuplication, array $slots,
                                array $teachers, array $centers, array $activities, array $cycles, array $levels, array $classrooms): array
    {
        /** @var WindevCours $cours */
        $cours = $this->getExisteFromId($items, $item->getCocleunik());
        $planning = $plannings[1]; //actual

        if($cours && $cours->getJour() != -1){
            /** @var CiSlot $slot */
            $slot = $this->helper->getExisteSlotForCours($item, $cours, $slots);

            if($slot instanceof CiSlot){
                $level = $item->getNicleunik() != 0 ? $item->getNicleunik() : $cours->getNicleunik();

                $start = $this->helper->createTime($cours->getHeuredeb());
                $end = $this->helper->createTime($cours->getHeurefin());
                $duration = $this->helper->createTime($cours->getDuree());
            }
        }

        return ['code' => 0];
    }
}
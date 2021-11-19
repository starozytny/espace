<?php

namespace App\Service\Synchro\Table;

use App\Entity\Cite\CiClasse;
use App\Entity\Cite\CiSlot;
use App\Service\Synchro\Sync;

class SyncClasseSlot extends Sync
{
    /**
     * @param $useless
     * @param CiSlot $slot
     * @param array $uselessWindevItems
     * @param array $uselessPlanning
     * @param CiClasse[] $classes
     * @param array $noDuplication
     * @return array
     */
    public function synchronize($useless, CiSlot $slot, array $uselessWindevItems, array $uselessPlanning, array $noDuplication, array $classes): array
    {
        $teacher = $slot->getTeacher();
        $center = $slot->getCenter();
        $activity = $slot->getActivity();
        $cycle = $slot->getCycle();
        $level = $slot->getLevel();

        if($cycle != null){
            return $this->createClasse($teacher, $center, $activity, $cycle, $level, $classes, $noDuplication);
        }else{
            return ['code' => 0];
        }
    }
}
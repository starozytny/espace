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
     * @return array|void
     */
    public function synchronize($useless, CiSlot $slot, array $uselessWindevItems, array $uselessPlanning, array $noDuplication, array $classes)
    {
        $teacher = $slot->getTeacher();
        $center = $slot->getCenter();
        $activity = $slot->getActivity();
        $cycle = $slot->getCycle();
        $level = $slot->getLevel();

        return $this->createClasse($teacher, $center, $activity, $cycle, $level, $classes, $noDuplication);
    }
}
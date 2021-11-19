<?php

namespace App\Service\Synchro\Table;

use App\Entity\Cite\CiClasse;
use App\Entity\Cite\CiSlot;
use App\Service\Synchro\Sync;

class SyncClasseSlot extends Sync
{
    /**
     * @param CiSlot $slot
     * @param bool $isAncien
     * @param CiClasse[] $classes
     * @param array $noDuplication
     * @return array|void
     */
    public function synchronize(CiSlot $slot, bool $isAncien, array $classes, array $noDuplication)
    {
        return $this->createClasse($slot, $classes, $noDuplication);
    }
}
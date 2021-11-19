<?php

namespace App\Service\Synchro\Table;

use App\Entity\Cite\CiClasse;
use App\Entity\Cite\CiSlot;
use App\Service\Synchro\Sync;
use App\Windev\WindevEmpltps;

class SyncClasse extends Sync
{
    /**
     * @param WindevEmpltps $item
     * @param bool $isAncien
     * @param CiClasse[] $classes
     * @param CiSlot[] $slots
     * @return array
     */
    public function synchronize(WindevEmpltps $item, bool $isAncien, array $classes, array $slots): array
    {
        $noDuplication = [];
        foreach($slots as $slot){
            $result = $this->createClasse($slot, $classes, $noDuplication);

            $result['noDuplication'] = $noDuplication;
        }

        return ['code' => 1];
    }
}
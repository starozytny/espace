<?php

namespace App\Service\Synchro\Table;

use App\Entity\Cite\CiActivity;
use App\Service\Synchro\Sync;
use App\Windev\WindevActivite;
use App\Windev\WindevEmpltps;

class SyncSlots extends Sync
{
    /**
     * @param WindevEmpltps $item
     * @param $slots
     * @return array
     */
    public function synchronize(WindevEmpltps $item, array $slots): array
    {

    }
}
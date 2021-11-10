<?php

namespace App\Service\Synchro\Table;

use App\Entity\Cite\CiCycle;
use App\Service\Synchro\Sync;
use App\Windev\WindevCycle;

class SyncCycle extends Sync
{
    /**
     * @param WindevCycle $item
     * @param bool $isAncien
     * @param $cycles
     * @return array
     */
    public function synchronize(WindevCycle $item, bool $isAncien, $cycles): array
    {
        /** @var CiCycle $cycle */
        if($item->getPlusutilise() == 0){

            $name = mb_strtoupper($item->getDesignation());

            $result = $this->checkExiste("name", new CiCycle(), $cycles, $item, $name);
            $cycle = $result[0];

            $cycle = ($cycle)
                ->setOldId($item->getId())
                ->setName($name)
                ->setDurationTotal($this->helper->createTime($item->getDuree()))
                ->setDuration($this->helper->getDuration($item))
                ->setMax($item->getEffectifmax())
                ->setMode($item->getMode())
                ->setCat($this->helper->getCatCycle(mb_strtoupper($item->getDesignation())))
            ;

            $this->em->persist($cycle);

            return ['code' => 1, 'status' => $result[1], 'data' => $result[2]];
        }else{
            return ['code' => 0];
        }
    }
}
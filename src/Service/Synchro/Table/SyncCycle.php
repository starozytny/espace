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
        $msg = "";
        if($item->getPlusutilise() == 0){

            $name = mb_strtoupper($item->getDesignation());

            if($cycle = $this->getExiste($cycles, $item)){
                if($cycle->getName() == $name){
                    $status = 3;
                }else{
                    $status = 2;
                    $msg = "Changement : " . $cycle->getId();
                }
            }else{
                $status = 1;
                $cycle = new CiCycle();
            }

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

            return ['code' => 1, 'status' => $status, 'data' => $msg];
        }else{
            return ['code' => 0];
        }
    }
}
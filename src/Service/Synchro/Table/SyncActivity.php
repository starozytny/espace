<?php

namespace App\Service\Synchro\Table;

use App\Entity\Cite\CiActivity;
use App\Service\Synchro\Sync;
use App\Windev\WindevActivite;

class SyncActivity extends Sync
{
    /**
     * @param WindevActivite $item
     * @param bool $isAncien
     * @param $activities
     * @return array
     */
    public function synchronize(WindevActivite $item, bool $isAncien, $activities): array
    {
        /** @var CiActivity $activity */
        if($item->getPlusutilise() == 0){

            $name = $item->getDesignation();

            $result = $this->checkExiste("name", new CiActivity(), $activities, $item, $name);

            $activity = $result[0];
            $status = $result[1];
            $msg = $result[2];

            $mode = $item->getMode();
            $departement = intval($item->getDpcleunik());
            if($departement == CiActivity::DEP_COLLECTIVES){
                $mode = 2;
            }

            $activity = ($activity)
                ->setOldId($item->getId())
                ->setName($name)
                ->setDurationTotal($this->helper->createTime($item->getDuree()))
                ->setDuration($this->helper->getDuration($item, $mode))
                ->setMax($item->getEffectifmax())
                ->setType(intval($item->getType()))
                ->setMode($mode)
                ->setDepartement($departement)
            ;

            $this->em->persist($activity);

            return ['code' => 1, 'status' => $status, 'data' => $msg];
        }else{
            return ['code' => 0];
        }
    }
}
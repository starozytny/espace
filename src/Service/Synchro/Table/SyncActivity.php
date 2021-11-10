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
        $msg = "";
        if($item->getPlusutilise() == 0){

            $name = $item->getDesignation();

            if($activity = $this->getExiste($activities, $item)){
                if($activity->getName() == $name){
                    $status = 3;
                }else{
                    $status = 2;
                    $msg = "Changement : " . $activity->getId();
                }
            }else{
                $status = 1;
                $activity = new CiActivity();
            }

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
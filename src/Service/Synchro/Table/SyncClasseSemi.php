<?php

namespace App\Service\Synchro\Table;

use App\Entity\Cite\CiClasse;
use App\Service\Synchro\Sync;

class SyncClasseSemi extends Sync
{
    /**
     * @param $useless
     * @param CiClasse $item
     * @param array $adhacts
     * @param array $uselessPlanning
     * @param array $noDuplicationMain
     * @param CiClasse[] $classesSemi
     * @param CiClasse[] $classes
     * @return array
     */
    public function synchronize($useless, CiClasse $item, array $adhacts, array $uselessPlanning, array $noDuplicationMain, array $classesSemi,
                                array $classes): array
    {
        $find = false; $cycle = null;
        foreach($classesSemi as $clSemi){
            $cycle = $clSemi->getCycle();
            if($clSemi->getTeacher()->getId()       == $item->getTeacher()->getId()
                && $clSemi->getActivity()->getId()  == $item->getActivity()->getId()
                && $clSemi->getCenter()->getId()    == $item->getCenter()->getId()
            ){
                $find = true;
            }
        }

        if(!$find){
            $teacher = $item->getTeacher();
            $center = $item->getCenter();
            $activity = $item->getActivity();
            $level = null;

            $result = $this->createClasse($teacher, $center, $activity, $cycle, $level, $classes, $noDuplicationMain);

            if($result['code'] == 1){
                $noDuplicationMain  = $result['data'];
                $status             = $result['status'];
            }else{
                return ['code' => 0];
            }
        }else{
            $status = 3;
        }

        return ['code' => 1, 'status' => $status, 'data' => $noDuplicationMain];
    }
}
<?php

namespace App\Service\Synchro\Table;

use App\Entity\Cite\CiCenter;
use App\Service\Synchro\Sync;
use App\Windev\WindevCentre;

class SyncCenter extends Sync
{
    /**
     * @param WindevCentre $item
     * @param boolean $isAncien
     * @param array $centres
     * @return array|int[]
     */
    public function synchronize(WindevCentre $item, bool $isAncien, array $centres): array
    {
        /** @var CiCenter $center */
        $msg = "";
        if($item->getPlusutilise() == 0){
            //Normalize data
            $name = mb_strtoupper($item->getNomCentre());

            //Check l'existance du centre
            if($center = $this->getExiste($centres, $item)){
                if($center->getName() == $name){
                    $status = 3;
                }else{
                    $status = 2;
                    $msg = "Changement de nom : " . $center->getName() . ' -> ' . $item->getNomCentre();
                }
            }else{
                $center = new CiCenter();
                $status = 1;
            }

            $center = ($center)
                ->setOldId($item->getId())
                ->setName($name)
            ;

            $this->em->persist($center);

            return ['code' => 1, 'status' => $status, 'data' => $msg];
        }else{
            return ['code' => 0];
        }
    }
}
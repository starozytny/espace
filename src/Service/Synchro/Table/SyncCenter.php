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
        if($item->getPlusutilise() == 0){
            //Normalize data
            $name = mb_strtoupper($item->getNomCentre());

            //Check l'existance du centre
            $result = $this->checkExiste("name", new CiCenter(), $centres, $item, $name);
            $center = $result[0];

            $center = ($center)
                ->setOldId($item->getId())
                ->setName($name)
            ;

            $this->em->persist($center);

            return ['code' => 1, 'status' => $result[1], 'data' => $result[2]];
        }else{
            return ['code' => 0];
        }
    }
}
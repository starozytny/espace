<?php

namespace App\Service\Synchro\Table;

use App\Entity\Cite\CiLevel;
use App\Service\Synchro\Sync;
use App\Windev\WindevNiveau;

class SyncLevel extends Sync
{
    /**
     * @param WindevNiveau $item
     * @param bool $isAncien
     * @param CiLevel[] $levels
     * @return array
     */
    public function synchronize(WindevNiveau $item, bool $isAncien, array $levels): array
    {
        /** @var CiLevel $level */
        if($item->getPlusutilise() == 0){
            $name = mb_strtoupper($item->getLibelleCourt());

            $result = $this->checkExiste("name", new CiLevel(), $levels, $item, $name);
            $level = $result[0];

            $level = ($level)
                ->setOldId($item->getId())
                ->setName($name)
            ;

            $this->em->persist($level);

            return ['code' => 1, 'status' => $result[1], 'data' => $result[2]];
        }else{
            return ['code' => 0];
        }
    }
}
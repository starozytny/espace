<?php

namespace App\Service\Synchro\Table;

use App\Entity\Cite\CiCenter;
use App\Entity\Cite\CiClassroom;
use App\Service\Synchro\Sync;
use App\Windev\WindevSalle;

class SyncClassroom extends Sync
{
    /**
     * @param WindevSalle $item
     * @param bool $isAncien
     * @param CiClassroom[] $classrooms
     * @param CiCenter[] $centers
     * @return array
     */
    public function synchronize(WindevSalle $item, bool $isAncien, array $classrooms, array $centers): array
    {
        /** @var CiClassroom $classroom */
        $name = mb_strtoupper($item->getNom());
        $num = $item->getNumsalle();

        $result = $this->checkExiste("num", new CiClassroom(), $classrooms, $item, $name, $num);
        $classroom = $result[0];
        $status = $result[1];
        $msg = $result[2];

        $center = $this->getExisteFromOldId($centers, $item->getCecleunik());
        if(!$center){
            $status = 0;
            $msg = sprintf('Classroom -> Centre not found : %d' , $item->getId());
        }else{
            $classroom = ($classroom)
                ->setOldId($item->getId())
                ->setNum($num)
                ->setName($name)
                ->setComment($item->getComment())
                ->setCenter($center)
            ;

            $this->em->persist($classroom);
        }

        return ['code' => 1, 'status' => $status, 'data' => $msg];
    }
}
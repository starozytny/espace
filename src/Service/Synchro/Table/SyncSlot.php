<?php

namespace App\Service\Synchro\Table;

use App\Entity\Cite\CiActivity;
use App\Entity\Cite\CiCenter;
use App\Entity\Cite\CiClassroom;
use App\Entity\Cite\CiCycle;
use App\Entity\Cite\CiLevel;
use App\Entity\Cite\CiPlanning;
use App\Entity\Cite\CiSlot;
use App\Entity\Cite\CiTeacher;
use App\Service\Synchro\Sync;
use App\Windev\WindevActivite;
use App\Windev\WindevEmpltps;
use Exception;

class SyncSlot extends Sync
{
    /**
     * @param $letter
     * @param WindevEmpltps $item
     * @param WindevEmpltps[] $items
     * @param array $plannings - 0 = prev | 1 = actual
     * @param CiSlot[] $slots
     * @param CiTeacher[] $teachers
     * @param CiCenter[] $centers
     * @param CiActivity[] $activities
     * @param CiCycle[] $cycles
     * @param CiLevel[] $levels
     * @param CiClassroom[] $classrooms
     * @return array
     * @throws Exception
     */
    public function synchronize($letter, WindevEmpltps $item, array $items, array $plannings, array $slots,
                                array $teachers, array $centers, array $activities, array $cycles, array $levels, array $classrooms): array
    {
        $oldId = $item->getId().$letter;
        $planning = $item->getPrevisionnel() == 1 ? $plannings[0] : $plannings[1];

        switch ($letter){
            case "D":
                $heureDeb = $item->getHeuredeb5();
                $heureFin = $item->getHeurefin5();
                $salleId  = $item->getSacleunik5();
                break;
            case "C":
                $heureDeb = $item->getHeuredeb4();
                $heureFin = $item->getHeurefin4();
                $salleId  = $item->getSacleunik4();
                break;
            case "B":
                $heureDeb = $item->getHeuredeb3();
                $heureFin = $item->getHeurefin3();
                $salleId  = $item->getSacleunik3();
                break;
            case "A":
                $heureDeb = $item->getHeuredeb2();
                $heureFin = $item->getHeurefin2();
                $salleId  = $item->getSacleunik2();
                break;
            default:
                $heureDeb = $item->getHeuredeb();
                $heureFin = $item->getHeurefin();
                $salleId  = $item->getSacleunik();
                break;
        }

        return $this->createSlot($oldId, $item, $items, $heureDeb, $heureFin, $salleId,
            $planning, $slots, $teachers, $centers, $activities, $cycles, $levels, $classrooms);
    }

    private function requiredData(array $data, $correspondance, $name)
    {
        $el = $this->getExisteFromOldId($data, $correspondance);
        if(!$el){
            return ['code' => 1, 'status' => 0, 'data' => sprintf('%s not found : %d' , $name, $correspondance)];
        }

        return $el;
    }

    /**
     * @param $oldId
     * @param WindevEmpltps $item
     * @param WindevEmpltps[] $items
     * @param $heureDeb
     * @param $heureFin
     * @param $salleId
     * @param CiPlanning $planning
     * @param CiSlot[] $slots
     * @param CiTeacher[] $teachers
     * @param CiCenter[] $centers
     * @param CiActivity[] $activities
     * @param CiCycle[] $cycles
     * @param CiLevel[] $levels
     * @param CiClassroom[] $classrooms
     * @return array
     * @throws Exception
     */
    protected function createSlot($oldId, WindevEmpltps $item, array $items, $heureDeb, $heureFin, $salleId, CiPlanning $planning, array $slots,
                                  array $teachers, array $centers, array $activities, array $cycles, array $levels, array $classrooms): array
    {
        if($heureDeb != null){
            $teacher = $this->requiredData($teachers, $item->getPrcleunik(), "Teacher");
            if(is_array($teacher)){
                return $teacher;
            }

            $center = $this->requiredData($centers, $item->getCecleunik(), "Center");
            if(is_array($center)){
                return $center;
            }

            $activity = $this->requiredData($activities, $item->getAccleunik(), "Activity");
            if(is_array($activity)){
                return $activity;
            }

            $cycle = $this->getExisteFromOldId($cycles, $item->getCycleunik());
            $level = $this->getExisteFromOldId($levels, $item->getNicleunik());
            $classroom = $this->getExisteFromOldId($classrooms, $salleId);


            //fill end when multiple cours
            $start = $heureDeb;
            $end = $heureFin;
            if($heureFin == null){
                foreach($items as $dupl){
                    if($dupl->getPrcleunik() == $item->getPrcleunik()
                        && $dupl->getAccleunik() == $item->getAccleunik()
                        && $dupl->getCecleunik() == $item->getCecleunik()
                        && $dupl->getJour() == $item->getJour()
                        && $dupl->getHeuredeb() == $heureDeb
                        && $dupl->getPrevisionnel() == $item->getPrevisionnel()
                        && $dupl->getId() != $item->getId()
                    ){
                        $end = $dupl->getHeurefin();
                    }
                }
            }

            $start = $this->helper->createTime($start);
            $end = $this->helper->createTime($end);
            $duration = $this->helper->createTime($item->getNbheure());

            if($start != null && $end != null){
                $interval = date_diff($start, $end);
                $duration = $this->sanitizeData->createDateFromString($interval->format("%H:%I:%S"), 'UTC');
            }else if($end == null){

                if($duration == null){
                    if($activity){
                        $duration = $activity->getDurationTotal();
                    }
                    if($cycle){
                        $duration = $cycle->getDurationTotal();
                    }
                }

                if($duration != null){
                    $end = $this->sanitizeData->createDateFromString($start->format('H:i:s'), 'UTC');
                    $duration = $this->sanitizeData->createDateFromString($duration->format('H:i:s'), 'UTC');
                    $durationInterval = new \DateInterval('PT' . $duration->getTimestamp() . 'S');
                    $end = $end->add($durationInterval);
                    $end = $this->sanitizeData->createDateFromString($end->format('H:i:s'), 'UTC');
                }
            }

            //check if existe
            $slot = null;
            foreach($slots as $s){
                if($slot == null && $s->getOldId() == $oldId){
                    $slot = $s;
                }
            }

            if($slot == null){
                $slot = new CiSlot();
                $identifiant = uniqid();
                $status = 1;
            }else{
                $identifiant = $slot->getIdentifiant();
                $status = 2;
            }

            $slot = ($slot)
                ->setOldId($oldId)
                ->setPlanning($planning)
                ->setTeacher($teacher)
                ->setActivity($activity)
                ->setCycle($cycle)
                ->setCenter($center)
                ->setLevel($level)
                ->setClassroom($classroom)
                ->setDay($item->getJour())
                ->setStart($start)
                ->setEnd($end)
                ->setDuration($duration)
                ->setIsActual($planning->getIsActual())
                ->setIdentifiant($identifiant)
            ;

            $this->em->persist($slot);

            return ['code' => 1,  'status' => $status, 'data' => $oldId];
        }

        return ['code' => 0];
    }
}
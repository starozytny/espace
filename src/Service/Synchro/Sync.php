<?php

namespace App\Service\Synchro;

use App\Entity\Cite\CiActivity;
use App\Entity\Cite\CiCenter;
use App\Entity\Cite\CiClasse;
use App\Entity\Cite\CiClassroom;
use App\Entity\Cite\CiCycle;
use App\Entity\Cite\CiLevel;
use App\Entity\Cite\CiPlanning;
use App\Entity\Cite\CiSlot;
use App\Entity\Cite\CiTeacher;
use App\Service\DatabaseService;
use App\Service\SanitizeData;
use App\Windev\WindevCours;
use App\Windev\WindevEmpltps;
use Symfony\Component\Console\Style\SymfonyStyle;

class Sync
{
    protected $em;
    protected $emWindev;
    protected $helper;
    protected $sanitizeData;

    public function __construct(DatabaseService $databaseService, Helper $helper, SanitizeData $sanitizeData)
    {
        $this->em = $databaseService->getEm();
        $this->emWindev = $databaseService->getEmWindev();
        $this->helper = $helper;
        $this->sanitizeData = $sanitizeData;
    }

    /**
     * Check if array of items is empty
     *
     * @param SymfonyStyle $io
     * @param array $items
     * @return bool
     */
    public function haveData(SymfonyStyle $io, array $items): bool
    {
        if(count($items) != 0){
            return true;
        }

        $io->comment("Aucune donnée");
        return false;
    }

    /**
     * Display date of array
     *
     * @param SymfonyStyle $io
     * @param array $data
     */
    public function displayDataArray(SymfonyStyle $io, array $data)
    {
        if(count($data) > 0){
            foreach($data as $item){
                $io->text($item);
            }
        }
    }

    /**
     * @param array $data
     * @param $correspondance
     * @return mixed|null
     */
    protected function getExisteFromOldId(array $data, $correspondance)
    {
        foreach($data as $el){
            if($el->getOldId() == $correspondance){
                return $el;
            }
        }

        return null;
    }

    /**
     * @param array $data
     * @param $correspondance
     * @return mixed|null
     */
    protected function getExisteFromId(array $data, $correspondance)
    {
        foreach($data as $el){
            if($el->getId() == $correspondance){
                return $el;
            }
        }

        return null;
    }

    protected function checkExiste($type, $entity, $data, $item, $diff0, $diff1 = null): array
    {
        $msg = "";
        if($elem = $this->getExisteFromOldId($data, $item->getId())){
            $same = false;
            switch ($type){
                case "num":
                    if($elem->getName() == $diff0 && $elem->getNum() == $diff1){
                        $same = true;
                    }
                    break;
                case "fullname":
                    if($elem->getLastname() == $diff0 && $elem->getFirstname() == $diff1){
                        $same = true;
                    }
                    break;
                default:
                    if($elem->getName() == $diff0){
                        $same = true;
                    }
                    break;
            }

            if($same){
                $status = 3;
            }else{
                $status = 2;
                $msg = "Changement : " . $elem->getId();
            }
        }else{
            $status = 1;
            $elem = $entity;
        }

        return [$elem, $status, $msg];
    }

    /**
     * @param CiSlot $slot
     * @param WindevEmpltps|WindevCours $item
     * @param $start
     * @param $end
     * @param $duration
     * @param $identifiant
     * @param CiPlanning $planning
     * @param CiTeacher $teacher
     * @param CiCenter $center
     * @param CiActivity $activity
     * @param CiCycle|null $cycle
     * @param CiLevel|null $level
     * @param CiClassroom|null $classroom
     * @return CiSlot
     */
    public function createSlotEntity(CiSlot $slot, $item, $start, $end, $duration, $identifiant, CiPlanning $planning,
                                     CiTeacher $teacher, CiCenter $center, CiActivity $activity,
                                     ?CiCycle $cycle, ?CiLevel $level, ?CiClassroom $classroom): CiSlot
    {
        return ($slot)
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
    }

    /**
     * @param CiClasse[] $data
     */
    private function getExisteClasse(array $data, $teacherId, $centerId, $activityId, $cycleId, $levelId): ?CiClasse
    {
        foreach($data as $el){
            if($el->getTeacher()->getId() == $teacherId
                && $el->getCenter()->getId() == $centerId
                && $el->getActivity()->getId() == $activityId
                && (($cycleId && $el->getCycle() && $el->getCycle()->getId() == $cycleId) || ($cycleId == null && $el->getCycle() == null))
                && (($levelId && $el->getLevel() && $el->getLevel()->getId() == $levelId) || ($levelId == null && $el->getLevel() == null))
            ){
                return $el;
            }
        }

        return null;
    }

    /**
     * @param $teacher
     * @param $center
     * @param $activity
     * @param $cycle
     * @param $level
     * @param CiClasse[] $classes
     * @param $noDuplication
     * @return array
     */
    protected function createClasse($teacher, $center, $activity, $cycle, $level, array $classes, $noDuplication): array
    {
        $teacherId = $teacher->getId();
        $centerId = $center->getId();
        $activityId = $activity->getId();
        $cycleId = $cycle ? $cycle->getId() : null;
        $levelId = $level ? $level->getId() : null;

        $unicite = [$teacherId, $centerId, $activityId, $cycleId, $levelId];

        if(!in_array($unicite, $noDuplication)){
            array_push($noDuplication, $unicite);

            // Check if classe already existe
            $classe = $this->getExisteClasse($classes, $teacherId, $centerId, $activityId, $cycleId, $levelId);
            if($classe){
                $status = 2;
            }else{
                $classe = new CiClasse();
                $status = 1;
            }

            $nameCycle = ''; $nameLevel = '';

            $isFm           = $activity->getDepartement() == "Formation musicale";

            $nameActivity   = $activity->getName();
            $max            = $activity->getMax();
            $mode           = $activity->getMode();
            $duration       = $activity->getDuration();
            $durationTotal  = $activity->getDurationTotal();

            if($activity->getDepartement() == "Formation musicale" && $max == 0){
                $max = 80;
            }

            if($cycle){
                $nameCycle  = ' - ' . $cycle->getName();
                $mode       = $cycle->getMode();
                if($mode != 0){
                    $max            = $cycle->getMax();
                    $duration       = $cycle->getDuration();
                    $durationTotal  = $cycle->getDurationTotal();
                }

                //Si cycle EVEIL 1EA or 2EA add different duration from Yolaine
                if($isFm){
                    if($cycle->getOldId() === 22 && $level){
                        if($level->getOldId() == 11 || $level->getOldId() == 17){
                            $duration = date_create_from_format('H:i:s', '00:45:00');
                        }else{
                            $duration = date_create_from_format('H:i:s', '01:00:00');
                        }
                        $max = 15;
                        $durationTotal = $duration;

                        //Si cycle 1 1EA or 2EA // same for ADOS // add different duration from Yolaine
                    }elseif($cycle->getOldId() === 23 && $level){
                        if($level->getOldId() == 11  || $level->getOldId() == 17
                            || $level->getOldId() == 73 || $level->getOldId() == 74){
                            $duration = date_create_from_format('H:i:s', '01:00:00');
                        }else{
                            $duration = date_create_from_format('H:i:s', '01:30:00');
                        }
                        $max = 12;
                        $durationTotal = $duration;
                    }
                }
            }

            if($level){
                $nameLevel = ' - ' . $level->getName();
            }

            $name = $nameActivity . $nameCycle . $nameLevel;

            /** @var CiClasse $classe */
            $classe = ($classe)
                ->setName($name)
                ->setActivity($activity)
                ->setCycle($cycle)
                ->setTeacher($teacher)
                ->setDuration($duration)
                ->setDurationTotal($durationTotal)
                ->setMode($mode)
                ->setMax($max)
                ->setLevel($level)
                ->setCenter($center)
                ->setIsFm($isFm)
            ;

            $this->em->persist($classe);

            return ['code' => 1, 'status' => $status, 'data' => $noDuplication];
        }

        return  ['code' => 0];
    }
}
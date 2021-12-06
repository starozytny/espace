<?php

namespace App\Service\Synchro;

use App\Entity\Cite\CiClasse;
use App\Entity\Cite\CiCycle;
use App\Entity\Cite\CiSlot;
use App\Windev\WindevAdhact;
use App\Windev\WindevCours;
use DateTime;

class Helper
{
    const TITLE_MME = 'Mme';
    const TITLE_MR = 'Mr';
    const TITLE_UNKNOWN = 'Mme ou Mr';

    /**
     * @param $item
     * @return array 0 = lastname and 1 = firstname
     */
    public function getFirstnameAndLastname($item): array
    {
        $lastname = trim($item->getNom());
        $firstname = trim($item->getPrenom());
        if($firstname == null){
            $pos = strrpos($lastname, ' ');
            if($pos != false){
                $firstname = substr($lastname, $pos+1, strlen($lastname));
                $lastname = substr($lastname, 0, $pos);
            }else{
                $firstname = null;
            }
        }
        return [
            $lastname,
            ucfirst(mb_strtolower($firstname))
        ];
    }

    public function setCommonData($obj, $item)
    {
        $obj->setOldId($item->getId());

        $obj->setLastname($this->getFirstnameAndLastname($item)[0]);
        $obj->setFirstname($this->getFirstnameAndLastname($item)[1]);

        $obj->setCivility($this->getCivility($item));

        $obj->setEmail($this->setToNullIfEmpty($item->getEmailPers()));
        $obj->setAdr($this->setToNullIfEmpty($item->getAdresse1()));
        $obj->setComplement($this->setToNullIfEmpty($item->getAdresse2()));

        $obj->setZipcode($this->getPostalCode($item->getCdePostal()));

        $obj->setCity($this->setToNullIfEmpty($item->getVille()));

        return $obj;
    }

    public function setToNullIfEmpty($val): ?string
    {
        $val = trim($val);
        return $val == "" ? null : $val;
    }

    public function getCivility($item): string
    {
        $title = self::TITLE_UNKNOWN;
        switch ($item->getTicleunik()){
            case 1:
            case 3:
                $title = self::TITLE_MME;
                break;
            case 2:
            case 5:
                $title = self::TITLE_MR;
                break;
            default:
                break;
        }

        return $title;
    }

    public function getCivilityEleve($item): string
    {
        $title = self::TITLE_UNKNOWN;
        switch ($item->getTicleunik()){
            case 1:
            case 3:
                $title = self::TITLE_MME;
                break;
            case 2:
            case 5:
                $title = self::TITLE_MR;
                break;
            default:
                break;
        }

        if($title == self::TITLE_UNKNOWN){
            if($item->getSexe() == 1){
                $title = self::TITLE_MR;
            }else{
                $title = self::TITLE_MME;
            }
        }

        return $title;
    }

    public function getPostalCode($cp): string
    {
        $cp = trim($cp);
        return strlen($cp) < 5 ? 0 . $cp : $cp;
    }

    public function formattedPhone($value)
    {
        $value = trim($value);
        return $value != null ? str_replace('.' , '', $value) : null;
    }

    public function getDuration($item, $mode=null)
    {
        if(!$mode){
            $mode = $item->getMode();
        }

        if($mode == 0){
            return null;
        }

        if($mode == 2){
            return $this->createTime($item->getDuree());
        }

        $duree = $item->getDuree();
        if($mode == 1){
            $hours = floor($duree / 100);
            $hoursToMin = $hours * 60;

            $remaining = $duree % 100;
            $minutes = $hoursToMin + $remaining;

            if($item->getEffectifmax() == 0){
                return $this->createTime($item->getDuree());
            }
            $perEleve = $minutes / $item->getEffectifmax();

            //to get seconds
            $sup = ceil($perEleve);
            $seconds = ($sup - $perEleve) * 60;

            $duration = $this->addZero(intdiv($perEleve, 60)).':'. $this->addZero(($perEleve % 60)) . ':' . $this->addZero(round($seconds));
            return date_create_from_format('H:i:s', $duration);
        }

        return null;
    }

    public function addZero($val): string
    {
        if($val == 0){
            return "00";
        }
        if($val < 10){
            return "0" . $val;
        }

        return $val;
    }

    public function createTime($time)
    {
        if($time == 0){
            return null;
        }
        if($time < 10){
            $value = '00:0' . $time;
        }else if($time < 100){
            $value = '00:' . $time;
        }else if($time < 1000){
            $a = substr($time, 0,1);
            $b = substr($time, 1,2);
            $b = $b == 0 ? '00' : $b;
            $value = '0' . $a . ':' . $b;
        }else if($time < 10000){
            $a = substr($time, 0,2);
            $b = substr($time, 2,2);
            $b = $b == 0 ? '00' : $b;
            $value = $a . ':' . $b;
        }else{
            return null;
        }

        return date_create_from_format('H:i', $value);
    }

    public function createDate($date): ?DateTime
    {
        $a = substr($date, 0,4);
        $b = substr($date, 4,2);
        $c = substr($date, 6,2);

        $dateFormat = DateTime::createFromFormat('Y-m-d H:i:s',$a.'-'.$b.'-'.$c . ' 00:00:00');
        if($dateFormat == false){
            return null;
        }
        return $dateFormat;
    }

    public function getIsUse($useful): bool
    {
        return $useful == 0;
    }

    public function getArrayIds($values): array
    {
        $array = [];
        if(count($values) != 0){
            foreach($values as $value){
                array_push($array, $value->getId());
            }
        }

        return $array;
    }

    public function getArrayPersonIds($values): array
    {
        $array = [];
        if(count($values) != 0){
            foreach($values as $value){
                array_push($array, $value->getPecleunik());
            }
        }

        return $array;
    }

    public function getArrayOldIds($values): array
    {
        $array = [];
        if(count($values) != 0){
            foreach($values as $value){
                array_push($array, $value->getOldId());
            }
        }

        return $array;
    }

    public function getCatCycle($name): int
    {
        if($name == "CYCLE EVEIL"){
            return CiCycle::CAT_EVEIL;
        }elseif($name == "CYCLE 1" || $name == "CYCLE 1A" || $name == "CYCLE 1B"){
            return CiCycle::CAT_CYCLE_ONE;
        }elseif($name == "CYCLE 2" || $name == "CYCLE 2A" || $name == "CYCLE 2B"){
            return CiCycle::CAT_CYCLE_TWO;
        }elseif($name == "ATELIER"){
            return CiCycle::CAT_ATELIER;
        }elseif($name == "PRE-ATELIER"){
            return CiCycle::CAT_PREATELIER;
        }elseif($name == "PAS DE CYCLE" || $name == "COURS SEMI-COLLECTIFS" || $name == "PRATIQUE AMATEUR"){
            return CiCycle::CAT_NO_CYCLE;
        }else{
            return CiCycle::CAT_UNKNOWN;
        }
    }

    /**
     * @param WindevAdhact|WindevCours $item
     * @param WindevCours $cours
     * @param CiSlot[] $initSlots
     * @return int|mixed
     */
    public function getExisteSlotForCours($item, WindevCours $cours, array $initSlots)
    {
        $level = $item->getNicleunik() != 0 ? $item->getNicleunik() : $cours->getNicleunik();

        $possibilities = [];
        foreach($initSlots as $slot){
            if($slot->getDay() == $cours->getJour()
                && $slot->getTeacher()->getOldId() == $cours->getPrcleunik()
                && $slot->getCenter()->getOldId() == $cours->getCecleunik()
                && $slot->getActivity()->getOldId() == $cours->getAccleunik()
            ){
                array_push($possibilities, $slot);
            }
        }

        if(count($possibilities) == 0){
            return 0;
        }else{

            // if cours have cycle, check if in possibilities, at least on with cycle ==
            $possibilities_cycles = $this->getPossibilitiesCycles($possibilities, $cours);

            // if cours have level, check if in possibilities, at least on with level ==
            $possibilities_levels = $this->getPossibilitiesLevel($possibilities_cycles, $level);

            // check by start if possibilities > 1
            $slots = $this->getPossibilitiesStart($possibilities_levels, $cours);

            if(count($slots) == 1){
                return $slots[0];
            }elseif(count($slots) == 0){
                return 1;
            }else{
                return 0;
            }
        }
    }

    /**
     * @param CiSlot[] $possibilities
     * @param $cours
     * @return array
     */
    public function getPossibilitiesCycles(array $possibilities, $cours): array
    {
        $possibilities_cycles = [];
        foreach($possibilities as $possibility){
            if($cours->getCycleunik() == 0){
                if($possibility->getCycle() == null){
                    array_push($possibilities_cycles, $possibility); //Get all slots with cycle null
                }
            }else{
                if($possibility->getCycle() && $possibility->getCycle()->getOldId() == $cours->getCycleunik()){
                    array_push($possibilities_cycles, $possibility); //Get all slots with cycle ==
                }
            }
        }
        if(count($possibilities_cycles) == 0){
            $possibilities_cycles = $possibilities; // check with level maybe
        }

        return $possibilities_cycles;
    }

    /**
     * @param CiSlot[] $possibilities_cycles
     * @param $level
     * @return array
     */
    public function getPossibilitiesLevel(array $possibilities_cycles, $level): array
    {
        $slots = [];
        foreach($possibilities_cycles as $possibility){
            if($level == 0){
                if($possibility->getLevel() == null){
                    array_push($slots, $possibility);
                }
            }else{
                if($possibility->getLevel() && $possibility->getLevel()->getOldId() == $level){
                    array_push($slots, $possibility);
                }
            }
        }

        return $slots;
    }

    /**
     * @param CiSlot[] $slots
     * @param $cours
     * @return array
     */
    public function getPossibilitiesStart(array $slots, $cours): array
    {
        $checkStart = false;
        if(count($slots) > 1 || count($slots) == 0){
            $checkStart = true;
        }

        if($checkStart){
            $possibilities_start = $slots; // check with start time maybe
            $slots = [];
            foreach($possibilities_start as $possibility){
                $coursStart = $this->createTime($cours->getHeuredeb());
                $slotStart = DateTime::createFromFormat("H:i:s", $possibility->getStartString());

                if($coursStart >= $slotStart){
                    $slots = [$possibility]; // get last
                }
            }
            if(count($slots) == 0 && count($possibilities_start) >= 1){
                $slots = [$possibilities_start[0]];
            }
        }

        return $slots;
    }

    /**
     * @param $type
     * @param $classes
     * @param WindevCours|CiSlot $item
     * @param $level
     * @return array|int
     */
    public function getClasseOptimize($type, $classes, $item, $level)
    {
        $prCleunik = $type == "normal" ? $item->getPrcleunik() : $item->getTeacher()->getOldId();
        $ceCleunik = $type == "normal" ? $item->getCecleunik() : $item->getCenter()->getOldId();
        $acCleunik = $type == "normal" ? $item->getAccleunik() : $item->getActivity()->getOldId();
        $cyCleunik = $type == "normal" ? $item->getCycleunik() : ($item->getCycle() ? $item->getCycle()->getOldId() : 0);

        $possibilities = [];
        /** @var CiClasse $classe */
        /** @var CiClasse $possibility */
        foreach($classes as $classe){
            if($classe->getTeacher()->getOldId() == $prCleunik
                && $classe->getCenter()->getOldId() == $ceCleunik
                && $classe->getActivity()->getOldId() == $acCleunik
            ){
                array_push($possibilities, $classe);
            }
        }

        if(count($possibilities) > 0){
            // check cycle
            $tmp = [];
            foreach($possibilities as $possibility) {
                if ($cyCleunik == 0) {
                    if ($possibility->getCycle() == null) {
                        array_push($tmp, $possibility);
                    }
                } else {
                    if($possibility->getCycle() && $possibility->getCycle()->getOldId() == $cyCleunik){
                        array_push($tmp, $possibility);
                    }
                }
            }

            // si tmp = 0 laisser possibilities a sa valeur sinon update
            if(count($tmp) >= 1){
                $possibilities = $tmp;
            }

            $tmp = [];
            foreach($possibilities as $possibility) {
                if ($level == 0) {
                    if ($possibility->getLevel() == null) {
                        array_push($tmp, $possibility);
                    }
                } else {
                    if($possibility->getLevel() && $possibility->getLevel()->getOldId() == $level){
                        array_push($tmp, $possibility);
                    }
                }
            }

            if(count($tmp) >= 1){
                $possibilities = $tmp;
            }

            if(count($possibilities) >= 1){
                return $possibilities;
            }
        }

        return 0;
    }
}
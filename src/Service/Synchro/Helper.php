<?php

namespace App\Service\Synchro;

use App\Entity\Cite\CiCycle;
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

    public function notUsed($emWindev, $classe, $searchId, $id, $msg): array
    {
        $obj = $emWindev->getRepository($classe)->findOneBy(['id' => $searchId]);

        if($obj){
            if($obj->getPlusutilise() == 1){
                if($msg == "Teacher"){
                    return [0, ""];
                }
                return [0, sprintf('Slot - %s plus utilisé : %d', $msg, $id)];
            }
        }

        return [0, sprintf('Slot - %s not found : %d', $msg, $id)];
    }
}
<?php


namespace App\Service\Cite;


use App\Entity\Cite\CiClasse;
use App\Entity\Cite\CiCycle;
use App\Entity\Cite\CiGroup;
use App\Entity\Cite\CiLevel;
use App\Entity\Cite\CiSlot;

class LevelUp
{
    public function getLevelUpFM($levels, $cycles, $classes, CiClasse $classe, $initSlotsPrev, $fetchAll = false, $levelUpDirect = false): array
    {
        $up = [];
        $multipleChoice = false;
        $EA1 = $this->getLevel($levels, "1EA");
        $EA2 = $this->getLevel($levels, "2EA");
        $EA3 = $this->getLevel($levels, "3EA");
        $EA4 = $this->getLevel($levels, "4EA");
        $EA5 = $this->getLevel($levels, "5EA");

        $EA2_ADO = $this->getLevel($levels, "2EA ADOS");
        $EA3_ADO = $this->getLevel($levels, "3EA ADOS");
        $EA4_ADO = $this->getLevel($levels, "4EA ADOS");

        $AD2_ADOS = $this->getLevel($levels, "AD2 ET ADOS");
        $ADULTES2 = $this->getCycleByName($cycles, "ADULTES 2");

        $CYCLE1 = $this->getCycleByCat($cycles, CiCycle::CAT_CYCLE_ONE);
        $PREATELIER = $this->getCycleByCat($cycles, CiCycle::CAT_PREATELIER);
        $ATELIER = $this->getCycleByCat($cycles, CiCycle::CAT_ATELIER);

        $nameCycle = $classe->getCycle()->getName();
        $nameActivity = $classe->getActivity()->getName();

        if($classe->getLevel()){
            $name = $classe->getLevel()->getName();
            $cat = $classe->getCycle()->getCat();

            if($cat == CiCycle::CAT_EVEIL || $cat == CiCycle::CAT_CYCLE_ONE){
                if($name == "1EA"){
                    $up = $this->getLevelUp($initSlotsPrev, $classes, $classe, $EA2);
                }elseif($name == "2EA"){
                    $up = $this->getLevelUp($initSlotsPrev, $classes, $classe, $EA3);
                }elseif($name == "3EA"){
                    $up = $this->getLevelUp($initSlotsPrev, $classes, $classe, $EA4);
                }elseif($name == "1EA ADOS"){
                    $up = $this->getLevelUp($initSlotsPrev, $classes, $classe, $EA2_ADO);
                }elseif($name == "2EA ADOS"){
                    $up = $this->getLevelUp($initSlotsPrev, $classes, $classe, $EA3_ADO);
                }elseif($name == "3EA ADOS"){
                    $up = $this->getLevelUp($initSlotsPrev, $classes, $classe, $EA4_ADO);
                }

                if($cat == CiCycle::CAT_EVEIL && $name == "4EA"){
                    $up = $this->getNextCycleLevelUp($initSlotsPrev, $classes, $classe, $CYCLE1, $EA1);
                }elseif($cat == CiCycle::CAT_CYCLE_ONE && ($name == "4EA" || $name == "4EA ADOS")){
                    if($levelUpDirect){
                        $up = $this->getNextCycleLevelUp($initSlotsPrev, $classes, $classe, $PREATELIER, $EA1);
                    }else{
                        $up = $this->getLevelUp($initSlotsPrev, $classes, $classe, $EA5);
                        $up2 = $this->getNextCycleLevelUp($initSlotsPrev, $classes, $classe, $PREATELIER, $EA1);

                        if(!$fetchAll){
                            $up = $this->multipleChoice($up, $classe);
                            $up2 = $this->multipleChoice($up2, $classe);
                        }

                        $up = [...$up, ...$up2];
                        $multipleChoice = true;
                    }
                }elseif($cat == CiCycle::CAT_CYCLE_ONE && $name == "5EA"){
                    $up = $this->getNextCycleLevelUp($initSlotsPrev, $classes, $classe, $PREATELIER, $EA1);
                }
            }elseif($cat == CiCycle::CAT_PREATELIER){
                if($name == "1EA"){
                    if($levelUpDirect){
                        $up = $this->getLevelUp($initSlotsPrev, $classes, $classe, $EA2);
                    }else{
                        $up = $this->getLevelUp($initSlotsPrev, $classes, $classe, $EA2);
                        $up2 = $this->getNextCycleLevelUp($initSlotsPrev, $classes, $classe, $ATELIER, $EA3);

                        if(!$fetchAll){
                            $up = $this->multipleChoice($up, $classe);
                            $up2 = $this->multipleChoice($up2, $classe);
                        }

                        $up = [...$up, ...$up2];
                        $multipleChoice = true;
                    }
                }elseif($name == "2EA" || $name == "4EA"){
                    $up = $this->getNextCycleLevelUp($initSlotsPrev, $classes, $classe, $ATELIER, $EA3);
                }elseif($name == "3EA"){
                    $up = $this->getLevelUp($initSlotsPrev, $classes, $classe, $EA3);
                    $up2 = $this->getNextCycleLevelUp($initSlotsPrev, $classes, $classe, $ATELIER, $EA3);

                    if(!$fetchAll){
                        $up = $this->multipleChoice($up, $classe);
                        $up2 = $this->multipleChoice($up2, $classe);
                    }

                    $up = [...$up, ...$up2];
                    $multipleChoice = true;
                }
            }elseif($cat == CiCycle::CAT_ATELIER){
                if($name == "3EA"){
                    $up = $this->getLevelUp($initSlotsPrev, $classes, $classe, $EA4);
                }
            }elseif($cat == CiCycle::CAT_UNKNOWN && $nameActivity = "ADULTES 1"){
                if($name == "AD 1 ET ADOS"){
                    $up = $this->getLevelUp($initSlotsPrev, $classes, $classe, $AD2_ADOS);
                    $up2 = $this->getNextCycle($initSlotsPrev, $classes, $classe, $ADULTES2);

                    if(!$fetchAll){
                        $up = $this->multipleChoice($up, $classe);
                        $up2 = $this->multipleChoice($up2, $classe);
                    }

                    $up = [...$up, ...$up2];
                    $multipleChoice = true;
                }

            }
        }else{

            //adultes 2 on top function
            $ADULTES3 = $this->getCycleByName($cycles, "ADULTES 3");
            $ADULTES4 = $this->getCycleByName($cycles, "ADULTES 4");
            $ORCHESTRE2 = $this->getCycleByName($cycles, "ORCHESTRE CYCLE  2");

            if($nameCycle == "ADULTES 1"){
                $up = $this->getNextCycle($initSlotsPrev, $classes, $classe, $ADULTES2);
            }elseif($nameCycle == "ADULTES 2"){
                $up = $this->getNextCycle($initSlotsPrev, $classes, $classe, $ADULTES3);
            }elseif($nameCycle == "ADULTES 3"){
                $up = $this->getNextCycle($initSlotsPrev, $classes, $classe, $ADULTES4);
            }

            if($nameActivity === "ORCHESTRE CYCLE 1"){
                $up = $this->getNextCycle($initSlotsPrev, $classes, $classe, $ORCHESTRE2);
            }
        }

        if(count($up) > 1 && $multipleChoice == false && !$fetchAll){
            $up = $this->getOnlySameTeacher($up, $classe);
        }

        if(count($up) == 0){
            $status = CiGroup::STAY;
            $up = [$classe];
        }else{
            $status = CiGroup::LEVEL_UP;
        }

        return [$up, $status];
    }

    public function getLevelUpInstru($cycles, $classes, $classe, $initSlotsPrev): array
    {
        $up = [];
        $nameActivity = $classe->getActivity()->getName();
        $cat = $classe->getCycle() ? $classe->getCycle()->getCat() : null;

        $CYCLE1 = $this->getCycleByCat($cycles, CiCycle::CAT_CYCLE_ONE);
        $CYCLE2 = $this->getCycleByCat($cycles, CiCycle::CAT_CYCLE_TWO);
        $CYCLE_SEMICOLLECTIF = $this->getCycleByCatAndName($cycles, CiCycle::CAT_NO_CYCLE, "COURS SEMI-COLLECTIFS");
        $ORCHESTRE2 = $this->getCycleByName($cycles, "ORCHESTRE CYCLE  2");
        $multiple = false;

        if($cat == CiCycle::CAT_EVEIL){
            $up = $this->getNextCycle($initSlotsPrev, $classes, $classe, $CYCLE1);
        }elseif($cat == CiCycle::CAT_CYCLE_ONE){
            $up = $this->getNextCycle($initSlotsPrev, $classes, $classe, $CYCLE2);
            if($classe->getCycle()->getName() == "CYCLE 1A"){
                $up2 = $this->getNextCycle($initSlotsPrev, $classes, $classe, $CYCLE_SEMICOLLECTIF);

                $up = $this->multipleChoice($up, $classe);
                $up2 = $this->multipleChoice($up2, $classe);

                $up = [...$up, ...$up2];
                $multiple = true;
            }
        }elseif($nameActivity === "ORCHESTRE CYCLE 1"){
            $up = $this->getNextCycle($initSlotsPrev, $classes, $classe, $ORCHESTRE2);
        }else{
            $up = [$classe];
        }

        if(count($up) == 0){
            $up = [$classe];
            $status = CiGroup::STAY;
        }elseif(count($up) > 1){
            $status = CiGroup::LEVEL_UP;
            if(!$multiple){
                $up = $this->multipleChoice($up, $classe);
            }else{
                $up = $this->getOnlySameTeacherSEMI($up, $classe);
            }
        }else{
            $status = CiGroup::LEVEL_UP;
        }

        return [$up, $status];
    }

    public function multipleChoice($up, $classe): array
    {
        $tmp = $this->getOnlySameTeacher($up, $classe);

        if(count($tmp) == 1){
            $up = $tmp;
        }

        return $up;
    }

    public function getOnlySameTeacher($up, $classe): array
    {
        foreach($up as $cl){
            if($cl->getTeacher() == $classe->getTeacher()){
                $up = [$cl];
            }
        }

        return $up;
    }
    public function getOnlySameTeacherSEMI($up, $classe): array
    {
        $newUp = [];
        foreach($up as $cl){
            if($cl->getTeacher() == $classe->getTeacher()){
                array_push($newUp, $cl);
            }
        }

        return $newUp;
    }

    public function getNextCycle($initSlotsPrev, $classes, CiClasse $classe, $cycles): array
    {
        $tab = [];
        /** @var CiClasse $cl */
        /** @var CiClasse $classe */
        foreach($classes as $cl){
            if($cl->getActivity() === $classe->getActivity()
                && $cl->getCenter() === $classe->getCenter()
                && $cl->getLevel() == null){
                foreach($cycles as $cycle){
                    if($cl->getCycle() === $cycle){
                        if($this->haveSlot($initSlotsPrev, $cl)){
                            if(!in_array($cl, $tab)){
                                array_push($tab, $cl);
                            }
                        }
                    }
                }
            }
        }

        return $tab;
    }

    public function getNextCycleLevelUp($initSlotsPrev, $classes, $classe, $cycles, $levelUp): array
    {
        $tab = [];
        /** @var CiClasse $cl */
        /** @var CiClasse $classe */
        foreach($classes as $cl){
            if($cl->getActivity() === $classe->getActivity()
                && $cl->getCenter() === $classe->getCenter()
                && $cl->getLevel() == $levelUp){
                foreach($cycles as $cycle){
                    if($cl->getCycle() === $cycle){
                        if($this->haveSlot($initSlotsPrev, $cl)){
                            if(!in_array($cl, $tab)){
                                array_push($tab, $cl);
                            }
                        }
                    }
                }
            }
        }

        if(count($tab) == 0){
            foreach($classes as $cl){
                if($cl->getActivity() === $classe->getActivity()
                    && $cl->getLevel() == $levelUp){
                    foreach($cycles as $cycle){
                        if($cl->getCycle() === $cycle){
                            if($this->haveSlot($initSlotsPrev, $cl)){
                                if(!in_array($cl, $tab)){
                                    array_push($tab, $cl);
                                }
                            }
                        }
                    }
                }
            }
        }

        return $tab;
    }

    public function getLevelUp($initSlotsPrev, $classes, $classe, $levelUp): array
    {
        $tab = [];
        /** @var CiClasse $cl */
        /** @var CiClasse $classe */
        foreach($classes as $cl){
            if($cl->getActivity() === $classe->getActivity()
                && $cl->getCenter() === $classe->getCenter()
                && $cl->getCycle() === $classe->getCycle()
                && $cl->getLevel() == $levelUp
            ){
                if($this->haveSlot($initSlotsPrev, $cl)){
                    array_push($tab, $cl);
                }
            }
        }

        if(count($tab) == 0){
            foreach($classes as $cl){
                if($cl->getActivity() === $classe->getActivity()
                    && $cl->getCycle() === $classe->getCycle()
                    && $cl->getLevel() == $levelUp
                ){
                    if($this->haveSlot($initSlotsPrev, $cl)){
                        array_push($tab, $cl);
                    }
                }
            }
        }

        return $tab;
    }

    public function getLevel($levels, $name): ?CiLevel
    {
        /** @var CiLevel $level */
        foreach ($levels as $level){
            if($level->getName() === $name){
                return $level;
            }
        }
        return null;
    }

    public function getCycleByCat($cycles, $cat): array
    {
        $tab = [];
        /** @var CiCycle $cycle */
        foreach ($cycles as $cycle){
            if($cycle->getCat() === $cat){
                array_push($tab, $cycle);
            }
        }

        return $tab;
    }

    public function getCycleByCatAndName($cycles, $cat, $name): array
    {
        $tab = [];
        /** @var CiCycle $cycle */
        foreach ($cycles as $cycle){
            if($cycle->getCat() === $cat && $cycle->getName() == $name){
                array_push($tab, $cycle);
            }
        }

        return $tab;
    }

    public function getCycleByName($cycles, $name): array
    {
        $tab = [];
        /** @var CiCycle $cycle */
        foreach ($cycles as $cycle){
            if($cycle->getName() === $name){
                array_push($tab, $cycle);
            }
        }

        return $tab;
    }

    public function haveSlot($initSlotsPrev, $classe): bool
    {
        /** @var CiSlot $slot */
        /** @var CiSlot $classe */
        $existe = false;
        if($classe->getActivity()->getDepartement() == "Formation musicale"){
            foreach($initSlotsPrev as $slot){
                if($existe == false && $slot->getActivity()->getId() == $classe->getActivity()->getId()
                    && $slot->getCenter() === $classe->getCenter()
                    && $slot->getTeacher()->getId() == $classe->getTeacher()->getId()
                    && ($slot->getCycle() && $slot->getCycle()->getId() == $classe->getCycle()->getId())
                    && ($slot->getLevel() == null || ($slot->getLevel() && $slot->getLevel()->getId() == $classe->getLevel()->getId()))
                ){
                    $existe = true;
                }
            }
        }else{
            foreach($initSlotsPrev as $slot){
                if($existe == false && $slot->getActivity()->getId() == $classe->getActivity()->getId()
                    && $slot->getTeacher()->getId() == $classe->getTeacher()->getId()
                ){
                    $existe = true;
                }
            }
        }

        return $existe;
    }
}
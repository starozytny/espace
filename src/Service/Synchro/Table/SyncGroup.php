<?php

namespace App\Service\Synchro\Table;

use App\Entity\Cite\CiClasse;
use App\Entity\Cite\CiCycle;
use App\Entity\Cite\CiEleve;
use App\Entity\Cite\CiGroup;
use App\Entity\Cite\CiLevel;
use App\Entity\Cite\CiSlot;
use App\Entity\Prev\PrGroup;
use App\Service\Synchro\Sync;
use App\Windev\WindevAdhact;
use App\Windev\WindevCours;

class SyncGroup extends Sync
{
    /**
     * @param $letter
     * @param WindevAdhact $item
     * @param WindevCours[] $items
     * @param array $plannings - 0 = prev | 1 = actual
     * @param array $noDuplication
     * @param CiSlot[] $slots
     * @param CiClasse[] $classes
     * @param CiGroup[] $groups
     * @param CiEleve[] $eleves
     * @param CiCycle[] $cycles
     * @param CiLevel[] $levels
     * @param CiSlot[] $slotsPrev
     * @param PrGroup[] $prGroups
     * @return array
     */
    public function synchronize($letter, WindevAdhact $item, array $items, array $plannings, array $noDuplication, array $slots,
                                array $classes, array $groups, array $eleves, array $cycles, array $levels, array $slotsPrev, array $prGroups): array
    {
        /** @var WindevCours $cours */
        $cours = $this->getExisteFromId($items, $item->getCocleunik());

        if($cours && $cours->getJour() != -1){
            /** @var CiSlot $slot */
            $slot = $this->helper->getExisteSlotForCours($item, $cours, $slots);

            if($slot instanceof CiSlot){
                $level = $item->getNicleunik() != 0 ? $item->getNicleunik() : $cours->getNicleunik();

                $classe = $this->helper->getClasseOptimize("normal", $classes, $cours, $level);
                if(!is_array($classe)) {
                    return ['code' => 1, 'status' => 0, 'data' => 'Classe introuvable.'];
                }
                $classe = $classe[0];

                $group = $this->isExisteGroup($groups, $item, $classe);
                $status = 2;
                if(!$group instanceof CiGroup){
                    $group = new CiGroup();
                    $status = 1;
                }

                $eleve = $this->getExisteFromOldId($eleves, $item->getAdcleunik());

                if($eleve){

                    $unicite = [$eleve->getId(), $classe->getId()];

                    if(!in_array($unicite, $noDuplication)) {
                        array_push($noDuplication, $unicite);

                        $isFm = $classe->getIsFm();

                        $group = ($group)
                            ->setClasse($classe)
                            ->setEleve($eleve)
                            ->setIsFm($isFm)
                            ->setIsFree($item->getGratuit())
                            ->setIsSuspended($item->getSuspendu())
                            ->setWindevCours($cours->getId())
                        ;

                        $this->em->persist($group);

                        if(!$item->getSuspendu()){
                            if($isFm){
                                if($group->getEleve()->getDispenseFm()){
                                    $result = [[], CiGroup::STAY];
                                }else{
                                    $result = $this->levelUp->getLevelUpFM($levels, $cycles, $classes, $classe, $slotsPrev, false, true);
                                }
                            }else{
                                if($this->levelUp->haveSlot($slotsPrev, $classe)){
                                    $result = [[$classe], CiGroup::STAY];
                                }else{
                                    $result = [[], CiGroup::STAY];
                                }
                            }

                            $up = $result[0];
                            $status = $result[1];

                            if(count($up) > 0){

                                $prGroup = $this->isExistePrGroup($prGroups, $group);
                                if(!$prGroup){
                                    $prGroup = new PrGroup();
                                }

                                $numGroup = uniqid();
                                if(count($up) > 1){ // si quand même > 1  = only same teacher not found

                                    $canLevelUp = true; $prev = null; $first = true;
                                    foreach($up as $u){
                                        if($first) {
                                            $first = false;
                                        } else{
                                            if($u->getName() != $prev){
                                                $canLevelUp = false;
                                            }
                                        }
                                        $prev = $u->getName();
                                    }

                                    if($canLevelUp){ // can level up if all up = same cycle and level
                                        foreach($up as $u){

                                            $prGroup = ($prGroup)
                                                ->setClasse($u)
                                                ->setEleve($group->getEleve())
                                                ->setIsFm($isFm)
                                                ->setIsMultiple(true)
                                                ->setNumGroup($numGroup)
                                                ->setClasseFrom($classe)
                                                ->setIsFree($group->getIsFree())
                                                ->setIsOri(true)
                                                ->setGroupe($group)
                                                ->setWindevCours($cours->getId())
                                            ;

                                            $group->setStatus($status);
                                            $group->setClasseTo($u);
                                            $group->setRenewAnswer(CiGroup::ANSWER_WAITING_PRIORITY);

                                            $this->em->persist($prGroup);
                                        }
                                    }

                                }else if(count($up) == 1){
                                    $prGroup = ($prGroup)
                                        ->setClasse($up[0])
                                        ->setEleve($group->getEleve())
                                        ->setIsFm($isFm)
                                        ->setNumGroup($numGroup)
                                        ->setClasseFrom($classe)
                                        ->setIsFree($group->getIsFree())
                                        ->setIsOri(true)
                                        ->setGroupe($group)
                                        ->setWindevCours($cours->getId())
                                    ;

                                    $group->setStatus($status);
                                    $group->setClasseTo($up[0]);

                                    $this->em->persist($prGroup);
                                }
                            }
                        }

                        return ['code' => 1, 'status' => $status, 'data' => $noDuplication];
                    }
                }
            }
        }

        return ['code' => 0];
    }

    /**
     * @param CiGroup[] $groups
     * @param WindevAdhact $item
     * @param CiClasse $classe
     * @return CiGroup|null
     */
    private function isExisteGroup(array $groups, WindevAdhact $item, CiClasse $classe): ?CiGroup
    {
        foreach($groups as $grp){
            if($grp->getEleve()->getOldId() == $item->getAdcleunik()
                && $grp->getClasse()->getId() == $classe->getId()
            ){
                return $grp;
            }
        }

        return null;
    }

    /**
     * @param PrGroup[] $prGroups
     * @param CiGroup $group
     * @return PrGroup|null
     */
    private function isExistePrGroup(array $prGroups, CiGroup $group): ?PrGroup
    {
        foreach($prGroups as $prGroup){
            if($prGroup->getGroupe()->getId() == $group->getId()){
                return $prGroup;
            }
        }

        return null;
    }
}
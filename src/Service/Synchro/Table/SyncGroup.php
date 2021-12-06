<?php

namespace App\Service\Synchro\Table;

use App\Entity\Cite\CiClasse;
use App\Entity\Cite\CiEleve;
use App\Entity\Cite\CiGroup;
use App\Entity\Cite\CiSlot;
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
     * @return array
     */
    public function synchronize($letter, WindevAdhact $item, array $items, array $plannings, array $noDuplication, array $slots,
                                array $classes, array $groups, array $eleves): array
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

                        $group = ($group)
                            ->setClasse($classe)
                            ->setEleve($eleve)
                            ->setIsFm($classe->getIsFm())
                            ->setIsFree($item->getGratuit())
                            ->setIsSuspended($item->getSuspendu())
                        ;

                        $this->em->persist($group);

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
}
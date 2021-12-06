<?php

namespace App\Service\Synchro\Table;

use App\Entity\Cite\CiAssignation;
use App\Entity\Cite\CiClasse;
use App\Entity\Cite\CiEleve;
use App\Entity\Cite\CiLesson;
use App\Entity\Cite\CiSlot;
use App\Service\Synchro\Sync;
use App\Windev\WindevAdhact;
use App\Windev\WindevCours;

class SyncLesson extends Sync
{
    /**
     * @param $letter
     * @param WindevAdhact|WindevCours $item
     * @param WindevCours[] $items
     * @param array $plannings - 0 = prev | 1 = actual
     * @param array $noDuplication
     * @param CiSlot[] $slots
     * @param CiClasse[] $classes
     * @param CiLesson[] $lessons
     * @param CiEleve[] $eleves
     * @param CiAssignation[] $assigns
     * @return array
     */
    public function synchronize($letter, $item, array $items, array $plannings, array $noDuplication, array $slots,
                                array $classes, array $lessons, array $eleves, array $assigns): array
    {
        /** @var WindevCours $cours */
        $cours = $this->getExisteFromId($items, $item instanceof WindevCours ? $item->getId() : $item->getCocleunik());
        $planning = $plannings[1]; //actual

        if($cours && $cours->getJour() != -1){
            /** @var CiSlot $slot */
            $slot = $this->helper->getExisteSlotForCours($item, $cours, $slots);

            if($slot instanceof CiSlot){
                $level = $item->getNicleunik() != 0 ? $item->getNicleunik() : $cours->getNicleunik();

                $start = $this->helper->createTime($cours->getHeuredeb());
                $end = $this->helper->createTime($cours->getHeurefin());
                $duration = $this->helper->createTime($cours->getDuree());

                $unicite = [$level, $cours->getHeuredeb(), $slot->getId()];

                if(!in_array($unicite, $noDuplication)) {
                    array_push($noDuplication, $unicite);

                    $classe = $this->helper->getClasseOptimize("normal", $classes, $cours, $level);
                    if(!is_array($classe)) {
                        return ['code' => 1, 'status' => 0, 'data' => 'Classe introuvable.'];
                    }
                    $classe = $classe[0];

                    $lesson = $this->isExisteLesson($lessons, $slot, $start);
                    $status = 2;
                    if(!$lesson instanceof CiLesson){
                        $lesson = new CiLesson();
                        $status = 1;
                    }

                    $lesson = ($lesson)
                        ->setStart($start)
                        ->setEnd($end)
                        ->setDuration($duration)
                        ->setSlot($slot)
                        ->setClasse($classe)
                        ->setTeacher($slot->getTeacher())
                        ->setIsActual($planning->getIsActual())
                        ->setSlotIdentifiant($slot->getIdentifiant())
                        ->setIsFm($classe->getIsFm())
                    ;

                    $this->em->persist($lesson);

                    if($item instanceof WindevAdhact){
                        $eleve = $this->getExisteFromOldId($eleves, $item->getAdcleunik());
                        if($eleve && !$eleve->getIsAncien()){

                            $assign = $this->isExisteAssign($assigns, $eleve, $classe, $lesson);
                            if(!$assign instanceof CiAssignation){
                                $assign = new CiAssignation();
                            }

                            $assign = ($assign)
                                ->setLesson($lesson)
                                ->setEleve($eleve)
                                ->setClasse($classe)
                                ->setIsSuspended($item->getSuspendu())
                                ->setIsFm($classe->getIsFm())
                                ->setIsActual($planning->getIsActual())
                            ;

                            $this->em->persist($assign);
                        }

                    }

                    return ['code' => 1, 'status' => $status, 'data' => $noDuplication];
                }
            }
        }

        return ['code' => 0];
    }

    /**
     * @param CiLesson[] $lessons
     */
    private function isExisteLesson(array $lessons, CiSlot $slot, $start): ?CiLesson
    {
        foreach($lessons as $lesson){
            if($lesson->getSlotIdentifiant() == $slot->getIdentifiant()
                && $lesson->getStartString() === $start->format("H:i:s")
            ){
                return $lesson;
            }
        }

        return null;
    }

    /**
     * @param CiAssignation [] $assigns
     * @param CiEleve $eleve
     * @param CiClasse $classe
     * @param CiLesson $lesson
     * @return CiAssignation |null
     */
    private function isExisteAssign(array $assigns, CiEleve $eleve, CiClasse $classe, CiLesson $lesson): ?CiAssignation
    {
        foreach($assigns as $assign){
            if($assign->getEleve()->getId() == $eleve->getId()
                && $assign->getClasse()->getId() == $classe->getId()
                && $assign->getLesson()->getId() == $lesson->getId()
            ){
                return $assign;
            }
        }

        return null;
    }
}
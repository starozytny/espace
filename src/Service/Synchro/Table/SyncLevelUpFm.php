<?php

namespace App\Service\Synchro\Table;

use App\Entity\Cite\CiAssignation;
use App\Entity\Cite\CiClasse;
use App\Entity\Cite\CiGroup;
use App\Entity\Cite\CiLesson;
use App\Entity\Prev\PrGroup;
use App\Service\Synchro\Sync;
use App\Windev\WindevCours;
use DateTime;

class SyncLevelUpFm extends Sync
{
    /**
     * @param $letter
     * @param PrGroup $item
     * @param WindevCours[] $items
     * @param array $plannings - 0 = prev | 1 = actual
     * @param array $noDuplication
     * @param CiAssignation[] $assignations
     * @param CiLesson[] $lessons
     * @return array
     */
    public function synchronize($letter, PrGroup $item, array $items, array $plannings, array $noDuplication, array $assignations, array $lessons): array
    {
        /** @var WindevCours $cours */
        $cours = $this->getExisteFromId($items, $item->getWindevCours());
        $classe = $item->getClasse();
        $lessons = $this->classeService->getLessons($classe, $lessons);

        if(trim($classe->getName()) !== "FORMATION MUSICALE - ATELIER - 4EA"){
            if(count($lessons) > 0){
                $possibilities = [];
                foreach($lessons as $lesson){
                    if(!$lesson->getIsActual() && $lesson->getSlot()->getCenter()->getOldId() == $cours->getCecleunik()){
                        array_push($possibilities, $lesson);
                    }
                }

                $lesson = null;
                if(count($possibilities) == 1){
                    $lesson = $possibilities[0];
                }else if(count($possibilities) > 1){

                    //check if in same day
                    $possibilities_days = [];
                    foreach($possibilities as $possibility){
                        if($possibility->getSlot()->getDay() == $cours->getJour()){
                            array_push($possibilities_days, $possibility);
                        }
                    }
                    if(count($possibilities_days) == 0){
                        $possibilities_days = $possibilities;
                    }

                    foreach($possibilities_days as $possibility){
                        $coursStart = DateTime::createFromFormat("H:i:s", $this->helper->createTime($cours->getHeuredeb())->format('H:i:s'));
                        $lessonStart = DateTime::createFromFormat("H:i:s", $possibility->getStartString());

                        if($coursStart >= $lessonStart){
                            $lesson = $possibility; // get last
                        }
                    }
                    if($lesson == null && count($possibilities_days) >= 1){
                        $lesson = $possibilities_days[0];
                    }
                }

                if($lesson){
                    $assignation = $this->isExisteAssign($assignations, $item, $lesson, $classe);
                    if(!$assignation){
                        $assign = (new CiAssignation())
                            ->setLesson($lesson)
                            ->setEleve($item->getEleve())
                            ->setClasse($classe)
                            ->setIsSuspended(false)
                            ->setIsFm($classe->getIsFm())
                            ->setIsActual($lesson->getIsActual())
                        ;

                        $item->setAssignation($assign);
                        $item->getGroupe()->setRenewAnswer(CiGroup::ANSWER_ASK);

                        $this->em->persist($assign);

                        return ['code' => 1, 'status' => 1, 'data' => $noDuplication];
                    }
                }
            }
        }

        return ['code' => 0];
    }

    /**
     * @param array $assignations
     * @param PrGroup $item
     * @param CiLesson $lesson
     * @param CiClasse $classe
     * @return mixed|null
     */
    public function isExisteAssign(array $assignations, PrGroup $item, CiLesson $lesson, CiClasse $classe)
    {
        foreach($assignations as $assignation){
            if($assignation->getEleve()->getId() == $item->getEleve()->getId()
                && $assignation->getLesson()->getId() == $lesson->getId()
                && $assignation->getClasse()->getId() == $classe->getId()
            ){
                return $assignation;
            }
        }

        return null;
    }
}
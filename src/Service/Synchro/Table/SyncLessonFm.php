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
use DateInterval;
use Exception;

class SyncLessonFm extends Sync
{
    /**
     * @param $letter
     * @param CiSlot $item
     * @param CiSlot[] $items
     * @param array $plannings - 0 = prev | 1 = actual
     * @param array $noDuplication
     * @param CiSlot[] $slots
     * @param CiClasse[] $classes
     * @param CiLesson[] $lessons
     * @return array
     * @throws Exception
     */
    public function synchronize($letter, CiSlot $item, array $items, array $plannings, array $noDuplication, array $slots,
                                array $classes, array $lessons): array
    {
        $planning = $item->getPlanning();
        $activity = $item->getActivity();

        if(!$planning->getIsActual() && mb_strtoupper($activity->getName()) == "FORMATION MUSICALE"){
            $classesSelected = $this->helper->getClasseOptimize("fm", $classes, $item, $item->getLevel() ? $item->getLevel()->getOldId() : 0);
            if(!is_array($classesSelected)) {
                return ['code' => 1, 'status' => 0, 'data' => 'Classe introuvable.'];
            }

            if(count($classesSelected) <= 4){
                $duration = $item->getDuration();
                $end = $item->getEnd();

                if($end == null){
                    $start = $this->sanitizeData->createDateFromString($item->getStartString(), 'UTC');
                    $duration = $this->sanitizeData->createDateFromString($classesSelected[0]->getDurationString(), 'UTC');
                    $durationInterval = new DateInterval('PT' . $duration->getTimestamp() . 'S');
                    $end = $start->add($durationInterval);
                    $end = $this->sanitizeData->createDateFromString($end->format('H:i:s'), 'UTC');
                }

                $start = $item->getStart();

                $lesson = $this->isExisteLesson($lessons, $item, $start);
                $status = 2;
                if(!$lesson instanceof CiLesson){
                    $lesson = new CiLesson();
                    $status = 1;
                }

                $lesson = ($lesson)
                    ->setStart($start)
                    ->setEnd($end)
                    ->setDuration($duration)
                    ->setSlot($item)
                    ->setClasse($classesSelected[0])
                    ->setClasseSecond($classesSelected[1] ?? null)
                    ->setClasseThird($classesSelected[2] ?? null)
                    ->setClasseFour($classesSelected[3] ?? null)
                    ->setTeacher($item->getTeacher())
                    ->setIsActual($planning->getIsActual())
                    ->setIsMixte(count($classesSelected) > 1)
                    ->setSlotIdentifiant($item->getIdentifiant())
                    ->setIsFm($classesSelected[0] ? $classesSelected[0]->getIsFm() : false)
                ;

                $this->em->persist($lesson);

                return ['code' => 1, 'status' => $status, 'data' => $noDuplication];
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
}
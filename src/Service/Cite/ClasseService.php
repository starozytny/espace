<?php

namespace App\Service\Cite;

use App\Entity\Cite\CiClasse;
use App\Entity\Cite\CiLesson;

class ClasseService
{
    /**
     * @param CiClasse $classe
     * @param CiLesson[] $lessons
     * @return array
     */
    public function getLessons(CiClasse $classe, array $lessons): array
    {
        $classeId = $classe->getId();

        $data = [];
        foreach($lessons as $lesson){
            if($lesson->getClasse()->getId() == $classeId
                || ($lesson->getClasseSecond() && $lesson->getClasseSecond()->getId() == $classeId)
                || ($lesson->getClasseThird() && $lesson->getClasseThird()->getId() == $classeId)
                || ($lesson->getClasseFour() && $lesson->getClasseFour()->getId() == $classeId)
            ){
                array_push($data, $lesson);
            }
        }

        return $data;
    }
}
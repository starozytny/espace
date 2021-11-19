<?php

namespace App\Service\Synchro\Table;

use App\Entity\Cite\CiClasse;
use App\Entity\Cite\CiLevel;
use App\Service\Synchro\Sync;
use App\Windev\WindevAdhact;
use App\Windev\WindevCours;

class SyncClasse extends Sync
{
    /**
     * @param $useless
     * @param WindevCours $item
     * @param WindevAdhact[] $adhacts
     * @param array $uselessPlanning
     * @param array $noDuplicationMain
     * @param CiClasse[] $classes
     * @param array $teachers
     * @param array $centers
     * @param array $activities
     * @param array $cycles
     * @param CiLevel[] $levels
     * @return array
     */
    public function synchronize($useless, WindevCours $item, array $adhacts, array $uselessPlanning, array $noDuplicationMain, array $classes,
                                array $teachers, array $centers, array $activities, array $cycles, array $levels): array
    {
        // Get adhact link to a cours
        $links = [];
        foreach($adhacts as $adhact) {
            if ($adhact->getCocleunik() == $item->getId()) {
                array_push($links, $adhact);
            }
        }

        //extract levels of adhact or cours
        $levelsPossibility = []; $noDuplication = [];
        /** @var WindevAdhact $link */
        foreach($links as $link){
            $searchLevel = $link->getNicleunik() != 0 ? $link->getNicleunik() : $item->getNicleunik();

            if($searchLevel != 0){
                foreach($levels as $level) {
                    if ($level->getOldId() == $searchLevel) {
                        if(!in_array( $level->getId(), $noDuplication)){
                            array_push($noDuplication, $level->getId());
                            array_push($levelsPossibility, $level);
                        }
                    }
                }
            }
        }

        $teacher    = $this->getExisteFromOldId($teachers,   $item->getPrcleunik());
        $center     = $this->getExisteFromOldId($centers,    $item->getCecleunik());
        $activity   = $this->getExisteFromOldId($activities, $item->getAccleunik());
        $cycle      = $this->getExisteFromOldId($cycles,     $item->getCycleunik());

        $total = -1; $created = 0; $updated = 0;
        foreach($levelsPossibility as $level){
            $result = $this->createClasse($teacher, $center, $activity, $cycle, $level, $classes, $noDuplicationMain);

            if($result['code'] == 1) {
                $total++;

                switch ($result['status']) {
                    case 2:
                        $updated++;
                        array_push($noDuplicationMain, $result['data']);
                        break;
                    case 1:
                        $created++;
                        array_push($noDuplicationMain, $result['data']);
                        break;
                    default:
                        break;
                }
            }
        }

        return ['code' => 1, 'status' => 4, 'total' => $total, 'created' => $created, 'updated' => $updated];
    }
}
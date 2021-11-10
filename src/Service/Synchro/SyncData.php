<?php

namespace App\Service\Synchro;

use App\Service\DatabaseService;

class SyncData
{
    private $em;
    private $sync;

    private $syncCenter;
    private $syncTeacher;

    public function __construct(DatabaseService $databaseService, Sync $sync,
                                SyncCenter $syncCenter, SyncTeacher $syncTeacher)
    {
        $this->em = $databaseService->getEm();
        $this->sync = $sync;

        $this->syncCenter = $syncCenter;
        $this->syncTeacher = $syncTeacher;
    }

    public function synchroData($io, $items, $name)
    {
        if($this->sync->haveData($io, $items)){
            $total = count($items);

            switch ($name){
                case "professeurs":
                    $syncFunction = $this->syncTeacher;
                    break;
                case "centres":
                    $syncFunction = $this->syncCenter;
                    break;
                default:
                    return;
            }

            $result = $syncFunction->synchronize($items);

            $total          = $result[0];
            $errors         = $result[1];
            $created        = $result[2];
            $notUsed        = $result[3];
            $updatedArray   = $result[4];
            $updated        = $result[5];
            $noUpdated      = $result[6];


            $this->em->flush();

            $this->sync->displayDataArray($io, $errors);
            $io->comment(sprintf("%d %s non utilisés.", $notUsed, $name));
            $io->comment(sprintf("%d %s mis à jour.", $updated, $name));
            $this->sync->displayDataArray($io, $updatedArray);
            $io->comment(sprintf("%d %s inchangés.", $noUpdated, $name));
            $io->comment(sprintf("%d / %d %s créés.", $created, $total, $name));
        }

    }
}
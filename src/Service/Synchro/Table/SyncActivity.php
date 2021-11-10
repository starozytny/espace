<?php

namespace App\Service\Synchro\Table;

use App\Entity\Cite\CiActivity;
use App\Service\Synchro\Sync;
use App\Windev\WindevActivite;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\OutputInterface;

class SyncActivity extends Sync
{
    /**
     * @param OutputInterface $output
     * @param WindevActivite[] $items
     * @return array
     */
    public function synchronize(OutputInterface $output, array $items): array
    {
//        $errors = []; $updatedArray = [];
//        $total = 0; $created = 0; $notUsed = 0; $updated = 0; $noUpdated = 0;
//
//        $activities = $this->em->getRepository(CiActivity::class)->findAll();
//
//        $progressBar = new ProgressBar($output, count($items));
//        $progressBar->start();
//
//        /** @var CiActivity $activity */
//        foreach($items as $item){
//
//            $progressBar->advance();
//
//            $isNew = false;
//
//            $isUse = $item->getPlusutilise() == 0;
//            $name = $item->getDesignation();
//
//            if($activity = $this->getExiste($activities, $item)){
//                if($activity->getName() == $name && $activity->getIsUse() == $isUse){
//                    $noChanged++;
//                }else{
//                    array_push($updatedArray, "Changement : " . $activity->getId());
//                    $updated++;
//                }
//            }else{
//                $isNew = true;
//                $activity = new CiActivity();
//                if($isUse) $count++;
//            }
//
//            $mode = $item->getMode();
//            $departement = intval($item->getDpcleunik());
//            if($departement == CiActivity::DEP_COLLECTIVES){
//                $mode = 2;
//            }
//
//            if($isUse){
//                $activity->setOldId($item->getId());
//                $activity->setName($name);
//                $activity->setDurationTotal($this->helper->createTime($item->getDuree()));
//                $activity->setDuration($this->helper->getDuration($item, $mode));
//                $activity->setMax($item->getEffectifmax());
//                $activity->setType(intval($item->getType()));
//                $activity->setMode($mode);
//                $activity->setIsUse(true);
//                $activity->setDepartement($departement);
//
//                $this->em->persist($activity);
//            }else{
//                if(!$isNew){
//                    $activity->setIsUse(false);
//                }
//                $notUsed++;
//            }
//        }
//
//        $progressBar->finish();
//
//        return [$total, $errors, $created, $notUsed, $updatedArray, $updated, $noUpdated];
    }
}
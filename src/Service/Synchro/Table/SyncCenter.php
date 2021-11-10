<?php

namespace App\Service\Synchro\Table;

use App\Entity\Cite\CiCenter;
use App\Service\Synchro\Sync;
use App\Windev\WindevCentre;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\OutputInterface;

class SyncCenter extends Sync
{
    /**
     * @param OutputInterface $output
     * @param WindevCentre[] $items
     * @return array
     */
    public function synchronize(OutputInterface $output, array $items): array
    {
        $errors = []; $updatedArray = [];
        $total = 0; $created = 0; $notUsed = 0; $updated = 0; $noUpdated = 0;

        $centres = $this->em->getRepository(CiCenter::class)->findAll();

        $progressBar = new ProgressBar($output, count($items));
        $progressBar->start();

        /** @var CiCenter $center */
        foreach($items as $item){

            $progressBar->advance();

            if($item->getPlusutilise() == 0){
                //Normalize data
                $name = mb_strtoupper($item->getNomCentre());

                //Check l'existance du centre
                if($center = $this->getExiste($centres, $item)){
                    if($center->getName() == $name){
                        $noUpdated++;
                    }else{
                        $updated++;
                        array_push($updatedArray, "Changement de nom : " . $center->getName() . ' -> ' . $item->getNomCentre());
                    }
                }else{
                    $created++;
                    $center = new CiCenter();
                }

                $center = ($center)
                    ->setOldId($item->getId())
                    ->setName($name)
                ;

                $this->em->persist($center);

                $total++;
            }else{
                $notUsed++;
            }
        }

        $progressBar->finish();

        return [$total, $errors, $created, $notUsed, $updatedArray, $updated, $noUpdated];
    }
}
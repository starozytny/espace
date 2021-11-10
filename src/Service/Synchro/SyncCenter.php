<?php

namespace App\Service\Synchro;

use App\Entity\Cite\CiCenter;
use App\Windev\WindevCentre;
use Symfony\Component\Console\Style\SymfonyStyle;

class SyncCenter extends Sync
{
    /**
     * @param SymfonyStyle $io
     * @param WindevCentre[] $items
     */
    public function synchronize(SymfonyStyle $io, array $items)
    {
        if($this->haveData($io, $items)){
            $total = count($items);

            $errors = []; $updatedArray = [];
            $created = 0; $notUsed = 0; $updated = 0; $noUpdated = 0;

            $centres = $this->em->getRepository(CiCenter::class)->findAll();

            /** @var CiCenter $center */
            foreach($items as $item){

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

                //Set data
                if($item->getPlusutilise() == 0){
                    $center = ($center)
                        ->setOldId($item->getId())
                        ->setName($name)
                    ;

                    $this->em->persist($center);
                }else{
                    $notUsed++;
                }
            }

            $this->em->flush();

            $this->displayDataArray($io, $errors);
            $io->comment(sprintf("%d centres non utilisés.", $notUsed));
            $io->comment(sprintf("%d centres mis à jour.", $updated));
            $this->displayDataArray($io, $updatedArray);
            $io->comment(sprintf("%d centres inchangés.", $noUpdated));
            $io->comment(sprintf("%d / %d centres créés.", $created, $total));
        }
    }
}
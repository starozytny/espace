<?php

namespace App\Service\Synchro\Table;

use App\Entity\Cite\CiResponsable;
use App\Service\Synchro\Sync;
use App\Windev\WindevPersonne;

class SyncResponsable extends Sync
{
    /**
     * @param WindevPersonne[] $items
     * @return array
     */
    public function synchronize(array $items): array
    {
        $errors = []; $updatedArray = [];
        $total = 0; $created = 0; $notUsed = 0; $updated = 0; $noUpdated = 0;

        $responsables = $this->em->getRepository(CiResponsable::class)->findAll();

        /** @var CiResponsable $responsable */
        foreach($items as $item){

            $lastname = $this->helper->getFirstnameAndLastname($item)[0];
            $firstname = $this->helper->getFirstnameAndLastname($item)[1];

            if($responsable = $this->getExiste($responsables, $item)){
                if($responsable->getLastname() == $lastname && $responsable->getFirstname() == $firstname){
                    $noUpdated++;
                }else{
                    $updated++;
                    array_push($updatedArray, "Changement : " . $responsable->getId());
                }

            }else{
                $responsable = new CiResponsable();
                $created++;
            }

            $responsable = $this->helper->setCommonData($responsable, $item);

            $responsable->setPhone1($item->getTelephone1());
            $responsable->setInfoPhone1($item->getInfoTel1());
            $responsable->setPhone2($item->getTelephone2());
            $responsable->setInfoPhone2($item->getInfoTel2());

            $phone3 = $item->getTelephone3();
            $infoPhone3 = $item->getInfoTel3();
            if($item->getTelTrav() != ""){
                $phone3 = $item->getTelTrav();
                $infoPhone3 = $item->getInfoTelTra();
            }
            $responsable->setPhone3($phone3);
            $responsable->setInfoPhone3($infoPhone3);

            $this->em->persist($responsable);

            $total++;
        }

        return [$total, $errors, $created, $notUsed, $updatedArray, $updated, $noUpdated];
    }
}
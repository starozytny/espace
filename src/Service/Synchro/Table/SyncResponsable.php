<?php

namespace App\Service\Synchro\Table;

use App\Entity\Cite\CiResponsable;
use App\Service\Synchro\Sync;
use App\Windev\WindevPersonne;

class SyncResponsable extends Sync
{
    /**
     * @param WindevPersonne $item
     * @param bool $isAncien
     * @param $responsables
     * @return array
     */
    public function synchronize(WindevPersonne $item, bool $isAncien, $responsables): array
    {
        /** @var CiResponsable $responsable */
        $msg = "";
        $lastname = $this->helper->getFirstnameAndLastname($item)[0];
        $firstname = $this->helper->getFirstnameAndLastname($item)[1];

        if($responsable = $this->getExiste($responsables, $item)){
            if($responsable->getLastname() == $lastname && $responsable->getFirstname() == $firstname){
                $status = 3;
            }else{
                $status = 2;
                $msg = "Changement : " . $responsable->getId();
            }
        }else{
            $status = 1;
            $responsable = new CiResponsable();
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

        return ['code' => 1, 'status' => $status, 'data' => $msg];
    }
}
<?php

namespace App\Service\Synchro\Table;

use App\Entity\Cite\CiEleve;
use App\Service\Synchro\Sync;

class SyncEleve extends Sync
{
    /**
     * @param $item
     * @param bool $isAncien
     * @param $eleves
     * @param $responsables
     * @return array
     */
    public function synchronize($item, bool $isAncien, $eleves, $responsables): array
    {
        /** @var CiEleve $eleve */
        $msg = "";
        $responsable = $this->getExisteFromOldId($responsables, $item->getPecleunik());
        if(!$responsable){
            $status = 0;
            $msg = sprintf('Eleve -> Responsable not found : %d' , $item->getId());
        }else{
            $numAdh = $isAncien ? $item->getNumFiche() : $item->getId();
            $lastname = $this->helper->getFirstnameAndLastname($item)[0];
            $firstname = $this->helper->getFirstnameAndLastname($item)[1];

            if($isAncien == false){
                $eleve = $this->getExiste($eleves, $item);
            }else{ // check with numAdh
                $eleve = $this->getExisteFromOldId($eleves, $numAdh);
            }

            $haveFm = false;
            if($eleve){
                if($eleve->getLastname() == $lastname && $eleve->getFirstname() == $firstname){
                    $status = 3;
                }else{
                    $status = 2;
                    $msg = "Changement : " . $eleve->getId();
                }

                $haveFm = $eleve->getHaveFm();
            }else{
                $status = 1;
                $eleve = new CiEleve();
            }

            $eleve = ($eleve)
                ->setOldId($numAdh)
                ->setNumAdh($numAdh)
                ->setIsAncien($isAncien)
                ->setLastname($lastname)
                ->setFirstname($firstname)
                ->setCivility($this->helper->getCivilityEleve($item))
                ->setEmail($this->helper->setToNullIfEmpty($item->getEmailAdh()))
                ->setBirthday($this->helper->createDate($item->getNaissance()))
                ->setRegisteredAt($this->helper->createDate($item->getInscription()))
                ->setAdr($this->helper->setToNullIfEmpty($item->getAdresseAdh()))
                ->setPhoneMobile($item->getTelephone1())
                ->setPhoneDomicile($item->getTelephone2())
                ->setResponsable($responsable)
                ->setRenew($item->getRenouvellement())
                ->setHaveFm($haveFm)
                ->setDispenseFm($item->getDispsolfege())
            ;

            $this->em->persist($eleve);
        }

        return ['code' => 1, 'status' => $status, 'data' => $msg];
    }
}
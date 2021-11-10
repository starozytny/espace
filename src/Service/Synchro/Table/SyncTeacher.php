<?php

namespace App\Service\Synchro\Table;

use App\Entity\Cite\CiTeacher;
use App\Service\Synchro\Sync;
use App\Windev\WindevProfs;

class SyncTeacher extends Sync
{
    /**
     * @param WindevProfs $item
     * @param bool $isAncien
     * @param $teachers
     * @param $persons
     * @return array
     */
    public function synchronize(WindevProfs $item, bool $isAncien, $teachers, $persons): array
    {
        /** @var CiTeacher $teacher */
        $msg = "";
        if($item->getPlusutilise() == 0){
            //Récupération des données du prof
            $person = $this->getPersonne($persons, $item);

            $lastname = $this->helper->getFirstnameAndLastname($person)[0];
            $firstname = $this->helper->getFirstnameAndLastname($person)[1];

            if($teacher = $this->getExiste($teachers, $item)){
                if($teacher->getLastname() == $lastname && $teacher->getFirstname() == $firstname){
                    $status = 3;
                }else{
                    $status = 2;
                    $msg = "Changement ci id : " . $teacher->getId() . ' - wi id : ' . $item->getId();
                }
            }else{
                $status = 1;
                $teacher = new CiTeacher();
            }

            //Création que si le professeur est lié à une ligne windev personne
            if($person == null){
                $status = 0;
                $msg = sprintf('Windev Prof : %d n\'a pas d\'infos pour être rempli.' , $item->getId());
            }else{
                $teacher = ($teacher)
                    ->setPhoneMobile($person->getTelephone1())
                    ->setPhoneDomicile($person->getTelephone2())
                    ->setQuotaInstru($this->helper->createTime($item->getNbheure()))
                    ->setQuotaFm($this->helper->createTime($item->getNbheure2()))
                    ->setComment($item->getComment())
                ;

                $teacher = $this->helper->setCommonData($teacher, $person);

                $teacher->setOldId($item->getId()); //replace personId set in oldId by setCommonData
                $teacher->setOldPersonId($person->getId());

                $this->em->persist($teacher);
            }

            return ['code' => 1, 'status' => $status, 'data' => $msg];
        }else{
            return ['code' => 0];
        }
    }

    /**
     * Récupération des données d'une personne
     *
     * @param $data
     * @param $windevItem
     * @return null
     */
    private function getPersonne($data, $windevItem)
    {
        foreach($data as $p){
            if($p->getId() == $windevItem->getPecleunik()){
                return $p;
            }
        }

        return null;
    }
}
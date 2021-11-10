<?php

namespace App\Service\Synchro\Table;

use App\Entity\Cite\CiTeacher;
use App\Service\Synchro\Sync;
use App\Windev\WindevPersonne;
use App\Windev\WindevProfs;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\OutputInterface;

class SyncTeacher extends Sync
{
    /**
     * @param OutputInterface $output
     * @param $items WindevProfs[]
     * @return array
     */
    public function synchronize(OutputInterface $output, array $items): array
    {
        $errors = []; $updatedArray = [];
        $total = 0; $created = 0; $notUsed = 0; $updated = 0; $noUpdated = 0;

        //Récupération des données des professeurs contenues dans la table windev personne
        $persons = $this->getPersonnes($items);
        $teachers = $this->em->getRepository(CiTeacher::class)->findAll();

        $progressBar = new ProgressBar($output, count($items));
        $progressBar->start();

        /** @var CiTeacher $teacher */
        foreach($items as $item){

            $progressBar->advance();

            if($item->getPlusutilise() == 0){
                //Récupération des données du prof
                $person = $this->getPersonne($persons, $item);

                $lastname = $this->helper->getFirstnameAndLastname($person)[0];
                $firstname = $this->helper->getFirstnameAndLastname($person)[1];

                if($teacher = $this->getExiste($teachers, $item)){
                    if($teacher->getLastname() == $lastname && $teacher->getFirstname() == $firstname){
                        $noUpdated++;
                    }else{
                        $updated++;
                        array_push($updatedArray, "Changement ci id : " . $teacher->getId() . ' - wi id : ' . $item->getId());
                    }

                }else{
                    $created++;
                    $teacher = new CiTeacher();
                }

                //Création que si le professeur est lié à une ligne windev personne
                if($person == null){
                    array_push($errors, sprintf('Windev Prof : %d n\'a pas d\'infos pour être rempli.' , $item->getId()));
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

                    $total++;
                }
            }else{
                $notUsed++;
            }
        }

        $progressBar->finish();

        return [$total, $errors, $created, $notUsed, $updatedArray, $updated, $noUpdated];
    }

    /**
     * Récupération des données des professeurs contenues dans la table windev personne
     *
     * @param $items
     * @return array
     */
    private function getPersonnes($items): array
    {
        $ids = [];
        foreach($items as $item){
            array_push($ids, $item->getPecleunik());
        }
        return $this->emWindev->getRepository(WindevPersonne::class)->findBy(['id' => $ids]);
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
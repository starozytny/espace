<?php

namespace App\Service\Synchro\Table;

use App\Entity\Cite\CiEleve;
use App\Entity\Cite\CiResponsable;
use App\Service\Synchro\Sync;
use App\Windev\WindevAdherent;
use App\Windev\WindevAncien;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\OutputInterface;

class SyncEleve extends Sync
{
    /**
     * @param OutputInterface $output
     * @param $items WindevAdherent|WindevAncien[]
     * @param bool $isAncien
     * @return array
     */
    public function synchronize(OutputInterface $output, array $items, bool $isAncien = false): array
    {
        $errors = []; $updatedArray = [];
        $total = 0; $created = 0; $notUsed = 0; $updated = 0; $noUpdated = 0;

        $eleves = $this->em->getRepository(CiEleve::class)->findAll();
        $responsables = $this->em->getRepository(CiResponsable::class)->findAll();

        $progressBar = new ProgressBar($output, count($items));
        $progressBar->start();

        /** @var CiEleve $eleve */
        foreach($items as $item){

            $progressBar->advance();

            $responsable = $this->getExisteFromOldId($responsables, $item->getPecleunik());
            if(!$responsable){
                array_push($errors, sprintf('Eleve -> Responsable not found : %d' , $item->getId()));
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
                        $noUpdated++;
                    }else{
                        $updated++;
                        array_push($updatedArray, "Changement : " . $eleve->getId());
                    }

                    $haveFm = $eleve->getHaveFm();
                }else{
                    $eleve = new CiEleve();
                    $created++;
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

                $total++;
            }
        }

        $progressBar->finish();

        return [$total, $errors, $created, $notUsed, $updatedArray, $updated, $noUpdated];
    }

    /**
     * @param $data
     * @param $correspondance
     * @return null
     */
    private function getExisteFromOldId($data, $correspondance)
    {
        foreach($data as $el){
            if($el->getOldId() == $correspondance){
                return $el;
            }
        }

        return null;
    }
}
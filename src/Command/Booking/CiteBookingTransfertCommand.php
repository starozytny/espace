<?php

namespace App\Command\Booking;

use App\Entity\Booking\BoEleve;
use App\Entity\Booking\BoResponsable;
use App\Entity\Cite\CiEleve;
use App\Entity\Cite\CiResponsable;
use App\Service\DatabaseService;
use App\Service\Export;
use App\Service\SanitizeData;
use App\Windev\WindevAdherent;
use App\Windev\WindevAncien;
use App\Windev\WindevPersonne;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CiteBookingTransfertCommand extends Command
{
    protected static $defaultName = 'cite:booking:transfert';
    private $em;
    private $emWindev;
    private $export;
    private $sanitizeData;

    private $lastPersonnesID;
    private $lastAdherentsID;

    private $ciResponsables;
    private $wiResponsables;
    private $ciEleves;
    private $wiEleves;
    private $wiElevesAnciens;

    private $currentPersonneID = null;

    const FOLDER = "booking/";

    public function __construct(DatabaseService $databaseService, Export $export, SanitizeData $sanitizeData)
    {
        parent::__construct();

        $this->em = $databaseService->getEm();
        $this->emWindev = $databaseService->getEmWindev();
        $this->export = $export;
        $this->sanitizeData = $sanitizeData;

        $this->ciResponsables   = $this->em->getRepository(CiResponsable::class)->findAll();
        $this->wiResponsables   = $this->emWindev->getRepository(WindevPersonne::class)->findAll();
        $this->ciEleves         = $this->em->getRepository(CiEleve::class)->findAll();
        $this->wiEleves         = $this->emWindev->getRepository(WindevAdherent::class)->findAll();
        $this->wiElevesAnciens  = $this->emWindev->getRepository(WindevAncien::class)->findAll();
    }

    protected function configure()
    {
        $this
            ->setDescription('Create CSV files ADHERENT and PERSONNE for cursus')
            ->addArgument('type', InputArgument::REQUIRED, '0 Nouveau - 1 ancien')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $em = $this->em;
        $emWindev = $this->emWindev;

        $dayType = $input->getArgument('type');

        $responsables = $em->getRepository(BoResponsable::class)->findBy(['status' => BoResponsable::STATUS_END]);

        $nbPersonnes = 0;
        $nbAdherents = 0;

        //get last id of table PERSONNE and ADHERENTS
        $windevPersonnes = $emWindev->getRepository(WindevPersonne::class)->findAll();
        $windevAdherents = $emWindev->getRepository(WindevAdherent::class)->findAll();

        $this->lastPersonnesID = $windevPersonnes[count($windevPersonnes) - 1]->getId() + 10000;
        $this->lastAdherentsID = $windevAdherents[count($windevAdherents) - 1]->getId() + 10000;

        $dataPersonnes = []; $dataAdherents = [];
        foreach($responsables as $responsable){
            if($responsable->getDay()->getType() == $dayType){
                $eleves = $responsable->getEleves();

                array_push($dataPersonnes, $this->createPersonne($responsable));
                $nbPersonnes++;

                foreach($eleves as $eleve){
                    array_push($dataAdherents, $this->createAdherent($eleve));
                    $nbAdherents++;
                }
            }
        }

        $io->text($nbAdherents . ' élèves inscrits.');
        $io->text($nbPersonnes . ' responsables inscrits.');

        $header = array(array('ADCLEUNIK', 'PECLEUNIK', 'NUM_FICHE', 'NUM_FAMILLE', 'NOM', 'PRENOM', 'TICLEUNIK', 'NAISSANCE', 'SEXE', 'CARTEADHERENT',
            'TYCLEUNIK', 'INSCRIPTION', 'ADHESION', 'RENOUVELLEMENT', 'SORTIE', 'NOCOMPTA', 'CECLEUNIK', 'COMMENT', 'NOTARIF', 'DATECREATION',
            'DATEMAJ', 'NORAPPEL', 'LIENPROFESSEUR', 'DISPSOLFEGE', 'MTRAPPEL', 'TELEPHONE1', 'INFO_TEL1', 'TELEPHONE2', 'INFO_TEL2', 'EMAIL_ADH',
            'ADRESSE_ADH', 'FACTURER_ADR_PERSO', 'MRCLEUNIK', 'NB_ECH', 'BQ_DOM1', 'BQ_DOM2', 'BQ_CPTE', 'BQ_CDEBQ', 'BQ_CDEGU', 'BQ_CLERIB',
            'TIRET', 'MoyenEnvoiFacture', 'MoyenEnvoiFacture_2', 'MoyenEnvoiFacture_3', 'MoyenEnvoiAbsence', 'MoyenEnvoiAbsence_2', 'MoyenEnvoiAbsence_3',
            'MoyenEnvoiRelance', 'MoyenEnvoiRelance_2', 'MoyenEnvoiRelance_3', 'AssoPartenaire', 'CNR_CRR', 'MajorationHM', 'IS_ANCIEN', 'IS_EXISTE'));
        $json = $this->export->createFile('csv', 'ADHERENT', 'ADHERENT.csv' , $header, $dataAdherents, 55, self::FOLDER);

        $header = [[
            'PECLEUNIK', 'TYCLEUNIK', 'NOM', 'PRENOM', 'TICLEUNIK', 'ADRESSE1', 'ADRESSE2', 'CDE_POSTAL', 'VILLE', 'TELEPHONE1', 'INFO_TEL1', 'TELEPHONE2', 'INFO_TEL2',
            'NOCOMPTA', 'SFCLEUNIK', 'NAISSANCE', 'CACLEUNIK', 'PROFESSION', 'ADRESSE_TRAV', 'TEL_TRAV', 'COMMENT', 'MRCLEUNIK', 'NB_ECH', 'BQ_DOM1', 'BQ_DOM2',
            'BQ_CPTE', 'BQ_CDEBQ', 'BQ_CDEGU', 'BQ_CLERIB', 'TIRET', 'INFO_TEL_TRA', 'TELEPHONE3', 'INFO_TEL3', 'TELEPHONE4', 'INFO_TEL4', 'TELEPHONE5',
            'INFO_TEL5', 'EMAIL_PERS', 'IS_EXISTE']];
        $json = $this->export->createFile('csv', 'PERSONNE', 'PERSONNE.csv' , $header, $dataPersonnes, 39, self::FOLDER);

        return Command::SUCCESS;
    }

    private function createPersonne(BoResponsable $boResponsable): array
    {
        $id = null;
        $tycleunik = 0;
        $lastname   = $this->sanitizeData->setToUpper($boResponsable->getLastname());
        $firstname  = $this->sanitizeData->setCapitalize($boResponsable->getFirstname());
        $civility   = $this->sanitizeData->getCivility($boResponsable->getCivility());
        $adr        = str_replace(';', ',', $boResponsable->getAdr());
        $complement = $boResponsable->getComplement();
        $zipcode    = $boResponsable->getCp();
        $city       = $this->sanitizeData->setToUpper($boResponsable->getCity());
        $phone1     = $boResponsable->getPhone1();
        $phone2     = $boResponsable->getPhone2();
        $phone3     = $boResponsable->getPhone3();
        $infoPhone1 = $boResponsable->getInfoPhone1();
        $infoPhone2 = $boResponsable->getInfoPhone2();
        $infoPhone3 = $boResponsable->getInfoPhone3();

        $numCompta  = null;
        $sfcleunik  = 0;
        $naissance  = null;
        $cacleunik  = 0;
        $profession = null;
        $adrTrav    = null;
        $telTrav    = null;
        $comment    = null;

        $mrcleunik  = 0;
        $nbEch      = 0;
        $bqDom1     = null;
        $bqDom2     = null;
        $bqCpte     = null;
        $bqCdebq    = null;
        $bqCdegu    = null;
        $bqClerib   = null;
        $tiret      = null;

        $infoTelTrav = null;
        $phone4      = null;
        $infoPhone4  = null;
        $phone5      = null;
        $infoPhone5  = null;
        $email       = $boResponsable->getEmail2() ?: $boResponsable->getEmail();

        $phone1     = $this->setPhoneWithDot($phone1);
        $phone2     = $this->setPhoneWithDot($phone2);
        $phone3     = $this->setPhoneWithDot($phone3);

        $find = false;
        if($boResponsable->getReferral()){

            $ciResponsable = $this->getCiResponsable($boResponsable);

            if($ciResponsable){
                $find = true;

                $wiResponsable = $this->getWiResponsable($ciResponsable);

                $id         = $ciResponsable->getOldId();

                $phone1     = $phone1 ?: ($ciResponsable->getPhone1() ?: ($wiResponsable ? $wiResponsable->getTelephone1() : null));
                $phone2     = $phone2 ?: ($ciResponsable->getPhone2() ?: ($wiResponsable ? $wiResponsable->getTelephone2() : null));
                $phone3     = $phone3 ?: ($ciResponsable->getPhone3() ?: ($wiResponsable ? $wiResponsable->getTelephone3() : null));
                $infoPhone1 = $infoPhone1 ?: ($ciResponsable->getInfoPhone1() ?: ($wiResponsable ? $wiResponsable->getInfoTel1() : null));
                $infoPhone2 = $infoPhone2 ?: ($ciResponsable->getInfoPhone2() ?: ($wiResponsable ? $wiResponsable->getInfoTel2() : null));
                $infoPhone3 = $infoPhone3 ?: ($ciResponsable->getInfoPhone3() ?: ($wiResponsable ? $wiResponsable->getInfoTel3() : null));

                $phone1     = $this->setPhoneWithDot($phone1);
                $phone2     = $this->setPhoneWithDot($phone2);
                $phone3     = $this->setPhoneWithDot($phone3);

                if($wiResponsable){
                    $tycleunik  = $wiResponsable->getTycleunik();
                    $numCompta  = $wiResponsable->getNocompta();
                    $sfcleunik  = $wiResponsable->getSfcleunik();
                    $naissance  = $wiResponsable->getNaissance();
                    $cacleunik  = $wiResponsable->getCacleunik();
                    $profession = $wiResponsable->getProfession();
                    $adrTrav    = $wiResponsable->getAdresseTrav();
                    $telTrav    = $wiResponsable->getTelTrav();
                    $comment    = $wiResponsable->getComment();

                    $mrcleunik  = $wiResponsable->getMrcleunik();
                    $nbEch      = $wiResponsable->getNbEch();
                    $bqDom1     = $wiResponsable->getBqDom1();
                    $bqDom2     = $wiResponsable->getBqDom2();
                    $bqCpte     = $wiResponsable->getBqCpte();
                    $bqCdebq    = $wiResponsable->getBqCdebq();
                    $bqCdegu    = $wiResponsable->getBqCdegu();
                    $bqClerib   = $wiResponsable->getBqClerib();
                    $tiret      = $wiResponsable->getTiret();

                    $infoTelTrav = $wiResponsable->getInfoTelTra();
                    $phone4      = $wiResponsable->getTelephone4();
                    $infoPhone4  = $wiResponsable->getInfoTel4();
                    $phone5      = $wiResponsable->getTelephone5();
                    $infoPhone5  = $wiResponsable->getInfoTel5();
                }
            }
        }

        if(!$find){
            $this->lastPersonnesID++;
            $id = $this->lastPersonnesID;
            $find = 0;
        }

        $this->currentPersonneID = $id;

        return [
            $id, $tycleunik, $lastname, $firstname, $civility, $adr, $complement, $zipcode, $city, $phone1, $infoPhone1 , $phone2, $infoPhone2,
            $numCompta, $sfcleunik, $naissance, $cacleunik, $profession, $adrTrav, $telTrav, $comment,
            $mrcleunik, $nbEch, $bqDom1, $bqDom2, $bqCpte, $bqCdebq, $bqCdegu, $bqClerib, $tiret,
            $infoTelTrav, $phone3, $infoPhone3, $phone4, $infoPhone4, $phone5, $infoPhone5, $email, $find
        ];
    }

    public function createAdherent(BoEleve $boEleve): array
    {
        date_default_timezone_set('Europe/Paris');

        $id          = null;
        $numFiche    = null;
        $numFamille  = null;
        $lastname    = $this->sanitizeData->setToUpper($boEleve->getLastname());
        $firstname   = $this->sanitizeData->setCapitalize($boEleve->getFirstname());
        $civility    = $this->sanitizeData->getCivility($boEleve->getCivility());
        $naissance   = intval(date_format($boEleve->getBirthday(), 'Ymd'));
        $sexe        = ($boEleve->getCivility() == "Mme") ? 2 : 1;
        $carteAdh    = 2;

        $tycleunik   = 0;
        $inscription = intval(date_format(new \DateTime(), 'Ymd'));
        $adhesion    = null;
        $renew       = substr(intval(date_format(new \DateTime(), 'Y')), 2, 2) . "A";
        $sortie      = null;
        $noCompta    = null;
        $cecleunik   = 0;
        $comment     = null;
        $noTarif     = 0;

        $createdAt   = intval(date_format(new \DateTime(), 'Ymd'));
        $updatedAt   = $createdAt;
        $noRappel    = 0;
        $lienProf    = 0;
        $dispsolfege = 0;
        $mtRappel    = 0;

        $phone1     = $boEleve->getPhoneMobile();
        $phone2     = null;
        $infoPhone1 = null;
        $infoPhone2 = null;
        $email      = $boEleve->getEmail();
        $adr        = null;
        $facturer   = 0;
        $mrcleunik  = 0;
        $nbEch      = 0;
        $bqDom1     = null;
        $bqDom2     = null;
        $bqCpte     = null;
        $bqCdebq    = null;
        $bqCdegu    = null;
        $bqClerib   = null;
        $tiret      = null;
        $mF         = 0;
        $mF2        = 0;
        $mF3        = 0;
        $mA         = 0;
        $mA2        = 0;
        $mA3        = 0;
        $mRe        = 0;
        $mRe2       = 0;
        $mRe3       = 0;
        $asso       = 0;
        $cnr        = 0;
        $major      = 0;
        $isAncien   = 0;

        $phone1     = $this->setPhoneWithDot($phone1);
        $phone2     = $this->setPhoneWithDot($phone2);

        $find = false;
        if($boEleve->getReferral()){

            $ciEleve = $this->getCiEleve($boEleve);

            if($ciEleve){
                $find = true;

                $wiEleve = $this->getWiEleve($ciEleve);

                $id         = $ciEleve->getOldId();
                $phone1     = $phone1 ?: ($ciEleve->getPhoneMobile() ?: ($wiEleve ? $wiEleve->getTelephone1() : null));
                $phone2     = $phone2 ?: ($ciEleve->getPhoneDomicile() ?: ($wiEleve ? $wiEleve->getTelephone2() : null));
                $infoPhone1 = $infoPhone1 ?: ($wiEleve ? $wiEleve->getInfoTel1() : null);
                $infoPhone2 = $infoPhone2 ?: ($wiEleve ? $wiEleve->getInfoTel2() : null);

                $sexe       = ($ciEleve->getCivility() == "Mme") ? 2 : 1;

                $isAncien   = $ciEleve->getIsAncien() ? 1 : 0;

                $phone1     = $this->setPhoneWithDot($phone1);
                $phone2     = $this->setPhoneWithDot($phone2);

                if($wiEleve){
                    $numFiche    = $wiEleve->getNumFiche();
                    $numFamille  = $wiEleve->getNumFamille();
                    $carteAdh    = $wiEleve->getCarteadherent();
                    $tycleunik   = $wiEleve->getTycleunik();
                    $inscription = $wiEleve->getInscription();
                    $adhesion    = $wiEleve->getAdhesion();
                    $noCompta    = $wiEleve->getNocompta();
                    $cecleunik   = $wiEleve->getCecleunik();
                    $comment     = $wiEleve->getComment();
                    $noTarif     = $wiEleve->getNotarif();
                    $createdAt   = $wiEleve->getDatecreation();
                    $noRappel    = $wiEleve->getNorappel();
                    $lienProf    = $wiEleve->getLienprofesseur();
                    $dispsolfege = $wiEleve->getDispsolfege();
                    $mtRappel    = $wiEleve->getMtrappel();
                    $email       = $wiEleve->getEmailAdh();
                    $adr         = $wiEleve->getAdresseAdh();
                    $asso        = $wiEleve->getAssopartenaire();
                    $cnr         = $wiEleve->getCnrCrr();

                    if(!$ciEleve->getIsAncien()){
                        $facturer    = $wiEleve->getFacturerAdrPerso();
                        $mrcleunik   = $wiEleve->getMrcleunik();
                        $nbEch       = $wiEleve->getNbEch();
                        $bqDom1      = $wiEleve->getBqDom1();
                        $bqDom2      = $wiEleve->getBqDom2();
                        $bqCpte      = $wiEleve->getBqCpte();
                        $bqCdebq     = $wiEleve->getBqCdebq();
                        $bqCdegu     = $wiEleve->getBqCdegu();
                        $bqClerib    = $wiEleve->getBqClerib();
                        $tiret       = $wiEleve->getTiret();
                        $mF          = $wiEleve->getMoyenenvoifacture();
                        $mF2         = $wiEleve->getMoyenenvoifacture2();
                        $mF3         = $wiEleve->getMoyenenvoifacture3();
                        $mA          = $wiEleve->getMoyenenvoiabsence();
                        $mA2         = $wiEleve->getMoyenenvoiabsence2();
                        $mA3         = $wiEleve->getMoyenenvoiabsence3();
                        $mRe         = $wiEleve->getMoyenenvoirelance();
                        $mRe2        = $wiEleve->getMoyenenvoirelance2();
                        $mRe3        = $wiEleve->getMoyenenvoirelance3();
                        $major       = $wiEleve->getMajorationhm();
                    }
                }
            }
        }

        if(!$find){
            $this->lastAdherentsID++;
            $id = $this->lastAdherentsID;
            $find = 0;
        }

        return [
            $id, $this->currentPersonneID, $numFiche, $numFamille, $lastname, $firstname, $civility, $naissance, $sexe, $carteAdh,
            $tycleunik, $inscription, $adhesion, $renew, $sortie, $noCompta, $cecleunik, $comment, $noTarif, $createdAt,
            $updatedAt, $noRappel, $lienProf, $dispsolfege, $mtRappel, $phone1, $infoPhone1, $phone2, $infoPhone2, $email,
            $adr, $facturer, $mrcleunik, $nbEch, $bqDom1, $bqDom2, $bqCpte, $bqCdebq, $bqCdegu, $bqClerib, $tiret,
            $mF, $mF2, $mF3, $mA, $mA2, $mA3, $mRe, $mRe2, $mRe3,
            $asso, $cnr, $major, $isAncien, $find
        ];
    }

    private function setPhoneWithDot($arg1): ?string
    {
        if(strlen($arg1) == 9){
            if(substr($arg1,0,1) != "0"){
                $arg1 = 0 . $arg1;
            }
        }elseif(strlen($arg1) == 11){
            if(substr($arg1,0,2) == "33"){
                $arg1 = 0 . substr($arg1,2, strlen($arg1));
            }
        }

        if(strlen($arg1) != 10){
            return $arg1;
        }
        $a = substr($arg1,0,2);
        $b = substr($arg1,2,2);
        $c = substr($arg1,4,2);
        $d = substr($arg1,6,2);
        $e = substr($arg1,8,2);

        return $a . '.' . $b . '.' . $c . '.' . $d . '.' . $e;
    }

    private function getCiResponsable(BoResponsable $boResponsable)
    {
        foreach($this->ciResponsables as $ciResponsable){
            if($ciResponsable->getOldId() == $boResponsable->getReferral()){
                return $ciResponsable;
            }
        }

        return null;
    }


    private function getWiResponsable(CiResponsable $ciResponsable)
    {
        foreach($this->wiResponsables as $wiResponsable){
            if($wiResponsable->getId() == $ciResponsable->getOldId()){
                return $wiResponsable;
            }
        }

        return null;
    }

    private function getCiEleve(BoEleve $boEleve)
    {
        foreach($this->ciEleves as $ciEleve){
            if($ciEleve->getId() == $boEleve->getReferral()){
                return $ciEleve;
            }
        }

        return null;
    }


    private function getWiEleve(CiEleve $ciEleve)
    {
        if($ciEleve->getIsAncien()){
            foreach($this->wiElevesAnciens as $wiEleve){
                if($wiEleve->getNumFiche() == $ciEleve->getOldId()){
                    return $wiEleve;
                }
            }
        }else{
            foreach($this->wiEleves as $wiEleve){
                if($wiEleve->getId() == $ciEleve->getOldId()){
                    return $wiEleve;
                }
            }
        }

        return null;
    }
}

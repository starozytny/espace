<?php

namespace App\Service\Synchro;

use App\Service\DatabaseService;
use App\Service\SanitizeData;
use Symfony\Component\Console\Style\SymfonyStyle;

class Sync
{
    protected $em;
    protected $emWindev;
    protected $helper;
    protected $sanitizeData;

    public function __construct(DatabaseService $databaseService, Helper $helper, SanitizeData $sanitizeData)
    {
        $this->em = $databaseService->getEm();
        $this->emWindev = $databaseService->getEmWindev();
        $this->helper = $helper;
        $this->sanitizeData = $sanitizeData;
    }

    /**
     * Check if array of items is empty
     *
     * @param SymfonyStyle $io
     * @param array $items
     * @return bool
     */
    public function haveData(SymfonyStyle $io, array $items): bool
    {
        if(count($items) != 0){
            return true;
        }

        $io->comment("Aucune donnée");
        return false;
    }

    /**
     * Display date of array
     *
     * @param SymfonyStyle $io
     * @param array $data
     */
    public function displayDataArray(SymfonyStyle $io, array $data)
    {
        if(count($data) > 0){
            foreach($data as $item){
                $io->text($item);
            }
        }
    }

    /**
     * @param array $data
     * @param $correspondance
     * @return mixed|null
     */
    protected function getExisteFromOldId(array $data, $correspondance)
    {
        foreach($data as $el){
            if($el->getOldId() == $correspondance){
                return $el;
            }
        }

        return null;
    }

    protected function checkExiste($type, $entity, $data, $item, $diff0, $diff1 = null): array
    {
        $msg = "";
        if($elem = $this->getExisteFromOldId($data, $item->getId())){
            $same = false;
            switch ($type){
                case "num":
                    if($elem->getName() == $diff0 && $elem->getNum() == $diff1){
                        $same = true;
                    }
                    break;
                case "fullname":
                    if($elem->getLastname() == $diff0 && $elem->getFirstname() == $diff1){
                        $same = true;
                    }
                    break;
                default:
                    if($elem->getName() == $diff0){
                        $same = true;
                    }
                    break;
            }

            if($same){
                $status = 3;
            }else{
                $status = 2;
                $msg = "Changement : " . $elem->getId();
            }
        }else{
            $status = 1;
            $elem = $entity;
        }

        return [$elem, $status, $msg];
    }
}
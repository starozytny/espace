<?php

namespace App\Service\Synchro;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class Sync
{
    protected $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * Check if array of items is empty
     *
     * @param SymfonyStyle $io
     * @param array $items
     * @return bool
     */
    protected function haveData(SymfonyStyle $io, array $items): bool
    {
        if(count($items) != 0){
            return true;
        }

        $io->comment("Aucune donnée");
        return false;
    }

    /**
     * Return object of windev entity or null
     *
     * @param array $windevData
     * @param $windevItem
     * @return mixed|null
     */
    protected function getExiste(array $windevData, $windevItem)
    {
        foreach($windevData as $item){
            if($item->getOldId() == $windevItem->getId()){
                return $item;
            }
        }

        return null;
    }

    /**
     * Display date of array
     *
     * @param SymfonyStyle $io
     * @param array $data
     */
    protected function displayDataArray(SymfonyStyle $io, array $data)
    {
        if(count($data) > 0){
            foreach($data as $item){
                $io->text($item);
            }
        }
    }
}
<?php

namespace App\Command\Booking;

use App\Service\Booking\BookingService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CiteBookingDayCommand extends Command
{
    protected static $defaultName = 'cite:booking:day';
    protected $em;
    protected $bookingService;

    public function __construct(EntityManagerInterface $entityManager, BookingService $bookingService)
    {
        parent::__construct();

        $this->em = $entityManager;
        $this->bookingService = $bookingService;
    }

    protected function configure()
    {
        $this
            ->setDescription('Check booking day and set to true isOpen day');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $find = $this->bookingService->checkDaysOpenable();

        if($find){
            $io->title(sprintf("La journée du %s est ouverte.", $find->getDayLongString()));
        }else{
            $io->comment("Aucune journée ouverte");
        }

        $io->comment('--- [FIN DE LA COMMANDE] ---');

        return 0;
    }
}
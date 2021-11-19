<?php

namespace App\Command\Booking;

use App\Service\Booking\BookingService;
use Doctrine\ORM\EntityManagerInterface;
use Mpdf\MpdfException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class CiteBookingRefreshCommand extends Command
{
    protected static $defaultName = 'cite:booking:refresh';
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
            ->setDescription('Delete booking after 10min for mobile or 15min for desktop if not filled.');
    }

    /**
     * @throws MpdfException
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $count = $this->bookingService->refreshBooking();
        $stats = $this->bookingService->setWaitingList();

        $io->text(sprintf("%d reservation(s) supprimee(s) pour cause d'inactivite.", $count));
        $io->newLine();
        foreach ($stats as $stat){
            if($stat){
                $io->text(sprintf("%s : %d reservation completee.", $stat['day'], $stat['count']));
            }
        }

        $io->comment('--- [FIN DE LA COMMANDE] ---');

        return 0;
    }
}
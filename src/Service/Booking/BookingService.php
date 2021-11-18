<?php


namespace App\Service\Booking;


use App\Entity\Booking\BoDay;
use App\Entity\Booking\BoResponsable;
use App\Entity\Booking\BoSlot;
use App\Service\MailerService;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Mpdf\HTMLParserMode;
use Mpdf\Mpdf;
use Mpdf\MpdfException;
use Mpdf\Output\Destination;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class BookingService
{
    private $em;
    private $mailerService;
    private $router;
    private $privateDirectory;
    private $publicDirectory;
    private $templating;

    public function __construct(EntityManagerInterface $entityManager, MailerService $mailerService, UrlGeneratorInterface $router,
                                $privateDirectory, $publicDirectory, Environment $templating)
    {
        $this->em = $entityManager;
        $this->mailerService = $mailerService;
        $this->router = $router;
        $this->privateDirectory = $privateDirectory;
        $this->publicDirectory = $publicDirectory;
        $this->templating = $templating;
    }

    public function checkDaysOpenable($current=null): ?BoDay
    {
        $find = null;
        if($current == null){
            $current = new DateTime();
            $current->setTimezone(new \DateTimeZone("Europe/Paris"));

            $current = date_create_from_format('d/m/Y H:i:s', $current->format("d/m/Y H:i:s"));
        }

        $days = $this->em->getRepository(BoDay::class)->findBy([], ['day' => "ASC", 'open' => "ASC", 'close' => "ASC"]);

        if($days){

            if($find == null){
                $prev = null;
                $atLeastOneOpenDay = false;

                /** @var BoDay $day */
                foreach($days as $day){
                    if($day->getIsOpen() != false){
                        $day->setIsOpen(false);
                        $atLeastOneOpenDay = true;
                    }
                    if($find == null){
                        // if day remaining ticket = 0 => day can't be open
                        if($day->getRemainingTickets() != 0 && $day->getTotalParticipantsWaiting() < $day->getRemainingTickets()){

                            if($prev == null){ // first loop => check date open existe
                                if($day->getOpen()){
                                    $find = $this->checkFullDate($day, $current);
                                }
                            }else{
                                if($day->getOpen() == null){
                                    $find = $this->checkCloseDate($day, $current);
                                    if($find){
                                        $find->setPrevDay($prev);
                                    }
                                    if(!$atLeastOneOpenDay){
                                        $find = null;
                                    }
                                }else{
                                    $find = $this->checkFullDate($day, $current);
                                }
                            }
                        }

                        if($find){
                            if($find->getIsOpen() != true){
                                $find->setIsOpen(true);
                            }
                        }
                    }else{
                        // si on a trouvé un jour ouvrable, on check si la journée actuelle devrait être ouverte
                        if($day->getOpen() != null){
                            $check = $this->checkFullDate($day, $current);
                            if($check){
                                if($check->getOpen() <= $find->getOpen()){
                                    if($find->getIsOpen() != false){
                                        $find->setIsOpen(false);
                                    }
                                    $find = $check;
                                    if($find->getIsOpen() != true){
                                        $find->setIsOpen(true);
                                    }
                                }
                            }
                        }
                    }
                    $prev = $day;
                }
                $this->em->flush();
            }
        }

        return $find;
    }

    /**
     * Return Day openable
     *      if current date >= date open && no date close (check remaining ticket before)
     *      if current date >= date open && have date close && current date <= date close
     *
     * @param BoDay $day
     * @param $current
     * @return BoDay|null
     */
    private function checkFullDate(BoDay $day, $current): ?BoDay
    {
        if($day->getOpen() <= $current){ // day ouvert, mais on va check si sa close date est atteinte
            return $this->checkCloseDate($day, $current);
        }

        return null;
    }

    private function checkCloseDate(BoDay $day, $current): ?BoDay
    {
        if($day->getClose() == null){ // si pas de date close = ouvert
            return $day;
        }else{
            if($current <= $day->getClose()){ // si date close est supérieur à aujourd'hui = ouvert
                return $day;
            }
        }

        return null;
    }

    public function getSlot($day, $creneaux): ?BoSlot
    {
        $slots = [];
        foreach($creneaux as $cr){
            if($cr->getDay() == $day){
                array_push($slots, $cr);
            }
        }

        $slot = null;
        foreach($slots as $s){
            if($slot == null){
                if($s->getRemaining() != 0){
                    $slot = $s;
                }
            }
        }

        return $slot;
    }

    /**
     * @throws MpdfException
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function setWaitingList(BoDay $day = null): array
    {
        if($day == null){
            $days = $this->em->getRepository(BoDay::class)->findAll();
        }else{
            $days = [$day];
        }

        $creneaux = $this->em->getRepository(BoSlot::class)->findBy([], ['timetable' => 'ASC']);

        $stats = [];
        /** @var BoDay $day */
        foreach($days as $day){

            $stats[$day->getId()] = [];
            $count = 0;

            if($day->getTotalParticipantsWaiting() != 0){
                $responsables = $this->em->getRepository(BoResponsable::class)->findBy(['day' => $day], ['createAt' => 'ASC']);

                $remainings = [];
                $waitings = [];
                foreach($responsables as $responsable){
                    if($responsable->getStatus() == BoResponsable::STATUS_START){
                        array_push($remainings, $responsable);
                    }else if($responsable->getStatus() == BoResponsable::STATUS_WAITING){
                        array_push($waitings, $responsable);
                    }
                }

                if(count($waitings) > 0) {

                    $stop = false; // = waiting have to wait first remaining finish his registration
                    /** @var BoResponsable $waiting */
                    foreach ($waitings as $waiting) {
                        if(!$stop){

                            $canAdd = false;

                            $prevDay = null;
                            if($waiting->getPrevDay()){
                                $prevDay = $waiting->getPrevDay();
                            }else if($day->getPrevDay()){
                                $prevDay = $day->getPrevDay();
                            }

                            //check si le waiting a un prev day
                            if($prevDay && $prevDay->getRemainingTickets() != 0){

                                if($prevDay->getTotalParticipantsWaiting() == 0){
                                    $day = $prevDay;
                                    $canAdd = true;
                                }else{
                                    $stop = true;
                                }

                            }else{
                                if (count($remainings) > 0) { // un ou des gens dans le process
                                    if ($waiting->getCreateAt() < $remainings[0]->getCreateAt()){ // si le premier du process a démarré après le waiter = add
                                        array_shift($remainings);
                                        $canAdd = true;
                                    }else{ // sinon on attend que le waiter soit le premier du process
                                        $stop = true;
                                    }
                                } else { // aucune personne dans le process donc on peut direct ajouter
                                    $canAdd = true;
                                }
                            }

                            if($canAdd){
                                $slot = $this->getSlot($day, $creneaux);
                                if($slot){
                                    $count++;
                                    $stats[$day->getId()] = [
                                        'day' => $day->getDayLongString(),
                                        'count' => $count
                                    ];

                                    $slot->setRemaining($slot->getRemaining() - 1);

                                    $waiting->setPrevDay(null);
                                    $waiting->setDay($day);
                                    $waiting->setSlot($slot);
                                    $waiting->setStatus(BoResponsable::STATUS_END);

                                    $print = $this->router->generate('api_booking_ticket_print', ['responsable' => $waiting->getId(), 'ticket' => $waiting->getTicket()], UrlGeneratorInterface::ABSOLUTE_URL);
                                    $this->mailerService->sendMail(
                                        $waiting->getEmail(),
                                        "Cité de la musique - Ticket reservation " . $waiting->getTicket(),
                                        "Ticket reservation pour les journees d'inscriptions.",
                                        "app/email/booking/index.html.twig",
                                        ['responsable' => $waiting, 'print' => $print],
                                        null,
                                        true,
                                        $waiting->getId() . '-barcode.jpg'
                                    );

                                    $this->em->flush();
                                }
                            }else{
                                $mpdf = new Mpdf(
                                    ['tempDir' => '/tmp']
                                );

                                $mpdf->SetTitle('Ticket cité de la musique - ' . $waiting->getFirstname() . ' ' . $waiting->getLastname());
                                $stylesheet = file_get_contents($this->publicDirectory . 'pdf/css/bootstrap.min.css');
                                $stylesheet2 = file_get_contents($this->publicDirectory . 'pdf/css/custom-pdf.css');
                                $mpdf->WriteHTML($stylesheet,HTMLParserMode::HEADER_CSS);
                                $mpdf->WriteHTML($stylesheet2,HTMLParserMode::HEADER_CSS);
                                $mpdf->SetProtection(array(
                                    'print'
                                ),'', 'Pf3zGgig5hy5');

                                $mpdf->WriteHTML(
                                    $this->templating->render('app/pdf/ticket.html.twig', ['responsable' => $waiting]),
                                    HTMLParserMode::HTML_BODY
                                );

                                $mpdf->Output($this->privateDirectory . 'booking/tickets/ticket-'.$waiting->getId().'.pdf', Destination::FILE);
                            }
                        }
                    }
                }
            }
        }

        return $stats;
    }

    public function refreshBooking(): int
    {
        $responsables = $this->em->getRepository(BoResponsable::class)->findBy([
            'status' => BoResponsable::STATUS_START
        ], ['createAt' => 'ASC']);

        $count = 0;
        if(count($responsables) > 0){
            /** @var BoResponsable $responsable */
            $now = new DateTime();
            foreach($responsables as $responsable){

                $updateAt = new DateTime($responsable->getUpdateAtPhpString());
                $interval = date_diff($updateAt, $now);

                if($responsable->getInMobile()){
                    if($interval->i > 10 || $interval->h > 0 || $interval->d > 0 || $interval->m > 0 || $interval->y > 0){
                        $slot = $responsable->getSlot();
                        if($slot){
                            $slot->setRemaining($slot->getRemaining() - 1);
                        }
                        $this->em->remove($responsable);
                        $count++;
                    }
                }else{
                    if($interval->i > 15 || $interval->h > 0 || $interval->d > 0 || $interval->m > 0 || $interval->y > 0){
                        $slot = $responsable->getSlot();
                        if($slot){
                            $slot->setRemaining($slot->getRemaining() - 1);
                        }
                        $this->em->remove($responsable);
                        $count++;
                    }
                }

            }

            $this->em->flush();
        }

        return $count;
    }
}
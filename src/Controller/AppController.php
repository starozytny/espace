<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\Booking\BookingService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class AppController extends AbstractController
{
    /**
     * @Route("/", name="app_homepage")
     */
    public function index(): Response
    {
        return $this->render('app/pages/index.html.twig');
    }

    /**
     * @Route("/inscriptions", options={"expose"=true}, name="app_booking")
     */
    public function register(SerializerInterface $serializer, BookingService $bookingService): Response
    {
        $obj = $bookingService->checkDaysOpenable();
        $obj = $serializer->serialize($obj, "json", ['groups' => User::VISITOR_READ]);

        return $this->render('app/pages/booking/index.html.twig', [
            'donnees' => $obj
        ]);
    }

    /**
     * @Route("/inscriptions/ma-reservation", options={"expose"=true}, name="app_my_booking")
     */
    public function myBooking(): Response
    {
        return $this->render('app/pages/booking/booking.html.twig');
    }

    /**
     * @Route("/legales/mentions-legales", name="app_mentions")
     */
    public function mentions(): Response
    {
        return $this->render('app/pages/legales/mentions.html.twig');
    }

    /**
     * @Route("/legales/politique-confidentialite", options={"expose"=true}, name="app_politique")
     */
    public function politique(): Response
    {
        return $this->render('app/pages/legales/politique.html.twig');
    }

    /**
     * @Route("/legales/cookies", name="app_cookies")
     */
    public function cookies(): Response
    {
        return $this->render('app/pages/legales/cookies.html.twig');
    }

    /**
     * @Route("/legales/rgpd", name="app_rgpd")
     */
    public function rgpd(): Response
    {
        return $this->render('app/pages/legales/rgpd.html.twig');
    }

    /**
     * @Route("/nous-contacter", name="app_contact")
     */
    public function contact(): Response
    {
        return $this->render('app/pages/contact/index.html.twig');
    }
}

<?php

namespace App\Controller;

use App\Entity\Cite\CiEleve;
use App\Entity\Cite\CiNews;
use App\Entity\Cite\CiResponsable;
use App\Entity\Settings\SeRenew;
use App\Entity\User;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/espace-responsable", name="responsable_")
 */
class ResponsableController extends AbstractController
{
    /**
     * @Route("/", options={"expose"=true}, name="homepage")
     * @param SerializerInterface $serializer
     * @param LoggerInterface $logger
     * @return Response
     */
    public function index(SerializerInterface $serializer, LoggerInterface $logger): Response
    {
        $em = $this->getDoctrine()->getManager();
        /** @var User $user */
        $user = $this->getUser();
        if(!$user){
            return $this->redirectToRoute('app_login');
        }
        $responsable = $em->getRepository(CiResponsable::class)->findOneBy(['oldId' => $user->getWho()]);
        $renew = $em->getRepository(SeRenew::class)->findAll();
        $news = $em->getRepository(CiNews::class)->findAll();

        $allEleves = $em->getRepository(CiEleve::class)->findBy(['responsable' => $responsable]);
        $eleves = [];
        foreach($allEleves as $eleve){
            if(!$eleve->getIsAncien()){
                array_push($eleves, $eleve);
            }
        }
        $data = $serializer->serialize($eleves, "json", ['groups' => "lesson:read"]);

        $newsData = null;
        if($news){
            $newsData = [
                'title' => $news[0]->getTitle(),
                'content' => $news[0]->getContent()
            ];
        }

        return $this->render('responsable/pages/index.html.twig', [
            'eleves' => $data,
            'renew' => $renew[0]->getIsOpen(),
            'news' => $newsData,
            'user' => $user
        ]);
    }
}
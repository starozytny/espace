<?php

namespace App\Controller;

use App\Entity\Cite\CiNews;
use App\Entity\Cite\CiPlanning;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/enseignant", name="teacher_")
 */
class TeacherController extends AbstractController
{
    /**
     * @Route("/", options={"expose"=true}, name="homepage")
     */
    public function index(): Response
    {
        $em = $this->getDoctrine()->getManager();
        $planning = $em->getRepository(CiPlanning::class)->findOneBy(['isActual' => false]);

        $em = $this->getDoctrine()->getManager();
        $news = $em->getRepository(CiNews::class)->findAll();
        $newsData = null;
        if($news){
            $newsData = [
                'title' => $news[0]->getTitle(),
                'content' => $news[0]->getContent()
            ];
        }

        return $this->render('teacher/pages/index.html.twig', [
            'planningEditable' => $planning->getIsOn(),
            'news' => $newsData
        ]);
    }
}
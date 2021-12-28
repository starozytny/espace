<?php

namespace App\Controller;

use App\Controller\Common\CommonRoute;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/espace-professeur", name="teacher_")
 */
class TeacherController extends AbstractController
{
    /**
     * @Route("/", options={"expose"=true}, name="homepage")
     */
    public function index(): Response
    {
        return $this->render('teacher/pages/index.html.twig');
    }

    /**
     * @Route("/gestion-niveaux", name="level_index")
     * @param CommonRoute $commonRoute
     * @return Response
     */
    public function level(CommonRoute $commonRoute): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        return $this->render('teacher/pages/level/index.html.twig', $commonRoute->returnLevelPage($user));
    }

    /**
     * @Route("/gestion-planning", name="planning_index")
     * @param CommonRoute $commonRoute
     * @return Response
     */
    public function planning(CommonRoute $commonRoute): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        return $this->render('teacher/pages/planning/index.html.twig', $commonRoute->returnPlanningPage($user));
    }
}
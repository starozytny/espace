<?php

namespace App\Controller;

use App\Controller\Common\CommonRoute;
use App\Entity\Cite\CiAuthorization;
use App\Entity\Contact;
use App\Entity\Notification;
use App\Entity\Settings;
use App\Entity\User;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/admin", name="admin_")
 */
class AdminController extends AbstractController
{
    private $doctrine;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }
    
    private function getAllData($classe, SerializerInterface $serializer, $groups = User::ADMIN_READ): string
    {
        $em = $this->doctrine->getManager();
        $objs = $em->getRepository($classe)->findAll();

        return $serializer->serialize($objs, 'json', ['groups' => $groups]);
    }

    private function getRenderView(Request $request, SerializerInterface $serializer, $class, $route): Response
    {
        $objs = $this->getAllData($class, $serializer);
        $search = $request->query->get('search');
        if($search){
            return $this->render($route, [
                'donnees' => $objs,
                'search' => $search
            ]);
        }

        return $this->render($route, [
            'donnees' => $objs
        ]);
    }

    /**
     * @Route("/", options={"expose"=true}, name="homepage")
     */
    public function index(): Response
    {
        $em = $this->doctrine->getManager();
        $users = $em->getRepository(User::class)->findAll();
        $settings = $em->getRepository(Settings::class)->findAll();

        $totalUsers = count($users); $nbConnected = 0;
        foreach($users as $user){
            if($user->getLastLogin()){
                $nbConnected++;
            }
        }
        return $this->render('admin/pages/index.html.twig', [
            'settings' => $settings ? $settings[0] : null,
            'totalUsers' => $totalUsers,
            'nbConnected' => $nbConnected,
        ]);
    }

    /**
     * @Route("/tous-les-utilisateurs", options={"expose"=true}, name="users_index_full")
     */
    public function usersFull(Request $request, SerializerInterface $serializer): Response
    {
        return $this->getRenderView($request, $serializer, User::class, 'admin/pages/user/index.html.twig');
    }

    /**
     * @Route("/utilisateurs", name="users_index")
     */
    public function users(Request $request, SerializerInterface $serializer): Response
    {
        $em = $this->getDoctrine()->getManager();
        $objs = $em->getRepository(User::class)->findBy(['fullAncien' => false]);

        $objs =  $serializer->serialize($objs, 'json', ['groups' => User::ADMIN_READ]);
        $search = $request->query->get('search');
        if($search){
            return $this->render('admin/pages/user/index.html.twig', [
                'donnees' => $objs,
                'search' => $search
            ]);
        }

        return $this->render('admin/pages/user/index.html.twig', [
            'donnees' => $objs
        ]);
    }

    /**
     * @Route("/styleguide/html", name="styleguide_html")
     */
    public function styleguideHtml(): Response
    {
        return $this->render('admin/pages/styleguide/index.html.twig');
    }

    /**
     * @Route("/styleguide/react", options={"expose"=true}, name="styleguide_react")
     */
    public function styleguideReact(Request  $request): Response
    {
        if($request->isMethod("POST")){
            return new JsonResponse(['code' => true]);
        }
        return $this->render('admin/pages/styleguide/react.html.twig');
    }

    /**
     * @Route("/parametres", name="settings_index")
     */
    public function settings(): Response
    {
        return $this->render('admin/pages/settings/index.html.twig');
    }

    /**
     * @Route("/contact", name="contact_index")
     */
    public function contact(Request $request, SerializerInterface $serializer): Response
    {
        return $this->getRenderView($request, $serializer, Contact::class, 'admin/pages/contact/index.html.twig');
    }

    /**
     * @Route("/notifications", options={"expose"=true}, name="notifications_index")
     */
    public function notifications(SerializerInterface $serializer): Response
    {
        $objs = $this->getAllData(Notification::class, $serializer);

        return $this->render('admin/pages/notifications/index.html.twig', [
            'donnees' => $objs
        ]);
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
        return $this->render('admin/pages/level/index.html.twig', $commonRoute->returnLevelPage($user));
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
        return $this->render('admin/pages/planning/index.html.twig', $commonRoute->returnPlanningPage($user));
    }
}

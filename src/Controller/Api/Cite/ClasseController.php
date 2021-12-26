<?php

namespace App\Controller\Api\Cite;

use App\Entity\Cite\CiClasse;
use App\Entity\Cite\CiTeacher;
use App\Entity\User;
use App\Service\ApiResponse;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;

/**
 * @Route("/api/classes", name="api_classes_")
 */
class ClasseController extends AbstractController
{
    private $doctrine;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    /**
     * Get array of classes of teacher
     *
     * @Route("/teacher/{id}", name="teacher", options={"expose"=true}, methods={"GET"})
     *
     * @OA\Tag(name="Classes")
     *
     * @param CiTeacher $obj
     * @param ApiResponse $apiResponse
     * @return JsonResponse
     */
    public function index(CiTeacher $obj, ApiResponse $apiResponse): JsonResponse
    {
        $em = $this->doctrine->getManager();
        $objs = $em->getRepository(CiClasse::class)->findBy(['teacher' => $obj]);

        return $apiResponse->apiJsonResponse($objs, User::USER_READ);
    }
}

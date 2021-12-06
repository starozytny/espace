<?php

namespace App\Controller\Api\Cite;

use App\Entity\User;
use App\Repository\Cite\CiTeacherRepository;
use App\Service\ApiResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/api/teachers", name="api_teachers_")
 */
class TeacherController extends AbstractController
{
    /**
     * Admin - Get array of teachers
     *
     * @Security("is_granted('ROLE_ADMIN')")
     *
     * @Route("/", name="index", options={"expose"=true}, methods={"GET"})
     *
     * @OA\Tag(name="Teacher")
     *
     * @param Request $request
     * @param CiTeacherRepository $repository
     * @param ApiResponse $apiResponse
     * @return JsonResponse
     */
    public function index(Request $request, CiTeacherRepository $repository, ApiResponse $apiResponse): JsonResponse
    {
        $orderLastname = $request->query->get('orderLastname') ?: 'ASC';

        $teachers = $repository->findBy([], ['lastname' => $orderLastname]);
        return $apiResponse->apiJsonResponse($teachers, User::ADMIN_READ);
    }
}

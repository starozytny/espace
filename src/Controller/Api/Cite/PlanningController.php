<?php

namespace App\Controller\Api\Cite;

use App\Entity\Cite\CiAssignation;
use App\Entity\Cite\CiLesson;
use App\Entity\Cite\CiPlanning;
use App\Entity\Cite\CiSlot;
use App\Entity\Cite\CiTeacher;
use App\Entity\User;
use Doctrine\Common\Persistence\ManagerRegistry;
use Http\Discovery\Exception\NotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/api/plannings", name="api_plannings_")
 */
class PlanningController extends AbstractController
{
    private $doctrine;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    /**
     * Get planning of teacher
     *
     * @Route("/{teacher}", name="teacher", options={"expose"=true}, methods={"GET"})
     *
     * @OA\Response(
     *     response=200,
     *     description="Returns array of planning slot",
     * )
     * @OA\Parameter(
     *     name="isActual",
     *     in="path",
     *     description="previsionel or actual",
     *     @OA\Schema(type="boolean")
     * )
     *
     * @OA\Response(
     *     response=403,
     *     description="Forbidden for not good role or user",
     * )
     *
     * @OA\Tag(name="Planning")
     *
     * @param Request $request
     * @param CiTeacher $teacher
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function teacher(Request $request, CiTeacher $teacher, SerializerInterface $serializer): JsonResponse
    {
        $em = $this->doctrine->getManager();
        $isActual = !($request->query->get('actuel') == "false");

        $planning = $em->getRepository(CiPlanning::class)->findOneBy(['isActual' => $isActual]);
        if(!$planning){
            throw new NotFoundException("Planning introuvable.");
        }

        $slots = $em->getRepository(CiSlot::class)->findBy([
            'teacher' => $teacher->getId(),
            'planning' => $planning->getId()
        ], ['start' => 'ASC', 'end' => 'DESC']);

        $lessons = $em->getRepository(CiLesson::class)->findBy(['slot' => $slots]);
        $assignations = $em->getRepository(CiAssignation::class)->findBy(['lesson' => $lessons]);

        $slots = $serializer->serialize($slots, "json", ['groups' => User::USER_READ]);
        $lessons = $serializer->serialize($lessons, "json", ['groups' => User::USER_READ]);
        $assignations = $serializer->serialize($assignations, "json", ['groups' => User::USER_READ]);

        return new JsonResponse([
            'slots' => $slots,
            'lessons' => $lessons,
            'assignations' => $assignations
        ]);
    }
}

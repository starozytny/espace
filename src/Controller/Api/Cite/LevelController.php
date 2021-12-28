<?php

namespace App\Controller\Api\Cite;

use App\Entity\Cite\CiEleve;
use App\Entity\Cite\CiGroup;
use App\Entity\Cite\CiTeacher;
use App\Entity\User;
use App\Service\ApiResponse;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/api/levels", name="api_levels_")
 */
class LevelController extends AbstractController
{
    private $doctrine;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    /**
     * Reset level eleve
     *
     * @Route("/reset/{group}/{eleve}", name="reset", options={"expose"=true}, methods={"POST"})
     *
     * @OA\Response(
     *     response=200,
     *     description="Returns data"
     * )
     *
     * @OA\Tag(name="Level")
     *
     * @param Request $request
     * @param CiGroup $group
     * @param CiEleve $eleve
     * @param ApiResponse $apiResponse
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function edit(Request $request, CiGroup $group, CiEleve $eleve, ApiResponse $apiResponse, SerializerInterface $serializer): JsonResponse
    {
        $em = $this->doctrine->getManager();
        $data = json_decode($request->getContent());
        if($data == null){
            return $apiResponse->apiJsonResponseBadRequest("Il manque des données.");
        }

        $messageCannotDelete = "Impossible de modifier cet élève car il est déjà assigné à un cours.";


        return $apiResponse->apiJsonResponseSuccessful("ok");
    }
}

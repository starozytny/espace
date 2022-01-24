<?php

namespace App\Controller\Api\Cite;

use App\Entity\Cite\CiClasse;
use App\Entity\Cite\CiCycle;
use App\Entity\Cite\CiGroup;
use App\Entity\Cite\CiLevel;
use App\Entity\Cite\CiPlanning;
use App\Entity\Cite\CiSlot;
use App\Entity\Cite\CiTeacher;
use App\Entity\Prev\PrGroup;
use App\Entity\User;
use App\Service\ApiResponse;
use App\Service\Cite\LevelUp;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;
use Symfony\Component\Serializer\SerializerInterface;

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
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function index(CiTeacher $obj, ApiResponse $apiResponse, SerializerInterface $serializer): JsonResponse
    {
        $em = $this->doctrine->getManager();
        $objs = $em->getRepository(CiClasse::class)->findBy(['teacher' => $obj]);

        $objs = $serializer->serialize($objs, "json", ['groups' => User::USER_READ]);

        return $apiResponse->apiJsonResponseCustom([
            "donnees" => $objs
        ]);
    }

    /**
     * Get array of classes of teacher
     *
     * @Route("/planning-teacher/{id}", name="planning", options={"expose"=true}, methods={"GET"})
     *
     * @OA\Tag(name="Classes")
     *
     * @param CiTeacher $obj
     * @param ApiResponse $apiResponse
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function planning(CiTeacher $obj, ApiResponse $apiResponse, SerializerInterface $serializer): JsonResponse
    {
        $em = $this->doctrine->getManager();
        $objs = $em->getRepository(CiClasse::class)->findBy(['teacher' => $obj]);
        $prGroups = $em->getRepository(PrGroup::class)->findBy(['classe' => $objs]);

        $objs = $serializer->serialize($objs, "json", ['groups' => CiClasse::CLASS_PLANNING_READ]);
        $prGroups = $serializer->serialize($prGroups, "json", ['groups' => PrGroup::PRGROUP_PLANNING_READ]);

        return $apiResponse->apiJsonResponseCustom([
            "donnees" => $objs,
            "prGroups" => $prGroups
        ]);
    }

    /**
     * Get array of classes of teacher and classe superior
     *
     * @Route("/up-teacher/{id}", name="teacher_up", options={"expose"=true}, methods={"GET"})
     *
     * @OA\Tag(name="Classes")
     *
     * @param CiTeacher $obj
     * @param ApiResponse $apiResponse
     * @param SerializerInterface $serializer
     * @param LevelUp $levelUp
     * @return JsonResponse
     */
    public function up(CiTeacher $obj, ApiResponse $apiResponse, SerializerInterface $serializer, LevelUp $levelUp): JsonResponse
    {
        $em = $this->doctrine->getManager();
        $objs = $em->getRepository(CiClasse::class)->findBy(['teacher' => $obj]);
        $grps = $em->getRepository(CiGroup::class)->findBy(['classe' => $objs]);

        $grps = $serializer->serialize($grps, "json", ['groups' => CiGroup::GROUP_READ]);

        // récupération des classes supérieures logiques à la classe current.
        $planningPrev = $em->getRepository(CiPlanning::class)->findOneBy(['isActual' => false]);
        $slotsPrev = $em->getRepository(CiSlot::class)->findBy(
            ['planning' => $planningPrev], ['start' => 'ASC', 'end' => 'DESC']
        );
        $classes = $em->getRepository(CiClasse::class)->findAll();
        $levels = $em->getRepository(CiLevel::class)->findAll();
        $cycles = $em->getRepository(CiCycle::class)->findAll();

        $data = [];
        /** @var CiClasse $obj */
        foreach($objs as $obj){
            if($obj->getCycle()){

                if($obj->getIsFm()){
                    $up = $levelUp->getLevelUpFM($levels, $cycles, $classes, $obj, $slotsPrev);
                } else {
                    $up = $levelUp->getLevelUpInstru($cycles, $classes, $obj, $slotsPrev);
                }
            }else{
                $up = [[$obj]];
            }

            $data[] = [
                'classe' => $obj,
                'up' => $up[0] //0 = classe | 1 = status
            ];
        }

        $objs = $serializer->serialize($data, "json", ['groups' => User::USER_READ]);

        return $apiResponse->apiJsonResponseCustom([
            "donnees" => $objs,
            "groupes" => $grps
        ]);
    }
}

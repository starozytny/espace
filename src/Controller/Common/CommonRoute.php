<?php

namespace App\Controller\Common;

use App\Entity\Cite\CiAuthorization;
use App\Entity\Cite\CiTeacher;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class CommonRoute
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    public function returnLevelPage(User $user): array
    {
        $authorization = $this->em->getRepository(CiAuthorization::class)->findOneBy(['rank' => 0]);
        $teacher = $this->em->getRepository(CiTeacher::class)->findOneBy(['oldId' => $user->getWho()]);

        return [
            'isOpenLevel' => $authorization->getIsOpenLevel(),
            'teacher' => $teacher
        ];
    }
}
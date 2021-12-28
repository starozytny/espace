<?php

namespace App\Entity\Cite;

use App\Repository\Cite\CiAuthorizationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CiAuthorizationRepository::class)
 */
class CiAuthorization
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isOpenLevel;

    /**
     * @ORM\Column(type="integer")
     */
    private $rank;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isOpenPlanning;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isOpenPlanningInstru;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isOpenPlanningFm;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIsOpenLevel(): ?bool
    {
        return $this->isOpenLevel;
    }

    public function setIsOpenLevel(bool $isOpenLevel): self
    {
        $this->isOpenLevel = $isOpenLevel;

        return $this;
    }

    public function getRank(): ?int
    {
        return $this->rank;
    }

    public function setRank(int $rank): self
    {
        $this->rank = $rank;

        return $this;
    }

    public function getIsOpenPlanning(): ?bool
    {
        return $this->isOpenPlanning;
    }

    public function setIsOpenPlanning(bool $isOpenPlanning): self
    {
        $this->isOpenPlanning = $isOpenPlanning;

        return $this;
    }

    public function getIsOpenPlanningInstru(): ?bool
    {
        return $this->isOpenPlanningInstru;
    }

    public function setIsOpenPlanningInstru(bool $isOpenPlanningInstru): self
    {
        $this->isOpenPlanningInstru = $isOpenPlanningInstru;

        return $this;
    }

    public function getIsOpenPlanningFm(): ?bool
    {
        return $this->isOpenPlanningFm;
    }

    public function setIsOpenPlanningFm(bool $isOpenPlanningFm): self
    {
        $this->isOpenPlanningFm = $isOpenPlanningFm;

        return $this;
    }
}

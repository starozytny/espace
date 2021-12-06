<?php

namespace App\Entity\Cite;

use App\Repository\Cite\CiAssignationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CiAssignationRepository::class)
 */
class CiAssignation
{
    const STATUS_NONE = 0;
    const STATUS_ACCEPT = 1;
    const STATUS_REFUSE = 2;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $status = self::STATUS_NONE;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActual;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isSuspended;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isFm;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $tarif;

    /**
     * @ORM\ManyToOne(targetEntity=CiEleve::class, inversedBy="assignations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $eleve;

    /**
     * @ORM\ManyToOne(targetEntity=CiClasse::class, inversedBy="assignations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $classe;

    /**
     * @ORM\ManyToOne(targetEntity=CiLesson::class, inversedBy="assignations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $lesson;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIsActual(): ?bool
    {
        return $this->isActual;
    }

    public function setIsActual(bool $isActual): self
    {
        $this->isActual = $isActual;

        return $this;
    }

    public function getIsSuspended(): ?bool
    {
        return $this->isSuspended;
    }

    public function setIsSuspended(bool $isSuspended): self
    {
        $this->isSuspended = $isSuspended;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getIsFm(): ?bool
    {
        return $this->isFm;
    }

    public function setIsFm(bool $isFm): self
    {
        $this->isFm = $isFm;

        return $this;
    }

    public function getTarif(): ?float
    {
        return $this->tarif;
    }

    public function setTarif(?float $tarif): self
    {
        $this->tarif = $tarif;

        return $this;
    }

    public function getEleve(): ?CiEleve
    {
        return $this->eleve;
    }

    public function setEleve(?CiEleve $eleve): self
    {
        $this->eleve = $eleve;

        return $this;
    }

    public function getClasse(): ?CiClasse
    {
        return $this->classe;
    }

    public function setClasse(?CiClasse $classe): self
    {
        $this->classe = $classe;

        return $this;
    }

    public function getLesson(): ?CiLesson
    {
        return $this->lesson;
    }

    public function setLesson(?CiLesson $lesson): self
    {
        $this->lesson = $lesson;

        return $this;
    }
}

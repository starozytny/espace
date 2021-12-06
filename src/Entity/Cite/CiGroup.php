<?php

namespace App\Entity\Cite;

use App\Repository\Cite\CiGroupRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CiGroupRepository::class)
 */
class CiGroup
{
    const TO_SET = 0;
    const STAY = 1;
    const LEVEL_UP = 2;
    const GIVEN = 3;

    const ANSWER_NONE = 0;
    const ANSWER_ACCEPT = 1;
    const ANSWER_REFUSE = 2;
    const ANSWER_WAITING = 3;
    const ANSWER_WAITING_PRIORITY = 4;
    const ANSWER_SEPTEMBER = 5;
    const ANSWER_ASK = 99;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $status = self::TO_SET;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isFm;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isGiven = false;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $givenGroup;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isFree = false;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isSuspended = false;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isFinal = false;

    /**
     * @ORM\Column(type="integer")
     */
    private $renewAnswer = self::ANSWER_NONE;

    /**
     * @ORM\ManyToOne(targetEntity=CiEleve::class, inversedBy="groups")
     * @ORM\JoinColumn(nullable=false)
     */
    private $eleve;

    /**
     * @ORM\ManyToOne(targetEntity=CiClasse::class, inversedBy="groups")
     * @ORM\JoinColumn(nullable=false)
     */
    private $classe;

    /**
     * @ORM\ManyToOne(targetEntity=CiClasse::class)
     */
    private $classeTo;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getIsGiven(): ?bool
    {
        return $this->isGiven;
    }

    public function setIsGiven(bool $isGiven): self
    {
        $this->isGiven = $isGiven;

        return $this;
    }

    public function getGivenGroup(): ?string
    {
        return $this->givenGroup;
    }

    public function setGivenGroup(?string $givenGroup): self
    {
        $this->givenGroup = $givenGroup;

        return $this;
    }

    public function getIsFree(): ?bool
    {
        return $this->isFree;
    }

    public function setIsFree(bool $isFree): self
    {
        $this->isFree = $isFree;

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

    public function getIsFinal(): ?bool
    {
        return $this->isFinal;
    }

    public function setIsFinal(bool $isFinal): self
    {
        $this->isFinal = $isFinal;

        return $this;
    }

    public function getRenewAnswer(): ?int
    {
        return $this->renewAnswer;
    }

    public function setRenewAnswer(int $renewAnswer): self
    {
        $this->renewAnswer = $renewAnswer;

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

    public function getClasseTo(): ?CiClasse
    {
        return $this->classeTo;
    }

    public function setClasseTo(?CiClasse $classeTo): self
    {
        $this->classeTo = $classeTo;

        return $this;
    }
}

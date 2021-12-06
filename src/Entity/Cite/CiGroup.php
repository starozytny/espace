<?php

namespace App\Entity\Cite;

use App\Repository\Cite\CiGroupRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CiGroupRepository::class)
 */
class CiGroup
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $status;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isFm;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isGiven;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $givenGroup;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isFree;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isSuspended;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isFinal;

    /**
     * @ORM\Column(type="integer")
     */
    private $renewAnswer;

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

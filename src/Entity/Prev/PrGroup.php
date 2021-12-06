<?php

namespace App\Entity\Prev;

use App\Entity\Cite\CiAssignation;
use App\Entity\Cite\CiClasse;
use App\Entity\Cite\CiEleve;
use App\Entity\Cite\CiGroup;
use App\Repository\Prev\PrGroupRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PrGroupRepository::class)
 */
class PrGroup
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $numGroup;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isFm;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isHidden = false;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isOri = false;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isRefused = false;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isMultiple = false;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isWaiting = false;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isFree = false;

    /**
     * @ORM\ManyToOne(targetEntity=CiEleve::class, inversedBy="prGroups")
     * @ORM\JoinColumn(nullable=false)
     */
    private $eleve;

    /**
     * @ORM\ManyToOne(targetEntity=CiClasse::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $classe;

    /**
     * @ORM\ManyToOne(targetEntity=CiClasse::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $classeFrom;

    /**
     * @ORM\ManyToOne(targetEntity=CiGroup::class, inversedBy="prGroups")
     * @ORM\JoinColumn(nullable=false)
     */
    private $groupe;

    /**
     * @ORM\OneToOne(targetEntity=CiAssignation::class, cascade={"persist", "remove"})
     */
    private $assignation;

    /**
     * @ORM\Column(type="integer")
     */
    private $windevCours;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumGroup(): ?string
    {
        return $this->numGroup;
    }

    public function setNumGroup(string $numGroup): self
    {
        $this->numGroup = $numGroup;

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

    public function getIsHidden(): ?bool
    {
        return $this->isHidden;
    }

    public function setIsHidden(bool $isHidden): self
    {
        $this->isHidden = $isHidden;

        return $this;
    }

    public function getIsOri(): ?bool
    {
        return $this->isOri;
    }

    public function setIsOri(bool $isOri): self
    {
        $this->isOri = $isOri;

        return $this;
    }

    public function getIsRefused(): ?bool
    {
        return $this->isRefused;
    }

    public function setIsRefused(bool $isRefused): self
    {
        $this->isRefused = $isRefused;

        return $this;
    }

    public function getIsMultiple(): ?bool
    {
        return $this->isMultiple;
    }

    public function setIsMultiple(bool $isMultiple): self
    {
        $this->isMultiple = $isMultiple;

        return $this;
    }

    public function getIsWaiting(): ?bool
    {
        return $this->isWaiting;
    }

    public function setIsWaiting(bool $isWaiting): self
    {
        $this->isWaiting = $isWaiting;

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

    public function getClasseFrom(): ?CiClasse
    {
        return $this->classeFrom;
    }

    public function setClasseFrom(?CiClasse $classeFrom): self
    {
        $this->classeFrom = $classeFrom;

        return $this;
    }

    public function getGroupe(): ?CiGroup
    {
        return $this->groupe;
    }

    public function setGroupe(?CiGroup $groupe): self
    {
        $this->groupe = $groupe;

        return $this;
    }

    public function getAssignation(): ?CiAssignation
    {
        return $this->assignation;
    }

    public function setAssignation(?CiAssignation $assignation): self
    {
        $this->assignation = $assignation;

        return $this;
    }

    public function getWindevCours(): ?int
    {
        return $this->windevCours;
    }

    public function setWindevCours(int $windevCours): self
    {
        $this->windevCours = $windevCours;

        return $this;
    }
}

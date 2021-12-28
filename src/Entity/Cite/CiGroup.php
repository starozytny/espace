<?php

namespace App\Entity\Cite;

use App\Entity\Prev\PrGroup;
use App\Repository\Cite\CiGroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=CiGroupRepository::class)
 */
class CiGroup
{
    const GROUP_READ = ["group:read"];

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
     * @Groups({"group:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"group:read"})
     */
    private $status = self::TO_SET;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isFm;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"group:read"})
     */
    private $isGiven = false;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"group:read"})
     */
    private $givenGroup;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isFree = false;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"group:read"})
     */
    private $isSuspended = false;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"group:read"})
     */
    private $isFinal = false;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"group:read"})
     */
    private $renewAnswer = self::ANSWER_NONE;

    /**
     * @ORM\ManyToOne(targetEntity=CiEleve::class, fetch="EAGER", inversedBy="groups")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"group:read"})
     */
    private $eleve;

    /**
     * @ORM\ManyToOne(targetEntity=CiClasse::class, inversedBy="groups")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"group:read"})
     */
    private $classe;

    /**
     * @ORM\ManyToOne(targetEntity=CiClasse::class, fetch="EAGER")
     * @Groups({"group:read"})
     */
    private $classeTo;

    /**
     * @ORM\OneToMany(targetEntity=PrGroup::class, mappedBy="groupe")
     */
    private $prGroups;

    /**
     * @ORM\Column(type="integer")
     */
    private $windevCours;

    public function __construct()
    {
        $this->prGroups = new ArrayCollection();
    }

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

    /**
     * @return Collection|PrGroup[]
     */
    public function getPrGroups(): Collection
    {
        return $this->prGroups;
    }

    public function addPrGroup(PrGroup $prGroup): self
    {
        if (!$this->prGroups->contains($prGroup)) {
            $this->prGroups[] = $prGroup;
            $prGroup->setGroupe($this);
        }

        return $this;
    }

    public function removePrGroup(PrGroup $prGroup): self
    {
        if ($this->prGroups->removeElement($prGroup)) {
            // set the owning side to null (unless already changed)
            if ($prGroup->getGroupe() === $this) {
                $prGroup->setGroupe(null);
            }
        }

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

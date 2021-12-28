<?php

namespace App\Entity\Cite;

use App\Repository\Cite\CiLessonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=CiLessonRepository::class)
 */
class CiLesson
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"user:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="time")
     * @Groups({"user:read"})
     */
    private $start;

    /**
     * @ORM\Column(type="time", nullable=true)
     */
    private $end;

    /**
     * @ORM\Column(type="time", nullable=true)
     */
    private $duration;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActual;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isFm;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isBbb = false;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"user:read"})
     */
    private $isMixte = false;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slotIdentifiant;

    /**
     * @ORM\ManyToOne(targetEntity=CiSlot::class, fetch="EAGER", inversedBy="lessons")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"user:read"})
     */
    private $slot;

    /**
     * @ORM\ManyToOne(targetEntity=CiTeacher::class, inversedBy="lessons")
     * @ORM\JoinColumn(nullable=false)
     */
    private $teacher;

    /**
     * @ORM\ManyToOne(targetEntity=CiClasse::class, fetch="EAGER");
     * @ORM\JoinColumn(nullable=false)
     */
    private $classe;

    /**
     * @ORM\ManyToOne(targetEntity=CiClasse::class, fetch="EAGER")
     */
    private $classeSecond;

    /**
     * @ORM\ManyToOne(targetEntity=CiClasse::class, fetch="EAGER")
     */
    private $classeThird;

    /**
     * @ORM\ManyToOne(targetEntity=CiClasse::class, fetch="EAGER")
     */
    private $classeFour;

    /**
     * @ORM\OneToMany(targetEntity=CiAssignation::class, mappedBy="lesson")
     */
    private $assignations;

    public function __construct()
    {
        $this->assignations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Get format time start lesson
     *
     * @return false|string|null
     * @Groups({"user:read"})
     */
    public function getStartString(){
        if($this->start){
            return date_format($this->start, 'H:i:s');
        }

        return null;
    }

    public function getStart(): ?\DateTimeInterface
    {
        return $this->start;
    }

    public function setStart(\DateTimeInterface $start): self
    {
        $this->start = $start;

        return $this;
    }

    /**
     * Get format time end lesson
     *
     * @return false|string|null
     * @Groups({"user:read"})
     */
    public function getEndString(){
        if($this->end){
            return date_format($this->end, 'H:i:s');
        }

        return null;
    }

    public function getEnd(): ?\DateTimeInterface
    {
        return $this->end;
    }

    public function setEnd(?\DateTimeInterface $end): self
    {
        $this->end = $end;

        return $this;
    }

    public function getDuration(): ?\DateTimeInterface
    {
        return $this->duration;
    }

    public function setDuration(?\DateTimeInterface $duration): self
    {
        $this->duration = $duration;

        return $this;
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

    public function getIsFm(): ?bool
    {
        return $this->isFm;
    }

    public function setIsFm(bool $isFm): self
    {
        $this->isFm = $isFm;

        return $this;
    }

    public function getIsBbb(): ?bool
    {
        return $this->isBbb;
    }

    public function setIsBbb(bool $isBbb): self
    {
        $this->isBbb = $isBbb;

        return $this;
    }

    public function getIsMixte(): ?bool
    {
        return $this->isMixte;
    }

    public function setIsMixte(bool $isMixte): self
    {
        $this->isMixte = $isMixte;

        return $this;
    }

    public function getSlotIdentifiant(): ?string
    {
        return $this->slotIdentifiant;
    }

    public function setSlotIdentifiant(string $slotIdentifiant): self
    {
        $this->slotIdentifiant = $slotIdentifiant;

        return $this;
    }

    public function getSlot(): ?CiSlot
    {
        return $this->slot;
    }

    public function setSlot(?CiSlot $slot): self
    {
        $this->slot = $slot;

        return $this;
    }

    public function getTeacher(): ?CiTeacher
    {
        return $this->teacher;
    }

    public function setTeacher(?CiTeacher $teacher): self
    {
        $this->teacher = $teacher;

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

    public function getClasseSecond(): ?CiClasse
    {
        return $this->classeSecond;
    }

    public function setClasseSecond(?CiClasse $classeSecond): self
    {
        $this->classeSecond = $classeSecond;

        return $this;
    }

    public function getClasseThird(): ?CiClasse
    {
        return $this->classeThird;
    }

    public function setClasseThird(?CiClasse $classeThird): self
    {
        $this->classeThird = $classeThird;

        return $this;
    }

    public function getClasseFour(): ?CiClasse
    {
        return $this->classeFour;
    }

    public function setClasseFour(?CiClasse $classeFour): self
    {
        $this->classeFour = $classeFour;

        return $this;
    }

    /**
     * @return Collection|CiAssignation[]
     */
    public function getAssignations(): Collection
    {
        return $this->assignations;
    }

    public function addAssignation(CiAssignation $assignation): self
    {
        if (!$this->assignations->contains($assignation)) {
            $this->assignations[] = $assignation;
            $assignation->setLesson($this);
        }

        return $this;
    }

    public function removeAssignation(CiAssignation $assignation): self
    {
        if ($this->assignations->removeElement($assignation)) {
            // set the owning side to null (unless already changed)
            if ($assignation->getLesson() === $this) {
                $assignation->setLesson(null);
            }
        }

        return $this;
    }

    /**
     * Get full name lesson
     *
     * @Groups({"user:read"})
     */
    public function getNameLesson(): string
    {
        if($this->getClasse()){
            $obj = $this->getClasse();
        }else{
            $obj = $this->getSlot();
        }

        $activityName = $obj->getActivity() ? $obj->getActivity()->getName() : null;
        $cycleName = $obj->getCycle() ? " - " . $obj->getCycle()->getName() : null;
        $levelName = $obj->getLevel() ? " - " . $obj->getLevel()->getName() : null;
        if($this->getClasseSecond()){
            $suite = $this->getNameCycleLevel();

            return $activityName . " " . $suite;
        }
        return $activityName . $cycleName . $levelName;
    }

    /**
     * Get short name lesson
     *
     * @Groups({"user:read"})
     */
    public function getNameCycleLevel(): string
    {
        if($this->getClasse()){
            $obj = $this->getClasse();
        }else{
            $obj = $this->getSlot();
        }

        $oriCycle = $obj->getCycle() ? $obj->getCycle() : null;

        $cycleName = $obj->getCycle() ? $obj->getCycle()->getName() : null;
        $levelName = $obj->getLevel() ? " - " . $obj->getLevel()->getName() : null;

        $name1 = $cycleName . $levelName;
        $name2 = "";
        $name3 = "";
        $name4 = "";
        $obj2CycleName = null;
        $obj3CycleName = null;
        if($this->getClasseSecond()){
            $obj2 = $this->getClasseSecond();

            $obj2CycleName = "";
            if($oriCycle && $oriCycle !== $obj2->getCycle()){
                $obj2CycleName = $obj2->getCycle() ? $obj2->getCycle()->getName() : null;
            }
            $levelName = $obj2->getLevel() ? ($this->getClasseThird() ? " - " : "") . $obj2->getLevel()->getName() : null;

            if($obj2CycleName == "" && $levelName != ""){
                $name2 = ($this->getClasseThird() ? ", " : " et ") . $obj2CycleName . $levelName;
            }
        }

        if($this->getClasseThird()){
            $obj3 = $this->getClasseThird();

            $obj3CycleName = "";
            if($obj2CycleName && $obj2CycleName !== $obj3->getCycle()){
                $obj3CycleName = $obj3->getCycle() ? $obj3->getCycle()->getName() : null;
            }
            $levelName = $obj3->getLevel() ? " - " . $obj3->getLevel()->getName() : null;

            if($obj3CycleName == "" && $levelName != ""){
                $name3 = ($this->getClasseFour() ? ", " : " et ") . $obj3CycleName . $levelName;
            }
        }

        if($this->getClasseFour()){
            $obj4 = $this->getClasseFour();

            if($obj3CycleName && $obj3CycleName !== $obj4->getCycle()){
                $cycleName = $obj4->getCycle() ? $obj4->getCycle()->getName() : null;
            }
            $levelName = $obj4->getLevel() ? " - " . $obj4->getLevel()->getName() : null;

            $name4 = " et " . $cycleName . $levelName;
        }

        return $name1 . $name2 . $name3 . $name4;
    }
}

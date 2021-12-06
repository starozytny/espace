<?php

namespace App\Entity\Cite;

use App\Repository\Cite\CiLessonRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CiLessonRepository::class)
 */
class CiLesson
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="time")
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
     */
    private $isMixte = false;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slotIdentifiant;

    /**
     * @ORM\ManyToOne(targetEntity=CiSlot::class, inversedBy="lessons")
     * @ORM\JoinColumn(nullable=false)
     */
    private $slot;

    /**
     * @ORM\ManyToOne(targetEntity=CiTeacher::class, inversedBy="lessons")
     * @ORM\JoinColumn(nullable=false)
     */
    private $teacher;

    /**
     * @ORM\ManyToOne(targetEntity=CiClasse::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $classe;

    /**
     * @ORM\ManyToOne(targetEntity=CiClasse::class)
     */
    private $classeSecond;

    /**
     * @ORM\ManyToOne(targetEntity=CiClasse::class)
     */
    private $classeThird;

    /**
     * @ORM\ManyToOne(targetEntity=CiClasse::class)
     */
    private $classeFour;

    public function getId(): ?int
    {
        return $this->id;
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
     * Get format time start lesson
     *
     * @return false|string|null
     */
    public function getStartString(){
        if($this->start){
            return date_format($this->start, 'H:i:s');
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
}

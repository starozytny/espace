<?php

namespace App\Entity\Cite;

use App\Repository\Cite\CiSlotRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=CiSlotRepository::class)
 */
class CiSlot
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"user:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $oldId;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $oldAdhactId;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $identifiant;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActual;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $max;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"user:read"})
     */
    private $day;

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
     * @ORM\ManyToOne(targetEntity=CiPlanning::class, inversedBy="slots")
     * @ORM\JoinColumn(nullable=false)
     */
    private $planning;

    /**
     * @ORM\ManyToOne(targetEntity=CiTeacher::class, inversedBy="slots")
     * @ORM\JoinColumn(nullable=false)
     */
    private $teacher;

    /**
     * @ORM\ManyToOne(targetEntity=CiCenter::class, inversedBy="slots")
     * @ORM\JoinColumn(nullable=false)
     */
    private $center;

    /**
     * @ORM\ManyToOne(targetEntity=CiActivity::class, inversedBy="slots")
     * @ORM\JoinColumn(nullable=false)
     */
    private $activity;

    /**
     * @ORM\ManyToOne(targetEntity=CiCycle::class, inversedBy="slots")
     */
    private $cycle;

    /**
     * @ORM\ManyToOne(targetEntity=CiLevel::class, inversedBy="slots")
     */
    private $level;

    /**
     * @ORM\ManyToOne(targetEntity=CiClassroom::class, inversedBy="slots")
     */
    private $classroom;

    /**
     * @ORM\OneToMany(targetEntity=CiLesson::class, mappedBy="slot")
     */
    private $lessons;

    public function __construct()
    {
        $this->lessons = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOldId(): ?string
    {
        return $this->oldId;
    }

    public function setOldId(?string $oldId): self
    {
        $this->oldId = $oldId;

        return $this;
    }

    public function getOldAdhactId(): ?int
    {
        return $this->oldAdhactId;
    }

    public function setOldAdhactId(?int $oldAdhactId): self
    {
        $this->oldAdhactId = $oldAdhactId;

        return $this;
    }

    public function getIdentifiant(): ?string
    {
        return $this->identifiant;
    }

    public function setIdentifiant(string $identifiant): self
    {
        $this->identifiant = $identifiant;

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

    public function getMax(): ?int
    {
        return $this->max;
    }

    public function setMax(?int $max): self
    {
        $this->max = $max;

        return $this;
    }

    public function getDay(): ?int
    {
        return $this->day;
    }

    public function setDay(int $day): self
    {
        $this->day = $day;

        return $this;
    }

    public function getStartString(){
        if($this->start == null){
            return null;
        }

        return date_format($this->start, 'H:i:s');
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

    public function getPlanning(): ?CiPlanning
    {
        return $this->planning;
    }

    public function setPlanning(?CiPlanning $planning): self
    {
        $this->planning = $planning;

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

    public function getCenter(): ?CiCenter
    {
        return $this->center;
    }

    public function setCenter(?CiCenter $center): self
    {
        $this->center = $center;

        return $this;
    }

    public function getActivity(): ?CiActivity
    {
        return $this->activity;
    }

    public function setActivity(?CiActivity $activity): self
    {
        $this->activity = $activity;

        return $this;
    }

    public function getCycle(): ?CiCycle
    {
        return $this->cycle;
    }

    public function setCycle(?CiCycle $cycle): self
    {
        $this->cycle = $cycle;

        return $this;
    }

    public function getLevel(): ?CiLevel
    {
        return $this->level;
    }

    public function setLevel(?CiLevel $level): self
    {
        $this->level = $level;

        return $this;
    }

    public function getClassroom(): ?CiClassroom
    {
        return $this->classroom;
    }

    public function setClassroom(?CiClassroom $classroom): self
    {
        $this->classroom = $classroom;

        return $this;
    }

    /**
     * @return Collection|CiLesson[]
     */
    public function getLessons(): Collection
    {
        return $this->lessons;
    }

    public function addLesson(CiLesson $lesson): self
    {
        if (!$this->lessons->contains($lesson)) {
            $this->lessons[] = $lesson;
            $lesson->setSlot($this);
        }

        return $this;
    }

    public function removeLesson(CiLesson $lesson): self
    {
        if ($this->lessons->removeElement($lesson)) {
            // set the owning side to null (unless already changed)
            if ($lesson->getSlot() === $this) {
                $lesson->setSlot(null);
            }
        }

        return $this;
    }
}

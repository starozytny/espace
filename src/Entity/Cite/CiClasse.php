<?php

namespace App\Entity\Cite;

use App\Repository\Cite\CiClasseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=CiClasseRepository::class)
 */
class CiClasse
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"user:read", "group:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"user:read"})
     */
    private $name;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isFm;

    /**
     * @ORM\Column(type="integer")
     */
    private $max;

    /**
     * @ORM\Column(type="integer")
     */
    private $mode;

    /**
     * @ORM\Column(type="time", nullable=true)
     */
    private $duration;

    /**
     * @ORM\Column(type="time", nullable=true)
     */
    private $durationTotal;

    /**
     * @ORM\ManyToOne(targetEntity=CiTeacher::class, fetch="EAGER", inversedBy="classes")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"user:read"})
     */
    private $teacher;

    /**
     * @ORM\ManyToOne(targetEntity=CiCenter::class, fetch="EAGER", inversedBy="classes")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"user:read"})
     */
    private $center;

    /**
     * @ORM\ManyToOne(targetEntity=CiActivity::class, inversedBy="classes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $activity;

    /**
     * @ORM\ManyToOne(targetEntity=CiCycle::class, inversedBy="classes")
     */
    private $cycle;

    /**
     * @ORM\ManyToOne(targetEntity=CiLevel::class, inversedBy="classes")
     */
    private $level;

    /**
     * @ORM\OneToMany(targetEntity=CiGroup::class, mappedBy="classe")
     */
    private $groups;

    /**
     * @ORM\OneToMany(targetEntity=CiAssignation::class, mappedBy="classe")
     */
    private $assignations;

    public function __construct()
    {
        $this->groups = new ArrayCollection();
        $this->assignations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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

    public function getMax(): ?int
    {
        return $this->max;
    }

    public function setMax(int $max): self
    {
        $this->max = $max;

        return $this;
    }

    public function getMode(): ?int
    {
        return $this->mode;
    }

    public function setMode(int $mode): self
    {
        $this->mode = $mode;

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

    public function getDurationTotal(): ?\DateTimeInterface
    {
        return $this->durationTotal;
    }

    public function setDurationTotal(?\DateTimeInterface $durationTotal): self
    {
        $this->durationTotal = $durationTotal;

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

    /**
     * @return Collection|CiGroup[]
     */
    public function getGroups(): Collection
    {
        return $this->groups;
    }

    public function addGroup(CiGroup $group): self
    {
        if (!$this->groups->contains($group)) {
            $this->groups[] = $group;
            $group->setClasse($this);
        }

        return $this;
    }

    public function removeGroup(CiGroup $group): self
    {
        if ($this->groups->removeElement($group)) {
            // set the owning side to null (unless already changed)
            if ($group->getClasse() === $this) {
                $group->setClasse(null);
            }
        }

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
            $assignation->setClasse($this);
        }

        return $this;
    }

    public function removeAssignation(CiAssignation $assignation): self
    {
        if ($this->assignations->removeElement($assignation)) {
            // set the owning side to null (unless already changed)
            if ($assignation->getClasse() === $this) {
                $assignation->setClasse(null);
            }
        }

        return $this;
    }
}

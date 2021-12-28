<?php

namespace App\Entity\Cite;

use App\Repository\Cite\CiCycleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=CiCycleRepository::class)
 */
class CiCycle
{
    const CAT_EVEIL = 0;
    const CAT_CYCLE_ONE = 1;
    const CAT_CYCLE_TWO = 2;
    const CAT_PREATELIER = 3;
    const CAT_ATELIER = 4;
    const CAT_NO_CYCLE = 5;
    const CAT_UNKNOWN = 999;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"user:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $oldId;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"user:read"})
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     */
    private $cat = self::CAT_UNKNOWN;

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
     * @ORM\OneToMany(targetEntity=CiSlot::class, mappedBy="cycle")
     */
    private $slots;

    /**
     * @ORM\OneToMany(targetEntity=CiClasse::class, mappedBy="cycle")
     */
    private $classes;

    public function __construct()
    {
        $this->slots = new ArrayCollection();
        $this->classes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOldId(): ?int
    {
        return $this->oldId;
    }

    public function setOldId(int $oldId): self
    {
        $this->oldId = $oldId;

        return $this;
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

    public function getCat(): ?int
    {
        return $this->cat;
    }

    public function setCat(int $cat): self
    {
        $this->cat = $cat;

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

    /**
     * @return Collection|CiSlot[]
     */
    public function getSlots(): Collection
    {
        return $this->slots;
    }

    public function addSlot(CiSlot $slot): self
    {
        if (!$this->slots->contains($slot)) {
            $this->slots[] = $slot;
            $slot->setCycle($this);
        }

        return $this;
    }

    public function removeSlot(CiSlot $slot): self
    {
        if ($this->slots->removeElement($slot)) {
            // set the owning side to null (unless already changed)
            if ($slot->getCycle() === $this) {
                $slot->setCycle(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|CiClasse[]
     */
    public function getClasses(): Collection
    {
        return $this->classes;
    }

    public function addClass(CiClasse $class): self
    {
        if (!$this->classes->contains($class)) {
            $this->classes[] = $class;
            $class->setCycle($this);
        }

        return $this;
    }

    public function removeClass(CiClasse $class): self
    {
        if ($this->classes->removeElement($class)) {
            // set the owning side to null (unless already changed)
            if ($class->getCycle() === $this) {
                $class->setCycle(null);
            }
        }

        return $this;
    }
}

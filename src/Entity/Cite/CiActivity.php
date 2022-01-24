<?php

namespace App\Entity\Cite;

use App\Repository\Cite\CiActivityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=CiActivityRepository::class)
 */
class CiActivity
{
    const TYPE_INSTRUMENT = 1;
    const TYPE_FM = 2;
    const TYPE_AUTRE = 3;
    const TYPE_DIVERS = 4;

    const DEP_CLAVIER = 1;
    const DEP_VENTS = 2;
    const DEP_CORDES = 3;
    const DEP_GUITARE = 4;
    const DEP_JAZZ = 5;
    const DEP_COLLECTIVES = 6;
    const DEP_FM = 7;
    const DEP_ECRITURE = 8;
    const DEP_MONDE = 9;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"user:read", "classe-planning:read"})
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
     * @ORM\Column(type="string", length=255)
     * @Groups({"user:read"})
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $departement;

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
     * @ORM\OneToMany(targetEntity=CiSlot::class, mappedBy="activity")
     */
    private $slots;

    /**
     * @ORM\OneToMany(targetEntity=CiClasse::class, mappedBy="activity")
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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getDepartement(): ?string
    {
        return $this->departement;
    }

    public function setDepartement(string $departement): self
    {
        $this->departement = $departement;

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
            $slot->setActivity($this);
        }

        return $this;
    }

    public function removeSlot(CiSlot $slot): self
    {
        if ($this->slots->removeElement($slot)) {
            // set the owning side to null (unless already changed)
            if ($slot->getActivity() === $this) {
                $slot->setActivity(null);
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
            $class->setActivity($this);
        }

        return $this;
    }

    public function removeClass(CiClasse $class): self
    {
        if ($this->classes->removeElement($class)) {
            // set the owning side to null (unless already changed)
            if ($class->getActivity() === $this) {
                $class->setActivity(null);
            }
        }

        return $this;
    }
}

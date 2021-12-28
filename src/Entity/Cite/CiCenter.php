<?php

namespace App\Entity\Cite;

use App\Repository\Cite\CiCenterRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=CiCenterRepository::class)
 */
class CiCenter
{
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
     * @Groups({"user:read", "group:read"})
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=CiClassroom::class, mappedBy="center", orphanRemoval=true)
     */
    private $classrooms;

    /**
     * @ORM\OneToMany(targetEntity=CiSlot::class, mappedBy="center")
     */
    private $slots;

    /**
     * @ORM\OneToMany(targetEntity=CiClasse::class, mappedBy="center")
     */
    private $classes;

    public function __construct()
    {
        $this->classrooms = new ArrayCollection();
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

    /**
     * @return Collection|CiClassroom[]
     */
    public function getClassrooms(): Collection
    {
        return $this->classrooms;
    }

    public function addClassroom(CiClassroom $classroom): self
    {
        if (!$this->classrooms->contains($classroom)) {
            $this->classrooms[] = $classroom;
            $classroom->setCenter($this);
        }

        return $this;
    }

    public function removeClassroom(CiClassroom $classroom): self
    {
        if ($this->classrooms->removeElement($classroom)) {
            // set the owning side to null (unless already changed)
            if ($classroom->getCenter() === $this) {
                $classroom->setCenter(null);
            }
        }

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
            $slot->setCenter($this);
        }

        return $this;
    }

    public function removeSlot(CiSlot $slot): self
    {
        if ($this->slots->removeElement($slot)) {
            // set the owning side to null (unless already changed)
            if ($slot->getCenter() === $this) {
                $slot->setCenter(null);
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
            $class->setCenter($this);
        }

        return $this;
    }

    public function removeClass(CiClasse $class): self
    {
        if ($this->classes->removeElement($class)) {
            // set the owning side to null (unless already changed)
            if ($class->getCenter() === $this) {
                $class->setCenter(null);
            }
        }

        return $this;
    }
}

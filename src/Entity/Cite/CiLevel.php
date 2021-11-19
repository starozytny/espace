<?php

namespace App\Entity\Cite;

use App\Repository\Cite\CiLevelRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CiLevelRepository::class)
 */
class CiLevel
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
    private $oldId;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=CiSlot::class, mappedBy="level")
     */
    private $slots;

    /**
     * @ORM\OneToMany(targetEntity=CiClasse::class, mappedBy="level")
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
            $slot->setLevel($this);
        }

        return $this;
    }

    public function removeSlot(CiSlot $slot): self
    {
        if ($this->slots->removeElement($slot)) {
            // set the owning side to null (unless already changed)
            if ($slot->getLevel() === $this) {
                $slot->setLevel(null);
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
            $class->setLevel($this);
        }

        return $this;
    }

    public function removeClass(CiClasse $class): self
    {
        if ($this->classes->removeElement($class)) {
            // set the owning side to null (unless already changed)
            if ($class->getLevel() === $this) {
                $class->setLevel(null);
            }
        }

        return $this;
    }
}

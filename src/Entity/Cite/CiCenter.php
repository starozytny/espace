<?php

namespace App\Entity\Cite;

use App\Repository\Cite\CiCenterRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CiCenterRepository::class)
 */
class CiCenter
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
     * @ORM\OneToMany(targetEntity=CiClassroom::class, mappedBy="center", orphanRemoval=true)
     */
    private $classrooms;

    public function __construct()
    {
        $this->classrooms = new ArrayCollection();
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
}

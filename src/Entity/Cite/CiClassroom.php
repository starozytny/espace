<?php

namespace App\Entity\Cite;

use App\Repository\Cite\CiClassroomRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=CiClassroomRepository::class)
 */
class CiClassroom
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
     * @ORM\Column(type="string", length=40, nullable=true)
     * @Groups({"user:read"})
     */
    private $num;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"user:read"})
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $comment;

    /**
     * @ORM\ManyToOne(targetEntity=CiCenter::class, inversedBy="classrooms")
     * @ORM\JoinColumn(nullable=false)
     */
    private $center;

    /**
     * @ORM\OneToMany(targetEntity=CiSlot::class, mappedBy="classroom")
     */
    private $slots;

    public function __construct()
    {
        $this->slots = new ArrayCollection();
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

    public function getNum(): ?string
    {
        return $this->num;
    }

    public function setNum(?string $num): self
    {
        $this->num = $num;

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

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

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
            $slot->setClassroom($this);
        }

        return $this;
    }

    public function removeSlot(CiSlot $slot): self
    {
        if ($this->slots->removeElement($slot)) {
            // set the owning side to null (unless already changed)
            if ($slot->getClassroom() === $this) {
                $slot->setClassroom(null);
            }
        }

        return $this;
    }
}

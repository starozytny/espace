<?php

namespace App\Entity\Cite;

use App\Repository\Cite\CiClassroomRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CiClassroomRepository::class)
 */
class CiClassroom
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
     * @ORM\Column(type="string", length=40, nullable=true)
     */
    private $num;

    /**
     * @ORM\Column(type="string", length=255)
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
}

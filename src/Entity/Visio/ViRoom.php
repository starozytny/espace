<?php

namespace App\Entity\Visio;

use App\Entity\Cite\CiTeacher;
use App\Repository\Visio\ViRoomRepository;
use Carbon\Carbon;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ViRoomRepository::class)
 */
class ViRoom
{
    const ROOM_READ = ['room:read'];
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"room:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"room:read"})
     */
    private $name;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"room:read"})
     */
    private $isBBB;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity=CiTeacher::class, inversedBy="rooms")
     * @ORM\JoinColumn(nullable=false)
     */
    private $teacher;

    public function __construct()
    {
        $this->updatedAt = new \DateTime();
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

    /**
     * Fullname of room
     *
     * @return string
     * @Groups({"room:read"})
     */
    public function getFullname(): string
    {
        if(!$this->isBBB){
            return 'citeMuseMars-' . $this->teacher->getId() . '-' . $this->getName();
        }else{
            return 'BBB-' . $this->teacher->getId() . '-' . $this->getName();
        }
    }

    /**
     * nameLesson of room
     *
     * @return string
     * @Groups({"room:read"})
     */
    public function getNameLesson(): string
    {
        return $this->getName();
    }

    public function getIsBBB(): ?bool
    {
        return $this->isBBB;
    }

    public function setIsBBB(bool $isBBB): self
    {
        $this->isBBB = $isBBB;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * How long ago this room was added.
     *
     * @return string
     * @Groups({"room:read"})
     */
    public function getUpdatedAtAgo(): string
    {
        Carbon::setLocale('fr');
        return Carbon::instance($this->getUpdatedAt())->diffForHumans();
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
}

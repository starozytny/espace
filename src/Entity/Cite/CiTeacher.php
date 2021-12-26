<?php

namespace App\Entity\Cite;

use App\Entity\DataEntity;
use App\Repository\Cite\CiTeacherRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=CiTeacherRepository::class)
 */
class CiTeacher extends DataEntity
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"admin:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $oldId;

    /**
     * @ORM\Column(type="integer")
     */
    private $oldPersonId;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $civility;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @ORM\Column(type="time", nullable=true)
     */
    private $quotaInstru;

    /**
     * @ORM\Column(type="time", nullable=true)
     */
    private $quotaFm;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $phoneDomicile;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $phoneMobile;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $adr;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $complement;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $zipcode;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $city;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $comment;

    /**
     * @ORM\OneToMany(targetEntity=CiSlot::class, mappedBy="teacher")
     */
    private $slots;

    /**
     * @ORM\OneToMany(targetEntity=CiClasse::class, mappedBy="teacher")
     */
    private $classes;

    /**
     * @ORM\OneToMany(targetEntity=CiLesson::class, mappedBy="teacher")
     */
    private $lessons;

    public function __construct()
    {
        $this->slots = new ArrayCollection();
        $this->classes = new ArrayCollection();
        $this->lessons = new ArrayCollection();
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

    public function getOldPersonId(): ?int
    {
        return $this->oldPersonId;
    }

    public function setOldPersonId(int $oldPersonId): self
    {
        $this->oldPersonId = $oldPersonId;

        return $this;
    }

    /**
     * @return string
     * @Groups({"admin:read"})
     */
    public function getFullname(): string
    {
        return $this->getFullNameString($this->lastname, $this->firstname);
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(?string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getCivility(): ?string
    {
        return $this->civility;
    }

    public function setCivility(string $civility): self
    {
        $this->civility = $civility;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getQuotaInstru(): ?\DateTimeInterface
    {
        return $this->quotaInstru;
    }

    public function setQuotaInstru(?\DateTimeInterface $quotaInstru): self
    {
        $this->quotaInstru = $quotaInstru;

        return $this;
    }

    public function getQuotaFm(): ?\DateTimeInterface
    {
        return $this->quotaFm;
    }

    public function setQuotaFm(?\DateTimeInterface $quotaFm): self
    {
        $this->quotaFm = $quotaFm;

        return $this;
    }

    public function getPhoneDomicile(): ?string
    {
        return $this->phoneDomicile;
    }

    public function setPhoneDomicile(?string $phoneDomicile): self
    {
        $this->phoneDomicile = $phoneDomicile;

        return $this;
    }

    public function getPhoneMobile(): ?string
    {
        return $this->phoneMobile;
    }

    public function setPhoneMobile(?string $phoneMobile): self
    {
        $this->phoneMobile = $phoneMobile;

        return $this;
    }

    public function getAdr(): ?string
    {
        return $this->adr;
    }

    public function setAdr(?string $adr): self
    {
        $this->adr = $adr;

        return $this;
    }

    public function getComplement(): ?string
    {
        return $this->complement;
    }

    public function setComplement(?string $complement): self
    {
        $this->complement = $complement;

        return $this;
    }

    public function getZipcode(): ?int
    {
        return $this->zipcode;
    }

    public function setZipcode(?int $zipcode): self
    {
        $this->zipcode = $zipcode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city;

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
            $slot->setTeacher($this);
        }

        return $this;
    }

    public function removeSlot(CiSlot $slot): self
    {
        if ($this->slots->removeElement($slot)) {
            // set the owning side to null (unless already changed)
            if ($slot->getTeacher() === $this) {
                $slot->setTeacher(null);
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
            $class->setTeacher($this);
        }

        return $this;
    }

    public function removeClass(CiClasse $class): self
    {
        if ($this->classes->removeElement($class)) {
            // set the owning side to null (unless already changed)
            if ($class->getTeacher() === $this) {
                $class->setTeacher(null);
            }
        }

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
            $lesson->setTeacher($this);
        }

        return $this;
    }

    public function removeLesson(CiLesson $lesson): self
    {
        if ($this->lessons->removeElement($lesson)) {
            // set the owning side to null (unless already changed)
            if ($lesson->getTeacher() === $this) {
                $lesson->setTeacher(null);
            }
        }

        return $this;
    }
}

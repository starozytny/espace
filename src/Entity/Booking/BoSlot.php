<?php

namespace App\Entity\Booking;

use App\Repository\Booking\BoSlotRepository;
use Carbon\Carbon;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=BoSlotRepository::class)
 * @UniqueEntity(
 *     fields={"day", "timetable"},
 *     message="Ce créneau est déjà assigné."
 * )
 */
class BoSlot
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"user:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="time")
     */
    private $timetable;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"user:read"})
     */
    private $max;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"user:read"})
     */
    private $remaining;

    /**
     * @ORM\ManyToOne(targetEntity=BoDay::class, inversedBy="slots")
     * @ORM\JoinColumn(nullable=false)
     */
    private $day;

    /**
     * @ORM\OneToMany(targetEntity=BoResponsable::class, mappedBy="slot")
     */
    private $participants;

    public function __construct()
    {
        $this->setMax(0);
        $this->setRemaining(0);
        $this->participants = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTimetable(): ?\DateTimeInterface
    {
        return $this->timetable;
    }

    public function setTimetable(\DateTimeInterface $timetable): self
    {
        $this->timetable = $timetable;

        return $this;
    }

    /**
     * @return string
     * @Groups({"user:read"})
     */
    public function getTimetableJavascript(): string
    {
        return date_format($this->getTimetable(), "Y/m/d H:i");
    }

    /**
     * @return string
     * @Groups({"user:read", "visitor:read", "visitor:edit"})
     */
    public function getTimetableString(): string
    {
        Carbon::setLocale('fr');
        return str_replace(':', 'h', Carbon::instance($this->getTimetable())->isoFormat('LT'));
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

    public function getRemaining(): ?int
    {
        return $this->remaining;
    }

    public function setRemaining(int $remaining): self
    {
        $this->remaining = $remaining;

        return $this;
    }

    public function getDay(): ?BoDay
    {
        return $this->day;
    }

    public function setDay(?BoDay $day): self
    {
        $this->day = $day;

        return $this;
    }

    /**
     * @return Collection|BoResponsable[]
     */
    public function getParticipants(): Collection
    {
        return $this->participants;
    }

    public function addParticipant(BoResponsable $participant): self
    {
        if (!$this->participants->contains($participant)) {
            $this->participants[] = $participant;
            $participant->setSlot($this);
        }

        return $this;
    }

    public function removeParticipant(BoResponsable $participant): self
    {
        if ($this->participants->removeElement($participant)) {
            // set the owning side to null (unless already changed)
            if ($participant->getSlot() === $this) {
                $participant->setSlot(null);
            }
        }

        return $this;
    }
}

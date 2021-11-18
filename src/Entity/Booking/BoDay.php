<?php

namespace App\Entity\Booking;

use App\Repository\Booking\BoDayRepository;
use Carbon\Carbon;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=BoDayRepository::class)
 * @UniqueEntity(
 *     fields={"day"},
 *     message="Cette journée est déjà assignée."
 * )
 * @UniqueEntity(
 *     fields={"open"},
 *     message="Cette date d'ouverture est déjà assignée."
 * )
 */
class BoDay
{
    const NEWCOMER = 0;
    const OLDER = 1;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"user:read", "visitor:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="date", unique=true)
     */
    private $day;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"user:read", "visitor:read"})
     */
    private $type;

    /**
     * @ORM\Column(type="datetime", nullable=true, unique=true)
     */
    private $open;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $close;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"user:read"})
     */
    private $isOpen;

    /**
     * @ORM\OneToMany(targetEntity=BoSlot::class, mappedBy="day", fetch="EAGER", orphanRemoval=true)
     * @ORM\OrderBy({"timetable" = "ASC"})
     */
    private $slots;

    /**
     * @ORM\OneToMany(targetEntity=BoResponsable::class, mappedBy="day", orphanRemoval=true)
     */
    private $participants;

    /**
     * @ORM\OneToOne(targetEntity=BoDay::class, cascade={"persist", "remove"})
     */
    private $prevDay;

    public function __construct()
    {
        $this->slots = new ArrayCollection();
        $this->setIsOpen(false);
        $this->participants = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDay(): ?\DateTimeInterface
    {
        return $this->day;
    }

    public function setDay(\DateTimeInterface $day): self
    {
        $this->day = $day;

        return $this;
    }

    /**
     * @return string
     * @Groups({"user:read"})
     */
    public function getDayJavascript(): string
    {
        return date_format($this->getDay(), "Y/m/d");
    }

    /**
     * @return string
     * @Groups({"user:read", "visitor:read", "visitor:edit"})
     */
    public function getDayLongString(): string
    {
        Carbon::setLocale('fr');
        return Carbon::instance($this->getDay())->isoFormat('ll');
    }

    /**
     * @return string
     * @Groups({"visitor:read"})
     */
    public function getDayDayString(): string
    {
        Carbon::setLocale('fr');
        return Carbon::instance($this->getDay())->isoFormat('DD');
    }

    /**
     * @return string
     * @Groups({"visitor:read"})
     */
    public function getDayMonthString(): string
    {
        Carbon::setLocale('fr');
        return Carbon::instance($this->getDay())->isoFormat('MMMM');
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(int $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return string
     * @Groups({"user:read"})
     */
    public function getTypeString(): string
    {
        if($this->getType() === self::NEWCOMER){
            return "nouveaux";
        }

        return "anciens";
    }

    public function getOpen(): ?\DateTimeInterface
    {
        return $this->open;
    }

    public function setOpen(?\DateTimeInterface $open): self
    {
        $this->open = $open;

        return $this;
    }

    public function getClose(): ?\DateTimeInterface
    {
        return $this->close;
    }

    public function setClose(?\DateTimeInterface $close): self
    {
        $this->close = $close;

        return $this;
    }

    /**
     * @return string
     * @Groups({"user:read"})
     */
    public function getOpenJavascript(): ?string
    {
        return $this->formatJavascript($this->getOpen());
    }

    /**
     * @return string
     * @Groups({"user:read"})
     */
    public function getOpenLongString(): ?string
    {
        return $this->formatLongString($this->getOpen());
    }

    /**
     * @return string
     * @Groups({"user:read"})
     */
    public function getCloseJavascript(): ?string
    {
        return $this->formatJavascript($this->getClose());
    }

    /**
     * @return string
     * @Groups({"user:read"})
     */
    public function getCloseLongString(): ?string
    {
        return $this->formatLongString($this->getClose());
    }

    private function formatJavascript($date)
    {
        if (!$date){
            return null;
        }
        return date_format($date, "Y/m/d H:i");
    }

    private function formatLongString($date): ?string
    {
        if (!$date){
            return null;
        }

        Carbon::setLocale('fr');
        return Carbon::instance($date)->isoFormat('lll');
    }

    /**
     * @return false|string
     * @Groups({"user:read"})
     */
    public function getSlug()
    {
        return date_format($this->getDay(), "d-m-Y");
    }

    /**
     * @return Collection|BoSlot[]
     */
    public function getSlots(): Collection
    {
        return $this->slots;
    }

    public function addSlot(BoSlot $slot): self
    {
        if (!$this->slots->contains($slot)) {
            $this->slots[] = $slot;
            $slot->setDay($this);
        }

        return $this;
    }

    public function removeSlot(BoSlot $slot): self
    {
        if ($this->slots->removeElement($slot)) {
            // set the owning side to null (unless already changed)
            if ($slot->getDay() === $this) {
                $slot->setDay(null);
            }
        }

        return $this;
    }

    public function getIsOpen(): ?bool
    {
        return $this->isOpen;
    }

    public function setIsOpen(bool $isOpen): self
    {
        $this->isOpen = $isOpen;

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
            $participant->setDay($this);
        }

        return $this;
    }

    public function removeParticipant(BoResponsable $participant): self
    {
        if ($this->participants->removeElement($participant)) {
            // set the owning side to null (unless already changed)
            if ($participant->getDay() === $this) {
                $participant->setDay(null);
            }
        }

        return $this;
    }

    /**
     * @Groups({"user:read"})
     */
    public function getFirstSlotFree(): ?BoSlot
    {
        $slots = $this->getSlots();
        $slot = null;

        if(count($slots) !== 0){
            foreach($slots as $s){
                if($slot == null && $s->getRemaining() != 0){
                    $slot = $s;
                }
            }
        }

        return $slot;
    }

    /**
     * @Groups({"user:read"})
     */
    public function getTotalParticipantsWaiting(): int
    {
        $participants = $this->getParticipants();
        $total = 0;

        if(count($participants) != 0){
            foreach ($participants as $participant) {
                if($participant->getStatus() != BoResponsable::STATUS_END){
                    $total++;
                }
            }
        }

        return $total;
    }

    /**
     * @Groups({"user:read"})
     */
    public function getRemainingTickets(): int
    {
        $slots = $this->getSlots();
        $remaining = 0;

        if(count($slots) !== 0){
            foreach($slots as $slot){
                $remaining += $slot->getRemaining();
            }
        }

        return $remaining;
    }

    /**
     * @Groups({"user:read"})
     */
    public function getMaxTickets(): int
    {
        $slots = $this->getSlots();
        $max = 0;

        if(count($slots) !== 0){
            foreach($slots as $slot){
                $max += $slot->getMax();
            }
        }

        return $max;
    }

    public function getPrevDay(): ?self
    {
        return $this->prevDay;
    }

    public function setPrevDay(?self $prevDay): self
    {
        $this->prevDay = $prevDay;

        return $this;
    }
}

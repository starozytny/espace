<?php

namespace App\Entity\Cite;

use App\Repository\Cite\CiCycleRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CiCycleRepository::class)
 */
class CiCycle
{
    const CAT_EVEIL = 0;
    const CAT_CYCLE_ONE = 1;
    const CAT_CYCLE_TWO = 2;
    const CAT_PREATELIER = 3;
    const CAT_ATELIER = 4;
    const CAT_NO_CYCLE = 5;
    const CAT_UNKNOWN = 999;

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
     * @ORM\Column(type="integer")
     */
    private $cat = self::CAT_UNKNOWN;

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

    public function getCat(): ?int
    {
        return $this->cat;
    }

    public function setCat(int $cat): self
    {
        $this->cat = $cat;

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
}

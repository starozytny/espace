<?php

namespace App\Windev;

use Doctrine\ORM\Mapping as ORM;

/**
 * WindevCycle
 *
 * @ORM\Table(name="windev_cycle")
 * @ORM\Entity
 */
class WindevCycle
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="DESIGNATION", type="string", length=24, nullable=false)
     */
    private $designation;

    /**
     * @var string
     *
     * @ORM\Column(name="LIBELLE_COURT", type="decimal", precision=38, scale=0, nullable=false)
     */
    private $libelleCourt;

    /**
     * @var string|null
     *
     * @ORM\Column(name="DUREE", type="decimal", precision=38, scale=0, nullable=true)
     */
    private $duree;

    /**
     * @var bool
     *
     * @ORM\Column(name="PLUSUTILISE", type="boolean", nullable=false)
     */
    private $plusutilise;

    /**
     * @var string
     *
     * @ORM\Column(name="EFFECTIFMAX", type="decimal", precision=38, scale=0, nullable=false)
     */
    private $effectifmax;

    /**
     * @var string|null
     *
     * @ORM\Column(name="COLONNE", type="decimal", precision=38, scale=0, nullable=true)
     */
    private $colonne;

    /**
     * @var string
     *
     * @ORM\Column(name="MODE", type="decimal", precision=38, scale=0, nullable=false)
     */
    private $mode;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDesignation(): ?string
    {
        return $this->designation;
    }

    public function setDesignation(string $designation): self
    {
        $this->designation = $designation;

        return $this;
    }

    public function getLibelleCourt(): ?string
    {
        return $this->libelleCourt;
    }

    public function setLibelleCourt(string $libelleCourt): self
    {
        $this->libelleCourt = $libelleCourt;

        return $this;
    }

    public function getDuree(): ?string
    {
        return $this->duree;
    }

    public function setDuree(?string $duree): self
    {
        $this->duree = $duree;

        return $this;
    }

    public function getPlusutilise(): ?bool
    {
        return $this->plusutilise;
    }

    public function setPlusutilise(bool $plusutilise): self
    {
        $this->plusutilise = $plusutilise;

        return $this;
    }

    public function getEffectifmax(): ?string
    {
        return $this->effectifmax;
    }

    public function setEffectifmax(string $effectifmax): self
    {
        $this->effectifmax = $effectifmax;

        return $this;
    }

    public function getColonne(): ?string
    {
        return $this->colonne;
    }

    public function setColonne(?string $colonne): self
    {
        $this->colonne = $colonne;

        return $this;
    }

    public function getMode(): ?string
    {
        return $this->mode;
    }

    public function setMode(string $mode): self
    {
        $this->mode = $mode;

        return $this;
    }


}

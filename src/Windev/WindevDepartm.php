<?php

namespace App\Windev;

use Doctrine\ORM\Mapping as ORM;

/**
 * WindevDepartm
 *
 * @ORM\Table(name="windev_departm")
 * @ORM\Entity
 */
class WindevDepartm
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
     * @ORM\Column(name="LIBELLE_COURT", type="string", length=2, nullable=false)
     */
    private $libelleCourt;

    /**
     * @var string
     *
     * @ORM\Column(name="DESIGNATION", type="string", length=21, nullable=false)
     */
    private $designation;

    /**
     * @var bool
     *
     * @ORM\Column(name="PLUSUTILISE", type="boolean", nullable=false)
     */
    private $plusutilise;

    /**
     * @var bool
     *
     * @ORM\Column(name="AssoPartenaire", type="boolean", nullable=false)
     */
    private $assopartenaire;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDesignation(): ?string
    {
        return $this->designation;
    }

    public function setDesignation(string $designation): self
    {
        $this->designation = $designation;

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

    public function getAssopartenaire(): ?bool
    {
        return $this->assopartenaire;
    }

    public function setAssopartenaire(bool $assopartenaire): self
    {
        $this->assopartenaire = $assopartenaire;

        return $this;
    }


}

<?php

namespace App\Windev;

use Doctrine\ORM\Mapping as ORM;

/**
 * WindevCentre
 *
 * @ORM\Table(name="windev_centre")
 * @ORM\Entity
 */
class WindevCentre
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
     * @ORM\Column(name="NOM_CENTRE", type="string", length=31, nullable=false)
     */
    private $nomCentre;

    /**
     * @var string
     *
     * @ORM\Column(name="LIBELLE_COURT", type="decimal", precision=38, scale=0, nullable=false)
     */
    private $libelleCourt;

    /**
     * @var bool
     *
     * @ORM\Column(name="PLUSUTILISE", type="boolean", nullable=false)
     */
    private $plusutilise;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomCentre(): ?string
    {
        return $this->nomCentre;
    }

    public function setNomCentre(string $nomCentre): self
    {
        $this->nomCentre = $nomCentre;

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

    public function getPlusutilise(): ?bool
    {
        return $this->plusutilise;
    }

    public function setPlusutilise(bool $plusutilise): self
    {
        $this->plusutilise = $plusutilise;

        return $this;
    }


}

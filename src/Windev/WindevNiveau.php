<?php

namespace App\Windev;

use Doctrine\ORM\Mapping as ORM;

/**
 * WindevNiveau
 *
 * @ORM\Table(name="windev_niveau")
 * @ORM\Entity
 */
class WindevNiveau
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
     * @ORM\Column(name="NIVEAU", type="string", length=17, nullable=false)
     */
    private $niveau;

    /**
     * @var string
     *
     * @ORM\Column(name="LIBELLE_COURT", type="string", length=12, nullable=false)
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

    public function getNiveau(): ?string
    {
        return $this->niveau;
    }

    public function setNiveau(string $niveau): self
    {
        $this->niveau = $niveau;

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

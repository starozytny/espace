<?php

namespace App\Windev;

use Doctrine\ORM\Mapping as ORM;

/**
 * WindevSalle
 *
 * @ORM\Table(name="windev_salle")
 * @ORM\Entity
 */
class WindevSalle
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
     * @ORM\Column(name="CECLEUNIK", type="decimal", precision=38, scale=0, nullable=false)
     */
    private $cecleunik;

    /**
     * @var string
     *
     * @ORM\Column(name="NUMSALLE", type="string", length=5, nullable=false)
     */
    private $numsalle;

    /**
     * @var string
     *
     * @ORM\Column(name="NOM", type="string", length=30, nullable=false)
     */
    private $nom;

    /**
     * @var string|null
     *
     * @ORM\Column(name="COMMENT", type="string", length=49, nullable=true)
     */
    private $comment;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCecleunik(): ?string
    {
        return $this->cecleunik;
    }

    public function setCecleunik(string $cecleunik): self
    {
        $this->cecleunik = $cecleunik;

        return $this;
    }

    public function getNumsalle(): ?string
    {
        return $this->numsalle;
    }

    public function setNumsalle(string $numsalle): self
    {
        $this->numsalle = $numsalle;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

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


}

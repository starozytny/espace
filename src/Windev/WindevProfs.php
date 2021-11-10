<?php

namespace App\Windev;

use Doctrine\ORM\Mapping as ORM;

/**
 * WindevProfs
 *
 * @ORM\Table(name="windev_profs")
 * @ORM\Entity
 */
class WindevProfs
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
     * @ORM\Column(name="PECLEUNIK", type="decimal", precision=38, scale=0, nullable=false)
     */
    private $pecleunik;

    /**
     * @var string|null
     *
     * @ORM\Column(name="COMMENT", type="string", length=726, nullable=true)
     */
    private $comment;

    /**
     * @var string|null
     *
     * @ORM\Column(name="CODE_PROF", type="decimal", precision=38, scale=0, nullable=true)
     */
    private $codeProf;

    /**
     * @var string
     *
     * @ORM\Column(name="CONTRAT", type="decimal", precision=38, scale=0, nullable=false)
     */
    private $contrat;

    /**
     * @var string|null
     *
     * @ORM\Column(name="NBHEURE", type="decimal", precision=38, scale=0, nullable=true)
     */
    private $nbheure;

    /**
     * @var string|null
     *
     * @ORM\Column(name="NBHEURE_2", type="decimal", precision=38, scale=0, nullable=true)
     */
    private $nbheure2;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="NBHEURE_3", type="boolean", nullable=true)
     */
    private $nbheure3;

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

    public function getPecleunik(): ?string
    {
        return $this->pecleunik;
    }

    public function setPecleunik(string $pecleunik): self
    {
        $this->pecleunik = $pecleunik;

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

    public function getCodeProf(): ?string
    {
        return $this->codeProf;
    }

    public function setCodeProf(?string $codeProf): self
    {
        $this->codeProf = $codeProf;

        return $this;
    }

    public function getContrat(): ?string
    {
        return $this->contrat;
    }

    public function setContrat(string $contrat): self
    {
        $this->contrat = $contrat;

        return $this;
    }

    public function getNbheure(): ?string
    {
        return $this->nbheure;
    }

    public function setNbheure(?string $nbheure): self
    {
        $this->nbheure = $nbheure;

        return $this;
    }

    public function getNbheure2(): ?string
    {
        return $this->nbheure2;
    }

    public function setNbheure2(?string $nbheure2): self
    {
        $this->nbheure2 = $nbheure2;

        return $this;
    }

    public function getNbheure3(): ?bool
    {
        return $this->nbheure3;
    }

    public function setNbheure3(?bool $nbheure3): self
    {
        $this->nbheure3 = $nbheure3;

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

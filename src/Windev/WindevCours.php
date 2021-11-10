<?php

namespace App\Windev;

use Doctrine\ORM\Mapping as ORM;

/**
 * WindevCours
 *
 * @ORM\Table(name="windev_cours")
 * @ORM\Entity
 */
class WindevCours
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
     * @ORM\Column(name="PRCLEUNIK", type="decimal", precision=38, scale=0, nullable=false)
     */
    private $prcleunik;

    /**
     * @var string
     *
     * @ORM\Column(name="ACCLEUNIK", type="decimal", precision=38, scale=0, nullable=false)
     */
    private $accleunik;

    /**
     * @var string
     *
     * @ORM\Column(name="CYCLEUNIK", type="decimal", precision=38, scale=0, nullable=false)
     */
    private $cycleunik;

    /**
     * @var string
     *
     * @ORM\Column(name="NICLEUNIK", type="decimal", precision=38, scale=0, nullable=false)
     */
    private $nicleunik;

    /**
     * @var bool
     *
     * @ORM\Column(name="RUCLEUNIK", type="boolean", nullable=false)
     */
    private $rucleunik;

    /**
     * @var string
     *
     * @ORM\Column(name="SACLEUNIK", type="decimal", precision=38, scale=0, nullable=false)
     */
    private $sacleunik;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="COMMENT", type="boolean", nullable=true)
     */
    private $comment;

    /**
     * @var string
     *
     * @ORM\Column(name="JOUR", type="decimal", precision=38, scale=0, nullable=false)
     */
    private $jour;

    /**
     * @var string|null
     *
     * @ORM\Column(name="HEUREDEB", type="decimal", precision=38, scale=0, nullable=true)
     */
    private $heuredeb;

    /**
     * @var string|null
     *
     * @ORM\Column(name="HEUREFIN", type="decimal", precision=38, scale=0, nullable=true)
     */
    private $heurefin;

    /**
     * @var string|null
     *
     * @ORM\Column(name="DUREE", type="decimal", precision=38, scale=0, nullable=true)
     */
    private $duree;

    /**
     * @var bool
     *
     * @ORM\Column(name="SUSPENDU", type="boolean", nullable=false)
     */
    private $suspendu;

    /**
     * @var bool
     *
     * @ORM\Column(name="PERIODICITE", type="boolean", nullable=false)
     */
    private $periodicite;

    /**
     * @var string
     *
     * @ORM\Column(name="EFFECTIFMAX", type="decimal", precision=38, scale=0, nullable=false)
     */
    private $effectifmax;

    /**
     * @var string
     *
     * @ORM\Column(name="MODE", type="decimal", precision=38, scale=0, nullable=false)
     */
    private $mode;

    /**
     * @var string|null
     *
     * @ORM\Column(name="COLONNE", type="decimal", precision=38, scale=0, nullable=true)
     */
    private $colonne;

    /**
     * @var bool
     *
     * @ORM\Column(name="AVerifier", type="boolean", nullable=false)
     */
    private $averifier;

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

    public function getPrcleunik(): ?string
    {
        return $this->prcleunik;
    }

    public function setPrcleunik(string $prcleunik): self
    {
        $this->prcleunik = $prcleunik;

        return $this;
    }

    public function getAccleunik(): ?string
    {
        return $this->accleunik;
    }

    public function setAccleunik(string $accleunik): self
    {
        $this->accleunik = $accleunik;

        return $this;
    }

    public function getCycleunik(): ?string
    {
        return $this->cycleunik;
    }

    public function setCycleunik(string $cycleunik): self
    {
        $this->cycleunik = $cycleunik;

        return $this;
    }

    public function getNicleunik(): ?string
    {
        return $this->nicleunik;
    }

    public function setNicleunik(string $nicleunik): self
    {
        $this->nicleunik = $nicleunik;

        return $this;
    }

    public function getRucleunik(): ?bool
    {
        return $this->rucleunik;
    }

    public function setRucleunik(bool $rucleunik): self
    {
        $this->rucleunik = $rucleunik;

        return $this;
    }

    public function getSacleunik(): ?string
    {
        return $this->sacleunik;
    }

    public function setSacleunik(string $sacleunik): self
    {
        $this->sacleunik = $sacleunik;

        return $this;
    }

    public function getComment(): ?bool
    {
        return $this->comment;
    }

    public function setComment(?bool $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getJour(): ?string
    {
        return $this->jour;
    }

    public function setJour(string $jour): self
    {
        $this->jour = $jour;

        return $this;
    }

    public function getHeuredeb(): ?string
    {
        return $this->heuredeb;
    }

    public function setHeuredeb(?string $heuredeb): self
    {
        $this->heuredeb = $heuredeb;

        return $this;
    }

    public function getHeurefin(): ?string
    {
        return $this->heurefin;
    }

    public function setHeurefin(?string $heurefin): self
    {
        $this->heurefin = $heurefin;

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

    public function getSuspendu(): ?bool
    {
        return $this->suspendu;
    }

    public function setSuspendu(bool $suspendu): self
    {
        $this->suspendu = $suspendu;

        return $this;
    }

    public function getPeriodicite(): ?bool
    {
        return $this->periodicite;
    }

    public function setPeriodicite(bool $periodicite): self
    {
        $this->periodicite = $periodicite;

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

    public function getMode(): ?string
    {
        return $this->mode;
    }

    public function setMode(string $mode): self
    {
        $this->mode = $mode;

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

    public function getAverifier(): ?bool
    {
        return $this->averifier;
    }

    public function setAverifier(bool $averifier): self
    {
        $this->averifier = $averifier;

        return $this;
    }


}

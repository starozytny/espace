<?php

namespace App\Windev;

use Doctrine\ORM\Mapping as ORM;

/**
 * WindevEmpltps
 *
 * @ORM\Table(name="windev_empltps")
 * @ORM\Entity
 */
class WindevEmpltps
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
     * @ORM\Column(name="PRCLEUNIK", type="decimal", precision=38, scale=0, nullable=false)
     */
    private $prcleunik;

    /**
     * @var string
     *
     * @ORM\Column(name="CECLEUNIK", type="decimal", precision=38, scale=0, nullable=false)
     */
    private $cecleunik;

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
     * @ORM\Column(name="NBHEURE", type="decimal", precision=38, scale=0, nullable=false)
     */
    private $nbheure;

    /**
     * @var string
     *
     * @ORM\Column(name="JOUR", type="decimal", precision=38, scale=0, nullable=false)
     */
    private $jour;

    /**
     * @var string
     *
     * @ORM\Column(name="HEUREDEB", type="decimal", precision=38, scale=0, nullable=false)
     */
    private $heuredeb;

    /**
     * @var string|null
     *
     * @ORM\Column(name="HEUREDEB_2", type="decimal", precision=38, scale=0, nullable=true)
     */
    private $heuredeb2;

    /**
     * @var string|null
     *
     * @ORM\Column(name="HEUREDEB_3", type="decimal", precision=38, scale=0, nullable=true)
     */
    private $heuredeb3;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="HEUREDEB_4", type="boolean", nullable=true)
     */
    private $heuredeb4;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="HEUREDEB_5", type="boolean", nullable=true)
     */
    private $heuredeb5;

    /**
     * @var string|null
     *
     * @ORM\Column(name="HEUREFIN", type="decimal", precision=38, scale=0, nullable=true)
     */
    private $heurefin;

    /**
     * @var string|null
     *
     * @ORM\Column(name="HEUREFIN_2", type="decimal", precision=38, scale=0, nullable=true)
     */
    private $heurefin2;

    /**
     * @var string|null
     *
     * @ORM\Column(name="HEUREFIN_3", type="decimal", precision=38, scale=0, nullable=true)
     */
    private $heurefin3;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="HEUREFIN_4", type="boolean", nullable=true)
     */
    private $heurefin4;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="HEUREFIN_5", type="boolean", nullable=true)
     */
    private $heurefin5;

    /**
     * @var bool
     *
     * @ORM\Column(name="PREVISIONNEL", type="boolean", nullable=false)
     */
    private $previsionnel;

    /**
     * @var string
     *
     * @ORM\Column(name="SACLEUNIK", type="decimal", precision=38, scale=0, nullable=false)
     */
    private $sacleunik;

    /**
     * @var string
     *
     * @ORM\Column(name="SACLEUNIK_2", type="decimal", precision=38, scale=0, nullable=false)
     */
    private $sacleunik2;

    /**
     * @var string
     *
     * @ORM\Column(name="SACLEUNIK_3", type="decimal", precision=38, scale=0, nullable=false)
     */
    private $sacleunik3;

    /**
     * @var bool
     *
     * @ORM\Column(name="SACLEUNIK_4", type="boolean", nullable=false)
     */
    private $sacleunik4;

    /**
     * @var bool
     *
     * @ORM\Column(name="SACLEUNIK_5", type="boolean", nullable=false)
     */
    private $sacleunik5;

    /**
     * @var string
     *
     * @ORM\Column(name="NICLEUNIK", type="decimal", precision=38, scale=0, nullable=false)
     */
    private $nicleunik;

    /**
     * @var string
     *
     * @ORM\Column(name="PERIODICITE", type="decimal", precision=38, scale=0, nullable=false)
     */
    private $periodicite;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCecleunik(): ?string
    {
        return $this->cecleunik;
    }

    public function setCecleunik(string $cecleunik): self
    {
        $this->cecleunik = $cecleunik;

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

    public function getNbheure(): ?string
    {
        return $this->nbheure;
    }

    public function setNbheure(string $nbheure): self
    {
        $this->nbheure = $nbheure;

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

    public function setHeuredeb(string $heuredeb): self
    {
        $this->heuredeb = $heuredeb;

        return $this;
    }

    public function getHeuredeb2(): ?string
    {
        return $this->heuredeb2;
    }

    public function setHeuredeb2(?string $heuredeb2): self
    {
        $this->heuredeb2 = $heuredeb2;

        return $this;
    }

    public function getHeuredeb3(): ?string
    {
        return $this->heuredeb3;
    }

    public function setHeuredeb3(?string $heuredeb3): self
    {
        $this->heuredeb3 = $heuredeb3;

        return $this;
    }

    public function getHeuredeb4(): ?bool
    {
        return $this->heuredeb4;
    }

    public function setHeuredeb4(?bool $heuredeb4): self
    {
        $this->heuredeb4 = $heuredeb4;

        return $this;
    }

    public function getHeuredeb5(): ?bool
    {
        return $this->heuredeb5;
    }

    public function setHeuredeb5(?bool $heuredeb5): self
    {
        $this->heuredeb5 = $heuredeb5;

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

    public function getHeurefin2(): ?string
    {
        return $this->heurefin2;
    }

    public function setHeurefin2(?string $heurefin2): self
    {
        $this->heurefin2 = $heurefin2;

        return $this;
    }

    public function getHeurefin3(): ?string
    {
        return $this->heurefin3;
    }

    public function setHeurefin3(?string $heurefin3): self
    {
        $this->heurefin3 = $heurefin3;

        return $this;
    }

    public function getHeurefin4(): ?bool
    {
        return $this->heurefin4;
    }

    public function setHeurefin4(?bool $heurefin4): self
    {
        $this->heurefin4 = $heurefin4;

        return $this;
    }

    public function getHeurefin5(): ?bool
    {
        return $this->heurefin5;
    }

    public function setHeurefin5(?bool $heurefin5): self
    {
        $this->heurefin5 = $heurefin5;

        return $this;
    }

    public function getPrevisionnel(): ?bool
    {
        return $this->previsionnel;
    }

    public function setPrevisionnel(bool $previsionnel): self
    {
        $this->previsionnel = $previsionnel;

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

    public function getSacleunik2(): ?string
    {
        return $this->sacleunik2;
    }

    public function setSacleunik2(string $sacleunik2): self
    {
        $this->sacleunik2 = $sacleunik2;

        return $this;
    }

    public function getSacleunik3(): ?string
    {
        return $this->sacleunik3;
    }

    public function setSacleunik3(string $sacleunik3): self
    {
        $this->sacleunik3 = $sacleunik3;

        return $this;
    }

    public function getSacleunik4(): ?bool
    {
        return $this->sacleunik4;
    }

    public function setSacleunik4(bool $sacleunik4): self
    {
        $this->sacleunik4 = $sacleunik4;

        return $this;
    }

    public function getSacleunik5(): ?bool
    {
        return $this->sacleunik5;
    }

    public function setSacleunik5(bool $sacleunik5): self
    {
        $this->sacleunik5 = $sacleunik5;

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

    public function getPeriodicite(): ?string
    {
        return $this->periodicite;
    }

    public function setPeriodicite(string $periodicite): self
    {
        $this->periodicite = $periodicite;

        return $this;
    }


}

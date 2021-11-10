<?php

namespace App\Windev;

use Doctrine\ORM\Mapping as ORM;

/**
 * WindevAdhact
 *
 * @ORM\Table(name="windev_adhact")
 * @ORM\Entity
 */
class WindevAdhact
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
     * @ORM\Column(name="ADCLEUNIK", type="decimal", precision=38, scale=0, nullable=false)
     */
    private $adcleunik;

    /**
     * @var string
     *
     * @ORM\Column(name="NICLEUNIK", type="decimal", precision=38, scale=0, nullable=false)
     */
    private $nicleunik;

    /**
     * @var string
     *
     * @ORM\Column(name="COCLEUNIK", type="decimal", precision=38, scale=0, nullable=false)
     */
    private $cocleunik;

    /**
     * @var string
     *
     * @ORM\Column(name="DATE", type="decimal", precision=38, scale=0, nullable=false)
     */
    private $date;

    /**
     * @var bool
     *
     * @ORM\Column(name="SUSPENDU", type="boolean", nullable=false)
     */
    private $suspendu;

    /**
     * @var string
     *
     * @ORM\Column(name="LIGNE", type="decimal", precision=38, scale=0, nullable=false)
     */
    private $ligne;

    /**
     * @var bool
     *
     * @ORM\Column(name="GRATUIT", type="boolean", nullable=false)
     */
    private $gratuit;

    /**
     * @var string
     *
     * @ORM\Column(name="NOTARIF", type="decimal", precision=38, scale=0, nullable=false)
     */
    private $notarif;

    /**
     * @var string
     *
     * @ORM\Column(name="RENOUVELLEMENT", type="string", length=5, nullable=false)
     */
    private $renouvellement;

    /**
     * @var string|null
     *
     * @ORM\Column(name="SUSPENDU_LE", type="decimal", precision=38, scale=0, nullable=true)
     */
    private $suspenduLe;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAdcleunik(): ?string
    {
        return $this->adcleunik;
    }

    public function setAdcleunik(string $adcleunik): self
    {
        $this->adcleunik = $adcleunik;

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

    public function getCocleunik(): ?string
    {
        return $this->cocleunik;
    }

    public function setCocleunik(string $cocleunik): self
    {
        $this->cocleunik = $cocleunik;

        return $this;
    }

    public function getDate(): ?string
    {
        return $this->date;
    }

    public function setDate(string $date): self
    {
        $this->date = $date;

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

    public function getLigne(): ?string
    {
        return $this->ligne;
    }

    public function setLigne(string $ligne): self
    {
        $this->ligne = $ligne;

        return $this;
    }

    public function getGratuit(): ?bool
    {
        return $this->gratuit;
    }

    public function setGratuit(bool $gratuit): self
    {
        $this->gratuit = $gratuit;

        return $this;
    }

    public function getNotarif(): ?string
    {
        return $this->notarif;
    }

    public function setNotarif(string $notarif): self
    {
        $this->notarif = $notarif;

        return $this;
    }

    public function getRenouvellement(): ?string
    {
        return $this->renouvellement;
    }

    public function setRenouvellement(string $renouvellement): self
    {
        $this->renouvellement = $renouvellement;

        return $this;
    }

    public function getSuspenduLe(): ?string
    {
        return $this->suspenduLe;
    }

    public function setSuspenduLe(?string $suspenduLe): self
    {
        $this->suspenduLe = $suspenduLe;

        return $this;
    }


}

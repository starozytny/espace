<?php

namespace App\Entity\Cite;

use App\Repository\Cite\CiResponsableRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CiResponsableRepository::class)
 */
class CiResponsable
{
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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $civility;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $adr;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $complement;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $zipcode;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $phone1;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $infoPhone1;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $phone2;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $infoPhone2;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $phone3;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $infoPhone3;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $email2;

    /**
     * @ORM\Column(type="boolean")
     */
    private $fullAncien = false;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $docImpot;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $docDomicile;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $docEtudiant;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $docAttestation;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $commentPay;

    /**
     * @ORM\Column(type="integer")
     */
    private $nbEleves = 0;

    /**
     * @ORM\OneToMany(targetEntity=CiEleve::class, mappedBy="responsable", orphanRemoval=true)
     */
    private $eleves;

    public function __construct()
    {
        $this->eleves = new ArrayCollection();
    }

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

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(?string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getCivility(): ?string
    {
        return $this->civility;
    }

    public function setCivility(string $civility): self
    {
        $this->civility = $civility;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getAdr(): ?string
    {
        return $this->adr;
    }

    public function setAdr(?string $adr): self
    {
        $this->adr = $adr;

        return $this;
    }

    public function getComplement(): ?string
    {
        return $this->complement;
    }

    public function setComplement(?string $complement): self
    {
        $this->complement = $complement;

        return $this;
    }

    public function getZipcode(): ?int
    {
        return $this->zipcode;
    }

    public function setZipcode(?int $zipcode): self
    {
        $this->zipcode = $zipcode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getPhone1(): ?string
    {
        return $this->phone1;
    }

    public function setPhone1(?string $phone1): self
    {
        $this->phone1 = $phone1;

        return $this;
    }

    public function getInfoPhone1(): ?string
    {
        return $this->infoPhone1;
    }

    public function setInfoPhone1(?string $infoPhone1): self
    {
        $this->infoPhone1 = $infoPhone1;

        return $this;
    }

    public function getPhone2(): ?string
    {
        return $this->phone2;
    }

    public function setPhone2(?string $phone2): self
    {
        $this->phone2 = $phone2;

        return $this;
    }

    public function getInfoPhone2(): ?string
    {
        return $this->infoPhone2;
    }

    public function setInfoPhone2(?string $infoPhone2): self
    {
        $this->infoPhone2 = $infoPhone2;

        return $this;
    }

    public function getPhone3(): ?string
    {
        return $this->phone3;
    }

    public function setPhone3(?string $phone3): self
    {
        $this->phone3 = $phone3;

        return $this;
    }

    public function getInfoPhone3(): ?string
    {
        return $this->infoPhone3;
    }

    public function setInfoPhone3(?string $infoPhone3): self
    {
        $this->infoPhone3 = $infoPhone3;

        return $this;
    }

    public function getEmail2(): ?string
    {
        return $this->email2;
    }

    public function setEmail2(?string $email2): self
    {
        $this->email2 = $email2;

        return $this;
    }

    public function getFullAncien(): ?bool
    {
        return $this->fullAncien;
    }

    public function setFullAncien(bool $fullAncien): self
    {
        $this->fullAncien = $fullAncien;

        return $this;
    }

    public function getDocImpot(): ?string
    {
        return $this->docImpot;
    }

    public function setDocImpot(?string $docImpot): self
    {
        $this->docImpot = $docImpot;

        return $this;
    }

    public function getDocDomicile(): ?string
    {
        return $this->docDomicile;
    }

    public function setDocDomicile(?string $docDomicile): self
    {
        $this->docDomicile = $docDomicile;

        return $this;
    }

    public function getDocEtudiant(): ?string
    {
        return $this->docEtudiant;
    }

    public function setDocEtudiant(?string $docEtudiant): self
    {
        $this->docEtudiant = $docEtudiant;

        return $this;
    }

    public function getDocAttestation(): ?string
    {
        return $this->docAttestation;
    }

    public function setDocAttestation(?string $docAttestation): self
    {
        $this->docAttestation = $docAttestation;

        return $this;
    }

    public function getCommentPay(): ?string
    {
        return $this->commentPay;
    }

    public function setCommentPay(?string $commentPay): self
    {
        $this->commentPay = $commentPay;

        return $this;
    }

    public function getNbEleves(): ?int
    {
        return $this->nbEleves;
    }

    public function setNbEleves(int $nbEleves): self
    {
        $this->nbEleves = $nbEleves;

        return $this;
    }

    /**
     * @return Collection|CiEleve[]
     */
    public function getEleves(): Collection
    {
        return $this->eleves;
    }

    public function addElefe(CiEleve $elefe): self
    {
        if (!$this->eleves->contains($elefe)) {
            $this->eleves[] = $elefe;
            $elefe->setResponsable($this);
        }

        return $this;
    }

    public function removeElefe(CiEleve $elefe): self
    {
        if ($this->eleves->removeElement($elefe)) {
            // set the owning side to null (unless already changed)
            if ($elefe->getResponsable() === $this) {
                $elefe->setResponsable(null);
            }
        }

        return $this;
    }
}

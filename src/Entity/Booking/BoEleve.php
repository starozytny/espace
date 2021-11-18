<?php

namespace App\Entity\Booking;

use App\Repository\Booking\BoEleveRepository;
use Carbon\Carbon;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;

/**
 * @ORM\Entity(repositoryClass=BoEleveRepository::class)
 */
class BoEleve
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"visitor:read", "visitor:edit", "link:read", "admin:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=20)
     * @Groups({"visitor:read", "visitor:edit", "link:read", "admin:read"})
     */
    private $civility;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"visitor:read", "visitor:edit", "link:read", "admin:read"})
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"visitor:read", "visitor:edit", "link:read", "admin:read"})
     */
    private $lastname;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"visitor:read", "visitor:edit", "link:read", "admin:read"})
     */
    private $referral;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"visitor:read", "visitor:edit", "link:read", "admin:read"})
     */
    private $email;

    /**
     * @ORM\Column(type="date")
     */
    private $birthday;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     * @Groups({"visitor:read", "visitor:edit", "link:read", "admin:read"})
     */
    private $phoneMobile;

    /**
     * @ORM\ManyToOne(targetEntity=BoResponsable::class, fetch="EAGER", inversedBy="eleves")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"admin:read"})
     */
    private $responsable;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"visitor:read", "visitor:edit", "admin:read"})
     */
    private $isRegistered;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"visitor:read",  "visitor:edit"})
     */
    private $isResponsable;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"visitor:read", "visitor:edit", "admin:read"})
     */
    private $isAncien;

    public function __construct()
    {
        $this->isRegistered = true;
        $this->isAncien = false;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
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

    public function getPhoneMobile(): ?string
    {
        return $this->phoneMobile;
    }

    public function setPhoneMobile(?string $phoneMobile): self
    {
        $this->phoneMobile = $phoneMobile;

        return $this;
    }

    public function getBirthday(): ?\DateTimeInterface
    {
        return $this->birthday;
    }

    public function setBirthday(\DateTimeInterface $birthday): self
    {
        $this->birthday = $birthday;

        return $this;
    }

    /**
     * @return string
     * @SerializedName("birthday")
     * @Groups({"visitor:read",  "visitor:edit"})
     */
    public function getBirthdayJavascript(): ?string
    {
        if($this->getBirthday()){
            return date_format($this->getBirthday(), "Y/m/d H:i");
        }

        return null;
    }

    /**
     * @return string
     * @Groups({"link:read", "admin:read"})
     */
    public function getBirthdayString(): ?string
    {
        if($this->getBirthday()){
            return date_format($this->getBirthday(), "d/m/Y");
        }

        return null;
    }

    /**
     * Get age of eleve
     *
     * @return int
     * @Groups({"visitor:read", "visitor:edit", "link:read", "admin:read"})
     */
    public function getAge(): int
    {
        if($this->getBirthday()){
            return Carbon::createFromTimestamp($this->getBirthday()->getTimestamp())->age;
        }
        return 0;
    }

    public function getResponsable(): ?BoResponsable
    {
        return $this->responsable;
    }

    public function setResponsable(?BoResponsable $responsable): self
    {
        $this->responsable = $responsable;

        return $this;
    }

    public function getReferral(): ?int
    {
        return $this->referral;
    }

    public function setReferral(?int $referral): self
    {
        $this->referral = $referral;

        return $this;
    }

    public function getIsRegistered(): ?bool
    {
        return $this->isRegistered;
    }

    public function setIsRegistered(bool $isRegistered): self
    {
        $this->isRegistered = $isRegistered;

        return $this;
    }

    public function getIsResponsable(): ?bool
    {
        return $this->isResponsable;
    }

    public function setIsResponsable(bool $isResponsable): self
    {
        $this->isResponsable = $isResponsable;

        return $this;
    }

    public function getIsAncien(): ?bool
    {
        return $this->isAncien;
    }

    public function setIsAncien(bool $isAncien): self
    {
        $this->isAncien = $isAncien;

        return $this;
    }
}

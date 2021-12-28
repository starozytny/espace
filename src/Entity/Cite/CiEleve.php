<?php

namespace App\Entity\Cite;

use App\Entity\DataEntity;
use App\Entity\Prev\PrGroup;
use App\Repository\Cite\CiEleveRepository;
use Carbon\Carbon;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=CiEleveRepository::class)
 */
class CiEleve extends DataEntity
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"group:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $oldId;

    /**
     * @ORM\Column(type="integer")
     */
    private $numAdh;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isAncien = 0;

    /**
     * @ORM\Column(type="integer")
     */
    private $canPay = 0;

    /**
     * @ORM\Column(type="boolean")
     */
    private $haveFm = false;

    /**
     * @ORM\Column(type="boolean")
     */
    private $dispenseFm = false;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"group:read"})
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"group:read"})
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
     * @ORM\Column(type="date", nullable=true)
     */
    private $birthday;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $phoneDomicile;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $phoneMobile;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $adr;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $zipcode;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $city;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $registeredAt;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $renew;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $somme;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateSomme;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $typePaiement;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $modePaiement;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $cout;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $pass;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $cotisations;

    /**
     * @ORM\Column(type="boolean")
     */
    private $authSpec = false;

    /**
     * @ORM\Column(type="boolean")
     */
    private $authConc = false;

    /**
     * @ORM\Column(type="boolean")
     */
    private $authProj = false;

    /**
     * @ORM\Column(type="boolean")
     */
    private $authSupp = false;

    /**
     * @ORM\Column(type="boolean")
     */
    private $answerAuth = false;

    /**
     * @ORM\ManyToOne(targetEntity=CiResponsable::class, inversedBy="eleves")
     * @ORM\JoinColumn(nullable=false)
     */
    private $responsable;

    /**
     * @ORM\OneToMany(targetEntity=CiGroup::class, mappedBy="eleve")
     */
    private $groups;

    /**
     * @ORM\OneToMany(targetEntity=CiAssignation::class, mappedBy="eleve")
     */
    private $assignations;

    /**
     * @ORM\OneToMany(targetEntity=PrGroup::class, mappedBy="eleve")
     */
    private $prGroups;

    public function __construct()
    {
        $this->groups = new ArrayCollection();
        $this->assignations = new ArrayCollection();
        $this->prGroups = new ArrayCollection();
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

    public function getNumAdh(): ?int
    {
        return $this->numAdh;
    }

    public function setNumAdh(int $numAdh): self
    {
        $this->numAdh = $numAdh;

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

    public function getCanPay(): ?int
    {
        return $this->canPay;
    }

    public function setCanPay(int $canPay): self
    {
        $this->canPay = $canPay;

        return $this;
    }

    public function getHaveFm(): ?bool
    {
        return $this->haveFm;
    }

    public function setHaveFm(bool $haveFm): self
    {
        $this->haveFm = $haveFm;

        return $this;
    }

    public function getDispenseFm(): ?bool
    {
        return $this->dispenseFm;
    }

    public function setDispenseFm(bool $dispenseFm): self
    {
        $this->dispenseFm = $dispenseFm;

        return $this;
    }

    /**
     * @return string
     */
    public function getFullname(): string
    {
        return $this->getFullNameString($this->lastname, $this->firstname);
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

    /**
     * Get age of eleve
     *
     * @return int
     * @Groups({"group:read"})
     */
    public function getAge(): int
    {
        if($this->getBirthday()){
            return Carbon::createFromTimestamp($this->getBirthday()->getTimestamp())->age;
        }
        return 0;
    }

    public function getBirthday(): ?\DateTimeInterface
    {
        return $this->birthday;
    }

    public function setBirthday(?\DateTimeInterface $birthday): self
    {
        $this->birthday = $birthday;

        return $this;
    }

    public function getPhoneDomicile(): ?string
    {
        return $this->phoneDomicile;
    }

    public function setPhoneDomicile(?string $phoneDomicile): self
    {
        $this->phoneDomicile = $phoneDomicile;

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

    public function getAdr(): ?string
    {
        return $this->adr;
    }

    public function setAdr(?string $adr): self
    {
        $this->adr = $adr;

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

    public function getRegisteredAt(): ?\DateTimeInterface
    {
        return $this->registeredAt;
    }

    public function setRegisteredAt(?\DateTimeInterface $registeredAt): self
    {
        $this->registeredAt = $registeredAt;

        return $this;
    }

    public function getRenew(): ?string
    {
        return $this->renew;
    }

    public function setRenew(?string $renew): self
    {
        $this->renew = $renew;

        return $this;
    }

    public function getSomme(): ?float
    {
        return $this->somme;
    }

    public function setSomme(?float $somme): self
    {
        $this->somme = $somme;

        return $this;
    }

    public function getDateSomme(): ?\DateTimeInterface
    {
        return $this->dateSomme;
    }

    public function setDateSomme(?\DateTimeInterface $dateSomme): self
    {
        $this->dateSomme = $dateSomme;

        return $this;
    }

    public function getTypePaiement(): ?int
    {
        return $this->typePaiement;
    }

    public function setTypePaiement(?int $typePaiement): self
    {
        $this->typePaiement = $typePaiement;

        return $this;
    }

    public function getModePaiement(): ?int
    {
        return $this->modePaiement;
    }

    public function setModePaiement(?int $modePaiement): self
    {
        $this->modePaiement = $modePaiement;

        return $this;
    }

    public function getCout(): ?float
    {
        return $this->cout;
    }

    public function setCout(?float $cout): self
    {
        $this->cout = $cout;

        return $this;
    }

    public function getPass(): ?float
    {
        return $this->pass;
    }

    public function setPass(?float $pass): self
    {
        $this->pass = $pass;

        return $this;
    }

    public function getCotisations(): ?float
    {
        return $this->cotisations;
    }

    public function setCotisations(?float $cotisations): self
    {
        $this->cotisations = $cotisations;

        return $this;
    }

    public function getAuthSpec(): ?bool
    {
        return $this->authSpec;
    }

    public function setAuthSpec(bool $authSpec): self
    {
        $this->authSpec = $authSpec;

        return $this;
    }

    public function getAuthConc(): ?bool
    {
        return $this->authConc;
    }

    public function setAuthConc(bool $authConc): self
    {
        $this->authConc = $authConc;

        return $this;
    }

    public function getAuthProj(): ?bool
    {
        return $this->authProj;
    }

    public function setAuthProj(bool $authProj): self
    {
        $this->authProj = $authProj;

        return $this;
    }

    public function getAuthSupp(): ?bool
    {
        return $this->authSupp;
    }

    public function setAuthSupp(bool $authSupp): self
    {
        $this->authSupp = $authSupp;

        return $this;
    }

    public function getAnswerAuth(): ?bool
    {
        return $this->answerAuth;
    }

    public function setAnswerAuth(bool $answerAuth): self
    {
        $this->answerAuth = $answerAuth;

        return $this;
    }

    public function getResponsable(): ?CiResponsable
    {
        return $this->responsable;
    }

    public function setResponsable(?CiResponsable $responsable): self
    {
        $this->responsable = $responsable;

        return $this;
    }

    /**
     * @return Collection|CiGroup[]
     */
    public function getGroups(): Collection
    {
        return $this->groups;
    }

    public function addGroup(CiGroup $group): self
    {
        if (!$this->groups->contains($group)) {
            $this->groups[] = $group;
            $group->setEleve($this);
        }

        return $this;
    }

    public function removeGroup(CiGroup $group): self
    {
        if ($this->groups->removeElement($group)) {
            // set the owning side to null (unless already changed)
            if ($group->getEleve() === $this) {
                $group->setEleve(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|CiAssignation[]
     */
    public function getAssignations(): Collection
    {
        return $this->assignations;
    }

    public function addAssignation(CiAssignation $assignation): self
    {
        if (!$this->assignations->contains($assignation)) {
            $this->assignations[] = $assignation;
            $assignation->setEleve($this);
        }

        return $this;
    }

    public function removeAssignation(CiAssignation $assignation): self
    {
        if ($this->assignations->removeElement($assignation)) {
            // set the owning side to null (unless already changed)
            if ($assignation->getEleve() === $this) {
                $assignation->setEleve(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|PrGroup[]
     */
    public function getPrGroups(): Collection
    {
        return $this->prGroups;
    }

    public function addPrGroup(PrGroup $prGroup): self
    {
        if (!$this->prGroups->contains($prGroup)) {
            $this->prGroups[] = $prGroup;
            $prGroup->setEleve($this);
        }

        return $this;
    }

    public function removePrGroup(PrGroup $prGroup): self
    {
        if ($this->prGroups->removeElement($prGroup)) {
            // set the owning side to null (unless already changed)
            if ($prGroup->getEleve() === $this) {
                $prGroup->setEleve(null);
            }
        }

        return $this;
    }
}

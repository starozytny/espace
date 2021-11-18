<?php

namespace App\Entity\Booking;

use App\Repository\Booking\BoResponsableRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=BoResponsableRepository::class)
 */
class BoResponsable
{
    const STATUS_START = 0;
    const STATUS_END = 1;
    const STATUS_WAITING = 2;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"visitor:read", "visitor:edit", "link:read", "admin:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"visitor:read", "visitor:edit", "link:read", "admin:read"})
     */
    private $status;

    /**
     * @ORM\Column(type="integer")
     */
    private $profil;

    /**
     * @ORM\Column(type="integer")
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"admin:read", "link:read", "visitor:edit"})
     */
    private $civility;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"admin:read", "link:read", "visitor:edit"})
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"admin:read", "link:read", "visitor:edit"})
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"admin:read", "link:read", "visitor:edit"})
     */
    private $email;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"admin:read", "link:read", "admin:read"})
     */
    private $referral;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updateAt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"visitor:read", "visitor:edit", "admin:read"})
     */
    private $ticket;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"visitor:read", "visitor:edit", "admin:read"})
     */
    private $barcode;

    /**
     * @ORM\Column(type="boolean")
     */
    private $inMobile;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $browser;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"visitor:edit"})
     */
    private $adr;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"visitor:edit"})
     */
    private $complement;

    /**
     * @ORM\Column(type="string", length=20)
     * @Groups({"visitor:edit"})
     */
    private $cp;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"visitor:edit"})
     */
    private $city;

    /**
     * @ORM\ManyToOne(targetEntity=BoDay::class, inversedBy="participants")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"visitor:read", "visitor:edit"})
     */
    private $day;

    /**
     * @ORM\ManyToOne(targetEntity=BoDay::class)
     */
    private $prevDay;

    /**
     * @ORM\OneToMany(targetEntity=BoEleve::class, mappedBy="responsable", fetch="EAGER", orphanRemoval=true)
     * @Groups({"link:read", "visitor:edit"})
     */
    private $eleves;

    /**
     * @ORM\ManyToOne(targetEntity=BoSlot::class, inversedBy="participants")
     * @Groups({"visitor:read", "visitor:edit"})
     */
    private $slot;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"admin:read", "link:read", "visitor:edit"})
     */
    private $email2;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"admin:read", "link:read", "visitor:edit"})
     */
    private $phone1;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"visitor:edit"})
     */
    private $infoPhone1;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"admin:read", "link:read", "visitor:edit"})
     */
    private $phone2;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"visitor:edit"})
     */
    private $infoPhone2;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"admin:read", "link:read", "visitor:edit"})
     */
    private $phone3;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"visitor:edit"})
     */
    private $infoPhone3;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"visitor:read", "visitor:edit", "link:read", "admin:read"})
     */
    private $token;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $qrcode;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $this->eleves = new ArrayCollection();
        $createAt = new \DateTime();
        $createAt->setTimezone(new \DateTimeZone('Europe/Paris'));
        $this->createAt = $createAt;
        $this->setToken(bin2hex(random_bytes(32)));
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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getAdr(): ?string
    {
        return $this->adr;
    }

    public function setAdr(string $adr): self
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

    public function getCp(): ?string
    {
        return $this->cp;
    }

    public function setCp(string $cp): self
    {
        $this->cp = $cp;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getInMobile(): ?bool
    {
        return $this->inMobile;
    }

    public function setInMobile(bool $inMobile): self
    {
        $this->inMobile = $inMobile;

        return $this;
    }

    public function getBrowser(): ?string
    {
        return $this->browser;
    }

    public function setBrowser(?string $browser): self
    {
        $this->browser = $browser;

        return $this;
    }

    public function getDay(): ?BoDay
    {
        return $this->day;
    }

    public function setDay(?BoDay $day): self
    {
        $this->day = $day;

        return $this;
    }

    public function getPrevDay(): ?BoDay
    {
        return $this->prevDay;
    }

    public function setPrevDay(?BoDay $prevDay): self
    {
        $this->prevDay = $prevDay;

        return $this;
    }

    /**
     * @return Collection|BoEleve[]
     */
    public function getEleves(): Collection
    {
        return $this->eleves;
    }

    public function addEleve(BoEleve $eleve): self
    {
        if (!$this->eleves->contains($eleve)) {
            $this->eleves[] = $eleve;
            $eleve->setResponsable($this);
        }

        return $this;
    }

    public function removeEleve(BoEleve $eleve): self
    {
        if ($this->eleves->removeElement($eleve)) {
            // set the owning side to null (unless already changed)
            if ($eleve->getResponsable() === $this) {
                $eleve->setResponsable(null);
            }
        }

        return $this;
    }

    public function getCreateAt(): ?\DateTimeInterface
    {
        return $this->createAt;
    }

    public function setCreateAt(\DateTimeInterface $createAt): self
    {
        $this->createAt = $createAt;

        return $this;
    }

    public function getUpdateAt(): ?\DateTimeInterface
    {
        return $this->updateAt;
    }

    public function setUpdateAt(\DateTimeInterface $updateAt): self
    {
        $this->updateAt = $updateAt;

        return $this;
    }
    /**
     * Return update date in string format d/m/Y H:i:s
     *
     * @return false|string|null
     */
    public function getUpdateAtPhpString(){
        if($this->updateAt == null){
            return null;
        }

        return date_format($this->updateAt, 'Y-m-d\TH:i:s');
    }

    public function getSlot(): ?BoSlot
    {
        return $this->slot;
    }

    public function setSlot(?BoSlot $slot): self
    {
        $this->slot = $slot;

        return $this;
    }

    public function getTicket(): ?string
    {
        return $this->ticket;
    }

    public function setTicket(?string $ticket): self
    {
        $this->ticket = $ticket;

        return $this;
    }

    public function getBarcode(): ?string
    {
        return $this->barcode;
    }

    public function setBarcode(?string $barcode): self
    {
        $this->barcode = $barcode;

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

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(int $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getProfil(): ?int
    {
        return $this->profil;
    }

    public function setProfil(int $profil): self
    {
        $this->profil = $profil;

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

    public function getEmail2(): ?string
    {
        return $this->email2;
    }

    public function setEmail2(?string $email2): self
    {
        $this->email2 = $email2;

        return $this;
    }

    public function getPhone1(): ?string
    {
        return $this->phone1;
    }

    public function setPhone1(string $phone1): self
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

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(string $token): self
    {
        $this->token = $token;

        return $this;
    }

    public function getQrcode(): ?string
    {
        return $this->qrcode;
    }

    public function setQrcode(?string $qrcode): self
    {
        $this->qrcode = $qrcode;

        return $this;
    }

    /**
     * @return string|null
     * @Groups({"admin:read"})
     */
    public function getFullPhone1(): ?string
    {
        if($this->phone1){
            return ($this->infoPhone1 ? $this->infoPhone1 . " : " : "") . $this->formatePhone($this->phone1);
        }

        return null;
    }

    /**
     * @return string|null
     * @Groups({"admin:read"})
     */
    public function getFullPhone2(): ?string
    {
        if($this->phone2){
            return ($this->infoPhone2 ? $this->infoPhone2 . " : " : "") . $this->formatePhone($this->phone2);
        }

        return null;
    }

    /**
     * @return string|null
     * @Groups({"admin:read"})
     */
    public function getFullPhone3(): ?string
    {
        if($this->phone3){
            return ($this->infoPhone3 ? $this->infoPhone3 . " : " : "") . $this->formatePhone($this->phone3);
        }

        return null;
    }

    /**
     * @return string
     * @Groups({"admin:read", "link:read"})
     */
    public function getFullAdr(): string
    {
        return $this->adr . ($this->complement ? ' - ' . $this->complement : '') . ', ' . $this->cp . ' ' . $this->city;
    }

    private function formatePhone($phone)
    {
        if(strlen($phone) == 10){
            $a = substr($phone, 0, 2);
            $b = substr($phone, 2, 2);
            $c = substr($phone, 4, 2);
            $d = substr($phone, 6, 2);
            $e = substr($phone, 8, 2);

            return $a . " " . $b . " " . $c . " " . $d . " " . $e;
        }

        return $phone;
    }
}

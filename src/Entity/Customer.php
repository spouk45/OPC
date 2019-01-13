<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CustomerRepository")
 */
class Customer
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $fullAdress;

    /**
     * @ORM\Column(type="string", length=15)
     * @Assert\Regex(pattern="/^((\+)33|0|0033)[1-9](\d{2}){4}$/", message="Numéro invalide")
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     *
     */
    private $phone2;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * Assert\Blank
     */
    private $mail;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $information;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\CustomerHeating", mappedBy="customer")
     */
    private $customerHeatings;

    /**
     * @ORM\Column(type="json_array", nullable=true)
     */
    private $coordGPS;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $department;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $region;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $postalCode;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private $complementAdress;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $contractDate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $anniversaryDate;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $contractFinish;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $lastMaintenanceDate;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\InterventionReport", mappedBy="customer")
     */
    private $interventionReports;

    private $plannedMaintenanceDate;

    /**
     * not mapped
     * @Assert\File(
     *     maxSize = "2M",
     *     mimeTypes = {"image/jpeg", "image/png" ,"image/gif"},
     * )
     */
    private $images;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Image", mappedBy="customer")
     */
    private $imagesLink;


    public function getFullname()
    {
        return $this->name.' '.$this->firstname;
    }

    public function makeAdress()
    {
       $this->fullAdress = $this->getComplementAdress().' '.$this->getPostalCode().' '.$this->getCity();
    }

    public function __construct()
    {
        $this->customerHeatings = new ArrayCollection();
        $this->interventionReports = new ArrayCollection();
        $this->imagesLink = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
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

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

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

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(?string $mail): self
    {
        $this->mail = $mail;

        return $this;
    }

    public function getInformation(): ?string
    {
        return $this->information;
    }

    public function setInformation(?string $information): self
    {
        $this->information = $information;

        return $this;
    }

    /**
     * @return Collection|CustomerHeating[]
     */
    public function getCustomerHeatings(): Collection
    {
        return $this->customerHeatings;
    }

    public function addCustomerHeating(CustomerHeating $customerHeating): self
    {
        if (!$this->customerHeatings->contains($customerHeating)) {
            $this->customerHeatings[] = $customerHeating;
            $customerHeating->setCustomer($this);
        }

        return $this;
    }

    public function removeCustomerHeating(CustomerHeating $customerHeating): self
    {
        if ($this->customerHeatings->contains($customerHeating)) {
            $this->customerHeatings->removeElement($customerHeating);
            // set the owning side to null (unless already changed)
            if ($customerHeating->getCustomer() === $this) {
                $customerHeating->setCustomer(null);
            }
        }

        return $this;
    }

    public function getCoordGPS()
    {
        return $this->coordGPS;
    }

    public function setCoordGPS($coordGPS): self
    {
        $this->coordGPS = $coordGPS;

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

    public function getDepartment(): ?string
    {
        return $this->department;
    }

    public function setDepartment(?string $department): self
    {
        $this->department = $department;

        return $this;
    }

    public function getPostalCode(): ?int
    {
        return $this->postalCode;
    }

    public function setPostalCode(?int $postalCode): self
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    public function getRegion(): ?string
    {
        return $this->region;
    }

    public function setRegion(?string $region): self
    {
        $this->region = $region;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getFullAdress()
    {
        return $this->fullAdress;
    }

    /**
     * @param mixed $fullAdress
     */
    public function setFullAdress($fullAdress): void
    {
        $this->fullAdress = $fullAdress;
    }

    /**
     * @return mixed
     */
    public function getComplementAdress()
    {
        return $this->complementAdress;
    }

    /**
     * @param mixed $complementAdress
     */
    public function setComplementAdress($complementAdress): void
    {
        $this->complementAdress = $complementAdress;
    }

    public function getContractDate(): ?\DateTimeInterface
    {
        return $this->contractDate;
    }

    public function setContractDate(?\DateTimeInterface $contractDate): self
    {
        $this->contractDate = $contractDate;

        return $this;
    }

    public function getAnniversaryDate(): ?\DateTimeInterface
    {
        return $this->anniversaryDate;
    }

    public function setAnniversaryDate(?\DateTimeInterface $anniversaryDate): self
    {
        $this->anniversaryDate = $anniversaryDate;

        return $this;
    }

    public function getContractFinish(): ?bool
    {
        return $this->contractFinish;
    }

    public function setContractFinish(?bool $contractFinish): self
    {
        $this->contractFinish = $contractFinish;

        return $this;
    }

    public function getLastMaintenanceDate(): ?\DateTimeInterface
    {
        return $this->lastMaintenanceDate;
    }

    public function setLastMaintenanceDate(?\DateTimeInterface $lastMaintenanceDate): self
    {
        $this->lastMaintenanceDate = $lastMaintenanceDate;

        return $this;
    }

    /**
     * @return Collection|InterventionReport[]
     */
    public function getInterventionReports(): Collection
    {
        return $this->interventionReports;
    }

    public function addInterventionReport(InterventionReport $interventionReport): self
    {
        if (!$this->interventionReports->contains($interventionReport)) {
            $this->interventionReports[] = $interventionReport;
            $interventionReport->setCustomer($this);
        }

        return $this;
    }

    public function removeInterventionReport(InterventionReport $interventionReport): self
    {
        if ($this->interventionReports->contains($interventionReport)) {
            $this->interventionReports->removeElement($interventionReport);
            // set the owning side to null (unless already changed)
            if ($interventionReport->getCustomer() === $this) {
                $interventionReport->setCustomer(null);
            }
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPlannedMaintenanceDate()
    {
        return $this->plannedMaintenanceDate;
    }

    /**
     * @param mixed $plannedMaintenanceDate
     */
    public function setPlannedMaintenanceDate($plannedMaintenanceDate): void
    {
        $this->plannedMaintenanceDate = $plannedMaintenanceDate;
    }

    /**
     * @return Collection|Image[]
     */
    public function getImagesLink(): Collection
    {
        return $this->imagesLink;
    }
    public function addImagesLink(Image $imagesLink): self
    {
        if (!$this->imagesLink->contains($imagesLink)) {
            $this->imagesLink[] = $imagesLink;
            $imagesLink->setAd($this);
        }
        return $this;
    }
    public function removeImagesLink(Image $imagesLink): self
    {
        if ($this->imagesLink->contains($imagesLink)) {
            $this->imagesLink->removeElement($imagesLink);
            // set the owning side to null (unless already changed)
            if ($imagesLink->getAd() === $this) {
                $imagesLink->setAd(null);
            }
        }
        return $this;
    }
    public function getImages(): ?array
    {
        return $this->images;
    }
    public function setImages(array $images): self
    {
        $this->images = $images;
        return $this;
    }


}

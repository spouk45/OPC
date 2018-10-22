<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
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
    private $adress;

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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $houseNumber;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $street;

    /**
     * @ORM\Column(type="string", length=80)
     */
    private $roadType;

    public function makeAdress(){
       $this->adress = $this->getHouseNumber().' '.$this->getRoadType().' '.$this->getStreet().' '.$this->getPostalCode().' '.$this->getCity();
    }

    public function __construct()
    {
        $this->customerHeatings = new ArrayCollection();
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

    public function getAdress(): ?string
    {
        return $this->adress;
    }

    public function setAdress(string $adress): self
    {
        $this->adress = $adress;

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

    public function getHouseNumber(): ?string
    {
        return $this->houseNumber;
    }

    public function setHouseNumber(?string $houseNumber): self
    {
        $this->houseNumber = $houseNumber;

        return $this;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(string $street): self
    {
        $this->street = $street;

        return $this;
    }

    public function getRoadType(): ?string
    {
        return $this->roadType;
    }

    public function setRoadType(string $roadType): self
    {
        $this->roadType = $roadType;

        return $this;
    }


}

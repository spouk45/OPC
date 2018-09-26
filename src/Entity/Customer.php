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
    private $customers;

    public function __construct()
    {
        $this->heating = new ArrayCollection();
        $this->customers = new ArrayCollection();
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

    public function setPhone2(string $phone2): self
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
     * @return Collection|Heating[]
     */
    public function getHeating(): Collection
    {
        return $this->heating;
    }

    public function addHeating(Heating $heating): self
    {
        if (!$this->heating->contains($heating)) {
            $this->heating[] = $heating;
            $heating->setCustomer($this);
        }

        return $this;
    }

    public function removeHeating(Heating $heating): self
    {
        if ($this->heating->contains($heating)) {
            $this->heating->removeElement($heating);
            // set the owning side to null (unless already changed)
            if ($heating->getCustomer() === $this) {
                $heating->setCustomer(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|CustomerHeating[]
     */
    public function getCustomers(): Collection
    {
        return $this->customers;
    }

    public function addCustomer(CustomerHeating $customer): self
    {
        if (!$this->customers->contains($customer)) {
            $this->customers[] = $customer;
            $customer->setCustomer($this);
        }

        return $this;
    }

    public function removeCustomer(CustomerHeating $customer): self
    {
        if ($this->customers->contains($customer)) {
            $this->customers->removeElement($customer);
            // set the owning side to null (unless already changed)
            if ($customer->getCustomer() === $this) {
                $customer->setCustomer(null);
            }
        }

        return $this;
    }
}

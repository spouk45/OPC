<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\HeatingRepository")
 */
class Heating
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\HeatingType", inversedBy="heatings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $heatingType;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\HeatingSource", inversedBy="heatings")
     */
    private $sourceType;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ExtractionType", inversedBy="heatings")
     */
    private $extractionType;

    /**
     * @ORM\Column(type="boolean")
     */
    private $onTheGround;


    /**
     * @ORM\OneToMany(targetEntity="App\Entity\CustomerHeating", mappedBy="heating")
     */
    private $customerHeatings;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $designation;

    public function __construct()
    {
        $this->customerHeatings = new ArrayCollection();
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
            $customerHeating->setHeating($this);
        }

        return $this;
    }

    public function removeCustomerHeating(CustomerHeating $customerHeating): self
    {
        if ($this->customerHeatings->contains($customerHeating)) {
            $this->customerHeatings->removeElement($customerHeating);
            // set the owning side to null (unless already changed)
            if ($customerHeating->getHeating() === $this) {
                $customerHeating->setHeating(null);
            }
        }

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOnTheGround(): ?bool
    {
        return $this->onTheGround;
    }

    public function setOnTheGround(bool $onTheGround): self
    {
        $this->onTheGround = $onTheGround;

        return $this;
    }

    public function getHeatingType(): ?HeatingType
    {
        return $this->heatingType;
    }

    public function setHeatingType(?HeatingType $heatingType): self
    {
        $this->heatingType = $heatingType;

        return $this;
    }

    public function getSourceType(): ?HeatingSource
    {
        return $this->sourceType;
    }

    public function setSourceType(?HeatingSource $sourceType): self
    {
        $this->sourceType = $sourceType;

        return $this;
    }

    public function getExtractionType(): ?ExtractionType
    {
        return $this->extractionType;
    }

    public function setExtractionType(?ExtractionType $extractionType): self
    {
        $this->extractionType = $extractionType;

        return $this;
    }

    public function getDesignation(): ?string
    {
        return $this->designation;
    }

    public function setDesignation(string $designation): self
    {
        $this->designation = $designation;

        return $this;
    }
}

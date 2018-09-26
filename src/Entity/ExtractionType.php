<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ExtractionTypeRepository")
 */
class ExtractionType
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Heating", mappedBy="extractionType")
     */
    private $heatings;

    public function __construct()
    {
        $this->heatings = new ArrayCollection();
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

    /**
     * @return Collection|Heating[]
     */
    public function getHeatings(): Collection
    {
        return $this->heatings;
    }

    public function addHeating(Heating $heating): self
    {
        if (!$this->heatings->contains($heating)) {
            $this->heatings[] = $heating;
            $heating->setExtractionType($this);
        }

        return $this;
    }

    public function removeHeating(Heating $heating): self
    {
        if ($this->heatings->contains($heating)) {
            $this->heatings->removeElement($heating);
            // set the owning side to null (unless already changed)
            if ($heating->getExtractionType() === $this) {
                $heating->setExtractionType(null);
            }
        }

        return $this;
    }
}

<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TypeInterventionReportRepository")
 */
class TypeInterventionReport
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
     * @ORM\OneToOne(targetEntity="App\Entity\InterventionReport", mappedBy="typeInterventionReport", cascade={"persist", "remove"})
     */
    private $interventionReport;

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

    public function getInterventionReport(): ?InterventionReport
    {
        return $this->interventionReport;
    }

    public function setInterventionReport(InterventionReport $interventionReport): self
    {
        $this->interventionReport = $interventionReport;

        // set the owning side of the relation if necessary
        if ($this !== $interventionReport->getTypeInterventionReport()) {
            $interventionReport->setTypeInterventionReport($this);
        }

        return $this;
    }
}

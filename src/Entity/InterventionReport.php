<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\InterventionReportRepository")
 */
class InterventionReport
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\TypeInterventionReport", inversedBy="interventionReport")
     * @ORM\JoinColumn(nullable=false)
     */
    private $typeInterventionReport;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $plannedDate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $realisedDate;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $comment;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Customer", inversedBy="interventionReports")
     * @ORM\JoinColumn(nullable=false)
     */
    private $customer;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTypeInterventionReport(): ?typeInterventionReport
    {
        return $this->typeInterventionReport;
    }

    public function setTypeInterventionReport(typeInterventionReport $typeInterventionReport): self
    {
        $this->typeInterventionReport = $typeInterventionReport;

        return $this;
    }

    public function getPlannedDate(): ?\DateTimeInterface
    {
        return $this->plannedDate;
    }

    public function setPlannedDate(?\DateTimeInterface $plannedDate): self
    {
        $this->plannedDate = $plannedDate;

        return $this;
    }

    public function getRealisedDate(): ?\DateTimeInterface
    {
        return $this->realisedDate;
    }

    public function setRealisedDate(?\DateTimeInterface $realisedDate): self
    {
        $this->realisedDate = $realisedDate;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): self
    {
        $this->customer = $customer;

        return $this;
    }
}

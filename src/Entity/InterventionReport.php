<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\InterventionReportRepository")
 */
class InterventionReport
{
    const CHOICE = [
        'Maintenance' => 'Maintenance',
        'Dépannage' => 'Dépannage',
        'Autre' => 'Autre',
        ];
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;


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

    /**
     * @ORM\Column(type="string",nullable=false)
     */
    private $typeInterventionReport = 0;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getTypeInterventionReport(): ?String
    {
        return $this->typeInterventionReport;
    }

    public function setTypeInterventionReport(string $typeInterventionReport): self
    {
        $this->typeInterventionReport = $typeInterventionReport;

        return $this;
    }
}

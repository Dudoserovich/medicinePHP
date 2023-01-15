<?php

namespace App\Entity;

use App\Repository\VisitRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VisitRepository::class)]
class Visit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $symptoms = null;

    #[ORM\Column(length: 100)]
    private ?string $diagnosis = null;

    #[ORM\ManyToOne(targetEntity: Patient::class)]
    #[ORM\JoinColumn(nullable: true, onDelete: "SET NULL")]
    private ?Patient $patient;

    #[ORM\ManyToOne(targetEntity: DoctorSchedule::class)]
    #[ORM\JoinColumn(nullable: true, onDelete: "SET NULL")]
    private ?DoctorSchedule $doctorSchedule;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSymptoms(): ?string
    {
        return $this->symptoms;
    }

    public function setSymptoms(string $symptoms): self
    {
        $this->symptoms = $symptoms;

        return $this;
    }

    public function getDiagnosis(): ?string
    {
        return $this->diagnosis;
    }

    public function setDiagnosis(string $diagnosis): self
    {
        $this->diagnosis = $diagnosis;

        return $this;
    }

    public function getPatient(): ?Patient
    {
        return $this->patient;
    }

    public function setPatient(?Patient $patient): self
    {
        $this->patient = $patient;

        return $this;
    }

    public function getDoctorSchedule(): ?DoctorSchedule
    {
        return $this->doctorSchedule;
    }

    public function setDoctorSchedule(?DoctorSchedule $doctorSchedule): self
    {
        $this->doctorSchedule = $doctorSchedule;

        return $this;
    }
}

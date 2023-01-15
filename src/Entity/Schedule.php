<?php

namespace App\Entity;

use App\Repository\ScheduleRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ScheduleRepository::class)]
class Schedule
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $s_date = null;

    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $starting = null;

    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $ending = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSDate(): ?\DateTimeInterface
    {
        return $this->s_date;
    }

    public function setSDate(\DateTimeInterface $s_date): self
    {
        $this->s_date = $s_date;

        return $this;
    }

    public function getStarting(): ?\DateTimeInterface
    {
        return $this->starting;
    }

    public function setStarting(?\DateTimeInterface $starting): self
    {
        $this->starting = $starting;

        return $this;
    }

    public function getEnding(): ?\DateTimeInterface
    {
        return $this->ending;
    }

    public function setEnding(?\DateTimeInterface $ending): self
    {
        $this->ending = $ending;

        return $this;
    }
}

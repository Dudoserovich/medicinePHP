<?php

namespace App\Entity;

use App\Repository\MedicineRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MedicineRepository::class)]
class Medicine
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 30)]
    private ?string $name = null;

    #[ORM\Column(length: 100)]
    private ?string $usage = null;

    #[ORM\Column(length: 80)]
    private ?string $actions = null;

    #[ORM\Column(length: 80, nullable: true)]
    private ?string $effects = null;

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

    public function getUsage(): ?string
    {
        return $this->usage;
    }

    public function setUsage(string $usage): self
    {
        $this->usage = $usage;

        return $this;
    }

    public function getActions(): ?string
    {
        return $this->actions;
    }

    public function setActions(string $actions): self
    {
        $this->actions = $actions;

        return $this;
    }

    public function getEffects(): ?string
    {
        return $this->effects;
    }

    public function setEffects(?string $effects): self
    {
        $this->effects = $effects;

        return $this;
    }
}

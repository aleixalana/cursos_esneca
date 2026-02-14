<?php

namespace App\Entity;

use App\Repository\CursosRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CursosRepository::class)]
class Cursos
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $codi = null;

    #[ORM\Column(length: 100)]
    private ?string $nom = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $data_inici = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $data_fi = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 4, scale: 1)]
    private ?string $duracio = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $preu = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCodi(): ?string
    {
        return $this->codi;
    }

    public function setCodi(string $codi): static
    {
        $this->codi = $codi;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDataInici(): ?\DateTime
    {
        return $this->data_inici;
    }

    public function setDataInici(\DateTime $data_inici): static
    {
        $this->data_inici = $data_inici;

        return $this;
    }

    public function getDataFi(): ?\DateTime
    {
        return $this->data_fi;
    }

    public function setDataFi(\DateTime $data_fi): static
    {
        $this->data_fi = $data_fi;

        return $this;
    }

    public function getDuracio(): ?string
    {
        return $this->duracio;
    }

    public function setDuracio(string $duracio): static
    {
        $this->duracio = $duracio;

        return $this;
    }

    public function getPreu(): ?string
    {
        return $this->preu;
    }

    public function setPreu(string $preu): static
    {
        $this->preu = $preu;

        return $this;
    }
}

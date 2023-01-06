<?php

namespace App\Entity;

use App\Repository\DepositoRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: "rentadb.depositos")]
#[ORM\Entity(repositoryClass: DepositoRepository::class)]
class Deposito
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: Arrendatario::class, inversedBy: 'depositos')]
    private $arrendatario;

    #[ORM\ManyToOne(targetEntity: Ambiente::class, inversedBy: 'depositos')]
    private $ambiente;

    #[ORM\Column(type: 'float')]
    private $monto;

    #[ORM\Column(type: 'string', length: 4)]
    private $anio;

    #[ORM\Column(type: 'string', length: 3)]
    private $mes;

    #[ORM\Column(type: 'string', length: 100)]
    private $observacion;

    #[ORM\Column(name: "fechaDeposito", type: 'datetime')]
    private $fechaDeposito;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getArrendatario(): ?Arrendatario
    {
        return $this->arrendatario;
    }

    public function setArrendatario(?Arrendatario $arrendatario): self
    {
        $this->arrendatario = $arrendatario;

        return $this;
    }

    public function getAmbiente(): ?Ambiente
    {
        return $this->ambiente;
    }

    public function setAmbiente(?Ambiente $ambiente): self
    {
        $this->ambiente = $ambiente;

        return $this;
    }

    public function getMonto(): ?float
    {
        return $this->monto;
    }

    public function setMonto(float $monto): self
    {
        $this->monto = $monto;

        return $this;
    }

    public function getAnio(): ?string
    {
        return $this->anio;
    }

    public function setAnio(string $anio): self
    {
        $this->anio = $anio;

        return $this;
    }

    public function getMes(): ?string
    {
        return $this->mes;
    }

    public function setMes(string $mes): self
    {
        $this->mes = $mes;

        return $this;
    }

    public function getObservacion(): ?string
    {
        return $this->observacion;
    }

    public function setObservacion(string $observacion): self
    {
        $this->observacion = $observacion;

        return $this;
    }

    public function getFechaDeposito(): ?\DateTimeInterface
    {
        return $this->fechaDeposito;
    }

    public function setFechaDeposito(\DateTimeInterface $fechaDeposito): self
    {
        $this->fechaDeposito = $fechaDeposito;

        return $this;
    }

    public function __toString() {
        return (string) $this->monto;
    }    
}

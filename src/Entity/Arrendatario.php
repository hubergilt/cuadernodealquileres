<?php

namespace App\Entity;

use App\Repository\ArrendatarioRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: "rentadb.arrendatarios")]
#[ORM\Entity(repositoryClass: ArrendatarioRepository::class)]
class Arrendatario
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 45)]
    private $nombres;

    #[ORM\Column(type: 'string', length: 45)]
    private $paterno;

    #[ORM\Column(type: 'string', length: 45)]
    private $materno;

    #[ORM\Column(type: 'string', length: 8)]
    private $dni;

    #[ORM\OneToMany(mappedBy: 'arrendatario', targetEntity: Deposito::class)]
    private $depositos;

    #[ORM\OneToMany(mappedBy: 'arrendatario', targetEntity: Ambiente::class)]
    private $ambientes;

    public function __construct()
    {
        $this->depositos = new ArrayCollection();
        $this->ambientes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombres(): ?string
    {
        return $this->nombres;
    }

    public function setNombres(string $nombres): self
    {
        $this->nombres = $nombres;

        return $this;
    }

    public function getPaterno(): ?string
    {
        return $this->paterno;
    }

    public function setPaterno(string $paterno): self
    {
        $this->paterno = $paterno;

        return $this;
    }

    public function getMaterno(): ?string
    {
        return $this->materno;
    }

    public function setMaterno(string $materno): self
    {
        $this->materno = $materno;

        return $this;
    }

    public function getDni(): ?string
    {
        return $this->dni;
    }

    public function setDni(string $dni): self
    {
        $this->dni = $dni;

        return $this;
    }

    /**
     * @return Collection<int, Deposito>
     */
    public function getDepositos(): Collection
    {
        return $this->depositos;
    }

    public function addDeposito(Deposito $deposito): self
    {
        if (!$this->depositos->contains($deposito)) {
            $this->depositos[] = $deposito;
            $deposito->setArrendatario($this);
        }

        return $this;
    }

    public function removeDeposito(Deposito $deposito): self
    {
        if ($this->depositos->removeElement($deposito)) {
            // set the owning side to null (unless already changed)
            if ($deposito->getArrendatario() === $this) {
                $deposito->setArrendatario(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Ambiente>
     */
    public function getAmbientes(): Collection
    {
        return $this->ambientes;
    }

    public function addAmbiente(Ambiente $ambiente): self
    {
        if (!$this->ambientes->contains($ambiente)) {
            $this->ambientes[] = $ambiente;
            $ambiente->setArrendatario($this);
        }

        return $this;
    }

    public function removeAmbiente(Ambiente $ambiente): self
    {
        if ($this->ambientes->removeElement($ambiente)) {
            // set the owning side to null (unless already changed)
            if ($ambiente->getArrendatario() === $this) {
                $ambiente->setArrendatario(null);
            }
        }

        return $this;
    }

    public function __toString() {
        return (string) $this->nombres.' '.$this->paterno.' '.$this->materno;
    }

}
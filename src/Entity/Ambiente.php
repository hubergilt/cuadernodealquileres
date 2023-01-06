<?php

namespace App\Entity;

use App\Repository\AmbienteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: "rentadb.ambientes")]
#[ORM\Entity(repositoryClass: AmbienteRepository::class)]
class Ambiente
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: Arrendatario::class, inversedBy: 'ambientes')]
    private $arrendatario;

    #[ORM\Column(type: 'string', length: 45)]
    private $nombre;

    #[ORM\Column(type: 'string', length: 20)]
    private $piso;

    #[ORM\Column(type: 'float')]
    private $precio;

    #[ORM\Column(type: 'string', length: 10)]
    private $estado;

    #[ORM\OneToMany(mappedBy: 'ambiente', targetEntity: Deposito::class)]
    private $depositos;

    public function __construct()
    {
        $this->depositos = new ArrayCollection();
    }

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

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getPiso(): ?string
    {
        return $this->piso;
    }

    public function setPiso(string $piso): self
    {
        $this->piso = $piso;

        return $this;
    }

    public function getPrecio(): ?float
    {
        return $this->precio;
    }

    public function setPrecio(float $precio): self
    {
        $this->precio = $precio;

        return $this;
    }

    public function getEstado(): ?string
    {
        return $this->estado;
    }

    public function setEstado(string $estado): self
    {
        $this->estado = $estado;

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
            $deposito->setAmbiente($this);
        }

        return $this;
    }

    public function removeDeposito(Deposito $deposito): self
    {
        if ($this->depositos->removeElement($deposito)) {
            // set the owning side to null (unless already changed)
            if ($deposito->getAmbiente() === $this) {
                $deposito->setAmbiente(null);
            }
        }

        return $this;
    }

    public function __toString() {
        return (string) $this->nombre.'-'.$this->piso;
    }

}

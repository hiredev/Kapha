<?php

namespace App\Entity;

use App\Repository\CobranzaRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CobranzaRepository::class)
 */
class Cobranza
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Maestro::class, inversedBy="cobranzas")
     * @ORM\JoinColumn(nullable=false)
     */
    private $maestro;

    /**
     * @ORM\Column(type="float")
     */
    private $monto;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $metodo;

    /**
     * @ORM\Column(type="text")
     */
    private $transaccion;

    /**
     * @ORM\Column(type="datetime")
     */
    private $fecha;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMaestro(): ?Maestro
    {
        return $this->maestro;
    }

    public function setMaestro(?Maestro $maestro): self
    {
        $this->maestro = $maestro;

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

    public function getMetodo(): ?string
    {
        return $this->metodo;
    }

    public function setMetodo(string $metodo): self
    {
        $this->metodo = $metodo;

        return $this;
    }

    public function getTransaccion(): ?string
    {
        return $this->transaccion;
    }

    public function setTransaccion(string $transaccion): self
    {
        $this->transaccion = $transaccion;

        return $this;
    }

    public function getFecha(): ?\DateTimeInterface
    {
        return $this->fecha;
    }

    public function setFecha(\DateTimeInterface $fecha): self
    {
        $this->fecha = $fecha;

        return $this;
    }
}

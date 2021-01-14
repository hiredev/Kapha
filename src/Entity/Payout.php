<?php

namespace App\Entity;

use App\Repository\PayoutRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PayoutRepository::class)
 */
class Payout
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Teacher::class, inversedBy="payouts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $teacher;

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

    public function getTeacher(): ?Teacher
    {
        return $this->teacher;
    }

    public function setTeacher(?Teacher $teacher): self
    {
        $this->teacher = $teacher;

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

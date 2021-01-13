<?php

namespace App\Entity;

use App\Repository\AlumnoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AlumnoRepository::class)
 */
class Alumno
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nombre;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $apellido;

    /**
     * @ORM\OneToMany(targetEntity=Pago::class, mappedBy="alumno", orphanRemoval=true)
     */
    private $pagos;

    /**
     * @ORM\ManyToMany(targetEntity=Aula::class, mappedBy="alumnos")
     */
    private $aulas;

    /**
     * @ORM\OneToOne(targetEntity=Usuario::class, inversedBy="alumno", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $usuario;

    public function __construct()
    {
        $this->pagos = new ArrayCollection();
        $this->aulas = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getApellido(): ?string
    {
        return $this->apellido;
    }

    public function setApellido(string $apellido): self
    {
        $this->apellido = $apellido;

        return $this;
    }

    /**
     * @return Collection|Pago[]
     */
    public function getPagos(): Collection
    {
        return $this->pagos;
    }

    public function addPago(Pago $pago): self
    {
        if (!$this->pagos->contains($pago)) {
            $this->pagos[] = $pago;
            $pago->setAlumno($this);
        }

        return $this;
    }

    public function removePago(Pago $pago): self
    {
        if ($this->pagos->contains($pago)) {
            $this->pagos->removeElement($pago);
            // set the owning side to null (unless already changed)
            if ($pago->getAlumno() === $this) {
                $pago->setAlumno(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Aula[]
     */
    public function getAulas(): Collection
    {
        return $this->aulas;
    }

    public function addAula(Aula $aula): self
    {
        if (!$this->aulas->contains($aula)) {
            $this->aulas[] = $aula;
            $aula->addAlumno($this);
        }

        return $this;
    }

    public function removeAula(Aula $aula): self
    {
        if ($this->aulas->contains($aula)) {
            $this->aulas->removeElement($aula);
            $aula->removeAlumno($this);
        }

        return $this;
    }

    public function getUsuario(): ?Usuario
    {
        return $this->usuario;
    }

    public function setUsuario(Usuario $usuario): self
    {
        $this->usuario = $usuario;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->usuario ? $this->usuario->getEmail() : '';
    }

    public function setEmail(string $email): self
    {
        if(!$this->usuario){
            $this->usuario = new Usuario;
        }

        $this->usuario->setEmail($email);

        return $this;
    }    

    public function __toString(){
        return $this->getNombre() . ' ' . $this->getApellido();
    }    
}

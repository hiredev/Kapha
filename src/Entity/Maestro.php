<?php

namespace App\Entity;

use App\Repository\MaestroRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MaestroRepository::class)
 */
class Maestro
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
     * @ORM\Column(type="string", length=500)
     */
    private $slug;

    /**
     * @ORM\OneToMany(targetEntity=Cobranza::class, mappedBy="maestro", orphanRemoval=true)
     */
    private $cobranzas;

    /**
     * @ORM\OneToMany(targetEntity=Aula::class, mappedBy="maestro", orphanRemoval=true)
     */
    private $aulas;

    /**
     * @ORM\OneToOne(targetEntity=Usuario::class, inversedBy="maestro", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $usuario;

    /**
     * @ORM\ManyToOne(targetEntity=Categoria::class, inversedBy="maestros")
     * @ORM\JoinColumn(nullable=false)
     */
    private $categoria;

    public function __construct()
    {
        $this->cobranzas = new ArrayCollection();
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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return Collection|Cobranza[]
     */
    public function getCobranzas(): Collection
    {
        return $this->cobranzas;
    }

    public function addCobranza(Cobranza $cobranza): self
    {
        if (!$this->cobranzas->contains($cobranza)) {
            $this->cobranzas[] = $cobranza;
            $cobranza->setMaestro($this);
        }

        return $this;
    }

    public function removeCobranza(Cobranza $cobranza): self
    {
        if ($this->cobranzas->contains($cobranza)) {
            $this->cobranzas->removeElement($cobranza);
            // set the owning side to null (unless already changed)
            if ($cobranza->getMaestro() === $this) {
                $cobranza->setMaestro(null);
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
            $aula->setMaestro($this);
        }

        return $this;
    }

    public function removeAula(Aula $aula): self
    {
        if ($this->aulas->contains($aula)) {
            $this->aulas->removeElement($aula);
            // set the owning side to null (unless already changed)
            if ($aula->getMaestro() === $this) {
                $aula->setMaestro(null);
            }
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

    public function getCategoria(): ?Categoria
    {
        return $this->categoria;
    }

    public function setCategoria(?Categoria $categoria): self
    {
        $this->categoria = $categoria;

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

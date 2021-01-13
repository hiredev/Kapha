<?php

namespace App\Entity;

use App\Repository\CategoriaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CategoriaRepository::class)
 */
class Categoria
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
    private $titulo;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\Column(type="text")
     */
    private $html;

    /**
     * @ORM\OneToMany(targetEntity=Maestro::class, mappedBy="categoria")
     */
    private $maestros;

    public function __construct()
    {
        $this->maestros = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitulo(): ?string
    {
        return $this->titulo;
    }

    public function setTitulo(string $titulo): self
    {
        $this->titulo = $titulo;

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

    public function getHtml(): ?string
    {
        return $this->html;
    }

    public function setHtml(string $html): self
    {
        $this->html = $html;

        return $this;
    }

    /**
     * @return Collection|Maestro[]
     */
    public function getMaestros(): Collection
    {
        return $this->maestros;
    }

    public function addMaestro(Maestro $maestro): self
    {
        if (!$this->maestros->contains($maestro)) {
            $this->maestros[] = $maestro;
            $maestro->setCategoria($this);
        }

        return $this;
    }

    public function removeMaestro(Maestro $maestro): self
    {
        if ($this->maestros->contains($maestro)) {
            $this->maestros->removeElement($maestro);
            // set the owning side to null (unless already changed)
            if ($maestro->getCategoria() === $this) {
                $maestro->setCategoria(null);
            }
        }

        return $this;
    }

    public function __toString(){
        return $this->getTitulo();
    }
}

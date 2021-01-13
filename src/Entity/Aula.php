<?php

namespace App\Entity;

use App\Repository\AulaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;


/**
 * @ORM\Entity(repositoryClass=AulaRepository::class)
 * @Vich\Uploadable
 */
class Aula
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
    private $descripcion;

    /**
     * @ORM\Column(type="string", length=150)
     */
    private $link;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @var string
     */
    private $imagen;

    /**
     * @Vich\UploadableField(mapping="programa_images", fileNameProperty="imagen")
     * @var File
     */
    private $imagenFile;

    /**
     * @ORM\Column(type="datetime")
     */
    private $fecha;

    /**
     * @ORM\ManyToOne(targetEntity=Maestro::class, inversedBy="aulas")
     * @ORM\JoinColumn(nullable=false)
     */
    private $maestro;

    /**
     * @ORM\ManyToMany(targetEntity=Alumno::class, inversedBy="aulas")
     */
    private $alumnos;

    /**
     * @ORM\ManyToOne(targetEntity=Programa::class, inversedBy="aulas")
     * @ORM\JoinColumn(nullable=false)
     */
    private $programa;

    public function __construct()
    {
        $this->alumnos = new ArrayCollection();
        $this->fecha = new \DateTime('now');
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

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(string $descripcion): self
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(string $link): self
    {
        $this->link = $link;

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

    public function getMaestro(): ?Maestro
    {
        return $this->maestro;
    }

    public function setMaestro(?Maestro $maestro): self
    {
        $this->maestro = $maestro;

        return $this;
    }

    /**
     * @return Collection|Alumno[]
     */
    public function getAlumnos(): Collection
    {
        return $this->alumnos;
    }

    public function addAlumno(Alumno $alumno): self
    {
        if (!$this->alumnos->contains($alumno)) {
            $this->alumnos[] = $alumno;
        }

        return $this;
    }

    public function removeAlumno(Alumno $alumno): self
    {
        if ($this->alumnos->contains($alumno)) {
            $this->alumnos->removeElement($alumno);
        }

        return $this;
    }

    public function getPrograma(): ?Programa
    {
        return $this->programa;
    }

    public function setPrograma(?Programa $programa): self
    {
        $this->programa = $programa;

        return $this;
    }

    public function setImagenFile(File $imagen = null)
    {
        $this->imagenFile = $imagen;

        // VERY IMPORTANT:
        // It is required that at least one field changes if you are using Doctrine,
        // otherwise the event listeners won't be called and the file is lost
        if ($imagen) {
            // if 'fecha' is not defined in your entity, use another property
            $this->fecha = new \DateTime('now');
        }
    }

    public function getImagenFile()
    {
        return $this->imagenFile;
    }

    public function setImagen($imagen)
    {
        $this->imagen = $imagen;
    }

    public function getImagen()
    {
        return $this->imagen;
    }    

    public function __toString(){
        return $this->getTitulo();
    }
}

<?php

namespace App\Entity;

use App\Repository\LessonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;


/**
 * @ORM\Entity(repositoryClass=LessonRepository::class)
 * @Vich\Uploadable
 */
class Lesson
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
     * @ORM\ManyToOne(targetEntity=Teacher::class, inversedBy="lessons")
     * @ORM\JoinColumn(nullable=false)
     */
    private $teacher;

    /**
     * @ORM\ManyToMany(targetEntity=Student::class, inversedBy="lessons")
     */
    private $students;

    /**
     * @ORM\ManyToOne(targetEntity=Course::class, inversedBy="lessons")
     * @ORM\JoinColumn(nullable=false)
     */
    private $course;

    public function __construct()
    {
        $this->students = new ArrayCollection();
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

    public function getTeacher(): ?Teacher
    {
        return $this->teacher;
    }

    public function setTeacher(?Teacher $teacher): self
    {
        $this->teacher = $teacher;

        return $this;
    }

    /**
     * @return Collection|Student[]
     */
    public function getStudents(): Collection
    {
        return $this->students;
    }

    public function addStudent(Student $student): self
    {
        if (!$this->students->contains($student)) {
            $this->students[] = $student;
        }

        return $this;
    }

    public function removeStudent(Student $student): self
    {
        if ($this->students->contains($student)) {
            $this->students->removeElement($student);
        }

        return $this;
    }

    public function getCourse(): ?Course
    {
        return $this->course;
    }

    public function setCourse(?Course $course): self
    {
        $this->course = $course;

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
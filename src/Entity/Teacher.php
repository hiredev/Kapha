<?php

namespace App\Entity;

use App\Repository\TeacherRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TeacherRepository::class)
 */
class Teacher
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
     * @ORM\OneToMany(targetEntity=Cobranza::class, mappedBy="teacher", orphanRemoval=true)
     */
    private $cobranzas;

    /**
     * @ORM\OneToMany(targetEntity=Lesson::class, mappedBy="teacher", orphanRemoval=true)
     */
    private $lessons;

    /**
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="teacher", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Categoria::class, inversedBy="teachers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $categoria;

    public function __construct()
    {
        $this->cobranzas = new ArrayCollection();
        $this->lessons = new ArrayCollection();
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
            $cobranza->setTeacher($this);
        }

        return $this;
    }

    public function removeCobranza(Cobranza $cobranza): self
    {
        if ($this->cobranzas->contains($cobranza)) {
            $this->cobranzas->removeElement($cobranza);
            // set the owning side to null (unless already changed)
            if ($cobranza->getTeacher() === $this) {
                $cobranza->setTeacher(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Lesson[]
     */
    public function getLessons(): Collection
    {
        return $this->lessons;
    }

    public function addLesson(Lesson $lesson): self
    {
        if (!$this->lessons->contains($lesson)) {
            $this->lessons[] = $lesson;
            $lesson->setTeacher($this);
        }

        return $this;
    }

    public function removeLesson(Lesson $lesson): self
    {
        if ($this->lessons->contains($lesson)) {
            $this->lessons->removeElement($lesson);
            // set the owning side to null (unless already changed)
            if ($lesson->getTeacher() === $this) {
                $lesson->setTeacher(null);
            }
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

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
        return $this->user ? $this->user->getEmail() : '';
    }

    public function setEmail(string $email): self
    {
        if(!$this->user){
            $this->user = new User;
        }

        $this->user->setEmail($email);

        return $this;
    }

    public function __toString(){
        return $this->getNombre() . ' ' . $this->getApellido();
    }
}

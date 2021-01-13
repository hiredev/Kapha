<?php

namespace App\Entity;

use App\Repository\UsuarioRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UsuarioRepository::class)
 */
class Usuario implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $password;

    private $newPassword;    

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $facebookID;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $facebookAccessToken;

    /**
     * @ORM\Column(type="datetime")
     */
    private $fecha;

    /**
     * @ORM\OneToOne(targetEntity=Alumno::class, mappedBy="usuario", cascade={"persist", "remove"})
     */
    private $alumno;

    /**
     * @ORM\OneToOne(targetEntity=Maestro::class, mappedBy="usuario", cascade={"persist", "remove"})
     */
    private $maestro;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getNewPassword(): string
    {
        return (string) $this->newPassword;
    }

    public function setNewPassword(string $newPassword = null): self
    {
        $this->newPassword = $newPassword;

        return $this;
    }    

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFacebookID(): ?string
    {
        return $this->facebookID;
    }

    public function setFacebookID(?string $facebookID): self
    {
        $this->facebookID = $facebookID;

        return $this;
    }

    public function getFacebookAccessToken(): ?string
    {
        return $this->facebookAccessToken;
    }

    public function setFacebookAccessToken(?string $facebookAccessToken): self
    {
        $this->facebookAccessToken = $facebookAccessToken;

        return $this;
    }

    public function getfecha(): ?\DateTimeInterface
    {
        return $this->fecha;
    }

    public function setfecha(\DateTimeInterface $fecha): self
    {
        $this->fecha = $fecha;

        return $this;
    }

    public function getAlumno(): ?Alumno
    {
        return $this->alumno;
    }

    public function setAlumno(Alumno $alumno): self
    {
        $this->alumno = $alumno;

        // set the owning side of the relation if necessary
        if ($alumno->getUsuario() !== $this) {
            $alumno->setUsuario($this);
        }

        return $this;
    }

    public function getMaestro(): ?Maestro
    {
        return $this->maestro;
    }

    public function setMaestro(Maestro $maestro): self
    {
        $this->maestro = $maestro;

        // set the owning side of the relation if necessary
        if ($maestro->getUsuario() !== $this) {
            $maestro->setUsuario($this);
        }

        return $this;
    }

    public function __construct(){
        $this->setfecha(new \Datetime());
    }    
}

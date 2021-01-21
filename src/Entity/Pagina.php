<?php

namespace App\Entity;

use App\Repository\PaginaRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PaginaRepository::class)
 */
class Pagina
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
     * @ORM\Column(type="string")
     */
    private $path;

    /**
     * @ORM\Column(type="integer")
     */
    private $menuOrder;

    /**
     * @ORM\Column(type="boolean")
     */
    private $showInMenu;

    /**
     * @ORM\Column(type="text")
     */
    private $html;

    public function __construct()
    {
        $this->menuOrder = 0;
        $this->showInMenu = true;
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
     * @return mixed
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param mixed $path
     * @return Pagina
     */
    public function setPath($path)
    {
        $this->path = $path;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMenuOrder()
    {
        return $this->menuOrder;
    }

    /**
     * @param mixed $menuOrder
     * @return Pagina
     */
    public function setMenuOrder($menuOrder)
    {
        $this->menuOrder = $menuOrder;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getShowInMenu()
    {
        return $this->showInMenu;
    }

    /**
     * @param mixed $showInMenu
     * @return Pagina
     */
    public function setShowInMenu($showInMenu)
    {
        $this->showInMenu = $showInMenu;
        return $this;
    }

}

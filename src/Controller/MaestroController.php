<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CategoriaRepository;
use App\Entity\Categoria;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MaestroController extends AbstractController
{
    protected $em;

    public function __construct(EntityManagerInterface $em){
        $this->em = $em;
    }

    /**
     * @Route("/ver_maestros/{categoria}", name="maestros")
     */
    public function index($categoria = '')
    {        
        $categorias = $this->em->getRepository(Categoria::class)->findAll();

        return $this->render('maestro/index.html.twig', [
            'categorias' => $categorias,
        ]);
    }
}

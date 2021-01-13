<?php

namespace App\Controller;


use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ProgramaRepository;
use App\Entity\Programa;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ProgramaController extends AbstractController
{

    protected $em;

    public function __construct(EntityManagerInterface $em){
        $this->em = $em;
    }

    /**
     * @Route("/ver_aulas/{programa}", name="programas")
     */
    public function index($programa = '')
    {
        $programas = $this->em->getRepository(Programa::class)->findAll();

        return $this->render('aula/index.html.twig', [
            'programas' => $programas
        ]);
    }
}
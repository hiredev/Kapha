<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CategoriaRepository;
use App\Entity\Categoria;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TeacherController extends BaseController
{
    protected $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

//    /**
//     * @Route("/ver_maestros", name="teachers")
//     */
//    public function index(Request $request)
//    {
//        $teache
//    }


}

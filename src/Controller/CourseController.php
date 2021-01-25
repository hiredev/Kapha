<?php

namespace App\Controller;


use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CourseRepository;
use App\Entity\Course;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CourseController extends BaseController
{

    protected $em;

    public function __construct(EntityManagerInterface $em){
        $this->em = $em;
    }

    /**
     * @Route("/ver_programas/{programa}", name="programas")
     */
    public function index($programa = '')
    {
        $courses = $this->em->getRepository(Course::class)->findAll();

        return $this->render('programa/index.html.twig', [
            'programas' => $courses
        ]);
    }
}
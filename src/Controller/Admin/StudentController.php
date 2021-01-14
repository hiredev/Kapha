<?php

namespace App\Controller\Admin;

use App\Entity\Course;
use App\Entity\Teacher;
use App\Entity\Lesson;
use App\Entity\User;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Router\CrudUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class StudentController extends AbstractDashboardController
{
    /**
     * @Route("/student", name="student")
     */
    public function index(): Response
    {
        $routeBuilder = $this->get(CrudUrlGenerator::class)->build();
        return $this->redirect($routeBuilder->setController(LessonCrudController::class)->generateUrl());
    }

    /**
     * @Route("/student/test", name="student_test")
     */
    public function test(): Response
    {
        return $this->render("student/list.html.twig");
    }

    /**
     * @Route("/student/subscription", name="student_subscription")
     */
    public function subscription(): Response
    {
        return $this->render("student/subscription.html.twig");
    }


    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Wesdap');
    }

    public function configureMenuItems(): iterable
    {
        //yield MenuItem::linktoDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linktoRoute('Subscription', 'fa fa-money', "student_subscription");


        yield MenuItem::section('Programas');
        yield MenuItem::linkToCrud('Programas', 'fa fa-users', Course::class);

        yield MenuItem::section('Aulas');
        yield MenuItem::linkToCrud('Aulas', 'fa fa-users', Lesson::class);


//        yield MenuItem::section('Perfil');
//        yield MenuItem::linkToCrud('Perfil', 'fa fa-users', Teacher::class)
//            ->setAction('edit')
//            ->setEntityId($this->getUser()->getTeacher()->getId());

        yield MenuItem::section('Perfil');
        yield MenuItem::linkToCrud('Acceso', 'fa fa-users', User::class)
            ->setAction('edit')
            ->setEntityId($this->getUser()->getId());
    }
}
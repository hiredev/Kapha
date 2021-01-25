<?php

namespace App\Controller\Admin;

use App\Entity\Meeting;
use App\Entity\Payment;
use App\Entity\PaymentPlan;
use App\Entity\User;
use App\Entity\Pagina;
use App\Entity\Categoria;
use App\Entity\Teacher;
use App\Entity\Student;
use App\Entity\Course;
use App\Entity\Lesson;
use App\Entity\CuentaZoom;
use App\Entity\Contacto;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Router\CrudUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class AdminController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        // redirect to some CRUD controller
        $routeBuilder = $this->get(CrudUrlGenerator::class)->build();
        return $this->redirect($routeBuilder->setController(UserCrudController::class)->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Wesdap');
    }

    public function configureMenuItems(): iterable
    {
        //yield MenuItem::linktoDashboard('Dashboard', 'fa fa-home');


        yield MenuItem::section('Aulas');
        yield MenuItem::linkToCrud('Programas', 'fa fa-users', Course::class);
        yield MenuItem::linkToCrud('Aulas', 'fa fa-users', Lesson::class);
//        yield MenuItem::linkToCrud('Meeting', 'fa fa-users', Meeting::class);

        yield MenuItem::section('Zoom');
        yield MenuItem::linkToCrud('Cuentas Zoom', 'fa fa-file', CuentaZoom::class);
        yield MenuItem::linktoRoute('Crear Zoom', 'fa fa-file', 'zoom_create');

        yield MenuItem::section('Contenido');
        yield MenuItem::linkToCrud('Paginas', 'fa fa-file', Pagina::class);
        yield MenuItem::linkToCrud('Contacto', 'fa fa-file', Contacto::class);

        yield MenuItem::section('Maestros');
        yield MenuItem::linkToCrud('Categorias', 'fa fa-users', Categoria::class);
        yield MenuItem::linkToCrud('Maestros', 'fa fa-users', Teacher::class);

        yield MenuItem::section('Finance');
        yield MenuItem::linkToCrud('Plans', 'fa fa-money', PaymentPlan::class);
        yield MenuItem::linkToCrud('Payment history', 'fa fa-money', Payment::class);

        yield MenuItem::section('Acceso');
        yield MenuItem::linkToCrud('Alumnos', 'fa fa-users', Student::class);
        yield MenuItem::linkToCrud('Usuarios', 'fa fa-users', User::class);

        yield MenuItem::section('Design');
        yield MenuItem::linktoRoute('Edit menus', 'fa fa-file', 'admin_design');

    }
}

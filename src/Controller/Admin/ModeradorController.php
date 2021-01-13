<?php

namespace App\Controller\Admin;

use App\Entity\Usuario;
use App\Entity\Pagina;
use App\Entity\Categoria;
use App\Entity\Maestro;
use App\Entity\Alumno;
use App\Entity\Programa;
use App\Entity\Aula;
use App\Entity\CuentaZoom;
use App\Entity\Contacto;

use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class ModeradorController extends AbstractDashboardController
{
    /**
     * @Route("/moderador", name="moderador")
     */
    public function index(): Response
    {
        $routeBuilder = $this->get(CrudUrlGenerator::class)->build();
        return $this->redirect($routeBuilder->setController(UsuarioCrudController::class)->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Wesdap');
    }

    public function configureMenuItems(): iterable
    {
        //yield MenuItem::linktoDashboard('Dashboard', 'fa fa-home');

        yield MenuItem::section('Maestros');
        yield MenuItem::linkToCrud('Categorias', 'fa fa-users', Categoria::class);
        yield MenuItem::linkToCrud('Maestros', 'fa fa-users', Maestro::class);

        yield MenuItem::section('Aulas');
        yield MenuItem::linkToCrud('Programas', 'fa fa-users', Programa::class);
        yield MenuItem::linkToCrud('Aulas', 'fa fa-users', Aula::class);        

        yield MenuItem::section('Contenido');
        yield MenuItem::linkToCrud('Paginas', 'fa fa-file', Pagina::class);
        yield MenuItem::linkToCrud('Contacto', 'fa fa-file', Contacto::class);        

        yield MenuItem::section('Acceso');
        yield MenuItem::linkToCrud('Alumnos', 'fa fa-users', Alumno::class);
        yield MenuItem::linkToCrud('Usuarios', 'fa fa-users', Usuario::class);
    }
}
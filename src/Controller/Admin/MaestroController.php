<?php

namespace App\Controller\Admin;

use App\Entity\Maestro;
use App\Entity\Aula;
use App\Entity\Usuario;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Router\CrudUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class MaestroController extends AbstractDashboardController
{
    /**
     * @Route("/maestro", name="maestro")
     */
    public function index(): Response
    {
        $routeBuilder = $this->get(CrudUrlGenerator::class)->build();
        return $this->redirect($routeBuilder->setController(AulaCrudController::class)->generateUrl());
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
        yield MenuItem::linkToCrud('Aulas', 'fa fa-users', Aula::class);

        yield MenuItem::section('Perfil');
        yield MenuItem::linkToCrud('Perfil', 'fa fa-users', Maestro::class)
            ->setAction('edit')
            ->setEntityId($this->getUser()->getMaestro()->getId());
        yield MenuItem::linkToCrud('Acceso', 'fa fa-users', Usuario::class)
            ->setAction('edit')
            ->setEntityId($this->getUser()->getId());
    }
}
<?php

namespace App\Controller\Admin;

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

/**
 * Class PaymentController
 * @package App\Controller\Admin
 * @Route("/admin")
 */
class PaymentController extends AbstractDashboardController
{
    /**
     * @Route("/payments/list", name="payments_list")
     */
    public function index(): Response
    {
        dd(123);

    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Wesdap');
    }
}
<?php

namespace App\Controller\Admin;

use App\Entity\PaymentPlan;
use App\Entity\Teacher;
use App\Entity\Lesson;
use App\Entity\User;

use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
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
class PaymentPlanCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return PaymentPlan::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            TextField::new('title', 'Titulo'),
            NumberField::new('amount', 'Monto'),
            NumberField::new('period', 'Period days'),
            TextareaField::new('description', 'Description'),
            NumberField::new('displayOrder', "Display order")->onlyOnForms(),
            BooleanField::new('isActive', 'Is active')
        ];
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Wesdap');
    }
}
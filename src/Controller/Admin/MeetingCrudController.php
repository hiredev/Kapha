<?php

namespace App\Controller\Admin;

use App\Entity\Meeting;
use App\Entity\Lesson;
use App\Entity\User;

use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Router\CrudUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Vich\UploaderBundle\Form\Type\VichImageType;

/**
 * Class PaymentController
 * @package App\Controller\Admin
 * @Route("/admin")
 */
class MeetingCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Meeting::class;
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Wesdap');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('lesson')->autocomplete(),
            TextField::new('zoomLink'),
            TextField::new('zoomPassword'),
            DateTimeField::new('date', 'Fecha'),
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        $this->crudUrlGenerator = $this->get(CrudUrlGenerator::class);

        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->setPermission(Action::EDIT, "ROLE_MODERATOR")
            ->setPermission(Action::NEW, "ROLE_MODERATOR")
            ->setPermission(Action::DELETE, "ROLE_MODERATOR")
            ->setPermission(Action::SAVE_AND_CONTINUE, "ROLE_MODERATOR");
    }
}
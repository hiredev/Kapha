<?php

namespace App\Controller\Admin;

use App\Entity\Course;
use App\Entity\Student;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Router\CrudUrlGenerator;
use Vich\UploaderBundle\Form\Type\VichImageType;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;

class CourseCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Course::class;
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

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->OnlyOnIndex()->setPermission('ROLE_MODERATOR')->setPermission('ROLE_ADMIN'),
            TextField::new('title', 'Titulo'),
            TextareaField::new('imagenFile')
                ->setFormType(VichImageType::class)
                ->setFormTypeOptions(['allow_delete' => false])
                ->OnlyOnForms(),
            ImageField::new('imagen')
                ->setBasePath($this->getParameter('app.path.course_image'))
                ->hideOnForm(),
            NumberField::new('displayOrder', "Display order")->onlyOnForms(),
            TextEditorField::new('description')->hideOnIndex(),
            DateTimeField::new('date', 'Fecha')->hideOnForm(),
            BooleanField::new("isActive", "Active")->setPermission("ROLE_MODERATOR"),
        ];
    }
}

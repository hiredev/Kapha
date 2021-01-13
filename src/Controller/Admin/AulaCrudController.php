<?php

namespace App\Controller\Admin;

use App\Entity\Aula;
use Vich\UploaderBundle\Form\Type\VichImageType;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;


use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;

class AulaCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Aula::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
                ->OnlyOnIndex(),
            TextField::new('titulo'),
            TextEditorField::new('descripcion')
                ->OnlyOnForms(),
            ImageField::new('imagenFile')
                ->setFormType(VichImageType::class)
                ->setFormTypeOptions(['allow_delete' => false])
                ->OnlyOnForms(),
            ImageField::new('imagen')
                ->setBasePath(
                    $this->getParameter('app.path.programa_image'))
                ->hideOnForm(),        
            UrlField::new('link')->hideOnIndex(),
            DateTimeField::new('fecha')->onlyOnIndex(),
            AssociationField::new('programa'),
            AssociationField::new('maestro')->autocomplete()->setPermission('ROLE_MODERADOR')->setPermission('ROLE_ADMIN')
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_EDIT, Action::INDEX)
            ->add(Crud::PAGE_NEW, Action::INDEX)
            ->setPermission(Action::SAVE_AND_CONTINUE, 'ROLE_ADMIN')            
            ->setPermission(Action::SAVE_AND_CONTINUE, 'ROLE_MODERADOR')
            ->remove(Crud::PAGE_NEW, Action::SAVE_AND_ADD_ANOTHER)
        ;
    }    
}

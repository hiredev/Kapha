<?php

namespace App\Controller\Admin;

use App\Entity\Contacto;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;

class ContactoCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Contacto::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
                ->OnlyOnIndex(),
            TextField::new('nombre'),
            TextField::new('comentario'),
            DateTimeField::new('fecha')
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        $actions = $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->remove(Crud::PAGE_INDEX, Action::EDIT)            
            ->remove(Crud::PAGE_INDEX, Action::DELETE)
            ->remove(Crud::PAGE_INDEX, Action::NEW)              
            ->remove(Crud::PAGE_EDIT, Action::SAVE_AND_CONTINUE);

        return $actions;
    }
}

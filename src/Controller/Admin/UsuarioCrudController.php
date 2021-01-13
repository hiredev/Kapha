<?php

namespace App\Controller\Admin;

use App\Entity\Usuario;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;

use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;

use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class UsuarioCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Usuario::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            EmailField::new('email'),
            DateTimeField::new('fecha')->onlyOnIndex(),
            ChoiceField::new('roles')->allowMultipleChoices()->setChoices(
                ['ROLE_USER' => 'ROLE_USER','ROLE_MAESTRO' => 'ROLE_MAESTRO','ROLE_MODERADOR' => 'ROLE_MODERADOR','ROLE_ADMIN' => 'ROLE_ADMIN']
            )->setPermission('ROLE_MODERADOR')->setPermission('ROLE_ADMIN'),
            TextField::new('newPassword')->onlyOnForms()
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_EDIT, Action::INDEX)
            ->add(Crud::PAGE_NEW, Action::INDEX)
            ->setPermission(Action::INDEX, 'ROLE_ADMIN')
            ->setPermission(Action::INDEX, 'ROLE_MODERADOR')
            ->setPermission(Action::NEW, 'ROLE_ADMIN')
            ->setPermission(Action::NEW, 'ROLE_MODERADOR')
            ->setPermission(Action::SAVE_AND_RETURN, 'ROLE_ADMIN')            
            ->setPermission(Action::SAVE_AND_RETURN, 'ROLE_MODERADOR')
        ;
    }    
}

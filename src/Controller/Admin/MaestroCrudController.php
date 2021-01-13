<?php

namespace App\Controller\Admin;

use App\Entity\Maestro;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Router\CrudUrlGenerator;

use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;

use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;

class MaestroCrudController extends AbstractCrudController
{

    public static function getEntityFqcn(): string
    {
        return Maestro::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            EmailField::new('email'),
            TextField::new('nombre'),
            TextField::new('apellido'),
            AssociationField::new('categoria'),
            AssociationField::new('aulas')->onlyOnIndex()
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        $this->crudUrlGenerator = $this->get(CrudUrlGenerator::class);
        $roles = $this->getUser()->getRoles();

        $verUsuario = Action::new('verUsuario','Usuario')->linkToUrl(function (Maestro $entity) {
            return  $this->crudUrlGenerator
            ->build()
            ->setController(UsuarioCrudController::class)
            ->setAction(Action::DETAIL)
            ->setEntityId($entity->getUsuario()->getId());                
        });

        $editarUsuario = Action::new('editarUsuario','Modificar Usuario')->linkToUrl(function (Maestro $entity) {
            return  $this->crudUrlGenerator
            ->build()
            ->setController(UsuarioCrudController::class)
            ->setAction(Action::EDIT)
            ->setEntityId($entity->getUsuario()->getId());                
        });

        $actions = $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->add(Crud::PAGE_INDEX, $verUsuario)
            ->add(Crud::PAGE_EDIT, Action::INDEX)
            ->add(Crud::PAGE_EDIT, $editarUsuario)
            ->add(Crud::PAGE_NEW, Action::INDEX)
            ->setPermission(Action::INDEX, 'ROLE_ADMIN')
            ->setPermission(Action::INDEX, 'ROLE_MODERADOR')
            ->setPermission(Action::NEW, 'ROLE_ADMIN')
            ->setPermission(Action::NEW, 'ROLE_MODERADOR')
            ->setPermission(Action::SAVE_AND_RETURN, 'ROLE_ADMIN')            
            ->setPermission(Action::SAVE_AND_RETURN, 'ROLE_MODERADOR')
            ;

        return $actions;
    }    
}
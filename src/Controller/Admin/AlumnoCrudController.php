<?php

namespace App\Controller\Admin;

use App\Entity\Alumno;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Router\CrudUrlGenerator;

use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;

use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;

class AlumnoCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Alumno::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            EmailField::new('email'),            
            TextField::new('nombre'),
            TextField::new('apellido'),
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        $this->crudUrlGenerator = $this->get(CrudUrlGenerator::class);

        $verUsuario = Action::new('verUsuario','Usuario')->linkToUrl(function (Alumno $entity) {
            return  $this->crudUrlGenerator
            ->build()
            ->setController(UsuarioCrudController::class)
            ->setAction(Action::DETAIL)
            ->setEntityId($entity->getUsuario()->getId());                
        });

        $editarUsuario = Action::new('editarUsuario','Modificar Usuario')->linkToUrl(function (Alumno $entity) {
            return  $this->crudUrlGenerator
            ->build()
            ->setController(UsuarioCrudController::class)
            ->setAction(Action::EDIT)
            ->setEntityId($entity->getUsuario()->getId());                
        });

        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->add(Crud::PAGE_INDEX, $verUsuario)
            ->add(Crud::PAGE_EDIT, Action::INDEX)
            ->add(Crud::PAGE_EDIT, $editarUsuario)
            ->add(Crud::PAGE_NEW, Action::INDEX);
    }    
}
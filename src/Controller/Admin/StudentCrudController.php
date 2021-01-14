<?php

namespace App\Controller\Admin;

use App\Entity\Student;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Router\CrudUrlGenerator;

use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;

use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;

class StudentCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Student::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            EmailField::new('email'),            
            TextField::new('firstName', 'Nombre'),
            TextField::new('lastName', 'Apellido'),
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        $this->crudUrlGenerator = $this->get(CrudUrlGenerator::class);

        $verUser = Action::new('verUser','User')->linkToUrl(function (Student $entity) {
            return  $this->crudUrlGenerator
            ->build()
            ->setController(UserCrudController::class)
            ->setAction(Action::DETAIL)
            ->setEntityId($entity->getUser()->getId());                
        });

        $editarUser = Action::new('editarUser','Modificar User')->linkToUrl(function (Student $entity) {
            return  $this->crudUrlGenerator
            ->build()
            ->setController(UserCrudController::class)
            ->setAction(Action::EDIT)
            ->setEntityId($entity->getUser()->getId());                
        });

        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->add(Crud::PAGE_INDEX, $verUser)
            ->add(Crud::PAGE_EDIT, Action::INDEX)
            ->add(Crud::PAGE_EDIT, $editarUser)
            ->add(Crud::PAGE_NEW, Action::INDEX);
    }    
}
<?php

namespace App\Controller\Admin;

use App\Entity\Teacher;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Router\CrudUrlGenerator;

use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;

use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;

class TeacherCrudController extends AbstractCrudController
{



    public static function getEntityFqcn(): string
    {
        return Teacher::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            EmailField::new('email'),
            TextField::new('firstName', 'Nombre'),
            TextField::new('lastName', 'Apellido'),
            AssociationField::new('categoria'),
            AssociationField::new('lessons')->onlyOnIndex()
        ];
    }



    public function configureActions(Actions $actions): Actions
    {
        $this->crudUrlGenerator = $this->get(CrudUrlGenerator::class);
        $roles = $this->getUser()->getRoles();

        $verUser = Action::new('verUser', 'Usuario')->linkToUrl(function (Teacher $entity) {
            return $this->crudUrlGenerator->build()
                ->setController(UserCrudController::class)
                ->setAction(Action::DETAIL)
                ->setEntityId($entity->getUser()->getId());
        });

        $editarUser = Action::new('editarUser', 'Modificar Usuario')->linkToUrl(function (Teacher $entity) {
            return $this->crudUrlGenerator->build()
                ->setController(UserCrudController::class)
                ->setAction(Action::EDIT)
                ->setEntityId($entity->getUser()->getId());
        });

        $actions = $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->add(Crud::PAGE_INDEX, $verUser)
            ->add(Crud::PAGE_EDIT, Action::INDEX)
            ->add(Crud::PAGE_EDIT, $editarUser)
            ->add(Crud::PAGE_NEW, Action::INDEX)
            ->setPermission(Action::INDEX, 'ROLE_ADMIN')
            ->setPermission(Action::INDEX, 'ROLE_MODERATOR')
            ->setPermission(Action::NEW, 'ROLE_ADMIN')
            ->setPermission(Action::NEW, 'ROLE_MODERATOR')
            ->setPermission(Action::SAVE_AND_RETURN, 'ROLE_ADMIN')
            ->setPermission(Action::SAVE_AND_RETURN, 'ROLE_MODERATOR');

        return $actions;
    }
}
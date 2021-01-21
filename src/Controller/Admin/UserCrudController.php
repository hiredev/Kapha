<?php

namespace App\Controller\Admin;

use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;

use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;

use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class UserCrudController extends AbstractCrudController
{

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {

            /** @var EntityManager $em */
            $em = $this->get('doctrine')->getManager();
            $qb = $em->createQueryBuilder();
            $qb->select("u")
                ->from("App:User", 'u')
                ->where('u.isDeleted = false')
//                ->where('l.teacher = :teacher')
//                ->andWhere('l.date >= :date')
                ->setParameters([
//                    'teacher' => $this->getUser()->getTeacher(),
//                    'date' => new \DateTime(),
                ]);

            return $qb;
    }

    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function deleteEntity(EntityManagerInterface $entityManager, $user):void {

        $user->setIsDeleted(true);
        $entityManager->persist($user);
        $entityManager->flush();
//        dd($entityInstance);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            EmailField::new('email'),
            ChoiceField::new('defaultLocale')->setChoices([
                'Español' => 'es',
                'English' => 'en',
            ])->hideOnIndex(),
            DateTimeField::new('date')->onlyOnIndex(),
            ChoiceField::new('roles')->allowMultipleChoices()->setChoices([
                'ROLE_USUARIO' => 'ROLE_USER',
                'ROLE_MAESTRO' => 'ROLE_TEACHER',
                'ROLE_MODERADOR' => 'ROLE_MODERATOR',
                'ROLE_ADMIN' => 'ROLE_ADMIN'
            ])
                ->setPermission('ROLE_MODERATOR')->setPermission('ROLE_ADMIN'),
            TextField::new('newPassword')->onlyOnForms()
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_EDIT, Action::INDEX)
            ->add(Crud::PAGE_NEW, Action::INDEX)
            ->setPermission(Action::INDEX, 'ROLE_ADMIN')
            ->setPermission(Action::INDEX, 'ROLE_MODERATOR')
            ->setPermission(Action::NEW, 'ROLE_ADMIN')
            ->setPermission(Action::NEW, 'ROLE_MODERATOR')
            ->setPermission(Action::SAVE_AND_RETURN, 'ROLE_ADMIN')
            ->setPermission(Action::SAVE_AND_RETURN, 'ROLE_MODERATOR');
    }
}

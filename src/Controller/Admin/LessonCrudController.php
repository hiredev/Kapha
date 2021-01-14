<?php

namespace App\Controller\Admin;

use App\Entity\Lesson;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
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

class LessonCrudController extends AbstractCrudController
{
    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        if ($this->getUser()->hasRole("ROLE_ADMIN") || $this->getUser()->hasRole("ROLE_MODERATOR")) {
            return parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters); // TODO: Change the autogenerated stub
        } else {
            /** @var EntityManager $em */
            $em = $this->get('doctrine')->getManager();
            $qb = $em->createQueryBuilder();
            $qb->select("l")
                ->from("App:Lesson", 'l')
                ->where('l.teacher = :teacher')
//                ->andWhere('l.date >= :date')
                ->setParameters([
                    'teacher' => $this->getUser()->getTeacher(),
//                    'date' => new \DateTime(),
                ]);

            return $qb;
        }
    }

    public static function getEntityFqcn(): string
    {
        return Lesson::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->OnlyOnIndex()->setPermission('ROLE_ADMIN'),
            TextField::new('titulo'),
            TextEditorField::new('descripcion')
                ->OnlyOnForms(),
            ImageField::new('imagenFile')
                ->setFormType(VichImageType::class)
                ->setFormTypeOptions(['allow_delete' => false])
                ->OnlyOnForms(),
            ImageField::new('imagen')
                ->setBasePath($this->getParameter('app.path.lesson_image'))
                ->hideOnForm(),
            UrlField::new('link')->hideOnIndex(),
            DateTimeField::new('fecha')->onlyOnIndex(),
            AssociationField::new('course'),
            AssociationField::new('teacher')->autocomplete()->setPermission('ROLE_MODERADOR')->setPermission('ROLE_ADMIN')
        ];
    }



    public function configureActions(Actions $actions): Actions
    {


        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->setPermission(Action::EDIT, "ROLE_MODERATOR")
            ->setPermission(Action::NEW, "ROLE_MODERATOR")
            ->setPermission(Action::DELETE, "ROLE_MODERATOR")
            ->setPermission(Action::SAVE_AND_CONTINUE, "ROLE_MODERATOR");


    }
}

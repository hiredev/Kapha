<?php

namespace App\Controller\Admin;

use App\Entity\Teacher;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;

use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Router\CrudUrlGenerator;

use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;

use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use Vich\UploaderBundle\Form\Type\VichImageType;

class TeacherCrudController extends AbstractCrudController
{

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {

        /** @var EntityManager $em */
        $em = $this->get('doctrine')->getManager();
        $qb = $em->createQueryBuilder();
        $qb->select("t")
            ->from("App:Teacher", 't')
            ->where('t.isDeleted = false')
            ->setParameters([
            ]);

        return $qb;
    }

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
            TextEditorField::new('biography', 'Bio')->hideOnIndex(),
            TextareaField::new('imagenFile')
                ->setFormType(VichImageType::class)
                ->setFormTypeOptions(['allow_delete' => false])
                ->OnlyOnForms(),
//            ImageField::new('imagen')
//                ->setBasePath($this->getParameter('app.path.teacher_image'))
//                ->hideOnForm(),
            AssociationField::new('categoria'),
            AssociationField::new('lessons')->onlyOnIndex(),
            BooleanField::new("isActive"),
            NumberField::new('displayOrder', "Display order")->onlyOnForms(),

        ];
    }

    public function deleteEntity(EntityManagerInterface $entityManager, $teacher):void {

        $teacher->setIsDeleted(true);
        $teacher->getUser()->setIsDeleted(true);
        $entityManager->persist($teacher);
        $entityManager->flush();
    }


    public function configureActions(Actions $actions): Actions
    {
        $this->crudUrlGenerator = $this->get(CrudUrlGenerator::class);

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
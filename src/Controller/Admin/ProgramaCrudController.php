<?php

namespace App\Controller\Admin;

use App\Entity\Programa;
use Vich\UploaderBundle\Form\Type\VichImageType;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;

class ProgramaCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Programa::class;
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
            DateTimeField::new('fecha')->hideOnForm()                    
        ];
    }
}

<?php

namespace App\Controller\Admin;

use App\Entity\Pagina;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;

class PaginaCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Pagina::class;
    }
    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->OnlyOnIndex(),
            TextField::new('titulo'),
            TextField::new('path')->OnlyOnForms(),
            TextEditorField::new('html')->OnlyOnForms(),
//            NumberField::new('menuOrder', "Menu Order")->onlyOnForms(),
//            BooleanField::new('showInMenu', "Show in menu")->onlyOnForms(),
        ];
    }
}

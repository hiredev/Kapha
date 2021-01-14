<?php

namespace App\Controller\Admin;

use App\Entity\Payout;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class PayoutCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Payout::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}

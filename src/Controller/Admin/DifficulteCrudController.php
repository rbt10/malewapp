<?php

namespace App\Controller\Admin;

use App\Entity\Difficulte;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class DifficulteCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Difficulte::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('libelleDifficulte'),
        ];
    }

}

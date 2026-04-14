<?php

namespace App\Controller\Admin;

use App\Entity\ImageFile;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ImageFileCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ImageFile::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('displayName', 'Nom d\'affichage'),
            TextField::new('storageName', 'Nom du fichier (stockage)'),
        ];
    }
}

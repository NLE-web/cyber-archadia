<?php

namespace App\Controller\Admin;

use App\Entity\Keyword;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class KeywordCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Keyword::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('key', 'Clé (stockage)'),
            TextField::new('display', 'Affichage'),
            AssociationField::new('items', 'Items')->setFormTypeOption('by_reference', false)->hideOnIndex(),
            AssociationField::new('feats', 'Feats')->setFormTypeOption('by_reference', false)->hideOnIndex(),
            AssociationField::new('actions', 'Actions')->setFormTypeOption('by_reference', false)->hideOnIndex(),
            AssociationField::new('skills', 'Skills')->setFormTypeOption('by_reference', false)->hideOnIndex(),
        ];
    }
}

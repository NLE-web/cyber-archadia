<?php

namespace App\Controller\Admin;

use App\Entity\Item;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ItemCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Item::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('name', 'Nom'),
            ChoiceField::new('type', 'Type')->setChoices(array_flip(Item::TYPES)),
            BooleanField::new('isLegal', 'Légal'),
            BooleanField::new('isCumbersome', 'Encombrant'),
            AssociationField::new('illustration', 'Illustration'),
            BooleanField::new('isConsume', 'Consommable (Mécanique d\'usage)'),
            IntegerField::new('price', 'Prix'),
            IntegerField::new('chargePrice', 'Prix de recharge'),
            TextField::new('description', 'Description'),
            IntegerField::new('stock', 'Stock'),
            BooleanField::new('isInfiniteStock', 'Stock infini'),
            IntegerField::new('humanityLoss', 'Perte d\'Humanité'),
            AssociationField::new('actions', 'Actions liées'),
        ];
    }
}

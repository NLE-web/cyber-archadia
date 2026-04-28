<?php

namespace App\Controller\Admin;

use App\Entity\Action as ActionEntity;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ActionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ActionEntity::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('name', 'Nom'),
            TextField::new('type', 'Type'),
            TextField::new('description', 'Description'),
            ChoiceField::new('usage', 'Usage')->setChoices(ActionEntity::USAGES),
            IntegerField::new('maxUse', 'Max Utilisations'),
            AssociationField::new('item', 'Item lié'),
        ];
    }
}

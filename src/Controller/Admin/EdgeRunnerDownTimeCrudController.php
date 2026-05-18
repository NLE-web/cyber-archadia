<?php

namespace App\Controller\Admin;

use App\Entity\EdgeRunnerDownTime;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;

class EdgeRunnerDownTimeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return EdgeRunnerDownTime::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            AssociationField::new('edgerunner', 'EdgeRunner'),
            AssociationField::new('downtime', 'Downtime'),
            TextareaField::new('effetBonus', 'Effet Bonus'),
            BooleanField::new('draft', 'draft'),
            BooleanField::new('discard', 'discard'),
        ];
    }
}

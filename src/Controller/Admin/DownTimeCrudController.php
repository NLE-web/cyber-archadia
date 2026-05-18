<?php

namespace App\Controller\Admin;

use App\Entity\DownTime;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class DownTimeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return DownTime::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('title', 'Titre'),
            TextareaField::new('description', 'Description'),
            TextareaField::new('effet', 'Effet'),
            IntegerField::new('timeCost', 'Coût en temps (heures)'),
            BooleanField::new('forced', 'Forcé'),
        ];
    }
}

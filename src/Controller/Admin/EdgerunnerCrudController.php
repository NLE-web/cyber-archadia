<?php

namespace App\Controller\Admin;

use App\Entity\Edgerunner;
use App\Form\CharacterActionType;
use App\Form\CharacterItemType;
use App\Form\CharacterSkillType;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class EdgerunnerCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Edgerunner::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('nom'),
            IntegerField::new('force'),
            IntegerField::new('dexterite'),
            IntegerField::new('intelligence'),
            IntegerField::new('lifepoints', 'Points de vie'),
            IntegerField::new('cyberpoints', 'Points de cyber'),
            IntegerField::new('stresspoints', 'Points de stress'),
            IntegerField::new('lostlife', 'Vie perdue'),
            IntegerField::new('lostcyber', 'Cyber perdu'),
            BooleanField::new('isActive', 'Actif'),
            AssociationField::new('avatar'),
            
            CollectionField::new('items', 'Inventaire')
                ->setEntryIsComplex(true)
                ->setEntryType(CharacterItemType::class)
                ->onlyOnForms(),

            CollectionField::new('actions', 'Actions')
                ->setEntryIsComplex(true)
                ->setEntryType(CharacterActionType::class)
                ->onlyOnForms(),

            CollectionField::new('skills', 'Compétences')
                ->setEntryIsComplex(true)
                ->setEntryType(CharacterSkillType::class)
                ->onlyOnForms(),
        ];
    }
}

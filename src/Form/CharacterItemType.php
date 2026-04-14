<?php

namespace App\Form;

use App\Entity\CharacterItem;
use App\Entity\Item;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class CharacterItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('item', EntityType::class, [
                'class' => Item::class,
                'choice_label' => 'name',
            ])
            ->add('amount', IntegerType::class, [
                'label' => 'Quantité',
                'attr' => ['min' => 1],
                'required' => false,
            ])
        ;

        $builder->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event) {
            $characterItem = $event->getData();
            $form = $event->getForm();

            if ($characterItem && $characterItem->getItem() && $characterItem->getItem()->getType() !== Item::TYPE_CONSOMMABLE) {
                $form->remove('amount');
            }
        });

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            $data = $event->getData();
            $form = $event->getForm();

            if (isset($data['item'])) {
                // On ne peut pas facilement accéder à l'entité Item ici car on n'a que l'ID dans $data
                // Mais EasyAdmin gère le chargement.
                // Pour faire simple et respecter la consigne "ne doit apparaitre que si...", 
                // le POST_SET_DATA gère déjà l'affichage initial.
                // Pour le PRE_SUBMIT (changement dynamique), sans JS c'est impossible d'adapter le formulaire 
                // AVANT le submit.
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CharacterItem::class,
        ]);
    }
}

<?php

namespace App\Form;

use App\Entity\CharacterSkill;
use App\Entity\Skill;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CharacterSkillType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('skill', EntityType::class, [
                'class' => Skill::class,
                'choice_label' => 'name',
            ])
            ->add('level', IntegerType::class, [
                'label' => 'Niveau',
                'attr' => ['min' => 0]
            ])
            ->add('xp', IntegerType::class, [
                'label' => 'XP',
                'attr' => ['min' => 0]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CharacterSkill::class,
        ]);
    }
}

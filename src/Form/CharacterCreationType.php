<?php

namespace App\Form;

use App\Entity\Edgerunner;
use App\Entity\ImageFile;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class CharacterCreationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom du personnage',
                'constraints' => [
                    new NotBlank(message: 'Veuillez donner un nom à votre personnage.'),
                ],
            ])
            ->add('avatarFile', FileType::class, [
                'label' => 'Avatar (Image)',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File(
                        maxSize: '5M',
                        mimeTypes: [
                            'image/jpeg',
                            'image/png',
                            'image/webp',
                        ],
                        mimeTypesMessage: 'Veuillez uploader une image valide (JPG, PNG, WEBP)',
                    )
                ],
            ])
            ->add('themeColor', ChoiceType::class, [
                'label' => 'Thème de couleur',
                'choices' => [
                    'Rouge' => 'red',
                    'Jaune' => 'yellow',
                    'Teal' => 'teal',
                    'Orange' => 'orange',
                    'Violet' => 'purple',
                    'Bleu' => 'blue',
                    'Vert' => 'green',
                ],
                'mapped' => false,
                'constraints' => [
                    new NotBlank(message: 'Veuillez choisir un thème de couleur.'),
                ],
            ])
            ->add('force', IntegerType::class, [
                'label' => 'FOR',
                'data' => 0,
                'attr' => ['min' => 0, 'max' => 3],
                'constraints' => [
                    new GreaterThanOrEqual(0),
                    new LessThanOrEqual(3),
                ],
            ])
            ->add('dexterite', IntegerType::class, [
                'label' => 'DEX',
                'data' => 0,
                'attr' => ['min' => 0, 'max' => 3],
                'constraints' => [
                    new GreaterThanOrEqual(0),
                    new LessThanOrEqual(3),
                ],
            ])
            ->add('intelligence', IntegerType::class, [
                'label' => 'INT',
                'data' => 0,
                'attr' => ['min' => 0, 'max' => 3],
                'constraints' => [
                    new GreaterThanOrEqual(0),
                    new LessThanOrEqual(3),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Edgerunner::class,
            'constraints' => [
                new Callback(callback: [$this, 'validateStats']),
            ],
        ]);
    }

    public function validateStats($data, ExecutionContextInterface $context): void
    {
        $total = $data->getForce() + $data->getDexterite() + $data->getIntelligence();
        if ($total !== 6) {
            $context->buildViolation('Vous devez répartir exactement 6 points entre vos caractéristiques (Actuel : {{ current }}).')
                ->setParameter('{{ current }}', $total)
                ->addViolation();
        }
    }
}

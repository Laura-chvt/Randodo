<?php

namespace App\Form;

use App\Entity\Hike;
use App\Entity\Location;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HikeCreateFormType extends AbstractType
{   
    /**
    * Formulaire de création de randonnée
    */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label'     => 'Nom de la randonnée'
            ])
            
            ->add('height', IntegerType::class, [
                'label'     => 'Dénivelé de la randonnée (en mètres)'
            ])

            ->add('time', NumberType::class, [
                'label'     => 'Durée de la randonnée (en minutes)'
            ])

            ->add('level', ChoiceType::class, [
                'label'     => 'Niveau de la randonnée',
                'choices'   => [
                    'Facile'    => 'Facile',
                    'Moyen'     => 'Moyen',
                    'Difficile' => 'Difficile',
                    ]
            ])

            ->add('length', NumberType::class, [
                'label'     => 'Longueur de la randonnée (en km)'
            ])

            ->add('family', CheckboxType::class, [
                'label'     => "La randonnée est-elle accessible aux familles ?",
                'required'  => false
            ])

            ->add('description', TextareaType::class, [
                'label'     => 'Description de la randonnée',
                'required'  => false
            ])

            ->add('thumbnail', FileType::class, [
                'label'     => "Miniature de la randonnée",
                'mapped'    => false,
                'required'  => false,
            ])

            ->add('location', EntityType::class, [
                'label'         => "Zone géographique",
                'class'         => Location::class,
                'choice_label'  => 'name',
                'multiple'      => false,
            ])

            ->add('submit', SubmitType::class, [
                'label'     => 'Ajouter'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Hike::class,
        ]);
    }
}

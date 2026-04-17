<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class UserInfoFormType extends AbstractType
{   
    /**
    * Formulaire de modification de l'utilisateur
    */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label'     => 'Adresse e-mail'
            ])

            ->add('firstname', TextType::class, [
                'label'     => 'Prénom'
            ])
            
            ->add('name', TextType::class, [
                'label'     => 'Nom de famille'
            ])

            ->add('description', TextareaType::class, [
                'label'     => 'Courte présentation',
                'required' => false
            ])

            ->add('image', FileType::class, [
                'label'     => "Photo de profil",
                'mapped'    => false,
                'required'  => false,
                'constraints' => [
                new File(
                    maxSize: '2M',
                    mimeTypes: [
                        'image/jpeg',
                        'image/png',
                    ],
                    mimeTypesMessage: 'Veuillez envoyer une image valide (JPEG ou PNG)',
                    )
                ],    
            ]) 

            ->add('submit', SubmitType::class, [
                'label'     => 'Ajouter'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

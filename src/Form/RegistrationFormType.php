<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotCompromisedPassword;
use Symfony\Component\Validator\Constraints\PasswordStrength;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label'     => 'Adresse e-mail'
            ])

            ->add('firstname', TextType::class, [
                'label'     => 'Prénom'
            ])
            
            ->add('lastname', TextType::class, [
                'label'     => 'Nom de famille'
            ])

            ->add('thumbnail', FileType::class, [
                'label'     => "Photo de profil",
                'mapped'    => false,
                'required'  => false,
            ])

            ->add('description', TextType::class, [
                'label'     => 'Courte présentation'
            ])
            
            ->add('plainPassword', RepeatedType::class, [
                'mapped'            => false,
                'type'              => PasswordType::class,
                'invalid_message'   => 'Les champs doivent être identiques', 
                'required'          => true,
                'first_options'     => ['label' => 'Mot de passe'],
                'second_options'    => ['label' => 'Confirmer le mot de passe'],
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank(
                        message: 'Please enter a password',
                    ),
                    new Length(
                        min: 12,
                        minMessage: 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        max: 4096,
                    ),
                    new PasswordStrength(),
                    new NotCompromisedPassword(),
                ],
            ])

            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue(
                        message: "Il faut autoriser notre politique de confidentialité pour s'inscrire",
                    ),
                ],
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

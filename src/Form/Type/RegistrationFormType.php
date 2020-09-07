<?php

namespace App\Form\Type;

use App\Entity\User\User;
use App\Validator\Constraints\IsMatriculate;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, [
                'attr' => array(
                    'placeholder' => "Matricule"
                ),
                'constraints' => [
                    new IsMatriculate()
                ]
            ])
            ->add('firstName', TextType::class, array(
                'attr' => array(
                    'placeholder' => "Prénom"
                )
            ))
            ->add('lastName', TextType::class, array(
                'attr' => array(
                    'placeholder' => "Nom de famille"
                )
            ))
            ->add('plainPassword', PasswordType::class, [
                'attr' => array(
                    'placeholder' => "Mot de passe"
                ),
                'mapped' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

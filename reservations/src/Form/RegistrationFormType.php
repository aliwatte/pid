<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
			->add('login')
			->add('firstname' )
            ->add('lastname')
            ->add('email')
			->add('agreeTerms', CheckboxType::class, [
				'label' => 'J accepte les conditions',
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devrez accepter les conditions',
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Entrez le mot de passe SVP',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Le mot de passe doit contenir minimum {{ limit }} char',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
					new Regex([
						'pattern' =>'/^(?=(?:.*[A-Z]){1,})(?=(?:.*[a-z]){1,}(?=(?:.*\d){1,}))(?=(?:.*[!@#$%^&*()\-_=+{};:,<.>]){1,})(.{8,})/', 
						/*
						/										 # delimiter
						^                                        # start of line
						(?=(?:.*[A-Z]){2,})                      # 2 upper case letters
						(?=(?:.*[a-z]){2,})                      # 2 lower case letters
						(?=(?:.*\d){2,})                         # 2 digits
						(?=(?:.*[!@#$%^&*()\-_=+{};:,<.>]){2,})  # 2 special characters
						(.{8,})                                  # length 8 or more
						/										 # delimiter
						*/
						'message' => 'Le mot de passe doit avoir min 1 majiscule
						, min 1 minuscule, min 1 nombre et 1 char special.',
					]),
                ],
            ])
			->add('confPassword', PasswordType::class, [
                'mapped' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Confirmer le mot de passe',
                    ]),
                ],
				'invalid_message' => 'Confirmation invalide.',
				
            ])
            ->add('langue', ChoiceType::class, [
                'choices'  => [
					'Veuillez choisir une langue' => null,
					'Français' => 'fr',
                    'English' => 'en',
                ],
				'constraints' => [
                    new NotBlank([
                        'message' => 'Choisissez une lange SVP',
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

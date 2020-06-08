<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Regex;

class PaswordType extends AbstractType
{
     public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('login')
            ->add('firstName')
            ->add('lastName')
            ->add('email', EmailType::class)
            ->add('langue', ChoiceType::class, [
                'choices'  => [
					'Veuillez choisir une langue' => null,
					'Français' => 'fr',
                    'Anglais' => 'en',
                ]
            ])
            ->add('password', PasswordType::class, [
                'mapped' => false,
                'required' => false,
            ])
			
            ->add('newPassword', PasswordType::class, [
				'mapped' => false,
				'required' => true,
				'constraints' => [
					new NotNull([
						'message' => 'Entrez le nouveau mot de passe SVP',
					]),
					new Length([
						'min' => 6,
                        'minMessage' => 'Le mot de passe doit contenir minimum {{ limit }} char',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
					]),
					//'pattern' =>'/(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9]).{7,}/',
					new Regex([
						'pattern' =>'/^(?=(?:.*[A-Z]){1,})(?=(?:.*[a-z]){1,}(?=(?:.*\d){1,}))(?=(?:.*[!@#$%^&*()\-_=+{};:,<.>]){1,})(.{8,})/', 
						'message' => 'Le mot de passe doit avoir min 1 majiscule
						, min 1 minuscule, min 1 nombre et 1 char special.',
					]),
				],
			])
			->add('confPassword', PasswordType::class, [
				'mapped' => false,
				'required' => true,
				'constraints' => [
					new NotNull([
						'message' => 'Confirmer le nouveau mot de passe SVP',
					]),
				],
				'invalid_message' => 'The password fields must match.',
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

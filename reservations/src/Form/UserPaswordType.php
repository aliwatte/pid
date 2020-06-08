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


class UserPaswordType extends AbstractType
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
                'required' => truee,
            ])
            ->add('newPassword', PasswordType::class, [
				'mapped' => false,
				'required' => true,
				'constraints' => [
					new NotNull([
						'message' => 'Please enter a password',
					]),
					new Length([
						'min' => 3,
						'minMessage' => 'Your password should be at least {{ limit }} characters',
						// max length allowed by Symfony for security reasons
						'max' => 4096,
					]),
					//'pattern' =>'/(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9]).{7,}/',
					new Regex([
						'pattern' =>'/^ (?=.*[A-Z])(?=.*[a-z])(?=.*\d).{6,}$/i', 
						'message' => 'New password is required to include at least one letter and one number.',
					]),
				],
			])
			->add('confPassword', PasswordType::class, [
				'mapped' => false,
				'required' => true,
				'constraints' => [
					new NotNull([
						'message' => 'Please confirm your password',
					]),
				],
			])
		;
		
		/*$this->setValidators(array(
			'login'    => new sfValidatorString(array('required' => false)),
			'firstName' => new sfValidatorString(array('min_length' => 4)),
			'email'   => new sfValidatorEmail(),
			//'subject' => new sfValidatorChoice(array('choices' => array_keys(self::$subjects))),	
		));*/
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

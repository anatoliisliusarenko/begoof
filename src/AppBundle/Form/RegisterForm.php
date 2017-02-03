<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\IsTrue;

class RegisterForm extends AbstractType {
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder
			->setAction($options['action'])
			->setMethod($options['method'])
			->add('full_name', TextType::class, ['label' => 'Full Name'])
			->add('email', EmailType::class, ['label' => 'Email'])
			->add('password', RepeatedType::class, [
				'type' => PasswordType::class,
				'first_options' => ['label' => 'Password'],
				'second_options' => ['label' => 'Repead Your Password']])
			->add('agree', CheckboxType::class, ['mapped' => false, 'label' => 'I agree with Ts and Cs', 'constraints' => new IsTrue()])
			->add('submit', SubmitType::class, ['label' => 'Register'])
		;

	}
}

<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class RegisterForm extends AbstractType {
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder
			->setAction($options['action'])
			->setMethod($options['method'])
			->add('full_name', TextType::class, ['label' => 'Full Name'])
			->add('email', EmailType::class, ['label' => 'Email'])
			->add('password', PasswordType::class, ['label' => 'Password'])
			->add('password2', PasswordType::class, ['label' => 'Repeat Your Password'])
			->add('agree', CheckboxType::class, ['label' => 'I agree with Ts and Cs'])
			->add('submit', SubmitType::class, ['label' => 'Register'])
		;

	}
}

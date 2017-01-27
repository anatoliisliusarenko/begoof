<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class LoginForm extends AbstractType {
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder
			->setAction($options['action'])
			->setMethod($options['method'])
			->add('username', TextType::class, ['label' => 'Username/Email'])
			->add('password', PasswordType::class, ['label' => 'Password'])
			->add('remember_me', CheckboxType::class, ['label' => 'Remember Me For 1 Year', 'required' => false])
			->add('submit', SubmitType::class, ['label' => 'Log In'])
		;

	}
}

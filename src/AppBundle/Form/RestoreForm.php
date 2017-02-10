<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\NotBlank;

class RestoreForm extends AbstractType {
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder
			->setAction($options['action'])
			->setMethod($options['method'])
			->add('username', TextType::class, ['mapped' => false, 'label' => 'Username/Email', 'constraints' => new NotBlank()])
			->add('submit', SubmitType::class, ['label' => 'Restore'])
		;

	}
}

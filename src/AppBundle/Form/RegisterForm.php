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
use Symfony\Component\Validator\Constraints\NotBlank; 
use Symfony\Component\Validator\Constraints\Length; 
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContext;
use Doctrine\ORM\EntityManager;
use AppBundle\Service\UserService;


class RegisterForm extends AbstractType {

	protected $em = null;
	protected $userService = null;

	public function __construct(EntityManager $em, UserService $userService) {
		$this->em = $em;
		$this->userService = $userService;
	}

	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder
			->setAction($options['action'])
			->setMethod($options['method'])
			->add('full_name', TextType::class, [
				'label' => 'Full Name', 
				'constraints' => [
					new NotBlank(),
					new Length(['min' => 5, 'max' => 255])
				]
			])
			->add('email', EmailType::class, [
				'label' => 'Email',
				'constraints' => [
					new NotBlank(),
					new Regex('/^[\w\.\-]+\@[\w\.\-]+$/'),
					new Callback([$this, 'validate'])
				]
			])
			->add('password', RepeatedType::class, [
				'type' => PasswordType::class,
				'first_options' => [
					'label' => 'Password',
					'constraints' => [
						new NotBlank(),
						new Length(['min' => 8])
					]
				],
				'second_options' => [
					'label' => 'Repead Your Password',
					'constraints' => [
						new NotBlank(),
						new Length(['min' => 8])
					]
				]
			])
			->add('agree', CheckboxType::class, [
				'label' => 'I agree with Ts and Cs', 
				'constraints' => [
					new IsTrue()
				]
			])
			->add('submit', SubmitType::class, [
				'label' => 'Register'
			])
		;

	}

	public function validate($email, ExecutionContext $context)
    {
    	$user = $this->userService->getUserByEmail($email);

    	if ($user) {
    		$context->addViolation('User with this email address is already existing');
    	}

    }
}

<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContext;
use Doctrine\ORM\EntityManager;
use AppBundle\Service\UserService;

class RestoreForm extends AbstractType {

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
			->add('username', TextType::class, [
				'label' => 'Username/Email', 
				'constraints' => [
					new NotBlank(),
					new Callback([$this, 'validate'])
				]
			])
			->add('submit', SubmitType::class, ['label' => 'Restore'])
		;

	}

	public function validate($username, ExecutionContext $context)
    {
    	$user = $this->userService->getUserByUsernameOrEmail($username, $username);

    	if (!$user) {
    		$context->addViolation('Username/Email not found');
    	}

    }
    
}

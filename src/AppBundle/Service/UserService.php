<?php

namespace AppBundle\Service;

use AppBundle\Entity\UserEntity;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class UserService {

	protected $em = null;
	protected $user = null;

	public function __construct(EntityManager $em, TokenStorage $tokenStorage) {
		$this->em = $em;
		$user = $tokenStorage->getToken()->getUser();

		if ($user instanceof UserEntity) {
			$this->user = $user;
		}
	}

	public function setLastActive() {
		if ($this->user) {
			$this->user->setLastActive(new \DateTime());
			$this->em->flush();
		}
		
		return $this;
	}
}
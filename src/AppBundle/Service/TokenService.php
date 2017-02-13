<?php

namespace AppBundle\Service;

use AppBundle\Entity\TokenEntity;
use AppBundle\Entity\UserEntity;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

class TokenService {

	protected $em = null;
	protected $container = null;

	public function __construct(EntityManager $em, ContainerInterface $container) {
		$this->em = $em;
		$this->container = $container;
	}

	public function generateTokenValue() {
		return crypt(uniqid(), $this->container->getParameter('secret'));
	}

	// move it to user service
	public function createTokenForRegister(UserEntity $user) {
		$token = new TokenEntity($user, TokenEntity::$ACTION_REGISTER, $this->generateTokenValue());
		$this->em->persist($token);
		$this->em->flush();

		return $token;
	}

	// move it to user service
	public function createTokenForRestore(UserEntity $user) {
		$token = new TokenEntity($user, TokenEntity::$ACTION_RESTORE, $this->generateTokenValue());
		$this->em->persist($token);
		$this->em->flush();

		return $token;
	}

	public function getTokenByValue(string $value) {
		return $this->em->getRepository('AppBundle:TokenEntity')->findOneByValue($value);
	}

	// move it to token entity
	public function isTokenValid(TokenEntity $token) {
		$dateTimeOffset = (new \DateTime())->modify('-24 hour');

        return $token->getCreated() > $dateTimeOffset;
	}

	public function removeToken(TokenEntity $token) {
		$this->em->remove($token);
		$this->em->flush();

		return $this;
	}
}
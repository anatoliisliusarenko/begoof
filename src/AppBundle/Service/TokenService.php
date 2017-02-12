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

	public function createTokenForRegister(UserEntity $user) {
		$token = new TokenEntity($user, TokenEntity::$ACTION_REGISTER, $this->generateTokenValue());
		$this->em->persist($token);
		$this->em->flush();

		return $token;
	}

	public function createTokenForRestore(UserEntity $user) {
		$token = new TokenEntity($user, TokenEntity::$ACTION_RESTORE, $this->generateTokenValue());
		$this->em->persist($token);
		$this->em->flush();

		return $token;
	}

	public function getTokenByValue(string $value) {
		return $this->em->getRepository('AppBundle:TokenEntity')->findOneByValue($value);
	}

	public function isTokenExpired(TokenEntity $token) {
		$interval = $token->getCreated()->diff(new \DateTime());
        $differenceInHours = $interval->y*365*24 + $interval->m*30*24 + $interval->d*24 + $interval->h;

        return $differenceInHours > 24;
	}

	public function removeToken(TokenEntity $token) {
		$this->em->remove($token);
		$this->em->flush();

		return $this;
	}
}
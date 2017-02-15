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

	public function getTokenByValue(string $value) {
		return $this->em->getRepository('AppBundle:TokenEntity')->findOneByValue($value);
	}

	public function removeToken(TokenEntity $token) {
		$this->em->remove($token);
		$this->em->flush();

		return $this;
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

	public function clearExpiredRestoreTokens(UserEntity $user) {
		$this->em->getRepository('AppBundle:TokenEntity')
			  	 ->createQueryBuilder('t')
			  	 ->delete('AppBundle:TokenEntity', 't')
			  	 ->where('t.userId = :userId AND t.action = :action AND t.created < :datetimeOffset')
			  	 ->setParameter('userId', $user->getId())
			  	 ->setParameter('action', TokenEntity::$ACTION_RESTORE)
			  	 ->setParameter('datetimeOffset', (new \DateTime())->modify('-24 hour'))
			  	 ->getQuery()
			  	 ->getResult();

		return $this;
	}
}
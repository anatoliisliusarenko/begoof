<?php

namespace AppBundle\Service;

use AppBundle\Entity\UserEntity;
use AppBundle\Entity\TokenEntity;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\DependencyInjection\ContainerInterface;

class UserService {

	protected $em = null;
	protected $user = null;
	protected $container = null;
	protected $tokenService = null;

	public function __construct(EntityManager $em, TokenStorage $tokenStorage, ContainerInterface $container, TokenService $tokenService) {
		$this->em = $em;
		$this->container = $container;
		$this->tokenService = $tokenService;
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

	public function createUser(string $fullName, string $email, string $plainPassword) {
		$encoder = $this->container->get('security.password_encoder');
		$user = new UserEntity();
		$password = $encoder->encodePassword($user, $plainPassword);

		$user->setUsername($email);
		$user->setEmail($email);
		$user->setPassword($password);
		$user->setFullName($fullName);

		$this->em->persist($user);
		$this->em->flush();

		return $user;
	}

	public function getUserByEmail(string $email) {
		return $this->em->getRepository('AppBundle:UserEntity')->findOneByEmail($email);
	}

	public function getUserByUsernameOrEmail(string $username, string $email) {
		return $this->em->getRepository('AppBundle:UserEntity')
						->createQueryBuilder('u')
						->where('u.username = :username OR u.email = :email')
						->setParameter('username', $username)
						->setParameter('email', $username)
						->getQuery()
						->getOneOrNullResult();
	}

	public function removeUser(UserEntity $user) {
		$this->em->remove($user);
		$this->em->flush();

		return $this;
	}

	public function activateUser(UserEntity $user) {
		$user->setActive(true);
		$this->em->flush();

		return $this;
	}

	public function generateTemporaryPassword(UserEntity $user) {
		$temporaryPassword = $this->tokenService->generateTokenValue();
        $encoder = $this->container->get('security.password_encoder');
        $password = $encoder->encodePassword($user, $temporaryPassword);
        $user->setPassword($password);
        $this->em->flush();

        return $temporaryPassword;
	}

	public function hasValidRestoreToken(UserEntity $user) {
		//maybe make it easier
		return !!$this->em->getRepository('AppBundle:TokenEntity')
						  ->createQueryBuilder('t')
						  ->where('t.userId = :userId AND t.action = :action AND t.created > :datetimeOffset')
						  ->setParameter('userId', $user->getId())
						  ->setParameter('action', TokenEntity::$ACTION_RESTORE)
						  ->setParameter('datetimeOffset', (new \DateTime())->modify('-24 hour'))
						  ->getQuery()
						  ->getOneOrNullResult();
	}
}
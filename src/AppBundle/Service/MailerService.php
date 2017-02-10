<?php

namespace AppBundle\Service;
use Symfony\Component\Templating\EngineInterface;
use AppBundle\Entity\UserEntity;
use AppBundle\Entity\TokenEntity;

class MailerService {

	private $mailer = null;
	private $templating = null;

	public function __construct(\Swift_Mailer $mailer, EngineInterface $templating) {
		$this->mailer = $mailer;
		$this->templating = $templating;
	}

	public function sendRegisterToken(UserEntity $user, TokenEntity $token) {
		$message = \Swift_Message::newInstance()
					->setSubject('Registration Token | begoof.com')
					->setFrom('support@begoof.com')
					->setTo($user->getEmail())
					->setBody($this->templating->render('AppBundle:EmailTemplates:send_register_token.html.twig', ['fullName' => $user->getFullName(), 'token' => $token->getValue()]), 'text/html');

		$this->mailer->send($message);
	}

	public function sendRestoreToken(UserEntity $user, TokenEntity $token) {
		$message = \Swift_Message::newInstance()
					->setSubject('Restoration Token | begoof.com')
					->setFrom('support@begoof.com')
					->setTo($user->getEmail())
					->setBody($this->templating->render('AppBundle:EmailTemplates:send_restore_token.html.twig', ['fullName' => $user->getFullName(), 'token' => $token->getValue()]), 'text/html');

		$this->mailer->send($message);
	}

	public function sendTemporaryPassword(UserEntity $user, string $password) {
		$message = \Swift_Message::newInstance()
					->setSubject('Temporary Password | begoof.com')
					->setFrom('support@begoof.com')
					->setTo($user->getEmail())
					->setBody($this->templating->render('AppBundle:EmailTemplates:send_temporary_password.html.twig', ['fullName' => $user->getFullName(), 'password' => $password]), 'text/html');

		$this->mailer->send($message);
	}

}
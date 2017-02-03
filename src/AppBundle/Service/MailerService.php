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

}
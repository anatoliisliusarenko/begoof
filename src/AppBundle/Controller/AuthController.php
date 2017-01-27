<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\LoginForm;
use AppBundle\Object\LoginObject;
use AppBundle\Form\RegisterForm;
use AppBundle\Object\RegisterObject;
use AppBundle\Entity\UserEntity;

class AuthController extends Controller {
	
    public function loginAction(Request $request) {
    	$loginObject = new LoginObject();

    	$form = $this->createForm(LoginForm::class, $loginObject, ['action' => $this->generateUrl('login'), 'method' => 'POST']);

    	$form->handleRequest($request);
    	
    	//var_dump($this->get('security.authentication_utils'));



    	if ($form->isSubmitted() && $form->isValid()) {
    		echo "HERE";
    	} else if ($form->isSubmitted()) {
    		echo "HERE - 2";
    	}

    	$error = $this->get('security.authentication_utils')->getLastAuthenticationError();

        return $this->render('AppBundle:Auth:login.html.twig', [
        	'error' => $error,
        	'form' => $form->createView()
        ]);
    }

    public function registerAction(Request $request) {

    	$registerObject = new RegisterObject();
    	$form = $this->createForm(RegisterForm::class, $registerObject, ['action' => $this->generateUrl('register'), 'method' => 'POST']);
    	$form->handleRequest($request);

    	if ($form->isSubmitted() && $form->isValid()) {
    		$em = $this->getDoctrine()->getManager();
    		$encoder = $this->container->get('security.password_encoder');
    		$user = new UserEntity();
    		$email = $registerObject->getEmail();
    		$username = substr($email, 0, strpos($email, "@"));
    		$now = new \DateTime();
    		$password = $encoder->encodePassword($user, $registerObject->getPassword());

    		$user->setUsername($username);
    		$user->setEmail($email);
    		$user->setPassword($password);
    		$user->setFullName($registerObject->getFullName());
    		$user->setRegistered($now);
    		$user->setLastActive($now);
    		$user->setActive(false);
    		$user->setRole('ROLE_USER');

    		$em->persist($user);
    		$em->flush();

    		//send confirmation email

    		$this->addFlash('success', 'User was created, but it is blocked. Email was sent to confirm your person. Please check inbox.');

    		return $this->redirectToRoute('login');
    	}

    	return $this->render('AppBundle:Auth:register.html.twig', [
        	'form' => $form->createView()
        ]);
    }

    public function restoreAction(Request $request) {
    	return $this->render('AppBundle:Auth:restore.html.twig', [
        	
        ]);
    }
}

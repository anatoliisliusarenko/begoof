<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\LoginForm;
use AppBundle\Form\RegisterForm;
use AppBundle\Entity\UserEntity;
use AppBundle\Entity\TokenEntity;

class AuthController extends Controller {
	
    public function loginAction(Request $request) {
    	$user = new UserEntity();

    	$form = $this->createForm(LoginForm::class, $user, ['action' => $this->generateUrl('login'), 'method' => 'POST']);

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

    	$user = new UserEntity();
    	$form = $this->createForm(RegisterForm::class, $user, ['action' => $this->generateUrl('register'), 'method' => 'POST']);
    	$form->handleRequest($request);

    	if ($form->isSubmitted() && $form->isValid()) {
    		$em = $this->getDoctrine()->getManager();
    		$encoder = $this->container->get('security.password_encoder');
    		$newUser = new UserEntity();
    		$email = $user->getEmail();
    		$password = $encoder->encodePassword($newUser, $user->getPassword());

    		$newUser->setUsername($email);
    		$newUser->setEmail($email);
    		$newUser->setPassword($password);
    		$newUser->setFullName($user->getFullName());

    		$em->persist($newUser);
    		$em->flush();

    		$token = new TokenEntity($newUser);
    		$em->persist($token);
    		$em->flush();

    		$this->get('app.service.mailer')->sendRegisterToken($newUser, $token);

    		$this->addFlash('success', 'User was successfully created, but it is blocked for now. Email was sent to confirm your person. Please check inbox.');

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

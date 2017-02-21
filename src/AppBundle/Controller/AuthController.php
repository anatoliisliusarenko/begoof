<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\LoginForm;
use AppBundle\Form\RegisterForm;
use AppBundle\Form\RestoreForm;
use AppBundle\Entity\UserEntity;
use AppBundle\Entity\TokenEntity;

use Symfony\Component\Form\FormError;

class AuthController extends Controller {
	
    public function loginAction(Request $request) {
    	$form = $this->createForm(LoginForm::class, null, ['action' => $this->generateUrl('login'), 'method' => 'POST']);

    	$form->handleRequest($request);
    	
    	//var_dump($this->get('security.authentication_utils'));


    	if ($form->isSubmitted() && $form->isValid()) {
    		die("HERE");
    		//echo "HERE";
    	} else if ($form->isSubmitted()) {
    		die("HERE - 2");
    		//echo "HERE - 2";
    	}



    	$error = $this->get('security.authentication_utils')->getLastAuthenticationError();

    	if ($error) {
    		$form->get('username')->addError(new FormError($error->getMessageKey()));

    		print(var_dump($error->getMessageKey()));

    		$form->get('username')->setData($this->get('security.authentication_utils')->getLastUsername());

    	}

        return $this->render('AppBundle:Auth:login.html.twig', [
        	'form' => $form->createView()
        ]);
    }

    public function registerAction(Request $request) {
    	
    	$form = $this->createForm(RegisterForm::class, null, ['action' => $this->generateUrl('register'), 'method' => 'POST']);
    	$form->handleRequest($request);

    	if ($form->isSubmitted() && $form->isValid()) {
    		$user = $form->getData();

			$newUser = $this->get('app.service.user')->createUser($user['full_name'], $user['email'], $user['password']);
			$token = $this->get('app.service.token')->createTokenForRegister($newUser);

    		$this->get('app.service.mailer')->sendRegisterToken($newUser, $token);

    		$this->addFlash('success', 'User was successfully created, but is blocked for now. Email has been sent to confirm your person. Please check inbox.');

    		return $this->redirectToRoute('login');
    		
    	}

    	return $this->render('AppBundle:Auth:register.html.twig', [
        	'form' => $form->createView()
        ]);
    }

    public function restoreAction(Request $request) {
    	
    	$form = $this->createForm(RestoreForm::class, null, ['action' => $this->generateUrl('restore'), 'method' => 'POST']);
    	$form->handleRequest($request);

    	if ($form->isSubmitted() && $form->isValid()) {
    		$data = $form->getData();
    		// Can't be null, because form is valid. Form has custom validation.

    		// try to get it from Form class
    		$user = $this->get('app.service.user')->getUserByUsernameOrEmail($data['username'], $data['username']);

			if (!$this->get('app.service.user')->hasValidRestoreToken($user)) {
				$this->get('app.service.token')->clearExpiredRestoreTokens($user);
				$token = $this->get('app.service.token')->createTokenForRestore($user);

    			$this->get('app.service.mailer')->sendRestoreToken($user, $token);

				$this->addFlash('success', 'User was successfully found, but still has old password. Email has been sent to confirm your person. Please check inbox.');

    			return $this->redirectToRoute('login');
			} else {
				$this->addFlash('success', 'Email has been already sent to confirm your person. Please check inbox.');

				return $this->redirectToRoute('login');
			}
    	}

    	return $this->render('AppBundle:Auth:restore.html.twig', [
        	'form' => $form->createView()
        ]);
    }
}

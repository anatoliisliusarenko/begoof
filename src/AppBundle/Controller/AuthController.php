<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\LoginForm;
use AppBundle\Form\RegisterForm;
use AppBundle\Form\RestoreForm;
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
    	$error = '';

    	$user = new UserEntity();
    	$form = $this->createForm(RegisterForm::class, $user, ['action' => $this->generateUrl('register'), 'method' => 'POST']);
    	$form->handleRequest($request);

    	if ($form->isSubmitted() && $form->isValid()) {
    		$existingUser = $this->get('app.service.user')->getUserByEmail($user->getEmail());

    		if ($existingUser == null) {
    			$newUser = $this->get('app.service.user')->createUser($user->getFullName(), $user->getEmail(), $user->getPassword());
    			$token = $this->get('app.service.token')->createTokenForRegister($newUser);

	    		$this->get('app.service.mailer')->sendRegisterToken($newUser, $token);

	    		$this->addFlash('success', 'User was successfully created, but is blocked for now. Email has been sent to confirm your person. Please check inbox.');

	    		return $this->redirectToRoute('login');
    		} else {
    			// change to form invalidation
    			$error = 'User with this email address is already existing.';
    		}
    	}

    	return $this->render('AppBundle:Auth:register.html.twig', [
        	'form' => $form->createView(),
        	'error' => $error
        ]);
    }

    public function restoreAction(Request $request) {
    	/*$user = new UserEntity();
    	$form = $this->createForm(RestoreForm::class, $user, ['action' => $this->generateUrl('restore'), 'method' => 'POST']);
    	$form->handleRequest($request);*/

    	$error = '';

    	$username = $request->get('_username');


    	//if ($form->isSubmitted() && $form->isValid()) {
    	if (!empty($username)) {
    		$user = $this->get('app.service.user')->getUserByUsernameOrEmail($username, $username);

			if ($user != null) {
				$token = $this->get('app.service.token')->createTokenForRestore($user);

    			$this->get('app.service.mailer')->sendRestoreToken($user, $token);

				$this->addFlash('success', 'User was successfully found, but still has old password. Email has been sent to confirm your person. Please check inbox.');

    			return $this->redirectToRoute('login');
			} else {
				// change to form invalidation
				$error = 'Username/Email not found';
			}
    	}

    	return $this->render('AppBundle:Auth:restore.html.twig', [
        	//'form' => $form->createView()
        	'error' => $error
        ]);
    }
}

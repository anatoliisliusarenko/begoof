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

    	$user = new UserEntity();
    	$form = $this->createForm(RegisterForm::class, $user, ['action' => $this->generateUrl('register'), 'method' => 'POST']);
    	$form->handleRequest($request);

    	if ($form->isSubmitted() && $form->isValid()) {

    		// check for existed email address

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

    		$token = new TokenEntity($newUser, TokenEntity::$ACTION_REGISTER);
    		$em->persist($token);
    		$em->flush();

    		$this->get('app.service.mailer')->sendRegisterToken($newUser, $token);

    		$this->addFlash('success', 'User was successfully created, but is blocked for now. Email has been sent to confirm your person. Please check inbox.');

    		return $this->redirectToRoute('login');
    	}

    	return $this->render('AppBundle:Auth:register.html.twig', [
        	'form' => $form->createView()
        ]);
    }

    public function restoreAction(Request $request) {
    	/*$user = new UserEntity();
    	$form = $this->createForm(RestoreForm::class, $user, ['action' => $this->generateUrl('restore'), 'method' => 'POST']);
    	$form->handleRequest($request);*/


    	$username = $request->get('_username');


    	//if ($form->isSubmitted() && $form->isValid()) {
    	if (!empty($username)) {
    		$user = $this->getDoctrine()
    					->getRepository('AppBundle:UserEntity')
						->createQueryBuilder('u')
						->where('u.username = :username OR u.email = :email')
						->setParameter('username', $username)
						->setParameter('email', $username)
						->getQuery()
						->getOneOrNullResult();

			if ($user != null) {
				$em = $this->getDoctrine()->getManager();
				$token = new TokenEntity($user, TokenEntity::$ACTION_RESTORE);
    			$em->persist($token);
    			$em->flush();

    			$this->get('app.service.mailer')->sendRestoreToken($user, $token);

				$this->addFlash('success', 'User was successfully found, but still has old password. Email has been sent to confirm your person. Please check inbox.');

    			return $this->redirectToRoute('login');
			}

    		// user not found message
    	}

    	return $this->render('AppBundle:Auth:restore.html.twig', [
        	//'form' => $form->createView()
        ]);
    }
}

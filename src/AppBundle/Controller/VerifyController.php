<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\UserEntity;
use AppBundle\Entity\TokenEntity;

class VerifyController extends Controller {
	
    public function registerAction(Request $request) {
        $tokenValue = $request->get('token');
            
    	if (!empty($tokenValue)) {
            $token = $this->get('app.service.token')->getTokenByValue($tokenValue);

            if ($token != null && $token->isForRegister()) {
                $user = $token->getUser();
                $tokenIsExpired = $this->get('app.service.token')->isTokenExpired($token);

                if (!$tokenIsExpired) {
                    $this->get('app.service.token')->removeToken($token);
                    $this->get('app.service.user')->activateUser($user);

                    $this->addFlash('success', 'User has been activated. Please provide your credentials to log in.');
                } else {
                    $this->get('app.service.token')->removeToken($token);
                    $this->get('app.service.user')->removeUser($user);

                    $this->addFlash('error', 'Token is expired. Please register again.');

                    return $this->redirectToRoute('register');
                }

            }
        }

        return $this->redirectToRoute('login');
    }

    public function restoreAction(Request $request) {
    	$tokenValue = $request->get('token');

        if (!empty($tokenValue)) {
            $token = $this->get('app.service.token')->getTokenByValue($tokenValue);

            if ($token != null && $token->isForRestore()) {
                $user = $token->getUser();
                $tokenIsExpired = $this->get('app.service.token')->isTokenExpired($token);

                if (!$tokenIsExpired) {
                    $temporaryPassword = $this->get('app.service.user')->generateTemporaryPassword($user);
                    $this->get('app.service.token')->removeToken($token);
                    
                    $this->get('app.service.mailer')->sendTemporaryPassword($user, $temporaryPassword);

                    $this->addFlash('success', 'Email has been sent with temporary password. Please check inbox and provide your credentials to log in.');
                } else {
                    $this->get('app.service.token')->removeToken($token);
                    
                    $this->addFlash('error', 'Token is expired. Please restore again.');

                    return $this->redirectToRoute('restore');
                }
            }
        }

        return $this->redirectToRoute('login');
    }
}

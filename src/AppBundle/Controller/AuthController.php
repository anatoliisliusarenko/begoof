<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class AuthController extends Controller {
	
    public function loginAction(Request $request) {

    	$error = $this->get('security.authentication_utils')->getLastAuthenticationError();

        return $this->render('AppBundle:Auth:login.html.twig', [
        	'error' => $error
        ]);
    }
}

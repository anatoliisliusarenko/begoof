<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller {



    public function indexAction(Request $request) {
        
    	$this->get('app.service.user')->setLastActive();
    	
        $users = $this->getDoctrine()->getRepository('AppBundle:UserEntity')->findAll();

        return $this->render('AppBundle:Default:index.html.twig', [
            "users" => $users
        ]);
    }
}

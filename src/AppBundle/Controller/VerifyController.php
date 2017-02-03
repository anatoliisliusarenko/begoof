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
            $token = $this->getDoctrine()->getRepository('AppBundle:TokenEntity')->findOneByValue($tokenValue);

            if ($token != null) {
                $user = $token->getUser();
                $em = $this->getDoctrine()->getManager();
                $interval = $token->getCreated()->diff(new \DateTime());
                $differenceInHours = $interval->y*365*24 + $interval->m*30*24 + $interval->d*24 + $interval->h;

                if ($differenceInHours < 24) {
                    $em->remove($token);
                    $user->setActive(true);
                    $em->flush();

                    $this->addFlash('success', 'User has been activated. Please provide your credentials to log in.');
                } else {
                    $em->remove($token);
                    $em->remove($user);
                    $em->flush();

                    $this->addFlash('error', 'Token has expired. Please register again.');

                    return $this->redirectToRoute('register');
                }

            }
        }

        return $this->redirectToRoute('login');
    }

    public function restoreAction(Request $request) {
    	
        return $this->redirectToRoute('login');
    }
}

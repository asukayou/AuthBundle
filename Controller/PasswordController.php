<?php

namespace Ay\AuthBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

use Symfony\Component\HttpFoundation\Request;

use Ay\AuthBundle\Entity\Password;
use Ay\AuthBundle\Form\Type\PasswordType;

/**
 * @Route("/password")
 * @Security("has_role('ROLE_USER')")
 */
class PasswordController extends Controller {

    /**
     * @Route("/", name="password_index")
     * @Method({"GET", "POST"})
     */
    public function indexAction(Request $request) {

        $password = new Password();

        $form = $this->createForm(new PasswordType(), $password);

        if ($request->getMethod() == 'GET') {
            return $this->render('AyAuthBundle:Password:index.html.twig', array('form' => $form->createView()));
        }
        
        $form->handleRequest($request);
        if (!$form->isValid()) {
            return $this->render('AyAuthBundle:Password:index.html.twig', array('form' => $form->createView()));
        }

        $sf = $this->get('ay_auth.common.system_function');
        $user = $sf->getAuthUser();
        $salt = $sf->generateRandomString(8);
        $user->setPassword($sf->encodePassword($password->getPassword(), $salt));
        $user->setSalt($salt);
        $em = $this->getDoctrine()->getManager();
        $em->flush();
        return $this->redirect($this->generateUrl('index'));
    }

}

<?php

namespace Ay\AuthBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;

use Ay\AuthBundle\Entity\User;
use Ay\AuthBundle\Form\Type\UserType;

/**
 * @Route("/user")
 * @Security("has_role('ROLE_ADMIN')")
 */
class UserController extends Controller {

    /**
     * @Route("/", name="user_index")
     * @Template()
     */
    public function indexAction() {
        $users = $this->getDoctrine()
                ->getRepository('AyAuthBundle:User')
                ->findBy(array(), array('sort' => 'ASC'));

        if (!$users) {
            throw $this->createNotFoundException('User not found');
        }

        return array('users' => $users);
    }

    /**
     * @Route("/create", name="user_create")
     * @Method({"GET", "POST"})
     */
    public function createAction(Request $request) {
        $user = new User();

        $form = $this->createForm(new UserType(), $user);

        if ($request->getMethod() == 'GET') {
            return $this->render('AyAuthBundle:User:create.html.twig', array('form' => $form->createView()));
        }

        $form->handleRequest($request);

        if (!$form->isValid()) {
            return $this->render('AyAuthBundle:User:create.html.twig', array('form' => $form->createView()));
        }

        $sf = $this->get('ay_auth.common.system_function');
        $password = $sf->generateRandomString(8);
        $salt = $sf->generateRandomString(8);
        $user->setPassword($sf->encodePassword($password, $salt));
        $user->setSalt($salt);
        $user->setActive(true);
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();
        $this->sendMail($user, $password);
        $msg = $this->get('translator')->trans('This System sent e-mail for login.');
        $this->get('session')->getFlashBag()->add('ay_ses_msg', $msg);
        return $this->redirect($this->generateUrl('user_index'));
    }

    /**
     * @Route("/update/{id}", requirements={"id" = "\d+"}, name="user_update")
     * @Method({"GET", "POST"})
     */
    public function updateAction(Request $request, $id) {
        $user = $this->getDoctrine()
                ->getRepository('AyAuthBundle:User')
                ->find($id);

        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }

        $form = $this->createForm(new UserType(), $user, array(
            'validation_groups' => array('Default', 'update')
        ));

        if ($request->getMethod() == 'GET') {
            return $this->render('AyAuthBundle:User:update.html.twig', array(
                        'form' => $form->createView(),
                        'id' => $id
            ));
        }

        $form->handleRequest($request);

        if (!$form->isValid()) {
            return $this->render('AyAuthBundle:User:update.html.twig', array(
                        'form' => $form->createView(),
                        'id' => $id
            ));
        }

        $em = $this->getDoctrine()->getManager();
        $em->flush();

        return $this->redirect($this->generateUrl('user_index'));
    }

    /**
     * @Route("/resetpw/{id}", requirements={"id" = "\d+"}, name="user_reset_password")
     * @Method("GET")
     */
    public function resetPasswordAction($id) {
        $user = $this->getDoctrine()
                ->getRepository('AyAuthBundle:User')
                ->find($id);

        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }
        
        $sf = $this->get('ay_auth.common.system_function');
        $password = $sf->generateRandomString(8);
        $salt = $sf->generateRandomString(8);
        $user->setPassword($sf->encodePassword($password, $salt));
        $user->setSalt($salt);
        $em = $this->getDoctrine()->getManager();
        $em->flush();
        $this->sendMail($user, $password);
        $msg = $this->get('translator')->trans('This System sent e-mail for login.');
        $this->get('session')->getFlashBag()->add('ay_ses_msg', $msg);
        return $this->redirect($this->generateUrl('user_index'));
    }

    /**
     */
    private function sendMail(User $user, $passowrd) {
        $template = 'AyAuthBundle:User:email.txt.twig';
        $systemName = $this->container->getParameter('system_name');
        $systemMailAddress = $this->container->getParameter('system_mail_address');
        $subject = '[' . $systemName . '] ' .
                $this->get('translator')->trans('Welcome');
        $from = $systemMailAddress;
        $to = $user->getEmail();
        $params = array(
            'systemName' => $systemName,
            'userId' => $user->getUsername(),
            'userName' => $user->getName(),
            'password' => $passowrd,
            'systemMailAddress' => $systemMailAddress,
        );
        $mailDatas = array(
            'subject' => $subject,
            'from' => $from,
            'to' => $to,
            'body' => $this->renderView($template, $params),
        );
        $sf = $this->get('ay_auth.common.system_function');
        $sf->sendMail($this->get('mailer'), $template, $mailDatas);
    }

}

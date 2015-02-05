<?php

namespace Ay\AuthBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class DefaultController extends Controller {

    /**
     * @Route("/", name="index")
     * @Template()
     */
    public function indexAction() {
        return array();
    }

    /**
     * @Route("/secure", name="secure")
     * @Security("has_role('ROLE_USER')")
     * @Template()
     */
    public function secureAction() {
        return array();
    }

}

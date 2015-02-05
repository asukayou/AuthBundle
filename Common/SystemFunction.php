<?php

namespace Ay\AuthBundle\Common;

use Ay\AuthBundle\Entity\User;

/**
 */
class SystemFunction {

    private $doctrine;
    private $serviceContainer;
    private $user;
    private $encoderFactory;

    public function __construct($doctrine, $serviceContainer) {
        $this->doctrine = $doctrine;
        $this->serviceContainer = $serviceContainer;
        $this->user = $serviceContainer->get('security.context')->getToken()->getUser();
        $this->encoderFactory = $serviceContainer->get('security.encoder_factory');
    }

    /**
     */
    public function getAuthUser() {
        return $this->doctrine->getRepository('AyAuthBundle:User')
                        ->find($this->user->getId());
    }

    /**
     */
     public function generateRandomString($size) {
        $password_chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        $pin = '';
        for ($i = 0; $i < $size; $i++) {
            $pin .= substr($password_chars, mt_rand(0, strlen($password_chars) - 1), 1);
        }
        return $pin;
    }

    /**
     */
    public function sendMail($mailer, $template, $mailDatas) {
        $message = \Swift_Message::newInstance()
                ->setSubject($mailDatas['subject'])
                ->setFrom($mailDatas['from'])
                ->setTo($mailDatas['to'])
                ->setBody($mailDatas['body']);
        $mailer->send($message);
    }

    /**
     */
    public function encodePassword($password, $salt) {
        $encoder = $this->encoderFactory->getEncoder(new User());
        return $encoder->encodePassword($password, $salt);
    }

}

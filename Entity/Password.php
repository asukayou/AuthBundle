<?php

namespace Ay\AuthBundle\Entity;

use Symfony\Component\Validator\ExecutionContextInterface;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\Validator\Constraints as SecurityAssert;

/**
 * Password
 * @Assert\Callback(methods={"validateEqualPasswordConfirm"})
 *
 s */
class Password {

    /**
     * @var string
     *
     * @SecurityAssert\UserPassword()
     */
    private $oldPassword;

    /**
     * @var string
     *
     * @Assert\Length(min=6, max=32)
     */
    private $password;

    /**
     * @var string
     *
     * @Assert\Length(min=6, max=32)
     */
    private $passwordConfirm;
    
    
    function getOldPassword() {
        return $this->oldPassword;
    }

    function setOldPassword($oldPassword) {
        $this->oldPassword = $oldPassword;
    }

    /**
     * Set password
     *
     * @param string $password
     */
    public function setPassword($password) {
        $this->password = $password;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword() {
        return $this->password;
    }

    /**
     * Set passwordConfirm
     *
     * @param string $passwordConfirm
     */
    public function setPasswordConfirm($passwordConfirm) {
        $this->passwordConfirm = $passwordConfirm;
    }

    /**
     * Get passwordConfirm
     *
     * @return string 
     */
    public function getPasswordConfirm() {
        return $this->passwordConfirm;
    }

    /**
     */
    public function validateEqualPasswordConfirm(ExecutionContextInterface $context) {
        if ($this->getPassword() != $this->getPasswordConfirm()) {
            $context->addViolationAt('passwordConfirm', 'The password cannot match confirm one.', array(), 'validators');
        }
    }

}

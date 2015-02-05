<?php

namespace Ay\AuthBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PasswordType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('oldPassword', 'password', array(
            'attr' => array('class' => 'ay_input_password form-control'),
        ));
        $builder->add('password', 'password', array(
            'attr' => array('class' => 'ay_input_password form-control'),
        ));
        $builder->add('passwordConfirm', 'password', array(
            'attr' => array('class' => 'ay_input_password form-control'),
        ));
        $builder->add('submit', 'submit', array(
            'label' => 'Update',
            'attr' => array('class' => 'btn btn-primary'),
        ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Ay\AuthBundle\Entity\Password',
        ));
    }

    public function getName() {
        return 'password';
    }

}

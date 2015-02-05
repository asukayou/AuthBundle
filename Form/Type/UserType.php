<?php

namespace Ay\AuthBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $userIdAttr = array('attr' => array('class' => 'form-control'));
        if (isset($options['validation_groups']) && in_array('update', $options['validation_groups'])) {
            $userIdAttr = array('attr' => array('class' => 'form-control disable_bg_color'), 'read_only' => true);
        }
        $builder->add('username', 'text', $userIdAttr);
        $builder->add('name', 'text', array(
            'attr' => array('class' => 'form-control'),
        ));
        $mailRequired = isset($options['validation_groups']) && in_array('system', $options['validation_groups']);
        $builder->add('email', 'email', array(
            'required' => $mailRequired,
            'attr' => array('class' => 'form-control'),
        ));
        if (isset($options['validation_groups']) && in_array('system', $options['validation_groups'])) {
            $builder->add('password', 'password', array(
                'attr' => array('class' => 'form-control'),
            ));
        }
        $builder->add('admin', 'checkbox', array(
            'required' => false,
        ));
        $builder->add('active', 'checkbox', array(
            'required' => false,
        ));
        $builder->add('sort', 'integer', array(
            'required' => false,
        ));
        $builder->add('memo', 'textarea', array(
            'required' => false,
            'attr' => array('class' => 'form-control'),
        ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Ay\AuthBundle\Entity\User',
        ));
    }

    public function getName() {
        return 'user';
    }

}

<?php
namespace Ay\AuthBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Ay\AuthBundle\Entity\User;

class LoadData implements FixtureInterface, ContainerAwareInterface {

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
    
    public function load(ObjectManager $om) {
        $user = new User();
        $user->setUsername('admin');
        $user->setName('管理者');
        $user->setPassword('34c49dbe2ea6a1c02e69370695fd6c3a04c47f763e3535b74fc695dbf01ab074219c0763b8afd593ab69a5a2bddd5938f65fce0202a7343f279e3ba0889b13d8');
        $user->setSalt('D4wIEUEX');
        $user->setEmail($this->container->getParameter('system_mail_address'));
        $user->setSort(100);
        $user->setAdmin(true);
        $user->setActive(true);
        $om->persist($user);

        $user = new User();
        $user->setUsername('user01');
        $user->setName('ユーザー０１');
        $user->setPassword('34c49dbe2ea6a1c02e69370695fd6c3a04c47f763e3535b74fc695dbf01ab074219c0763b8afd593ab69a5a2bddd5938f65fce0202a7343f279e3ba0889b13d8');
        $user->setSalt('D4wIEUEX');
        $user->setEmail($this->container->getParameter('system_mail_address'));
        $user->setSort(1000);
        $user->setAdmin(false);
        $user->setActive(true);
        $om->persist($user);

        $om->flush();
    }

}

<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ay\AuthBundle\EventListener;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * WebDebugToolbarListener injects the Web Debug Toolbar.
 *
 * The onKernelResponse method must be connected to the kernel.response event.
 *
 * The WDT is only injected on well-formed HTML (with a proper </body> tag).
 * This means that the WDT is never included in sub-requests or ESI requests.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class NavbarListener implements EventSubscriberInterface {
//
//    const DISABLED = 1;
//    const ENABLED = 2;

    protected $twig;
    protected $container;

    public function __construct(\Twig_Environment $twig, ContainerInterface $container) {
        $this->twig = $twig;
        $this->container = $container;
    }
//
//    public function isEnabled() {
//        return self::DISABLED !== $this->mode;
//    }

    public function onKernelResponse(FilterResponseEvent $event) {
        $response = $event->getResponse();
        $request = $event->getRequest();

        if (!$event->isMasterRequest()) {
            return;
        }

        // do not capture redirects or modify XML HTTP Requests
        if ($request->isXmlHttpRequest()) {
            return;
        }
//
//        if (self::DISABLED === $this->mode || !$response->headers->has('X-Debug-Token') || $response->isRedirection() || ($response->headers->has('Content-Type') && false === strpos($response->headers->get('Content-Type'), 'html')) || 'html' !== $request->getRequestFormat()
//        ) {
//            return;
//        }

        if (false === $this->container->get('security.context')->isGranted('ROLE_USER')) {
            return;
        }

        $this->injectNavbar($response);
    }

    /**
     * Injects the web debug toolbar into the given Response.
     *
     * @param Response $response A Response instance
     */
    protected function injectNavbar(Response $response) {
        $content = $response->getContent();
        $pos = strripos($content, '</head>');
        if (false !== $pos) {
            $navbar = "\n" . $this->twig->render(
                            '@AyAuth/Security/bootstrap.html.twig'
                    ) . "\n";
            $content = substr($content, 0, $pos) . $navbar . substr($content, $pos);
            $pos = strripos($content, '<body>');
            if (false !== $pos) {
                $navbar = "\n" . $this->twig->render(
                                '@AyAuth/Security/navbar.html.twig'
                        ) . "\n";
                $content = substr($content, 0, $pos + 6) . $navbar . substr($content, $pos + 6);
                $response->setContent($content);
            }
        }
    }

    public static function getSubscribedEvents() {
        return array(
            KernelEvents::RESPONSE => array('onKernelResponse', -128),
        );
    }

}

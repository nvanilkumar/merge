<?php

namespace AppBundle\Security\Authentication\Handler;

use Symfony\Component\Security\Http\Logout\LogoutSuccessHandlerInterface;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Router;
use Symfony\Component\HttpFoundation\Session\Session;

class LogoutSuccessHandler implements LogoutSuccessHandlerInterface {

    private $session;
    private $router;
    private $security;

    public function __construct(Router $router, SecurityContext $security, Session $session) {
        $this->router = $router;
        $this->security = $security;
        $this->session = $session;
    }

    public function onLogoutSuccess(Request $request) {
        $this->session->getFlashBag()->add('notice', 'You have been successfully logged out, See you soon.');
        $response = new RedirectResponse($this->router->generate('_home'));
        return $response;
    }

}

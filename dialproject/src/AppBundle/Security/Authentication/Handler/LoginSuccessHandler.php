<?php

namespace AppBundle\Security\Authentication\Handler;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Router;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Doctrine\ORM\EntityManager;
use AppBundle\Security\Authentication\Handler\ServiceContainer;
use Symfony\Component\HttpFoundation\Session\Session;

class LoginSuccessHandler implements AuthenticationSuccessHandlerInterface {

    protected
            $router,
            $security,
            $session;

    //  private $em;
    public function __construct(Router $router, SecurityContext $security, $container, Session $session) {
        $this->router = $router;
        $this->security = $security;
        $this->container = $container;
        $this->session = $session;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token) {
        // URL for redirect the user to where they were before the login process begun if you want.
        // $referer_url = $request->headers->get('referer');
        // Default target for unknown roles. Everyone else go there.
        $url = '_home';
        //$token->getUser()->setLastlogin(getdate());

        $this->container->get('doctrine')->getEntityManager()->flush();
        $this->session->getFlashBag()->add('notice', 'Welcome '.$token->getUser()->getFullName().'! You have been successfully logged in.');
        if ($this->security->isGranted('ROLE_AGENT')) {
            $url = '_agent_dashboard';
        }else if($this->security->isGranted('ROLE_ADMIN')){
            $url = '_admin_dashboard';
        } else {
            $url = '_choose_account';
        }
        $response = new RedirectResponse($this->router->generate($url));

        return $response;
    }

}

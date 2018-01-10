<?php

namespace AdminBundle\EventListener;

use Symfony\Component\Security\Http\Logout\LogoutSuccessHandlerInterface;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;

class LogoutListener implements LogoutSuccessHandlerInterface {

    private $security;

    public function __construct(SecurityContext $security, $em, $router) {
        $this->security = $security;
        $this->em = $em;
        $this->router = $router;
    }

    public function onLogoutSuccess(Request $request) {

        $connection = $this->em->getConnection();
        $usr = $this->security->getToken()->getUser();
        $ipaddr = $request->server->get("REMOTE_ADDR");
        $log = "Logged Out from <strong>$ipaddr</strong>.";
        $statement = $connection->prepare("  INSERT INTO dah_activity_log (adminid, message, logged_on)
VALUES (" . $usr->getAdminid() . ", '" . $log . "', " . time() . ")  ");
        $statement->execute();
        $response = new RedirectResponse($this->router->generate('_admin_login'));

        return $response;
    }

}

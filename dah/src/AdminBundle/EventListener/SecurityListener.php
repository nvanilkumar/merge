<?php

namespace AdminBundle\EventListener;

use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\HttpFoundation\Request;

class SecurityListener {

    public function __construct(SecurityContextInterface $security, Session $session, $em, $request) {
        $this->security = $security;
        $this->session = $session;
        $this->em = $em;
        $this->request = $request;
    }

    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event) {
        $connection = $this->em->getConnection();
        $usr = $this->security->getToken()->getUser();
        $className = $this->em->getClassMetadata(get_class($usr))->getName();
        //echo $className;
        //exit;
        if($className == 'AdminBundle\Entity\DahAdmin'){
        $ipaddr = $this->request->getCurrentRequest()->server->get("REMOTE_ADDR");
        $time = time();
        $usr->setLastlogin($time);
        
        $usr->setLastloginIp($ipaddr);
        $this->em->persist($usr);
        $this->em->flush();
        $log = "Logged in from <strong>$ipaddr</strong> .";
        $statement = $connection->prepare("  INSERT INTO dah_activity_log (adminid, message, logged_on)
VALUES (" . $usr->getAdminid() . ", '" . $log . "', " . $time . ")  ");
        $statement->execute();
        }
    }

}

<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\ExpressionLanguage\Expression;
// these import the "@Route" and "@Template" annotations 
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Session\Session;

class DefaultController extends Controller {

    public $title = "ScopDial";
    public static $template = "AppBundle:Templates:default.html.twig";
    public $data = array();
    private $session;

    public function __construct() {
        $this->data['extend_view'] = self::$template;
    }

    /**
     * @Route("/", name="_home")
     * @Template("AppBundle:Default:login.html.twig")
     */
    public function loginAction() {
        $this->data['title'] = 'Login ScopDial';
        $request = $this->getRequest();
        $session = $request->getSession();

        $sc = $this->get('security.context');
        $access = $sc->isGranted(new Expression('is_remember_me() or is_fully_authenticated()'));

        //check if user alreay logged in
        if (true === $access) {
            if ($sc->isGranted('ROLE_AGENT')) {
                $url = '_agent_dashboard';
            } else if ($sc->isGranted('ROLE_ADMIN')) {
                $url = '_admin_dashboard';
            }
            return $this->redirect($this->generateUrl($url));
        }
        // get the login error if there is one
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(
                    SecurityContext::AUTHENTICATION_ERROR
            );
        } else {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        }

        // last username entered by the user
        $lastUsername = (null === $session) ? '' : $session->get(SecurityContext::LAST_USERNAME);

        $this->data['error'] = $error;
        $this->data['last_username'] = $lastUsername;

        return $this->data;
    }

    /**
     * @Route("/login_check", name="_login_check")
     */
    public function loginCheckAction() {
        
    }

    /**
     * @Route("/logout", name="_logout")
     */
    public function logoutAction() {
        return $this->redirect($this->generateUrl('_home'));
    }

    /**
     * @Route("/access_denied", name="access_denied")
     */
    public function accessDeniedAction() {
        $request = $this->getRequest();
        $session = $request->getSession();
        //clear flash bag if any notice exist;

        $session->getFlashBag()->clear();
        $session->getFlashBag()->add('notice', 'Sorry, You don\'t have access to that page.');
        return $this->redirect($this->generateUrl('_home'));
    }

    /**
     * @Route("/generic/download/{filename}", name="_generic_download_act")
     * @return BinaryFileResponse
     */
    public function downloaderAction($filename) {

        $request = $this->get('request');
        $path = $this->get('kernel')->getRootDir() . "/../web/uploads/";
        $content = file_get_contents($path . $filename);

        $response = new Response();

        //set headers
        $response->headers->set('Content-Type', 'mime/type');
        $response->headers->set('Content-Disposition', 'attachment;filename="' . $filename);

        $response->setContent($content);
        return $response;
    }

}

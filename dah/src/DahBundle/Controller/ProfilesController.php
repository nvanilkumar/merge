<?php

namespace DahBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Session\Session;
use AdminBundle\Entity\DahEnquires;

class ProfilesController extends Controller {

    public static $template = "DahBundle:templates:default.html.twig";
    public $data = array();

    public function __construct() {
        $this->data['extend_view'] = self::$template;
    }

   

    /**
     * @Route("/profile/student/{uid}", name="_profile_student"  , defaults={"uid"=0})
     * @Template("DahBundle:Profile:student.html.twig")
     */
    public function profileStudentAction(Request $request,$uid) {
        $session = $request->getSession();
        $common = $this->get('common_handler');
        $emailhand = $this->get('emails_handler');
        $error = array();
        $teachers = array();
        $em = $this->getDoctrine()->getManager();
        $validator = $this->get('validator');
        $connection = $em->getConnection();
       // $ref = $session->get('referrer');
       // if ($ref != '') {
       //     $session->remove('referrer');
       //     return $this->redirect($ref);
       // }
        $repository = $this->getDoctrine()->getRepository('AdminBundle:DahUsers');
        $user = $repository->findOneBy(
                array('uid' => $uid)
        );
        if (!$user) {
            $this->get('session')->getFlashBag()->add('success', ' Please choose a valid user.');
            return $this->redirect($this->generateUrl('_trainings'));
        }
        if ($user->getRole() == 'ROLE_STUDENT') {
            $repository = $this->getDoctrine()->getRepository('AdminBundle:DahStudentInfo');
        } else {
            $this->get('session')->getFlashBag()->add('success', ' Please choose a valid user.');
            return $this->redirect($this->generateUrl('_trainings'));
        }
        $userinfo = $repository->findOneByUid($user->getUid());

        $this->data['user'] = $user;
        $this->data['userinfo'] = $userinfo;
        $this->data['title'] = 'Administrator login';
        return $this->data;
    }

   /**
     * @Route("/profile/teacher/{uid}", name="_profile_teacher"  , defaults={"uid"=0})
     * @Template("DahBundle:Profile:teacher.html.twig")
     */
    public function profileTeacherAction(Request $request,$uid) {
        $session = $request->getSession();
        $common = $this->get('common_handler');
        $emailhand = $this->get('emails_handler');
        $error = array();
        $teachers = array();
        $em = $this->getDoctrine()->getManager();
        $validator = $this->get('validator');
        $connection = $em->getConnection();
      //  $ref = $session->get('referrer');
      //  if ($ref != '') {
      //      $session->remove('referrer');
      //      return $this->redirect($ref);
      //  }
        $repository = $this->getDoctrine()->getRepository('AdminBundle:DahUsers');
        $user = $repository->findOneBy(
                array('uid' => $uid)
        );
        if (!$user) {
            $this->get('session')->getFlashBag()->add('success', ' Please choose a valid user.');
            return $this->redirect($this->generateUrl('_trainings'));
        }
        if ($user->getRole() == 'ROLE_TEACHER') {
            $repository = $this->getDoctrine()->getRepository('AdminBundle:DahTeachersInfo');
        } else {
            $this->get('session')->getFlashBag()->add('success', ' Please choose a valid user.');
            return $this->redirect($this->generateUrl('_trainings'));
        }
        $userinfo = $repository->findOneByUid($user->getUid());
        $where = " where uid = " . $user->getUid() . " and dt.status='active' ";
        $statement = $connection->prepare("  select * from dah_trainings dt join dah_departments dp on dt.deptid = dp.deptid  " . $where . " order by dt.added_on desc limit 0,10 ");
        $statement->execute();
        $trainings = $statement->fetchAll();
         $this->data['trainings'] = $trainings;
        $this->data['user'] = $user;
        $this->data['userinfo'] = $userinfo;
        $this->data['title'] = 'Administrator login';
        return $this->data;
    }

}

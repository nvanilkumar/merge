<?php

namespace DahBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Session\Session;
use AdminBundle\Entity\DahEnquires;

class PageController extends Controller {

    public static $template = "DahBundle:templates:default.html.twig";
    public $data = array();

    public function __construct() {
        $this->data['extend_view'] = self::$template;
    }

   

    /**
     * @Route("/page/{link}", name="_pages")
     * @Template("DahBundle:Default:pages.html.twig")
     */
    public function pageAction($link) {
        $em = $this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()->getRepository('AdminBundle:DahContentPages');
        $pagedata = $repository->findOneBy(array('pageUrl' => $link));
        //print('<pre>');
        //print_r($page);
        //print('</pre>');
        //exit;
        $em = $this->getDoctrine()->getManager();
        $validator = $this->get('validator');
        $connection = $em->getConnection();
        $limit = 5;
        $page = 1;
        $where = " where dt.status='active'  ";
        $statement = $connection->prepare("  select * from dah_trainings dt join dah_departments dp on dt.deptid = dp.deptid join dah_users du on du.uid = dt.uid  " . $where . " order by dt.added_on desc limit " . (($page - 1) * $limit) . ",$limit  ");
        $statement->execute();
        $trainings = $statement->fetchAll();
        $this->data['trainings'] = $trainings;
        $todaysTimestamp = strtotime(date('d-m-Y'));
        $where = " where 1=1  and ( future_date is null or future_date <=  $todaysTimestamp ) ";
        $statement = $connection->prepare("  select * from dah_news " . $where . " order by added_on desc limit " . (($page - 1) * $limit) . ",$limit ");
        $statement->execute();
        $news = $statement->fetchAll();
        $this->data['news'] = $news;
        $this->data['page'] = $pagedata;
        $this->data['name'] = 'test';
        $this->data['meta'] = $page;
        return $this->data;
    }

   

}

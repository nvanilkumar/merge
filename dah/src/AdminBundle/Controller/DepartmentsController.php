<?php

namespace AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Session\Session;
use AdminBundle\Entity\DahDepartments;

class DepartmentsController extends Controller {

    public static $template = "AdminBundle:templates:default.html.twig";
    public $data = array();

    public function __construct() {
        $this->data['extend_view'] = self::$template;
    }

    /**
     * @Route("/secure/departments/{page}", name="_admin_departments",defaults={"page"=1})
     * @Template("AdminBundle:Departments:departments.html.twig")
     */
    public function departmentsAction(Request $request, $page) {
        $limit = 10;
        $key = '';
        $common = $this->get('common_handler');
        $error = array();
        $em = $this->getDoctrine()->getManager();
        $validator = $this->get('validator');
        $connection = $em->getConnection();
        $where = ' where 1=1 ';
        $key = trim($request->query->get('keyword'));

        if ($request->query->get('reset') != '') {
            return $this->redirect($this->generateUrl('_admin_departments'));
        }
        if ($key != '') {
            
            $where .= " and ( department like '%" . $key . "%')";
        } else {
           // echo $key;
           // exit;
        }
        $statement = $connection->prepare("  select * from dah_departments " . $where . "order by added_on desc limit " . (($page - 1) * $limit) . ",$limit ");
        $statement->execute();
        $departments = $statement->fetchAll();
        $statement = $connection->prepare("  select * from dah_departments " . $where . " ");
        $statement->execute();
        $totaldeps = $statement->fetchAll();
        $totalpages = ceil(count($totaldeps) / $limit);
        $pageinate = $common->paginate('_admin_departments', array('keyword' => $key), $totalpages, $page, $limit);
        $this->data['paginate'] = $pageinate;
        $this->data['page'] = $page;
        $this->data['key'] = $key;
        $this->data['title'] = 'Administrator login';
        $this->data['totalpages'] = count($totaldeps);
		
		if(count($totaldeps)<$limit)
		{
			$limit=count($totaldeps);
		}	
		
		if(count($totaldeps)>0)
		{
			if($page>1){
				$l=((($page - 1) * $limit) + 1)+$limit;
				 $this->data['range'] = ((($page - 1) * $limit) + 1) . " - " . $l;
			}
			else{
				 $this->data['range'] = ((($page - 1) * $limit) + 1) . " - " . $limit;
			}
			
		}
		
        $this->data['departments'] = $departments;
        return $this->data;
    }

}

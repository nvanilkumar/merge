<?php

namespace AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Session\Session;
use AdminBundle\Entity\DahNewsletterSubscribers;

class SubscribersController extends Controller {

    public static $template = "AdminBundle:templates:default.html.twig";
    public $data = array();

    public function __construct() {
        $this->data['extend_view'] = self::$template;
    }

    /**
     * @Route("/secure/subscribers/{page}", name="_admin_subsribers", defaults={"page"=1} )
     * @Template("AdminBundle:Subscribers:manage.html.twig")
     */
    public function manageAction(Request $request, $page) {
        $this->data['title'] = 'Administrator login';
        $common = $this->get('common_handler');
        $limit = 10;
        $key = '';
        $em = $this->getDoctrine()->getManager();
        $connection = $em->getConnection();
        $where = ' where 1=1 ';
        $key = trim($request->query->get('keyword'));
        if ($request->query->get('reset') != '') {
            return $this->redirect($this->generateUrl('_admin_subsribers'));
        }
        if ($key != '') {
            
            $where .= " and (email like '%" . $key . "%'  ) ";
        }
        $statement = $connection->prepare("  select * from dah_newsletter_subscribers " . $where . " order by subscribed_on desc limit " . (($page - 1) * $limit) . ",$limit ");
        $statement->execute();
        $subscribers = $statement->fetchAll();
        $statement = $connection->prepare("  select * from dah_newsletter_subscribers " . $where . " ");
        $statement->execute();
        $totalcontentpages = $statement->fetchAll();
        $totalpages = ceil(count($totalcontentpages) / $limit);
        $paginate = $common->paginate('_admin_subsribers', array('keyword' => $key), $totalpages, $page, 5);
        $this->data['subscribers'] = $subscribers;
        $this->data['key'] = $key;
        $this->data['paginate'] = $paginate;
        $this->data['page'] = $page;
        $this->data['title'] = 'Administrator login';
        $this->data['totalpages'] = count($totalcontentpages);
		if(count($totalcontentpages)<$limit)
		{
			$limit=count($totalcontentpages);
		}	
	
     if(count($totalcontentpages)>0)
		{
			if($page>1){
				$l=((($page - 1) * $limit) + 1)+$limit;
				 $this->data['range'] = ((($page - 1) * $limit) + 1) . " - " . $l;
			}
			else{
				 $this->data['range'] = ((($page - 1) * $limit) + 1) . " - " . $limit;
			}
		}

        return $this->data;
    }

 
}

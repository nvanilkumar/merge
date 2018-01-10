<?php

namespace AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Session\Session;
use AdminBundle\Entity\DahFaqs;
class FaqsController extends Controller {

    public static $template = "AdminBundle:templates:default.html.twig";
    public $data = array();

    public function __construct() {
        $this->data['extend_view'] = self::$template;
    }

   

    /**
     * @Route("/secure/faqs/manage/{page}", name="_admin_faqs" , defaults={"page"=1} )
     * @Template("AdminBundle:Faqs:manage.html.twig")
     */
    public function faqsAction(Request $request, $page) {
      $this->data['title'] = 'Administrator login';

        $common = $this->get('common_handler');
        $limit = 10;
        $key = '';
        $em = $this->getDoctrine()->getManager();
        $connection = $em->getConnection();
        $where = ' where 1=1 ';
        $key = trim($request->query->get('keyword'));
  
        if ($request->query->get('reset') != '') {
            return $this->redirect($this->generateUrl('_admin_faqs'));
        }
        if ($key != '') {
            $where .= "and  question like '%".$key."%' ";
        }
        $statement = $connection->prepare("  select * from dah_faqs " . $where . " order by added_on desc limit " . (($page - 1) * $limit) . ",$limit ");
        $statement->execute();
        $faqs = $statement->fetchAll();
        $statement = $connection->prepare("  select * from dah_faqs " . $where . " ");
        $statement->execute();
        $totalcontentpages = $statement->fetchAll();
        $totalpages = ceil(count($totalcontentpages) / $limit);
        $paginate = $common->paginate('_admin_faqs', array('keyword' => $key), $totalpages, $page, 5);
        $this->data['faqs'] = $faqs;
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
    
    
   /**
     * @Route("/secure/faqs/add_new", name="_admin_add_new_faq")
     * @Template("AdminBundle:Faqs:addNew.html.twig")
     */
    public function addNewAction(Request $request) {
        $this->data['mode'] = 'add';
        $this->data['title'] = 'Administrator login';
	  $common = $this->get('common_handler');
        $em = $this->getDoctrine()->getManager();
        $validator = $this->get('validator');
        $error = array();
        if ($request->getMethod() == 'POST' ) {
        $repository = $this->getDoctrine()->getRepository('AdminBundle:DahFaqs');  
        $existingQuestion = $repository->findBy(
                    array('question' => $request->request->get('question'))
                );
            if($request->request->get('question')=='')
            {
                $error['question'] = 'Please enter question';
            }
            if($request->request->get('answer')=='')
            {
                $error['answer'] = 'Please enter answer';
            }
           
            if(count($existingQuestion)>0)
            {
                $error['question'] = 'Question already exists';
            }
           
            if(empty($error)) {
                $admin = new DahFaqs();
                $admin->setQuestion($request->request->get('question'));
                $admin->setAnswer($request->request->get('answer'));
                $admin->setAddedOn(time());
                $admin->setStatus('active');
                $em->persist($admin);
                $em->flush();
               $id = $admin->getFaqId();
                $this->get('session')->getFlashBag()->add('success', 'New faq added successfully.');
                $common->logActivity('added question <a href="' . $this->generateUrl('_admin_edit_faq', array('faqid' => $id)) . '">' . trim($request->request->get('question')) . '</a>');
                 return $this->redirect($this->generateUrl('_admin_faqs'));
            }
        }
        $this->data['mode'] = 'add';
        $this->data['error'] = $error;
        //echo $common->test();
        $this->data['title'] = 'Administrator login';
        return $this->data;

    }
    
    /**
     * @Route("/secure/faqs/edit/{faqid}", name="_admin_edit_faq",defaults={"faqid"=0})
     * @Template("AdminBundle:Faqs:addNew.html.twig")
     */
    public function editAction(Request $request,$faqid) {
         $repository = $this->getDoctrine()->getRepository('AdminBundle:DahFaqs');
            $faq = $repository->find($faqid);
              $common = $this->get('common_handler');
            if(!$faq)
            {
                return $this->redirect($this->generateUrl('_admin_faqs'));
            }
            $em = $this->getDoctrine()->getManager();
            $validator = $this->get('validator');
            $error=Array();
            if ($request->getMethod() == 'POST' ) {
            
            $q=  $request->request->get('question');
            $em = $this->getDoctrine()->getManager();
            $connection = $em->getConnection();
            $statement = $connection->prepare("  select * from dah_faqs where  question= '$q' and faqid != '" . $faqid . "' ");
            $statement->execute();
            $existingQuestion= $statement->fetchAll();   
                
                
                
                
           if($request->request->get('question')=='')
            {
                $error['question'] = 'Please enter question';
            }
            if($request->request->get('answer')=='')
            {
                $error['answer'] = 'Please enter answer';
            }
            
                if(count($existingQuestion)>0)
            {
                $error['question'] = 'Question already exists';
            }
           
            
            if(empty($error)) {
                $faq->setQuestion($request->request->get('question'));
                $faq->setAnswer($request->request->get('answer'));
                $faq->setUpdatedOn(time());
                $em->persist($faq);
                $em->flush();
                $this->get('session')->getFlashBag()->add('success', ' Faq edited successfully.');
                   $common->logActivity('updated question <a href="' . $this->generateUrl('_admin_edit_faq', array('faqid' => $faqid)) . '">' . trim($request->request->get('question')) . '</a>');
        
                return $this->redirect($this->generateUrl('_admin_faqs'));
            }
           }
           $this->data['mode'] = 'edit';
           $this->data['error'] = $error;
           $this->data['faq'] = $faq;
        $this->data['mode'] = 'edit';
        //echo $common->test();
        $this->data['title'] = 'Administrator login';
        return $this->data;

    }
    

}

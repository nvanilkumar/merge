<?php

namespace AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class AjaxController extends Controller {

    public $data = array();

    public function __construct() {
        
    }

    /**
     * @Route("/admin/ajax/campaign_type", name="_admin_get_campaign_type")
     * @Method({"GET"})
     */
    public function ajaxUserStatusAction(Request $request) {
        $response = new Response();
        $encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());

        $serializer = new Serializer($normalizers, $encoders);
        $em = $this->getDoctrine()->getEntityManager();
        $student = $em->getRepository('AppBundle:CampaignType')->findAll();
        // print_r($student);
        //exit;
        $jsonContent = $serializer->serialize($student, 'json');
        $isAjax = $request->isXmlHttpRequest();
        $res = array('status' => 'failed',
            'message' => 'Something went wrong.',
            'response' => ''
        );
        if ($isAjax) {
            $res = array('status' => 'success',
                'message' => 'Agent Status changed Successfully',
                'response' => $jsonContent
            );
        }

        $response->setContent(json_encode($res));

        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
    
    /**
     * @Route("/admin/ajax/agent_customer", name="_admin_get_agent_customer")
     * @Method({"POST"})
     */
    public function ajaxAgenCustomerDetails(Request $request) {
       $response = new Response();
       $helperHandler = $this->get('helper_handler');
       $common = $this->get('common_handler');
       $error=array();
       $agent_id=$request->get('agentId');
       $campaign_id=$request->get('campaign_id');
        if ($agent_id == '' || !is_numeric($agent_id)) {
            $error['message'] = "Please enter a valid AgentId";
        }
        if (count($error) > 0) {
            $response = $common->apiValueNotFound("Please enter a valid AgentId");
            $response->setContent(json_encode($error));
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }

       $res=$helperHandler->getAgenCustomers($agent_id,$campaign_id);

       if (count($res) == 0) {
            $response = $common->apiValueNotFound("No data found");
           return $response;
        }
       $response->setContent(json_encode($res));
       $response->headers->set('Content-Type', 'application/json');
       return $response;
    }


    /**
     * @Route("/admin/ajax/campaing_status_filter", name="_admin_ajax_campaing_status_filter")
     * @Method({"GET"})
     */
    public function ajaxCampaignStatus(Request $request) {
       $response = new Response();
       $helperHandler = $this->get('helper_handler');
       $common = $this->get('common_handler');
       $error=array();
       $status=$request->get('status');
        $em = $this->getDoctrine()->getEntityManager();
        $connection = $em->getConnection();

       $em = $this->getDoctrine()->getEntityManager();
       $query= "select campaign_name,(select count(*) from campaign_data cd  where cd.campaign_id=c.campaign_id) as customers, campaign_status,added_on,pdf_report
from campaign c where is_deleted=0 ";
       if($status =='Completed'){

           $query.='and is_complete=1 ';
       } else if($status =='LiveCampaign'){
           $query.='and is_running=1';
       } else{
           $query.='and campaign_status="'.$status.'"';
       }
       $statement = $connection->prepare($query);
       $statement->execute();
       $res = $statement->fetch();
           if(count($res)==0){


                  $response->setContent('No records found');
           } else{
                   $response->setContent(json_encode($res));
           }
  
       $response->headers->set('Content-Type', 'application/json');
       return $response;
    }


}

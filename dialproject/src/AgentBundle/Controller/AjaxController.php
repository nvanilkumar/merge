<?php

namespace AgentBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Session\Session;

class AjaxController extends Controller {

    public $data = array();

    public function __construct() {
        
    }

    /**
     * @Route("/agent/ajax/campaign_data", name="_agent_get_campaign_data")
     * @Method({"POST"})
     */
    public function ajaxCampaignDataAction(Request $request) {
        $response = new Response();
        $error = array();

        $common = $this->get('common_handler');
        $helperHandler = $this->get('helper_handler');

        $campaignId = trim($request->request->get('campaignId'));
        if ($campaignId == '' || !is_numeric($campaignId)) {
            $error['message'] = "Please enter a valid AgentId";
        }
        if (count($error) > 0) {
            $response = $common->apiValueNotFound("Please enter a valid AgentId");
            $response->setContent(json_encode($error));
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }

        $res = $helperHandler->getCampainStatusInfo($campaignId);
        $response->setContent(json_encode($res));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("/agent/ajax/user_web_status", name="_agent_user_web_status")
     * @Method({"POST"})
     */
    public function ajaxUserWebLoginStatusAction(Request $request) {
        $response = new Response();
        $error = array();
        $em = $this->getDoctrine()->getEntityManager();
        $connection = $em->getConnection();
        $common = $this->get('common_handler');
        $userExtn = trim($request->request->get('userExtn'));
        $userStatus = trim($request->request->get('userStatus'));
        if ($userExtn == '' || !is_numeric($userExtn)) {
            $error['message'] = "Please enter a valid Extension";
        }
        if (count($error) > 0) {
            $response = $response = $common->apiValueNotFound("Please enter a valid Extension");
            $response->setContent(json_encode($error));
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }

        $userStatus = (isset($userStatus) && $userStatus == 1) ? $userStatus : 0;
        $uquery = "UPDATE user SET web_login=" . $userStatus . " where extension=" . $userExtn;
        $connection->executeUpdate($uquery);

        $res = array("message" => "sucessfully updated");
        $response->setContent(json_encode($res));

        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("/agent/ajax/user_astrisk_status", name="_agent_user_astrisk_status")
     * @Method({"POST"})
     */
    public function ajaxUserAstriskLoginStatusAction(Request $request) {
        $response = new Response();
        $error = array();
        $em = $this->getDoctrine()->getEntityManager();
        $connection = $em->getConnection();
        $common = $this->get('common_handler');
        $userExtn = trim($request->request->get('userExtn'));
        $userStatus = trim($request->request->get('userStatus'));
        if ($userExtn == '' || !is_numeric($userExtn)) {
            $error['message'] = "Please enter a valid Extension";
        }
        if (count($error) > 0) {
            $response = $response = $common->apiValueNotFound("Please enter a valid Extension");
            $response->setContent(json_encode($error));
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }

        $userStatus = (isset($userStatus) && $userStatus == 1) ? $userStatus : 0;
        $uquery = "UPDATE user SET astrisk_login=" . $userStatus . " where extension=" . $userExtn;
        $connection->executeUpdate($uquery);

        $res = array("message" => "sucessfully updated");
        $response->setContent(json_encode($res));

        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("/agent/ajax/toggle_campaign_status", name="_agent_toggle_campaign_status")
     * @Method({"POST"})
     */
    public function ajaxToggleCampaignStatusAction(Request $request) {
        $response = new Response();
        $error = array();
        $em = $this->getDoctrine()->getEntityManager();
        $connection = $em->getConnection();
        $common = $this->get('common_handler');
        $campaignId = trim($request->request->get('campaignId'));
        if ($campaignId == '' || !is_numeric($campaignId)) {
            $error['message'] = 0;
        }
        if (count($error) > 0) {
            $response = $response = $common->apiValueNotFound("Please enter a valid Extension");
            $response->setContent(json_encode($error));
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }
        $sql = "select * from campaign where campaign_id=" . $campaignId;
        $statement = $connection->prepare($sql);
        $statement->execute();
        $data = $statement->fetch();
        if (!empty($data)) {
            $userStatus = ( $data['is_paused'] == 1) ? 0 : 1;
            
            $isRunning="";
            if($userStatus==0){
                $isRunning=" ,is_running=0";
            }
            
            
            $uquery = "UPDATE campaign SET is_paused=" . $userStatus .$isRunning. " where campaign_id=" . $campaignId;
            $connection->executeUpdate($uquery);
        }
        $res = array("message" => 1, 'is_paused' => $userStatus);
        $response->setContent(json_encode($res));

        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("/agent/ajax/get_customer_details", name="_agent_get_customer_details")
     */
    public function ajaxGetCustomerDetailsAction(Request $request) {
        $response = new Response();

        $em = $this->getDoctrine()->getEntityManager();

        $isAjax = $request->isXmlHttpRequest();

        $res = array('status' => 'failed',
            'message' => 'Something went wrong.',
            'response' => ''
        );
        if ($isAjax) {
            if ($request->getMethod() == 'POST') {
                $ext = trim($request->request->get('customerphone'));
                $campaign_id = trim($request->request->get('campaign_id'));
                $connection = $em->getConnection();
                $statement = $connection->prepare(" select * from customer c  join campaign_data cd
                                                  on cd.customer_id=c.customer_id
                                                     where cd.campaign_id=".$campaign_id." and c.phone_number=".$ext);
                $statement->execute();
                $agentdata = $statement->fetch();
                // print_r($student);
                //exit;
                // $jsonContent = $serializer->serialize($agentdata, 'json');
                $res = array('status' => 'success',
                    'message' => 'Agent Status changed Successfully',
                    'response' => $agentdata
                );
               
                if ($campaign_id != '') {
                    $statement = $connection->prepare(" select cm.campaign_name as campaignName , cm.is_paused as isPaused ,(select count(*) from  campaign_data where campaign_id = " . $campaign_id . " ) as totalUsers , (select count(*) from  campaign_data where campaign_id = " . $campaign_id . " and ( ds_id != 0  )  ) as completedUsers from campaign cm where cm.campaign_id = " . $campaign_id . " ");
                    $statement->execute();
                    $campdata = $statement->fetch();
                    if (!empty($campdata)) {
                        $res['campdata'] = $campdata;
                    }
                }
            }
        }

        $response->setContent(json_encode($res));

        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("/agent/ajax/get_campcustomer_details", name="_agent_get_campcustomer_details")
     */
    public function ajaxGetCampCustomerDetailsAction(Request $request) {
        $response = new Response();
        $limit=10;
        $em = $this->getDoctrine()->getEntityManager();
        $isAjax = $request->isXmlHttpRequest();
        $helperHandler = $this->get('helper_handler');
        $res = array('status' => 'failed',
            'message' => 'Something went wrong.',
            'response' => ''
        );
        if ($isAjax) {
            if ($request->getMethod() == 'POST') {
                $campaign_id = trim($request->request->get('campaign_id'));
                $connection = $em->getConnection();
                if ($campaign_id != '') {
                    $campdata = $helperHandler->nextCustomers($campaign_id,$limit,'');
                    if (!empty($campdata)) {
                        $res['status'] = 'success';
                        $res['response'] = $campdata;
                    }
                }
            }
        }
        $response->setContent(json_encode($res));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("/agent/ajax/skip_call", name="_agent_skipp_call")
     * @Method({"POST"})
     */
    public function ajaxSkipCallAction(Request $request) {
        $response = new Response();
        $error = array();
        $em = $this->getDoctrine()->getEntityManager();
        $connection = $em->getConnection();
        $common = $this->get('common_handler');
        $res = array('status' => 'failed',
            'message' => 'Something went wrong.',
            'response' => ''
        );
        $cd_id = trim($request->request->get('cd_id'));
        $skip = trim($request->request->get('skip'));
        if ($cd_id == '' || !is_numeric($cd_id)) {
            $error['message'] = "Please enter a valid Extension";
        }
        $user = $this->getUser();
        if (!$user) {
            $error['message'] = "Please enter user not loggedin";
        }
        if (count($error) > 0) {
            $response = $response = $common->apiValueNotFound("Please enter a valid Extension");
            $response->setContent(json_encode($error));
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }
        $sql = "select * from campaign_data where cd_id=" . $cd_id;
        $statement = $connection->prepare($sql);
        $statement->execute();
        $data = $statement->fetch();
        if (!empty($data)) {
            $userStatus = ( $data['skipped_by'] == $user->getUserId()) ? 0 : $user->getUserId();
            $uquery = "UPDATE campaign_data SET skipped_by=" . $userStatus . " where cd_id=" . $cd_id;
            $connection->executeUpdate($uquery);
            $res = array('status' => 'success',
                'message' => 'Something went wrong.',
                'response' => $userStatus
            );
        }
        $response->setContent(json_encode($res));

        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("/ajax/update_call_status", name="_update_call_status")
     * @Method({"POST"})
     */
    public function ajaxUpdateCallStatusAction(Request $request) {
        $response = new Response();
        $error = array();
        $em = $this->getDoctrine()->getEntityManager();
        $connection = $em->getConnection();
        $common = $this->get('common_handler');
        $res = array('status' => 'failed',
            'message' => 'Something went wrong.',
            'response' => ''
        );
        $cbname = trim($request->request->get('cbname'));
        $dsid = trim($request->request->get('dsid'));
        $split = explode('_', $cbname);
        $cd_id = '';
        if (isset($split[1])) {
            $cd_id = $split[1];
        }
        
        //To get the campain data id from campain id & customer id
        if($request->request->get('campain_id')){
            $customer_id=$request->request->get('customer_id');
            $campain_id=$request->request->get('campain_id');
            
            $sql = "select cd_id from campaign_data where campaign_id=" . $campain_id." and customer_id= ".$customer_id ;
        $statement = $connection->prepare($sql);
        $statement->execute();
        $data = $statement->fetch();
        
//        print_r($data);exit;
           $cd_id= $data['cd_id'];
        }

        if ($cd_id == '' || !is_numeric($cd_id)) {
            $error['message'] = "Please enter a valid Extension";
        }
        $user = $this->getUser();
        if (!$user) {
            $error['message'] = "Please enter user not loggedin";
        }
        if (count($error) > 0) {
            $response = $response = $common->apiValueNotFound("Please enter a valid Extension");
            $response->setContent(json_encode($error));
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }
        $sql = "select * from campaign_data where cd_id=" . $cd_id;
        $statement = $connection->prepare($sql);
        $statement->execute();
        $data = $statement->fetch();
        $maildata = array(
            'camp' => $data
        );
        if (!empty($data)) {
            $uquery = "UPDATE campaign_data SET ds_id=" . $dsid . " where cd_id=" . $cd_id;
            $connection->executeUpdate($uquery);
            $sql = " select c.* , cd.cd_id , cd.skipped_by , cd.ds_id
                            from campaign_data cd
                            join customer c on c.customer_id = cd.customer_id where cd_id=" . $cd_id;
            $statement = $connection->prepare($sql);
            $statement->execute();
            $data = $statement->fetch();
            $maildata = array(
                'camp' => $data
            );
            $res = array('status' => 'success',
                'message' => 'Something went wrong.',
                'response' => $this->renderView('AgentBundle:Default:indDoneCust.html.twig', $maildata),
                'cd_id' => $cd_id
            );
        }
        $response->setContent(json_encode($res));

        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("/agent/ajax/update_call_status_on_end", name="_update_call_status_on_call_end")
     * @Method({"POST"})
     */
    public function ajaxUpdateCallStatusonEndAction(Request $request) {
        $response = new Response();
        $error = array();
        $em = $this->getDoctrine()->getEntityManager();
        $connection = $em->getConnection();
        $common = $this->get('common_handler');
        $res = array('status' => 'failed',
            'message' => 'Something went wrong.',
            'response' => ''
        );
        $customerphone = trim($request->request->get('customerphone'));
        $campaign_id = trim($request->request->get('campaign_id'));


        if ($campaign_id == '' || !is_numeric($campaign_id)) {
            $error['message'] = "Please enter a valid Extension";
        }
        $user = $this->getUser();
        if (!$user) {
            $error['message'] = "Please enter user not loggedin";
        }
        if (count($error) > 0) {
            $response = $response = $common->apiValueNotFound("Please enter a valid Extension");
            $response->setContent(json_encode($error));
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }
        $sql = " select c.* , cd.cd_id , cd.skipped_by , cd.ds_id
                            from campaign_data cd
                            join customer c on c.customer_id = cd.customer_id where cd.campaign_id=" . $campaign_id . " and c.phone_number = '" . $customerphone . "' ";
        $statement = $connection->prepare($sql);
        $statement->execute();
        $data = $statement->fetch();
        $maildata = array(
            'camp' => $data
        );
        if (!empty($data)) {

            $res = array('status' => 'success',
                'message' => 'Something went wrong.',
                'response' => $this->renderView('AgentBundle:Default:indDoneCust.html.twig', $maildata),
                'cd_id' => $data['cd_id']
            );
        }
        $response->setContent(json_encode($res));

        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("/agent/ajax/hangup_agent", name="_agent_hangup_agent")
     * @Method({"GET"})
     */
    public function ajaxAgentHangupAction(Request $request) {
        $response = new Response();
        $error = array();
        $em = $this->getDoctrine()->getEntityManager();
        $connection = $em->getConnection();
        $common = $this->get('common_handler');
        $res = array('status' => 'failed',
            'message' => 'Something went wrong.',
            'response' => ''
        );

        $user = $this->getUser();
        if (!$user) {
            $error['message'] = "Please enter user not loggedin";
        }
        if (count($error) > 0) {
            $response = $response = $common->apiValueNotFound("Please enter a valid Extension");
            $response->setContent(json_encode($error));
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }

        $hangup = $user->getHangupAgent();
        
        if ($hangup == 1) {
            $hangup_agnet  = 'no';
            $uquery = "UPDATE user SET hangup_agent=" . 0 . " where user_id=" . $user->getUserId();
        } else {
            $hangup_agnet  = 'yes';
            $uquery = "UPDATE user SET hangup_agent=" . 1 . " where user_id=" . $user->getUserId();
        }
        $connection->executeUpdate($uquery);
        $res = array('status' => 'success',
            'message' => 'Something went wrong.',
            'response' => $hangup_agnet
        );

        $response->setContent(json_encode($res));

        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

}

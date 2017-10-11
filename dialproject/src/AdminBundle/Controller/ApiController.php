<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
// these import the "@Route" and "@Template" annotations    
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use AppBundle\Entity\Campaign;
use AppBundle\Entity\User;
use AppBundle\Entity\CampaignType;
use AppBundle\Entity\CampaignAgents;
use AppBundle\Entity\Customer;
use AppBundle\Entity\CampaignData;
use AppBundle\Entity\LiveCalls;
use \DateTime;
use AppBundle\Entity\LiveAgents;
use AdminBundle\Model\CampaignModel;

class ApiController extends Controller
{

    public function __construct()
    {
        
    }

    /**
     * @Route("/api/v1/campaigns", name="_api_v1_campaigns")
     * @Method({"GET", "POST"})
     */
    public function campaignsAction(Request $request)
    {
        $response = new Response();
        $helperHandler = $this->get('helper_handler');
        $common = $this->get('common_handler');
        $responseArray = array();
        $error = array();
        if ($request->getMethod() == 'POST') {
            $extension = trim($request->request->get('AgentId'));
            $campaignType = trim($request->request->get('CampaignType'));
            $campaignType = strlen($campaignType) > 0 ? $campaignType : 'Direct Dialing';

            if ($extension == '') {
                $error['agentId'] = "Please enter a valid AgentId";
            }
            if ($extension != '') {

                if (strlen($extension) > 5) {
                    $error['agentId'] = "AgentId must not exceed more than 5 characters";
                }
                if (strlen($extension) < 3) {
                    $error['agentId'] = "AgentId must contain atleast 3 characters";
                }
                if (!preg_match('/^[1-9][0-9]{0,5}$/', $extension)) {
                    $error['agentId'] = "Please enter valid numbers";
                }
            }
        } else {
            $responseArray = array("error" => "Invalid form method");
            $response->setContent(json_encode($responseArray));
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }

        //server side validations
        if (count($error) > 0) {
            $response->setContent(json_encode($error));
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }

        $em = $this->getDoctrine()->getEntityManager();

        $todayDate = date("Y-m-d");
        $timeStamp = date("H:i:s");
        $day = " and " . strtolower(date("l")) . "='1'";

        $connection = $em->getConnection();
        $data = $helperHandler->getAgentCampaign($campaignType, $extension);

        if (count($data) > 0) {
            $campainId = $data[0]['campaign_id'];

            $userId = $data[0]['user_id'];

            $campaign_name = $data[0]['campaign_name'];
            //print_r($data[0]);exit;
            //Bring latest customer data related to passed campaign id
            $campaignDataQuery = "SELECT  cu.*,cd.* , cm.campaign_name, cm.ct_id , ct.campaign_type , qm.priority
                            from customer as cu
                        join campaign_data as cd on cd.customer_id=cu.customer_id
                        join campaign cm on cm.campaign_id = cd.campaign_id
                        join campaign_type ct on cm.ct_id = ct.ct_id
                        left join extensions qm on qm.exten =  '" . $extension . "' and qm.app = 'Queue' and qm.appdata = '" . str_replace(' ', '_', $campaign_name) . "'
                        where cd.campaign_id=" . $campainId . "  and ds_id = 0 and cd.retry_count < cd.max_retry_count and is_paused = 0 and cd.skipped_by != " . $userId . " limit 0,1";

            $campaignDataStatement = $connection->prepare($campaignDataQuery);
            $result = $campaignDataStatement->execute();
            $customersData = $campaignDataStatement->fetch();


            if (count($customersData) > 0 && $customersData != false) {
                $customerId = $customersData['customer_id'];

                $responseArray = array(
                    'status' => 'success',
                    'response' => $customersData,
                    'message' => 'first customer details'
                );
                //Insert the live agent table
                $helperHandler->insertLiveAgentDetails($campainId, $userId);
                $helperHandler->updateCampainData($campainId, $customerId, $userId);
                $response->setContent(json_encode($responseArray));
                $response->headers->set('Content-Type', 'application/json');
            } else {
//                echo 111;exit;
                // $response = $common->apiValueNotFound("No Customers for selected campaign");

                $nquery = "SELECT  cu.*,cd.* , cm.campaign_name, cm.ct_id , ct.campaign_type
                            from customer as cu
                        join campaign_data as cd on cd.customer_id=cu.customer_id
                        join campaign cm on cm.campaign_id = cd.campaign_id
                        join campaign_type ct on cm.ct_id = ct.ct_id
                        where cd.campaign_id=" . $campainId . "  and ds_id != 0 and retry_count = max_retry_count and is_paused = 0 limit 0,1";
                $campaignDataStatementtemp = $connection->prepare($nquery);
                $result = $campaignDataStatementtemp->execute();
                $tempdata = $campaignDataStatementtemp->fetch();
                if (!empty($tempdata)) {
                    $helperHandler->updateCampainDataStatus($campainId, $tempdata['customer_id'], 5);
                }
                $responseArray = array(
                    'status' => 'error',
                    'response' => "",
                    'message' => 'No Customers for selected campaign'
                );
                $response->setContent(json_encode($responseArray));
                $response->headers->set('Content-Type', 'application/json');
                return $response;
//                print_r($response);exit;
            }
        } else {
            $response = $common->apiValueNotFound("No Campaigns");
        }


        return $response;
    }

    /**
     * @Route("/api/v1/live_calls", name="_api_v1__putcampaigns")
     * @Method({ "PUT"})
     */
    public function campaignsputAction(Request $request)
    {
        $response = new Response();
        $responseArray = array('hithere');

        $common = $this->get('common_handler');
        $helperHandler = $this->get('helper_handler');

        $em = $this->getDoctrine()->getManager();
        $doctrine = $this->getDoctrine();

        $put_str = $this->getRequest()->getContent();

        parse_str($put_str, $_PUT);

        $msg = "No change";
        if (isset($_PUT['campaignId'])) {
            $ansTime = (isset($_PUT['ansTime'])) ? trim($_PUT['ansTime']) : 0;
            $endTime = (isset($_PUT['endTime'])) ? trim($_PUT['endTime']) : 0;

            $customerExtn = trim($_PUT['customerExtn']);
            $campaignId = trim($_PUT['campaignId']);
            $em = $this->getDoctrine()->getManager();
            $connection = $em->getConnection();


            if ($ansTime > 0) {
                $statement = $connection->prepare(" update live_calls set ans_time=" . $ansTime . " where to_number=" . $customerExtn . " and campaign_id=" . $campaignId . " and end_time is NULL ");
                $statement->execute();

                $msg = "Answer Time has been updated";
            }
            if ($endTime > 0) {

                $statement = $connection->prepare(" update live_calls set end_time=" . $endTime . " where  to_number=" . $customerExtn . " and campaign_id=" . $campaignId . " and end_time is NULL ");
                $statement->execute();
                $statement = $connection->prepare(" update live_calls set duration=(end_time-ans_time) where to_number=" . $customerExtn . " and campaign_id=" . $campaignId . " and end_time=" . $endTime);
                $statement->execute();



                $msg = "End Time and Duration has been updated";
            }
        }
        $response->setContent(json_encode($msg));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("/api/v1/campaigns_data", name="_api_v1__putcampaigns_data")
     * @Method({ "PUT"})
     */
    public function camapignDataAction(Request $request)
    {
        $response = new Response();
        $responseArray = array();
        $common = $this->get('common_handler');
        $helperHandler = $this->get('helper_handler');
        $error = array();
        $doctrine = $this->getDoctrine();
        if ($request->getMethod() == 'PUT') {


            $customerExtn = trim($request->get('customerExtn'));
            $campaignId = trim($request->get('campaignId'));
            $dailStatus = trim($request->get('dailStatus'));

            if ($customerExtn && $campaignId && $dailStatus) {
                $em = $this->getDoctrine()->getManager();
                $customerId = $common->customerIdbyCustomerExtn($doctrine, $customerExtn);


                $repository = $this->getDoctrine()->getRepository('AppBundle:DialStatus');
                $campaignType = $repository->findOneBy(array('dialStatus' => $dailStatus));
                if ($campaignType) {
                    $dsId = $campaignType->getDsId();
                }
                if ($customerId != 0) {
                    $sgentuid = 1; //Assign Agent to 1
                    $repository = $this->getDoctrine()->getRepository('AppBundle:CampaignData');
                    $campaingData = $repository->findOneBy(
                            array('campaign' => $campaignId,
                                'customer' => $customerId,
                            )
                    );
                    //echo $campaignId." ".$customerId." ".$sgentuid;exit;

                    if ($campaingData) {
                        if ($campaignType) {

                            $campaingData->setDsId($dsId);

                            $em->persist($campaingData);
                            $em->flush();
                        } else {
                            if ($campaingData->getRetryCount() == $campaingData->getMaxRetryCount()) {
                                $helperHandler->updateCampainDataStatus($campaignId, $customerId, 5);
                            } else {
                                $helperHandler->updateCampainDataStatus($campaignId, $customerId, 0);
                            }
                        }
                        $responseArray = array(
                            'status' => 'success',
                            'response' => 'customer details',
                            'message' => 'Updated customer data'
                        );
                        $response->setContent(json_encode($responseArray));
                        $response->headers->set('Content-Type', 'application/json');
                    } else {
                        $response = $common->apiValueNotFound('customer data not found');
                    }
                } else {
                    $response = $common->apiValueNotFound('customer not found');
                }
            } else {
                $response = $common->apiParameterValidations($customerExtn, $campaignId, 'campaigns_data', $dailStatus, '');
            }
        }
        return $response;
    }

    /**
     * @Route("/api/v1/live_calls", name="_api_v1_live_calls")
     * @Method({ "POST"})
     */
    public function liveCallsAction(Request $request)
    {
        $response = new Response();
        $responseArray = array();
        $common = $this->get('common_handler');
        $error = array();
        $em = $this->getDoctrine()->getManager();
        $doctrine = $this->getDoctrine();

        if (!$request->get('customerExtn') && !$request->get('campaignId')) {
            $response = $common->apiParameterValidations($request->get('customerExtn'), $request->get('campaignId'), 'live_calls', '', '');
        } else {
            $msg = "";

            $customerExtn = trim($request->get('customerExtn'));
            $campaignId = trim($request->get('campaignId'));
            $dailTime = trim($request->get('dialTime'));
            if ($request->getMethod() == 'POST') {
                $repository = $doctrine->getRepository('AppBundle:Campaign');
                $campaign = $repository->findBy(array('campaignId' => $campaignId));

                $d = new DateTime($dailTime);
                $dt = $d->format("U");
                $liveCalls = new LiveCalls();
                $liveCalls->setCampaign($campaign[0]);
                $liveCalls->setToNumber($customerExtn);
                $liveCalls->setDialTime($dt);
                $em->persist($liveCalls);
                $em->flush();
                $msg = "Data has been inserted into livecalls";
            }


            $responseArray = array(
                'status' => 'success',
                'response' => 'live calls',
                'message' => $msg
            );
            $response->setContent(json_encode($responseArray));
            $response->headers->set('Content-Type', 'application/json');
        }
        return $response;
    }

    /**
     * @Route("/api/v1/users", name="_api_v1_users")
     * @Method({ "PUT"})
     */
    public function usersAction(Request $request)
    {
        $response = new Response();
        if ($request->getMethod() == 'PUT') {
            $common = $this->get('common_handler');
            $agentExtn = trim($request->get('agentExtn'));
            $status = trim($request->get('status'));
            if (!$agentExtn && !$status) {
                $response = $common->apiParameterValidations($request->get('agentExtn'), '', '', 'users', '', $status);
            } else {
                $em = $this->getDoctrine()->getManager();
                $repository = $this->getDoctrine()->getRepository('AppBundle:User');
                $user = $repository->findOneBy(
                        array('extension' => $agentExtn,
                        )
                );

                if (!$user) {
                    $response = $common->apiValueNotFound('User not found');
                } else {
                    $user->setAstriskLogin($status);
                    $em->persist($user);
                    $em->flush();
                    $msg = "Asteric status has been updated";


                    $responseArray = array(
                        'status' => 'success',
                        'response' => 'Asterisk has been updated in User',
                        'message' => $msg
                    );
                    $response->setContent(json_encode($responseArray));
                    $response->headers->set('Content-Type', 'application/json');
                }
            }
        }
        return $response;
    }

    /**
     * @Route("/api/v1/users", name="_api_get_v1_users")
     * @Method({ "POST"})
     */
    public function getusersAction(Request $request)
    {
        $response = new Response();
        if ($request->getMethod() == 'POST') {
            $common = $this->get('common_handler');
            $agentExtn = trim($request->get('agentExtn'));
            if (!$agentExtn) {
                $responseArray = array(
                    'status' => 'error',
                    'message' => " agentExtn parameter is missing"
                );
                $response->setContent(json_encode($responseArray));
            } else {
                $em = $this->getDoctrine()->getManager();
                $repository = $this->getDoctrine()->getRepository('AppBundle:User');
                $user = $repository->findOneBy(
                        array('extension' => $agentExtn,
                        )
                );

                if (!$user) {
                    $response = $common->apiValueNotFound('User not found');
                } else {
                    $hangups = 'yes';
                    if ($user->getHangupAgent() == 1) {
                        $hangups = 'yes';
                    } else {
                        $hangups = 'no';
                    }
                    $msg = "Asteric status has been updated";
                    $user->setHangupAgent(0);
                    $em->persist($user);
                    $em->flush();

                    $responseArray = array(
                        'status' => 'success',
                        'response' => $hangups,
                        'message' => $msg
                    );
                    $response->setContent(json_encode($responseArray));
                    $response->headers->set('Content-Type', 'application/json');
                }
            }
        }
        return $response;
    }

    /**
     * @Route("/api/v1/live_campaign_customer", name="_api_test")
     * @Method({ "GET"})
     */
    public function liveCampaignCustomer(Request $request)
    {

        $response = new Response();
        $campaignModel = $this->get('campaign_model');
        $customer = $campaignModel->liveCampaings();

        if (count($customer) == 0) {
            $responseArray = array(
                'status' => 'error',
                'message' => " No live Campaign"
            );
            $response->setContent(json_encode($responseArray));
            return $response;
        }

        $helperHandler = $this->get('helper_handler');
        $helperHandler->updateCampainData($customer[0]['campaign_id'], $customer[0]['customer_id']);

        $responseArray = array(
            'status' => 'success',
            'message' => " Customer Found",
            'customer' => $customer[0]
        );
        $response->setContent(json_encode($responseArray));
        return $response;
    }

    /**
     * @Route("/api/v1/campaign_next_customer", name="_api_v1_campaign_next_customer")
     * @Method({"GET", "POST"})
     */
    public function getLiveCampaignData(Request $request)
    {
        $response = new Response();
        $helperHandler = $this->get('helper_handler');
        $responseArray = array();
        $error = array('status' => 'error');

        if ($request->getMethod() == 'POST') {

            $campaignId = trim($request->request->get("campaignId"));
            if ($campaignId == '') {
                $error['campaignId'] = "Please enter a valid CampaignId";
            }
            //server side validations
            if (count($error) > 1) {
                $response->setContent(json_encode($error));
                $response->headers->set('Content-Type', 'application/json');
                return $response;
            }
        } else {
            $responseArray = array("error" => "Invalid form method");
            $response->setContent(json_encode($responseArray));
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }



        $em = $this->getDoctrine()->getEntityManager();
        $connection = $em->getConnection();

        //get the campagin related customer
        $limit = 1; //single customer data only
        $customersData = $helperHandler->nextCustomers($campaignId, $limit, 'api');

        if (count($customersData) > 0 && $customersData != false) {
            $customerId = $customersData[0]['customer_id'];
            $campainId = $customersData[0]['campaign_id'];
            $responseArray = array(
                'status' => 'success',
                'customer' => $customersData[0],
                'message' => 'first customer details'
            );
            //Insert the live agent table

            $helperHandler->updateCampainData($campainId, $customerId);
            $response->setContent(json_encode($responseArray));
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }

        $responseArray = array(
            'status' => 'error',
            'response' => "",
            'message' => 'No Customers for selected campaign'
        );

        $response->setContent(json_encode($responseArray));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Route("/api/v1/sbc_info", name="_sbc_info")
     * @Method({ "GET"})
     */
    public function sbcInfo(Request $request)
    {

        $response = new Response();
        $redisHandler = $this->get('redis_handler');
        $noOfAgents = $redisHandler->getRadisKeyValue("no_of_agents");
        $extensionNumber = $redisHandler->getRadisKeyValue("extension");
        $busyAgents = $redisHandler->getRadisKeyValue("busy_agents");
        $dialUsersCount = $redisHandler->getRadisKeyValue("dial_users_count");

        $responseArray = array(
            'status' => 'success',
            'message' => " Agent Info Found",
            'noOfAgents' => $noOfAgents,
            'extensionNumber' => $extensionNumber,
            'dialUsersCount' => $dialUsersCount,
            'busyAgents' => $busyAgents
        );
        $response->setContent(json_encode($responseArray));
        return $response;
    }

    /**
     * @Route("/api/v1/campaign_running_status", name="_campaign_running_status")
     * @Method({ "POST"})
     */
    public function campaignRunningStatus(Request $request)
    {
        $response = new Response();
        if ($request->getMethod() == 'POST') {

            $campaignId = trim($request->request->get("campaignId"));
            if ($campaignId == '') {
                $error['campaignId'] = "Please enter a valid CampaignId";
            }
        } else {
            $responseArray = array("error" => "Invalid form method");
            $response->setContent(json_encode($responseArray));
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }
        $helperHandler = $this->get('helper_handler');
        $helperHandler->updateCampaignRunningStatus($campaignId);

        $responseArray = array(
            'status' => 'success',
            'message' => " campaign running status updated ",
        );
        $response->setContent(json_encode($responseArray));
        return $response;
    }

    /**
     * @Route("/api/v1/change_customer_answer_type", name="_api_v1_change_customer_answer_type")
     * @Method({ "POST"})
     */
    public function changeCustomerAnswerTypeAction(Request $request)
    {
        $response = new Response();
        $responseArray = array();
        $common = $this->get('common_handler');
        $helperHandler = $this->get('helper_handler');
        $error = array();
        $em = $this->getDoctrine()->getManager();
        $doctrine = $this->getDoctrine();

        if (!$request->get('customerExtn') && !$request->get('campaignId')) {
            $response = $common->apiParameterValidations($request->get('customerExtn'), $request->get('campaignId'), 'live_calls', '', '');
        } else {
            $msg = "";

            $customerExtn = trim($request->get('customerExtn'));
            $campaignId = trim($request->get('campaignId'));

            if ($request->getMethod() == 'POST') {
                $em = $this->getDoctrine()->getManager();
                $customerId = $common->customerIdbyCustomerExtn($doctrine, $customerExtn);

                $helperHandler->updateCampainDataStatus($campaignId, $customerId, 3);

                $msg = "Data has been inserted into livecalls";
            }


            $responseArray = array(
                'status' => 'success',
                'response' => 'call status has been changed',
                'message' => $msg
            );
            $response->setContent(json_encode($responseArray));
            $response->headers->set('Content-Type', 'application/json');
        }
        return $response;
    }

    /**
     * @Route("/api/v1/redis_called_customers", name="_set_redis_called_customers_count")
     * @Method({ "POST"})
     */
    public function setRedisCalledCustomersCount(Request $request)
    {
        $response = new Response();
        $redisHandler = $this->get('redis_handler');
        $dialString = $request->request->get("dialerId");
        $type = $request->request->get("type");
        if ($type === "setValue") {
            $redisHandler->setRedisArrayElement("dial_users_count", $dialString);
        }

        if ($type === "removeValue") {
            $redisHandler->deleteRedisArrayElement("dial_users_count", $dialString);
        }
        if ($type === "resetValue") {
            $redisHandler->setRadisKeyValue("dial_users_count", "");
        }



        $responseArray = array(
            'status' => 'success',
            'message' => " successfully inserted the value",
        );
        $response->setContent(json_encode($responseArray));
        return $response;
    }

    /**
     * @Route("/api/v1/check_customer_retry_count", name="_api_check_customer_retry_count")
     * @Method({ "POST"})
     */
    public function changeCustomerRetryCountAction(Request $request)
    {
        $response = new Response();
        $responseArray = array();
        $common = $this->get('common_handler');
        $helperHandler = $this->get('helper_handler');
        $error = array();
        $em = $this->getDoctrine()->getManager();
        $doctrine = $this->getDoctrine();

        if (!$request->get('campaignDataId')) {
            $responseArray = array(
                'status' => 'error',
                'message' => "Invalid Campaign Data Id"
            );
            $response->setContent(json_encode($responseArray));
            // $response->setStatusCode(Response::HTTP_BAD_REQUEST);
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }

        $msg = "";
        $connection = $em->getConnection();
        $campaignDataId = trim($request->get('campaignDataId'));

        $campaignDataQuery = "SELECT  cd.* 
                            from campaign_data as cd
                            where cd.cd_id=" . $campaignDataId;

        $campaignDataStatement = $connection->prepare($campaignDataQuery);
        $result = $campaignDataStatement->execute();
        $customersData = $campaignDataStatement->fetch();
//        var_dump($customersData);
//        print_r(count($customersData));
//        exit;
//        

        if ($customersData) {
            if ($customersData['retry_count'] == $customersData['max_retry_count']) {
                //change cd_id status to Unreachable -6
                $helperHandler->updateCampainDataStatusById($campaignDataId, 6);

                $responseArray = array(
                    'status' => 'success',
                    'response' => 'max retry count and retry count are not equal',
                    'message' => "max"
                );
                $response->setContent(json_encode($responseArray));
                $response->headers->set('Content-Type', 'application/json');

                return $response;
            }
        }

        $responseArray = array(
            'status' => 'success',
            'response' => 'call status has been changed',
            'message' => "notMax"
        );
        $response->setContent(json_encode($responseArray));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

}

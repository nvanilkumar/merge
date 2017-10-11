<?php

namespace ApiBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use ApiBundle\Entity\Campaign;
use ApiBundle\Entity\User;
use ApiBundle\Entity\CampaignType;
use ApiBundle\Entity\CampaignAgents;
use ApiBundle\Entity\Customer;
use ApiBundle\Entity\CampaignData;
use ApiBundle\Entity\LiveCalls;
use \DateTime;
use ApiBundle\Entity\LiveAgents;

class DefaultController extends Controller {

    /**
     * @Route("/")
     */
    public function indexAction() {
        return $this->render('ApiBundle:Default:index.html.twig');
    }

    /**
     * @Route("/v1/addCustomer", name="_api_add_customer")
     * @Method({"GET"})
     */
    public function addCustomerAction(Request $request) {
        $response = new Response();
        $op = trim($request->get('op'));
        $campaign = trim($request->get('campaign'));
        $number = trim($request->get('number'));
        $em = $this->getDoctrine()->getManager();
        $connection = $em->getConnection();
        $newcustomerID = '';
        if ($op == '') {
            $response = $this->apiParameterValidations('op is missing ', 400);
        } else if ($campaign == '') {
            $response = $this->apiParameterValidations('campaign is missing ', 400);
        } else if ($number == '') {
            $response = $this->apiParameterValidations('number is missing ', 400);
        } else {
            if (!(is_string($op)) && $op != "addcall") {
                $response = $this->apiParameterValidations('op should a string', 404);
            }/*
              else if(!(is_numeric($number))){
              $response=$this-> apiParameterValidations('number should be a number string given',404);
              } */ else {
                $repository = $this->getDoctrine()->getRepository('ApiBundle:Campaign');
                $campaign = $repository->findOneBy(array('campaignName' => $campaign));

                if (!$campaign) {
                    $response = $this->apiParameterValidations('No campaings are running', 404);
                } else {
                    $repository = $this->getDoctrine()->getRepository('ApiBundle:Customer');
                    $customer = $repository->findOneBy(array('phoneNumber' => $number));
                    if (!$customer) {
                        $title = trim($request->get('title')) ? trim($request->get('campaign')) : 'title';
                        $firstName = trim($request->get('firstName')) ? trim($request->get('firstName')) : 'firsstName';
                        $lastName = trim($request->get('lastName')) ? trim($request->get('lastName')) : 'lastName';
                        $company = trim($request->get('company')) ? trim($request->get('firstName')) : 'firsstName';
                        $accCode = trim($request->get('accCode')) ? trim($request->get('accCode')) : '1212';
                        $newcustomer = new Customer();
                        $newcustomer->setTitle($title);
                        $newcustomer->setFirstName($firstName);
                        $newcustomer->setLastName($lastName);
                        $newcustomer->setCompany($number);
                        $newcustomer->setPhoneNumber($number);
                        $newcustomer->setAccCode($accCode);
                        $em->persist($newcustomer);
                        $em->flush();

                        $repository = $this->getDoctrine()->getRepository('ApiBundle:Customer');
                        $customer = $repository->findOneBy(array('phoneNumber' => $number));
                    }
                    $customerQuery = "select *
                        from campaign_data c
                          WHERE c.campaign_id=" . $campaign->getCampaignId() . " and c.customer_id=" . $customer->getCustomerId() . " ";
                    $statement = $connection->prepare($customerQuery);
                    $statement->execute();
                    $data = $statement->fetch();
                    if (empty($data)) {
                        $newcampdata = new CampaignData();
                        $newcampdata->setCampaign($campaign);
                        $newcampdata->setCustomer($customer);
                        $newcampdata->setDsId(0);
                        $newcampdata->setRetryCount(0);
                        $newcampdata->setAssignedTo(0);
                        $em->persist($newcampdata);
                        $em->flush();
                        $responseArray = array(
                            'status' => 'success',
                            'message' => 'Customer has been inserted',
                        );
                    } else {
                        $responseArray = array(
                            'status' => 'success',
                            'message' => 'Customer exists in the DB',
                        );
                    }
                    $response->setContent(json_encode($responseArray));
                    $response->headers->set('Content-Type', 'application/json');
                }
            }
        }
        return $response;
    }

    /**
     * @Route("/v1/rmCustomer", name="_api_rm_customer")
     * @Method({ "GET"})
     */
    public function removeCustomerAction(Request $request) {
        $response = new Response();
        $op = trim($request->get('op'));
        $number = trim($request->get('number'));
        $campaign = trim($request->get('campaign'));
        $em = $this->getDoctrine()->getManager();
        if ($op == '') {
            $response = $this->apiParameterValidations('op is missing ', 400);
        } else if ($number == '') {
            $response = $this->apiParameterValidations('number is missing ', 400);
        } else if ($campaign == '') {
            $response = $this->apiParameterValidations('campaign is missing ', 400);
        } else {
            if (!(is_string($op)) && $op != "rmcall") {
                $response = $this->apiParameterValidations('op should a string', 400);
            } else if (!(is_numeric($number))) {
                $response = $this->apiParameterValidations('number should be a number string given', 400);
            } else {
                $repository = $this->getDoctrine()->getRepository('ApiBundle:Customer');
                $customer = $repository->findOneBy(array('phoneNumber' => $number));
                if (!$customer) {
                    $response = $this->apiParameterValidations('Given customer does not exist', 404);
                } else {
                    $repository = $this->getDoctrine()->getRepository('ApiBundle:Campaign');
                    $campaign = $repository->findOneBy(array('campaignName' => $campaign));
                    if (!$campaign) {
                        $response = $this->apiParameterValidations('No campaings are running', 404);
                    } else {
                        $rmCampaignData = $this->getDoctrine()->getRepository('ApiBundle:CampaignData')
                                ->findOneBy(array('customer' => $customer->getCustomerId() , 'campaign' => $campaign->getCampaignId()));
                        if($rmCampaignData){
                        $em->remove($rmCampaignData);
                        $em->flush();
                        $responseArray = array(
                            'status' => 'success',
                            'message' => 'Customer has been deleted',
                        );
                        }
                        else
                        {
                             $responseArray = array(
                            'status' => 'success',
                            'message' => 'No data to delete in DB',
                        );
                        }
                    }
                    $response->setContent(json_encode($responseArray));
                    $response->headers->set('Content-Type', 'application/json');
                }
            }
        }
        return $response;
    }

    /**
     * @Route("/v1/callRecording", name="_call_record")
     * @Method({ "GET"})
     */
    public function callRecordingAction(Request $request) {
        $response = new Response();
        $op = trim($request->get('op'));
        $campaign = trim($request->get('campaign'));
        $agent = trim($request->get('agent'));
        $customer = trim($request->get('customer'));

        if ($op == '') {
            $response = $this->apiParameterValidations('op is missing ', 400);
        } else if ($campaign == '') {
            $response = $this->apiParameterValidations('campaign is missing ', 400);
        } else if ($agent == '') {
            $response = $this->apiParameterValidations('agent is missing ', 400);
        } else if ($customer == '') {
            $response = $this->apiParameterValidations('customer is missing ', 400);
        } else {
            if (!(is_string($op)) && $op != "callrecording") {
                $response = $this->apiParameterValidations('op should be a string', 400);
            }
            /*
              else if(!(is_numeric($number))){
              $this-> apiParameterValidations('number should be a number,'
              . ' string given',400);
              } */ else {
                $repository = $this->getDoctrine()->getRepository('ApiBundle:Campaign');
                $campaign = $repository->findOneBy(array('campaignName' => $campaign));
                if (!$campaign) {
                    $response = $this->apiParameterValidations('Given campaign does not exist', 404);
                } else {

                    $repository = $this->getDoctrine()->getRepository('ApiBundle:User');
                    $agent = $repository->findOneBy(array('extension' => $agent));
                    if (!$agent) {
                        $response = $this->apiParameterValidations('Given agent does not exist', 404);
                    } else {
                        $repository = $this->getDoctrine()->getRepository('ApiBundle:Customer');
                        $customer = $repository->findOneBy(array('phoneNumber' => $customer));
                        if (!$customer) {
                            $response = $this->apiParameterValidations('Given customer does not exist', 404);
                        } else {
                            $repository = $this->getDoctrine()->getRepository('ApiBundle:CampaignData');
                            $callData = $repository->findOneBy(array('campaign' => $campaign->getCampaignId(),
                                'customer' => $customer->getCustomerId(),
                                'assignedTo' => $agent->getUserId()));
                            if (!$callData) {
                                $response = $this->apiParameterValidations('No call data exist with given parameters ', 404);
                            } else {
                                $recfile = 'NIL';
                                if ($callData->getCallRecordingFile() != '') {
                                    $recfile = $this->generateUrl('_generic_download_external_act', array('filename' => $callData->getCallRecordingFile()), UrlGeneratorInterface::ABSOLUTE_URL);
                                }
                                $responseArray = array(
                                    'status' => 'success',
                                    'data' => $callData->getCallRecordingFile(),
                                    'downloadlink' => $recfile
                                );
                                $response->setContent(json_encode($responseArray));
                                $response->headers->set('Content-Type', 'application/json');
                            }
                        }
                    }
                }
            }
        }
        return $response;
    }

    /**
     * @Route("/v1/liveCall", name="_live_call")
     * @Method({ "GET"})
     */
    public function liveCallAction(Request $request) {
        $response = new Response();
        $em = $this->getDoctrine()->getEntityManager();
        $connection = $em->getConnection();
        $campaignName = $request->get('campaign');

        if ($campaignName) {
            $statement = $connection->prepare("SELECT campaign_id,campaign_name FROM campaign WHERE campaign_name='" . $campaignName . "'");
            $statement->execute();
            $campaigns = $statement->fetchAll();
            if (!$campaigns) {
                return $response = $this->apiParameterValidations('Given campaign does not exist', 404);
            }
        } else {

            $statement = $connection->prepare("SELECT campaign_id,campaign_name FROM campaign WHERE from_date<NOW() AND to_date>NOW()");
            $statement->execute();
            $campaigns = $statement->fetchAll();
        }
        if (!$campaigns) {
            
        } else {
            $totalCampagins = count($campaigns);
            $data['currentCampaigns']['count'] = $totalCampagins;
            $op = $request->get('op') ? trim($request->get('op')) : '';
            $type = $request->get('type') ? trim($request->get('type')) : '';
            if ($op == '') {
                $response = $this->apiParameterValidations('op is missing ', 400);
            } else {
                if ($type == 'xml') {
                    $rootNode = new \SimpleXMLElement("<?xml version='1.0' standalone='yes'?><currentCampaigns></currentCampaigns>");
                }

                foreach ($campaigns as $campaign) {
                    $campaignQuery = "select first_name,last_name,company,phone_number,acc_code
                                from dial_status ds
                                join campaign_data cd on cd.ds_id=ds.ds_id
                                join campaign c on c.campaign_id=cd.campaign_id
                                join customer cust on cust.customer_id=cd.customer_id
                                where cd.campaign_id=" . $campaign['campaign_id'] . " and ds.dial_status='complete'";
                    $statement = $connection->prepare($campaignQuery);
                    $statement->execute();
                    $customers = $statement->fetchAll();
                    $customerCount = count($customers);
                    $customers = $customers? : 0;
                    $data['currentCampaigns'][$campaign['campaign_name']]['totalCompletedCalls'] = $customerCount;
                    $data['currentCampaigns'][$campaign['campaign_name']]['completedCustomers'] = $customers;
                    if (isset($rootNode)) {
                        $rootNode->addAttribute('count', $totalCampagins);
                        $campaign = $rootNode->addChild($campaign['campaign_name']);
                        $campaign->addAttribute('totalCompletedCalls', count($customers));
                        $i = 1;
                        foreach ($customers as $customer) {
                            $child = 'call-' . $i;
                            $c = $campaign->addChild($child);
                            $c->addChild('first_name', $customer['first_name']);
                            $c->addChild('last_name', $customer['last_name']);
                            $c->addChild('company', $customer['company']);
                            $c->addChild('phone_number', $customer['phone_number']);
                            $c->addChild('acc_code', $customer['acc_code']);
                            $i = $i + 1;
                        }
                    }

                    if (isset($rootNode)) {
                        $response = new Response($rootNode->asXML());
                    } else {
                        $responseArray = array(
                            'status' => 'success',
                            'data' => $data
                        );
                        $response->setContent(json_encode($responseArray));
                        $response->headers->set('Content-Type', 'application/json');
                    }
                }
            }
        }
        return $response;
    }

    public function apiParameterValidations($msg, $code) {
        $response = new Response();
        $responseArray = array(
            'status' => 'error',
            'message' => $msg
        );
        $response->setContent(json_encode($responseArray));
        if ($code == 400) {
            $response->setStatusCode(Response::HTTP_BAD_REQUEST);
        } else {
            $response->setStatusCode(Response::HTTP_NOT_FOUND);
        }
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("/generic/download/{filename}", name="_generic_download_external_act")
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

<?php

namespace WidgetBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\ExpressionLanguage\Expression;
// these import the "@Route" and "@Template" annotations
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Session\Session;
use AppBundle\Entity\User;

class DefaultController extends Controller {

    public function indexAction() {
        return $this->render('WidgetBundle:Default:index.html.twig');
    }

    /**
     * @Route("/", name="_widget_login")
     * @Template("WidgetBundle:Default:login.html.twig")
     */
    public function widgetLogin(Request $request) {
        //$user = new User();
        // $encoderFactory = $this->get('security.encoder_factory');
        //         $encoder = $encoderFactory->getEncoder($user);
        //$salt = md5(uniqid());
        // echo $salt."     ";
        // $newpassword = $encoder->encodePassword('123456', $salt);
        // echo $newpassword;
        // exit;
        $this->data['title'] = 'Login CesDialer';
        $error = array();
        $request = $this->getRequest();
        $username = $request->get('username')? : '';
        $password = $request->get('password')? : '';
        if ($username && $password) {
            $email = $request->get('username');
            $password = $request->get('password');
            $repository = $this->getDoctrine()->getRepository('AppBundle:User');
            $user = $repository->findOneBy(array('email' => $email));
            if ($user) {
                $salt = ($user->getSalt())? : '';
                $userId = $user->getUserId();
                $userRoleId = $user->getRole();
                $dbpassword = $user->getPassword();
                $extension = $user->getExtension();
                $user = new User();
                $encoderFactory = $this->get('security.encoder_factory');
                $encoder = $encoderFactory->getEncoder($user);

                $newpassword = $encoder->encodePassword($password, $salt);
                if ($newpassword === $dbpassword) {
                    $repository = $this->getDoctrine()->getRepository('AppBundle:Roles');
                    $userRole = $repository->findOneBy(array('roleId' => $userRoleId));
                    if ($userRole->getRole() === 'ROLE_AGENT') {
                        $session = new Session();
                        $session->set('userId', $userId);
                        $session->set('role', $userRole->getRole());
                        $session->set('extension', $extension);
                        $session->set('widget', 'Yes');
                        $session->set('userName', $email);
                        return $this->redirect($this->generateUrl('_widget_campaign'));
                    } else {
                        $error['message'] = 'Invalid credentials';
                    }
                } else {
                    $error['message'] = 'Invalid credentials';
                }
            } else {
                $error['message'] = 'Invalid credentials';
            }
        }

        $request = $this->getRequest();
        $session = $request->getSession();
        $this->data['error'] = $error;
        return $this->data;
    }

    /**
     * @Route("/campaign", name="_widget_campaign")
     * @Template("WidgetBundle:Default:campaign.html.twig")
     */
    public function widgetCampaignAction() {
        $em = $this->getDoctrine()->getEntityManager();
        $connection = $em->getConnection();
        $helperHandler = $this->get('helper_handler');
        $this->data['title'] = 'Login CesDialer';
        $request = $this->getRequest();
        $session = $request->getSession();
        if (!$session->get('widget')) {
            return $this->redirect($this->generateUrl('_widget_login'));
        }
        $extension = $session->get('extension');
        $userId = $session->get('userId');
        $campdata = array();
        $statement = $connection->prepare(" select c.* , cd.cd_id , cd.skipped_by , cd.ds_id
                            from campaign_data cd
                            join customer c on c.customer_id = cd.customer_id  where cd.ds_id != 0 and cd.assigned_to = $userId order by cd.updated_on DESC ");
        $statement->execute();
        $campdata = $statement->fetchAll();
        $this->data['userName'] = $session->get('userName');
        $this->data['extension'] = $extension;
        $statement = $connection->prepare("SELECT * FROM  campaign_agents ca  LEFT JOIN campaign c ON ca.campaign_id=c.campaign_id LEFT JOIN   campaign_type ct ON ct.ct_id=c.ct_id WHERE ca.user_id=" . $userId);
        $statement->execute();
        $this->data['campaigns'] = $statement->fetchAll();
        $this->data['campdata'] = $campdata;
        return $this->data;
    }

    /**
     * @Route("/openurl", name="_widget_openurl")
     * @Template("WidgetBundle:Default:openurl.html.twig")
     * @Method({ "GET"})
     */
    public function widgetOpenurlAction(Request $request) {
        $extension = trim($request->get('extension'));
        $config = false;
        $repository = $this->getDoctrine()->getRepository('ApiBundle:User');
        $agent = $repository->findOneBy(array('extension' => $extension));
        if($agent)
        {
            $config = true;
        }
        return array('extension' => $extension , 'config' => $config , 'agent' => $agent );
    }
    
    /**
     * @Route("/ajax/hangup_agent", name="_widget_hangup_agent")
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
        $ext = trim($request->request->get('ext'));
        $repository = $this->getDoctrine()->getRepository('ApiBundle:User');
        $user = $repository->findOneBy(array('extension' => $ext));
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

    /**
     * @Route("/ajax/get_customer_details", name="_widget_get_customer_details")
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
                $statement = $connection->prepare(" select * from customer u  where u.phone_number = " . $ext);
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
     * @Route("/ajax/get_campcustomer_details", name="_widget_agent_get_campcustomer_details")
     */
    public function ajaxGetCampCustomerDetailsAction(Request $request) {
        $response = new Response();

        $em = $this->getDoctrine()->getEntityManager();

        $isAjax = $request->isXmlHttpRequest();

        $res = array('status' => 'failed',
            'message' => 'Something went wrong.',
            'response' => ''
        );
        if ($isAjax) {
            if ($request->getMethod() == 'POST') {
                $campaign_id = trim($request->request->get('campaign_id'));
                $connection = $em->getConnection();

                if ($campaign_id != '') {
                    $statement = $connection->prepare(" select c.* , cd.cd_id , cd.skipped_by
                            from campaign_data cd
                            join customer c on c.customer_id = cd.customer_id  where cd.ds_id = 0 and cd.campaign_id = " . $campaign_id);
                    $statement->execute();
                    $campdata = $statement->fetchAll();
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
     * @Route("/ajax/skip_call", name="_widget_agent_skipp_call")
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
        $ext = trim($request->request->get('ext'));
        if ($cd_id == '' || !is_numeric($cd_id)) {
            $error['message'] = "Please enter a valid Extension";
        }
        $repository = $this->getDoctrine()->getRepository('ApiBundle:User');
        $user = $repository->findOneBy(array('extension' => $ext));
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
     * @Route("/logout", name="_widget_logout")
     *
     */
    public function widgetLogoutAction(Request $request) {
        $session = $request->getSession();
        $session->remove('userId');
        $session->remove('widget');
        return $this->redirect($this->generateUrl('_widget_login'));
    }
    
    
    /**
     * @Route("/ajax/agent_status", name="_widget_get_agent_status")
     */
    public function ajaxWidgetUserStatusAction(Request $request) {
        $response = new Response();
        $encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());

        $serializer = new Serializer($normalizers, $encoders);
        $em = $this->getDoctrine()->getEntityManager();

        $isAjax = $request->isXmlHttpRequest();

        $res = array('status' => 'failed',
            'message' => 'Something went wrong.',
            'response' => ''
        );
        if ($isAjax) {
            if ($request->getMethod() == 'POST') {
                $ext = trim($request->request->get('agentext'));
                $connection = $em->getConnection();
                $statement = $connection->prepare(" select u.astrisk_login from user u  where u.extension = " . $ext);
                $statement->execute();
                $agentdata = $statement->fetch();
                // print_r($student);
                //exit;
                // $jsonContent = $serializer->serialize($agentdata, 'json');
                $res = array('status' => 'success',
                    'message' => 'Agent Status changed Successfully',
                    'response' => $agentdata
                );
            }
        }

        $response->setContent(json_encode($res));

        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
    
    /**
     * @Route("/ajax/update_call_status", name="_widget_update_call_status")
     * @Method({"POST"})
     */
    public function ajaxWidgetUpdateCallStatusAction(Request $request) {
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
        $ext = trim($request->request->get('ext'));
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
        $repository = $this->getDoctrine()->getRepository('ApiBundle:User');
        $user = $repository->findOneBy(array('extension' => $ext));
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
    

}

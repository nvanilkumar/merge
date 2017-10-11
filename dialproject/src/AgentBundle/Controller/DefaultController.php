<?php

namespace AgentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
// these import the "@Route" and "@Template" annotations    
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use PAMI\Message\Event\DialEvent;
use PAMI\Message\Action\SIPShowRegistryAction;
use PAMI\Message\Action\PingAction;
use PAMI\Message\Action\OriginateAction;
use PAMI\Message\Action\EventsAction;
use PAMI\Message\Action\SIPNotifyAction;
use PAMI\Message\Action\StatusAction;
use PAMI\Message\Action\AgentaddAction;

class DefaultController extends Controller {

    public $title = "";
    public static $template = "AgentBundle:Templates:agent.html.twig";
    public $data = array();

    public function __construct() {
        $this->data['extend_view'] = self::$template;
        $this->data['section'] = '';
        $this->data['section_item'] = '';
        
    }

    /**
     * @Route("/agent", name="_agent_dashboard")
     * @Template("AgentBundle:Default:index.html.twig")
     */
    public function dashboardAction(request $request) {
        
        $this->data['title'] = 'Agent Panel | ' . $this->getParameter('site_name');
        $error = "Please login";
        $helperHandler = $this->get('helper_handler');
        $this->data['error'] = $error;
        $key = trim($request->query->get('keyword'));
        $em = $this->getDoctrine()->getEntityManager();
        $connection = $em->getConnection();
        
        //////
        $user = $this->getUser();
        $userId = $user->getUserId();
        $asteriskLogin = $user->getAstriskLogin();
        $extension = $user->getExtension();

        $statement = $connection->prepare("SELECT * FROM campaign c  JOIN campaign_agents ca ON ca.campaign_id=c.campaign_id 
             WHERE ca.user_id=" . $userId . " and c.campaign_status = 'active' and c.is_deleted = 0  AND from_date<NOW() AND to_date>NOW()");
        $statement->execute();
        $campaigns = $statement->fetchAll();
        $currentCampaigns = $campaigns;
        $this->data['currentCampaigns'] = $currentCampaigns;

       
        $statement = $connection->prepare("SELECT * FROM  campaign_agents ca  "
                . "LEFT JOIN campaign c ON ca.campaign_id=c.campaign_id "
                . "LEFT JOIN   campaign_type ct ON ct.ct_id=c.ct_id "
                . "WHERE  c.is_deleted = 0 and ca.user_id=" . $userId);
        $statement->execute();
        $this->data['campaigns'] = $statement->fetchAll();
        $this->data['campaigns_count'] = count($this->data['campaigns']);
        $statement = $connection->prepare("SELECT * FROM  live_calls lc "
                . "LEFT JOIN campaign c ON c.campaign_id=lc.campaign_id "
                . "WHERE lc.user_id=" . $userId);
        $statement->execute();
        $data = $helperHandler->getAgentCampaign('', $extension);
        $campdata = array();
        if (count($data) > 0 && isset($data[0]['campaign_id'])) {
            $campid = $data[0]['campaign_id'];
            $statement = $connection->prepare(" select c.* , cd.cd_id , cd.skipped_by , cd.ds_id
                            from campaign_data cd
                            join customer c on c.customer_id = cd.customer_id  where cd.ds_id != 0 and cd.assigned_to = $userId  and cd.campaign_id = " . $campid . " order by cd.updated_on DESC ");
            $statement->execute();
            $campdata = $statement->fetchAll();
        }
        $this->data['asteriskLogin'] = $asteriskLogin;
        $this->data['campdata'] = $campdata;
        return $this->data;
    }

    /**
     * @Route("/agent/ajax/agent_status", name="_agent_get_agent_status")
     */
    public function ajaxUserStatusAction(Request $request) {
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
     * @Route("/ajax/pamitets", name="pamitets")
     */
    public function ajaxPamitesttatusAction(Request $request) {
        $response = new Response();
        
        $amihost = $this->getParameter('amihost');
        $amiuser = $this->getParameter('amiuser');
        $amipassword = $this->getParameter('amipassword');
        $options = array(
            'host' => $amihost,
            'scheme' => 'tcp://',
            'port' => 5038,
            'username' => $amiuser,
            'secret' =>  $amipassword,
            'connect_timeout' => 10,
            'read_timeout' => 10000000
        );
        $client = new \PAMI\Client\Impl\ClientImpl($options);
        $client->open();
        $actionid = md5(uniqid());
        $response = $client->send(new StatusAction());
        $originateMsg = new OriginateAction('Local/1234567899874561@wakeup');
        $originateMsg->setContext('dialer');
        $originateMsg->setPriority('1');
        $originateMsg->setExtension(1234567899874561);
        $originateMsg->setAsync(false);
        $originateMsg->setVariable('hello', 'something');
        $originateMsg->setActionID($actionid);
        $orgresp = $client->send($originateMsg);
        //$notify = new SIPNotifyAction('marcelog');
        //$notify->setVariable('a', 'b');
        // $response = $client->send($notify);



        print('<pre>');
        print_r($orgresp);
        print('</pre>');
        sleep(5);
        exit;
    }
}

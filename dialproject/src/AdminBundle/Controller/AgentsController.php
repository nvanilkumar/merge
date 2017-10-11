<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\DateTime;
// these import the "@Route" and "@Template" annotations    
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\User;
use AppBundle\Entity\Roles;
use AppBundle\Entity\VoicemailUsers;
use AppBundle\Entity\CampaignAgents;
use PAMI\Message\Action\SIPShowRegistryAction;
use PAMI\Message\Action\PingAction;
use PAMI\Message\Action\OriginateAction;
use PAMI\Message\Action\EventsAction;
use PAMI\Message\Action\SIPNotifyAction;
use PAMI\Message\Action\StatusAction;
use PAMI\Message\Action\AgentaddAction;
use PAMI\Message\Action\AgentdeleteAction;

class AgentsController extends Controller {

    public $title;
    public static $template = "AdminBundle:Templates:admin.html.twig";
    public $data = array();

    public function __construct() {
        $this->data['extend_view'] = self::$template;
        $this->data['section'] = '';
        $this->data['section_item'] = '';
    }

    /**
     * @Route("/admin/agents/manage/{page}", name="_admin_agents_management", defaults={"page"=1} )
     * @Template("AdminBundle:Agents:agents.html.twig")
     */
    public function manageAgentsAction(Request $request, $page) {
        $common = $this->get('common_handler');
        $limit = 10;
        $key = '';
        $this->data['title'] = 'Agent Management | ' . $this->getParameter('site_name');
        $error = "Please login";
        $this->data['error'] = $error;
        $roleId = 2;

        $em = $this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()
                ->getRepository('AppBundle:USer');
        $total_agents = $repository->createQueryBuilder('p')
                ->select('count(p.userId)')
                ->where('p.role = :role')
                ->setParameter('role', '2')
                ->getQuery()
                ->getResult();
        $agents = $repository->createQueryBuilder('p')
                ->where('p.role = :role')
                ->setParameter('role', $roleId)
                ->setMaxResults($limit)
                ->setFirstResult((($page - 1) * $limit))
                ->getQuery()
                ->getResult();
        $totalagents = $total_agents[0][1];
        $totalpages = ceil($totalagents / $limit);

        if ($key != '') {
            $pageinate = $common->paginate('_admin_agents_management', array('keyword' => $key), $totalpages, $page, $limit);
        } else {
            $pageinate = $common->paginate('_admin_agents_management', array(), $totalpages, $page, $limit);
        }
        $this->data['paginate'] = $pageinate;
        $this->data['page'] = $page;
        $this->data['key'] = $key;
        $this->data['title'] = 'Administrator login';
        $this->data['totalpages'] = count($totalagents);
        if (count($totalagents) < $limit) {
            $limit = count($totalagents);
        }

        if (count($totalagents) > 0) {
            if ($page > 1) {
                $l = ((($page - 1) * $limit) + 1) + $limit;
                $this->data['range'] = ((($page - 1) * $limit) + 1) . " - " . $l;
            } else {
                $this->data['range'] = ((($page - 1) * $limit) + 1) . " - " . $limit;
            }
        }


        $this->data['agents'] = $agents;
        return $this->data;
    }

    /**
     * @Route("/admin/agents/add", name="_admin_agents_add")
     * @Template("AdminBundle:Agents:add.html.twig")
     */
    public function addAgentsAction(Request $request) {
        $error = array();
        $time = time();
        $em = $this->getDoctrine()->getManager();
        $validator = $this->get('validator');
        $connection = $em->getConnection();
        if ($request->getMethod() == 'POST') {
            $aname = trim($request->request->get('aname'));
            $email = trim($request->request->get('email'));
            $password = trim($request->request->get('password'));
            $extension = trim($request->request->get('extension'));
            $pin = trim($request->request->get('pin'));
            $gender = trim($request->request->get('gender'));
            $status = trim($request->request->get('status'));
            if ($aname == '') {
                $error['aname'] = "Please enter a agent name";
            }
            if ($email == '') {
                $error['email'] = "Please enter an email address";
            }
            if ($password == '') {
                $error['password'] = "Please enter a password";
            }
            if ($extension == '') {
                $error['extension'] = "Please enter a extension";
            }
            if ($pin == '') {
                $error['pin'] = "Please enter a pin";
            }
            if ($gender == '') {
                $error['gender'] = "Please select a gender";
            }
            if ($status == '') {
                $error['status'] = "Please select a status";
            }

            if ($extension != '') {

                if (strlen($extension) > 5) {
                    $error['extension'] = "Extension must not exceed more than 5 characters";
                }
                if (strlen($extension) < 3) {
                    $error['extension'] = "Extension must contain atleast 3 characters";
                }
                if (!preg_match('/^[1-9][0-9]{0,5}$/', $extension)) {
                    $error['extension'] = "Please enter valid numbers";
                }
            }

            if ($password != '') {
                if (strlen($password) > 20) {
                    $error['password'] = "Password must not exceed more than 20 characters";
                }
                if (strlen($password) < 3) {
                    $error['password'] = "Password must contain atleast 3 characters";
                }
            }
            if (empty($error)) {
                $repository = $this->getDoctrine()->getRepository('AppBundle:User');
                $users = $repository->findByEmail($email);
                $validated = true;
                if (!empty($users)) {
                    $validated = false;
                    $error['email'] = "This email already exists.";
                }

                $repository = $this->getDoctrine()->getRepository('AppBundle:User');
                $users = $repository->findByExtension($extension);
                if (!empty($users)) {
                    $validated = false;
                    $error['extension'] = "This extension already exists.";
                }
                //add user and send email if needed send password too
                if ($validated) {
                    $sendpassword = false;
                    $repository = $this->getDoctrine()->getRepository('AppBundle:Roles');
                    $role = $repository->findByRole('ROLE_AGENT');


                    $user = new User();
                    $salt = md5(uniqid());
                    $verify = md5(uniqid());

                    $encoderFactory = $this->get('security.encoder_factory');
                    $encoder = $encoderFactory->getEncoder($user);
                    $newpassword = $encoder->encodePassword($password, $salt);
                    $user->setFullName($aname);
                    $user->setEmail($email);
                    $user->setSalt($salt);
                    $user->setPassword($newpassword);
                    $user->setRole($role[0]);
                    $user->setStatus($status);
                    $user->setGender($gender);
                    $user->setPin($pin);
                    $user->setExtension($extension);
                    $user->setAstriskLogin(0);
                    $user->setWebLogin(0);
                    $user->setAddedOn(new \DateTime("now"));
                    $em->persist($user);
                    // $em->flush();

                    $voicemailUser = new VoicemailUsers();
                    $salt = md5(uniqid());
                    $encoderFactory = $this->get('security.encoder_factory');
                    $encoder = $encoderFactory->getEncoder($user);
                    $newpassword = $encoder->encodePassword($password, $salt);
                    $voicemailUser->setFullname($aname);
                    $voicemailUser->setEmail($email);
                    $voicemailUser->setPassword($pin);
                    $voicemailUser->setContext('from-sip');
                    $voicemailUser->setCustomerId($extension);
                    $voicemailUser->setMailbox($extension);
                    $voicemailUser->setPager('');
                    $voicemailUser->setTz('central');
                    $voicemailUser->setAttach('yes');
                    $voicemailUser->setSaycid('yes');
                    $voicemailUser->setDialout('');
                    $voicemailUser->setCallback('no');
                    $voicemailUser->setReview('no');
                    $voicemailUser->setOperator('no');
                    $voicemailUser->setEnvelope('no');
                    $voicemailUser->setSayduration('no');
                    $voicemailUser->setSaydurationm(1);
                    $voicemailUser->setSendvoicemail('no');
                    $voicemailUser->setDeleted('no');
                    $voicemailUser->setNextaftercmd('yes');
                    $voicemailUser->setForcename('no');
                    $voicemailUser->setForcegreetings('no');
                    $voicemailUser->setHidefromdir('yes');
                    $voicemailUser->setStamp(new \DateTime("now"));
                    $em->persist($voicemailUser);
                    $em->flush();


                    $param = array(
                        'agentid' => $extension,
                        'secret' => $password,
                        'name' => $aname
                    );
                    $this->addtoqueuemembers($param);
                    $amihost = $this->getParameter('amihost');
                    $amiuser = $this->getParameter('amiuser');
                    $amipassword = $this->getParameter('amipassword');
                    $fp = $this->connectandAdd($amihost, $amiuser, $amipassword, $param);
                    // print_r($fp);
                    //  exit;
                    $this->get('session')->getFlashBag()->add('notice', ' Agent has been added successfully.');
                    return $this->redirect($this->generateUrl('_admin_agents_management'));
                }
            }
        }
        $this->data['error'] = $error;
        $this->data['mode'] = 'add';
        return $this->data;
    }

    function addtoqueuemembers($param = array()) {
        $em = $this->getDoctrine()->getEntityManager();
        $connection = $em->getConnection();
        $sql = "select * from sipusers where `name` = '" . $param['agentid'] . "'  ";
        $statement = $connection->prepare($sql);
        $statement->execute();
        $data = $statement->fetch();
        if (empty($data)) {
            $livAgentInsert = " INSERT INTO sipusers ( `name` ,username , type , host , secret , context , nat , qualify , disallow , allow )
                             VALUES ( '" . $param['agentid'] . "' , '" . $param['agentid'] . "' , 'friend' , 'dynamic' , '" . $param['secret'] . "' , 'scopPBX_incoming' , 'force_rport,comedia' , 'yes' , 'all' , 'ulaw'  )";
            $connection->executeUpdate($livAgentInsert);
        }
    }

    function connectandAdd($host, $user, $pass, $param = array()) {
        $em = $this->getDoctrine()->getManager();
        $validator = $this->get('validator');
        $connection = $em->getConnection();
        $tmout = 3;
        if (isset($param['agentid']) && isset($param['secret']) && isset($param['name'])) {

            $options = array(
                'host' => $host,
                'scheme' => 'tcp://',
                'port' => 5038,
                'username' => $user,
                'secret' => $pass,
                'connect_timeout' => 10,
                'read_timeout' => 10000000
            );
            $client = new \PAMI\Client\Impl\ClientImpl($options);
            $client->open();

            $originateMsg = new AgentaddAction();
            $originateMsg->setAgentid($param['agentid']);
            $originateMsg->setSecret($param['secret']);
            $originateMsg->setName($param['name']);
            $orgresp = $client->send($originateMsg);
        }
        return true;
    }

    function connectandDelete($host, $user, $pass, $param = array()) {
        $em = $this->getDoctrine()->getManager();
        $validator = $this->get('validator');
        $connection = $em->getConnection();
        $tmout = 3;
        if (isset($param['agentid'])) {

            $options = array(
                'host' => $host,
                'scheme' => 'tcp://',
                'port' => 5038,
                'username' => $user,
                'secret' => $pass,
                'connect_timeout' => 10,
                'read_timeout' => 10000000
            );
            $client = new \PAMI\Client\Impl\ClientImpl($options);
            $client->open();

            $originateMsg = new AgentdeleteAction();
            $originateMsg->setAgentid($param['agentid']);
            $orgresp = $client->send($originateMsg);
        }
        return true;
    }

    /**
     * @Route("/secure/agents/edit/{eid}", name="_admin_agents_edit" ,defaults={"eid"=0})
     * @Template("AdminBundle:Agents:add.html.twig")
     */
    public function editAction(Request $request, $eid) {
        $error = array();
        $em = $this->getDoctrine()->getManager();
        $validator = $this->get('validator');
        $connection = $em->getConnection();
        $repository = $this->getDoctrine()->getRepository('AppBundle:User');
        $user = $repository->findOneBy(
                array('userId' => $eid)
        );
        if ($request->getMethod() == 'POST') {
            $aname = trim($request->request->get('aname'));
            $email = trim($request->request->get('email'));
            $password = trim($request->request->get('password'));
            $extension = trim($request->request->get('extension'));
            $pin = trim($request->request->get('pin'));
            $gender = trim($request->request->get('gender'));
            $status = trim($request->request->get('status'));
            if ($aname == '') {
                $error['aname'] = "Please enter a agent name";
            }
            if ($email == '') {
                $error['email'] = "Please enter an email address";
            }
            /*
              if ($password == '') {
              $error['password'] = "Please enter a password";
              } */
            if ($extension == '') {
                $error['extension'] = "Please enter a extension";
            }
            if ($pin == '') {
                $error['pin'] = "Please enter a pin";
            }
            if ($gender == '') {
                $error['gender'] = "Please select a gender";
            }
            if ($status == '') {
                $error['status'] = "Please select a status";
            }
            if ($extension != '') {

                if (strlen($extension) > 5) {
                    $error['extension'] = "extension must not exceed more than 5 characters";
                }
                if (strlen($extension) < 3) {
                    $error['extension'] = "extension must contain atleast 3 characters";
                }
                if (!preg_match('/^[1-9][0-9]{0,5}$/', $extension)) {
                    $error['extension'] = "Please enter valid numbers";
                }
            }
            if ($password != '') {
                if (strlen($password) > 20) {
                    $error['password'] = "Password must not exceed more than 20 characters";
                }
                if (strlen($password) < 3) {
                    $error['password'] = "Password must contain atleast 3 characters";
                }
            }
            if (empty($error)) {

                //$repository = $this->getDoctrine()->getRepository('AppBundle:User');
                //$users = $repository->findBy(array('email'=>$email,'userId'=> !$eid));

                $users = $em
                        ->createQueryBuilder()
                        ->select('t')
                        ->from('AppBundle:User', 't')
                        ->where('t.email = :email')
                        ->setParameter("email", $email)
                        ->andwhere('t.userId != :userId')
                        ->setParameter("userId", $eid)
                        ->getQuery()
                        ->getResult();

                $validated = true;
                if (!empty($users)) {
                    $validated = false;
                    $error['email'] = "This email already exists.";
                }
                $repository = $this->getDoctrine()->getRepository('AppBundle:User');
                $users = $em
                        ->createQueryBuilder()
                        ->select('t')
                        ->from('AppBundle:User', 't')
                        ->where('t.extension = :extension')
                        ->setParameter("extension", $extension)
                        ->andwhere('t.userId != :userId')
                        ->setParameter("userId", $eid)
                        ->getQuery()
                        ->getResult();
                if (!empty($users)) {
                    $validated = false;
                    $error['extension'] = "This extension already exists.";
                }
                if ($validated) {
                    //add user and send email if needed send password too
                    $sendpassword = false;
                    $encoderFactory = $this->get('security.encoder_factory');
                    $encoder = $encoderFactory->getEncoder($user);


                    if ($password != '') {
                        $salt = md5(uniqid());
                        $verify = md5(uniqid());
                        $newpassword = $encoder->encodePassword($password, $salt);
                        $user->setPassword($newpassword);
                        $user->setSalt($salt);
                    }

                    $user->setFullName($aname);
                    $user->setEmail($email);
                    $user->setStatus($status);
                    $user->setGender($gender);
                    $user->setPin($pin);
                    $user->setExtension($extension);
                    $user->setAddedOn(new \DateTime("now"));
                    $em->persist($user);
                    $em->flush();


                    $repository = $this->getDoctrine()->getRepository('AppBundle:VoicemailUsers');
                    $voicemailUser = $repository->findOneBy(
                            array('customerId' => $extension)
                    );

                    $voicemailUser->setFullname($aname);
                    $voicemailUser->setEmail($email);
                    $voicemailUser->setPassword($pin);
                    $voicemailUser->setStamp(new \DateTime("now"));
                    $em->persist($voicemailUser);
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('success', '  Agent has been updated successfully.');

                    return $this->redirect($this->generateUrl('_admin_agents_management'));
                }
            }
        }
        $this->data['user'] = $user;
        $this->data['error'] = $error;
        $this->data['mode'] = 'edit';
        return $this->data;
    }

    /**
     * @Route("/secure/agents/delete/{uid}", name="_admin_agents_delete" ,defaults={"uid"=0})
     * @Template("AdminBundle:Agents:add.html.twig")
     */
    public function agentDeleteAction(Request $request, $uid) {

        $response = new Response();
        $isAjax = $request->isXmlHttpRequest();
        // get the value of a $_POST parameter
        $userId = $request->request->get('userId');
        $ext = $request->request->get('ext');
        $delete = $request->request->get('delete');
        $em = $this->getDoctrine()->getEntityManager();
        $agent_delete = 0;
        $vmuser_delete = 0;
        if ($delete == 'multiple') {
            $userId = rtrim($userId, ',');
            $uid = explode(',', $userId);
            $custid = explode(',', $ext);
            $message = 'Agent(s) has been deleted successfully';
        } else {
            $uid = $userId;
            $custid = $ext;
            $message = 'Agent has been deleted successfully';
        }
        $agents = $em->getRepository('AppBundle:User')->findByUserId($uid);
        if (!empty($agents)) {
            foreach ($agents as $agent) {
                $param = array(
                    'agentid' => $agent->getExtension()
                );
                $amihost = $this->getParameter('amihost');
                    $amiuser = $this->getParameter('amiuser');
                    $amipassword = $this->getParameter('amipassword');
                $fp = $this->connectandDelete($amihost, $amiuser, $amipassword, $param);
                $em->remove($agent);
            }
            $agent_delete = 1;
        }
        $voicemail_users = $em->getRepository('AppBundle:VoicemailUsers')->findByCustomerId($custid);
        if (!empty($voicemail_users)) {
            foreach ($voicemail_users as $voicemail_user) {
                $em->remove($voicemail_user);
            }
            $em->flush();
            $vmuser_delete = 1;
        }
        if ($agent_delete == 1 && $vmuser_delete == 1) {
            $res = array('status' => 'success',
                'message' => $message,
                'response' => ''
            );
        } else {
            $res = array('status' => 'failed',
                'message' => 'Something went wrong.',
                'response' => ''
            );
        }

        $response->setContent(json_encode($res));

        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

}

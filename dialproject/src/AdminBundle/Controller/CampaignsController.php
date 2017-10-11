<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
// these import the "@Route" and "@Template" annotations    
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Session\Session;
use AppBundle\Entity\Campaign;
use AppBundle\Entity\User;
use AppBundle\Entity\CampaignType;
use AppBundle\Entity\CampaignAgents;
use AppBundle\Entity\Customer;
use AppBundle\Entity\CampaignData;
use AppBundle\Entity\Queues;
use AppBundle\Entity\Extensions;
use AppBundle\Entity\QueueMember;

class CampaignsController extends Controller
{

    public $title = "";
    public static $template = "AdminBundle:Templates:admin.htmlv1.twig";
    public $data = array();
    private $file;
    public $amihost = '41.87.218.57';
    public $amiuser = 'admin';
    public $amipassword = 'tms88in##';

    public function __construct()
    {
        $this->data['extend_view'] = self::$template;
        $this->data['section'] = '';
        $this->data['section_item'] = '';
    }

    /**
     * @Route("/admin/campaigns/{status}", name="_admin_campaigns_management", defaults={"status"="All"})
     * @Template("AdminBundle:Campaigns:campaigns.html.twig")
     */
    public function manageCampaignsAction(Request $request, $status)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $error=array();
        if ($status !=='All') {
            $em = $this->getDoctrine()->getEntityManager();
            $connection = $em->getConnection();
            $query = "select campaign_id as campaignId,campaign_name as campaignName,campaign_status as campaignStatus, "
                    . "is_complete as isComplete, is_running as isRunning, added_on as addedOn, pdf_report as pdfReport from campaign  where is_deleted=0 ";
            if ($status == 'Completed') {

                $query .= 'and is_complete=1 ';
            } else if ($status == 'LiveCampaign') {
                $query .= 'and is_running=1';
            } else {
                $query .= 'and campaign_status="' . $status . '" and  is_running=0 and is_complete=0';
            }
            $statement = $connection->prepare($query);
            $statement->execute();
            $this->data['campaigns'] = $statement->fetchAll();
        } else {
            $query = $em->createQuery(
                            'SELECT c
                                    FROM AppBundle:Campaign c
                                    WHERE c.isDeleted != :isdelval
                                    ORDER BY c.addedOn Desc'
                    )->setParameter('isdelval', '1');
            $this->data['campaigns'] = $query->getResult();
        }

        // $this->data['campaigns'] = $em->getRepository('AppBundle:Campaign')->findBy(array());
        if ($request->getMethod() == 'POST') {
            $action = trim($request->request->get('action'));
            $campaigns = $request->request->get('campaigns');
            if(empty($campaigns)){
                $error['campaingchek']="Please select atleast one campaign";
            }
            if ($action != '' && empty($error)){
                foreach ($campaigns as $cmp) {
                    $campaign = $em->getRepository('AppBundle:Campaign')->findOneBy(array('campaignId' => $cmp));
                    if ($campaign) {
                        if ($action == 'active' || $action == 'inactive') {
                            $campaign->setCampaignStatus($action);
                            $campaign->setUpdatedOn(new \DateTime('NOW'));
                            $em->persist($campaign);
                            $em->flush();
                            $em->clear();
                        }
                        elseif($action === 'completed' ){
                            if($campaign->getCampaignStatus() ==='inactive'){
                                $error['action']= "To mark as completed, selected campaign status should be active";
                            } else{
                                $campaign->setIsComplete(1);
                                $campaign->setUpdatedOn(new \DateTime('NOW'));
                                $em->persist($campaign);
                                $em->flush();
                                $em->clear();
                           }
                           
                        }elseif ($action == 'delete') {
                            $campaign->setIsDeleted(1);
                            $campaign->setUpdatedOn(new \DateTime('NOW'));
                            $em->persist($campaign);
                            $em->flush();
                            $em->clear();
                            $campaign_name = str_replace(' ', '_', $campaign->getCampaignName());
                            $this->deletefromqueuemem($campaign_name);
                            $this->deletefromqueue($campaign_name);
                            $this->deletecmextensions($campaign_name);
                        }
                    }
                }
                if(empty($error)){
                     if ($action == 'active' || $action == 'inactive' || $action == 'completed') {
                    $this->get('session')->getFlashBag()->add('notice', ' Campaigns marked as ' . $action . ' successfully.');
                } elseif ($action == 'delete') {
                    $this->get('session')->getFlashBag()->add('notice', ' Campaigns deleted successfully.');
                }
                     return $this->redirect($this->generateUrl('_admin_campaigns_management'));
                }
               
            }
        }
        $this->data['title'] = 'Campaigns Management | ' . $this->getParameter('site_name');
        $this->data['status'] =$status;
        //$error = "Please login";
        $this->data['error'] = $error;
        return $this->data;
    }

    /**
     * @Route("/admin/campaign/{campaign_id}", name="_admin_view_campaign",defaults={"campaign_id"=0})
     * @Template("AdminBundle:Campaigns:campaign.html.twig")
     */
    public function viewCampaignAction(Request $request, $campaign_id)
    {
        $common = $this->get('common_handler');
        $added = 0;
        $skipped = 0;
        $helperHandler = $this->get('helper_handler');
        $em = $this->getDoctrine()->getEntityManager();
        $this->data['campaign'] = $em->getRepository('AppBundle:Campaign')->findOneBy(array('campaignId' => $campaign_id));
        $cam = $this->data['campaign'];
        $isComplete = $cam->getIsComplete();
        if (!$this->data['campaign']) {
            $this->get('session')->getFlashBag()->add('error', ' Invalid Campaign.');
            return $this->redirect($this->generateUrl('_admin_campaigns_management'));
        }

        // $this->data['agents'] = $em->getRepository('AppBundle:CampaignAgents')->findBy(array('campaign' => $campaign_id ));
        $connection = $em->getConnection();
        $statement = $connection->prepare("SELECT ds.dial_status ,(SELECT COUNT(cd.campaign_id) FROM campaign_data AS cd
                                              WHERE cd.campaign_id=" . $campaign_id . " AND cd.ds_id=ds.`ds_id`) AS sec FROM dial_status ds");
        $statement->execute();
        $dailStatastics = $statement->fetchAll();
        $dailStatasticsColor = array('Busy' => '#DD4B39',
            'Call Back' => '#FFFF00',
            'Complete' => '#00A65A',
            'No Answer' => '#F39C12',
            'Do Not Call' => '#3C8DBC',
            'Voicemail' => '#605CA8',
            'Unreachable' => '#111111');
        foreach ($dailStatastics as $dailStatastic) {
            $status = $dailStatastic['dial_status'];
            $paiArray[] = array(
                "value" => intval($dailStatastic["sec"]),
                "color" => $dailStatasticsColor[$status],
                "highlight" => $dailStatasticsColor[$status],
                "label" => $status);
        }
        $camapginStatus = $helperHandler->getCampainStatusInfo($campaign_id);
        $totalCalls = $camapginStatus['totalUsers'];
        //import campaign data
        if (isset($_POST['Upload'])) {

            if (!empty($_FILES)) {
                $tempFile = $_FILES['customer_file']['tmp_name'];
                $fileParts = pathinfo($_FILES['customer_file']['name']);
                $newname = time() . '.' . $fileParts['extension'];
                $filePath = $this->getParameter('customers_directory') . $newname;
                if (move_uploaded_file($tempFile, $filePath)) {
                    $customers = $common->importCustomers($filePath, true, 6);
                    //process csv data
                    if (empty($customers)) {
                        $this->get('session')->getFlashBag()->add('notice', ' Invalid file format.');
                        return $this->redirect($this->generateUrl('_admin_view_campaign', array('campaign_id' => $campaign_id)));
                    } else {
                        foreach ($customers as $customer) {
                            $cuser = $em->getRepository('AppBundle:Customer')->findOneBy(array('phoneNumber' => $customer[0]));
                            $dialStatus = $em->getRepository('AppBundle:DialStatus')->findOneBy(array('dsId' => 1));
                             // Phonenumber validation
                            $regex = "/^(\d[\s-]?)?[\(\[\s-]{0,2}?\d{3}[\)\]\s-]{0,2}?\d{3}[\s-]?\d{4}$/i";
                            if ($cuser) {
                                $existcamp = $em->getRepository('AppBundle:CampaignData')->findOneBy(array('customer' => $cuser->getCustomerId(), 'campaign' => $this->data['campaign']->getCampaignId()));
                                if ($existcamp) {
                                    $skipped++;
                                } else {
                                    $added++;
                                    $newcampdata = new CampaignData();
                                    $newcampdata->setCampaign($this->data['campaign']);
                                    $newcampdata->setCustomer($cuser);
                                    $newcampdata->setDsId(0);
                                    $newcampdata->setRetryCount(0);
                                    $newcampdata->setAssignedTo(0);
                                    $newcampdata->setSkippedBy(0);
                                    $newcampdata->setMaxRetryCount($this->data['campaign']->getRetryCount());
                                    $em->persist($newcampdata);
                                    $em->flush();
                                }
                            } else {
                                if(!preg_match( $regex, $customer[0])){
                                    $skipped++;
                                } else{
                                    $added++;
                                    $newcust = new Customer();
                                    $newcust->setTitle(trim($customer[1]));
                                    $newcust->setFirstName(trim($customer[2]));
                                    $newcust->setLastName(trim($customer[3]));
                                    $newcust->setCompany(trim($customer[4]));
                                    $newcust->setPhoneNumber(trim($customer[0]));
                                    $newcust->setAccCode(trim($customer[5]));
                                    $em->persist($newcust);
                                    $em->flush();
                                    $newcampdata = new CampaignData();
                                    $newcampdata->setCampaign($this->data['campaign']);
                                    $newcampdata->setCustomer($newcust);
                                    $newcampdata->setDsId(0);
                                    $newcampdata->setRetryCount(0);
                                    $newcampdata->setAssignedTo(0);
                                    $newcampdata->setSkippedBy(0);
                                    $newcampdata->setMaxRetryCount($this->data['campaign']->getRetryCount());
                                    $em->persist($newcampdata);
                                     $em->flush();
                                }

                            }
                        }
                        $this->get('session')->getFlashBag()->add('notice', ' File imported successfully.' . $added . ' Added and ' . $skipped . ' Skipped');
                        return $this->redirect($this->generateUrl('_admin_view_campaign', array('campaign_id' => $campaign_id)));
                    }
                } else {
                    
                }
            }
        }

        $statement = $connection->prepare(" select * from campaign_agents ca join user u on u.user_id = ca.user_id  where ca.campaign_id = " . $campaign_id);
        $statement->execute();
        $this->data['agents'] = $statement->fetchAll();
        if ($isComplete == 1) {
            $query = "select cu.title,cu.first_name,cu.last_name,cu.phone_number,cu.company,cu.acc_code,lc.duration,ds.dial_status
                        from  dial_status ds
                       left join campaign_data cd  on cd.ds_id=ds.ds_id
                       left join customer cu  on cu.customer_id=cd.customer_id
                       left join live_calls lc on lc.to_number=cu.phone_number
                       where cd.campaign_id=" . $campaign_id . " group by cu.customer_id";
            $statement = $connection->prepare($query);
            $statement->execute();
            $this->data['campaignData'] = $statement->fetchAll();
            $this->data['isComplete'] = $isComplete;
        } else {
            $this->data['campaignData'] = $em->getRepository('AppBundle:CampaignData')->findBy(array('campaign' => $campaign_id));
        }
        $this->data['title'] = 'View Campaign | ' . $this->getParameter('site_name');
        $this->data['dailStatastics'] = $dailStatastics;
        $this->data['dailStatasticsColors'] = $dailStatasticsColor;
        $this->data['paiArray'] = $paiArray;
        $this->data['totalCalls'] = $totalCalls;
        $error = "Please login";
        $this->data['error'] = $error;
        return $this->data;
    }

    /**
     * @Route("/admin/new_campaign", name="_admin_create_campaign")
     * @Template("AdminBundle:Campaigns:new_campaign.html.twig")
     */
    public function createCampaignAction(Request $request)
    {

        $em = $this->getDoctrine()->getEntityManager();
        $connection = $em->getConnection();
        $error = array();
        $days = array();
        $voice_file='';
        if ($request->getMethod() == 'POST') {

            $campaign_name = trim($request->request->get('campaign_name'));
            $campaign_type = 3; // Campaign Type -Dialing With Preview
            $from_date = trim($request->request->get('from_date'));
            $to_date = trim($request->request->get('to_date'));
            $from_time = trim($request->request->get('from_time'));
            $to_time = trim($request->request->get('to_time'));
            $retry_count = trim($request->request->get('retry_count'));
            //$days = $request->request->get('days');

            if ($campaign_name == '') {
                $error['campaign_name'] = "Please enter a campaign name";
            }
            $existcampaign = $em->getRepository('AppBundle:Campaign')->findOneBy(array('campaignName' => $campaign_name, 'isDeleted' =>0));
            if ($existcampaign) {
                $error['campaign_name'] = "Campaign already exist please enter a new campaign name.";
            }
            if ($campaign_type == '') {
                $error['campaign_type'] = "Please select a campaign type";
            }
            if ($from_date == '') {
                $error['from_date'] = "Please select a from date";
            }
            if ($to_date == '') {
                $error['to_date'] = "Please select a to date";
            }
            if ($from_time == '') {
                $error['from_time'] = "Please select a from time";
            }
            if ($to_time == '') {
                $error['to_time'] = "Please select a to time";
            }
            if ($retry_count == '') {
                $error['retry_count'] = "Please eneter a retry count";
            }
           $helperHandler = $this->get('helper_handler');
           if(!empty($_FILES['voice_file']['name'])){
                $rootfolder =  $this->get('kernel')->getRootDir();
                if($helperHandler->fileUpload($_FILES['voice_file'],$rootfolder)){
                  $voice_file=$_FILES['voice_file']['name'];
              }
            } else{
                 $error['voice_file'] = "Please upload a voice file";
            }
            if (empty($error)) {
                $from_date_time = \DateTime::createFromFormat('m/d/Y H:i:s', $from_date . " " . $from_time)->format('y-m-d H:i');
                $to_date_time = \DateTime::createFromFormat('m/d/Y H:i:s', $to_date . " " . $to_time)->format('y-m-d H:i');
                $campaignInsert = " INSERT INTO campaign ( campaign_name , ct_id , from_date , to_date, from_time, to_time, 
                    monday, tuesday,wednesday,thursday,friday,saturday ,sunday,campaign_status,is_deleted, added_on, updated_on,retry_count,voice_file )
                             VALUES ('" . $campaign_name . "',3,'" . $from_date_time . "','" . $to_date_time . "','" . $from_time . "','" . $to_time .
                        "',0,0,0,0,0,0,0,'inactive',0,NOW(),NOW(), ".$retry_count.",'".$voice_file." ' )";
                $connection->executeUpdate($campaignInsert);
                $this->get('session')->getFlashBag()->add('success', ' New Campaign added successfully.');
                return $this->redirect($this->generateUrl('_admin_campaigns_management'));
            }
        }

        $this->data['title'] = 'Create New Campaign | ' . $this->getParameter('site_name');
        $this->data['error'] = $error;
        return $this->data;
    }

    function addtoqueuemembers($name, $extstring, $extn)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $connection = $em->getConnection();
        $sql = "select * from queue_member where queue_name = '" . $name . "' and interface = '" . $extstring . "' ";
        $statement = $connection->prepare($sql);
        $statement->execute();
        $data = $statement->fetch();
        if (empty($data)) {
            $livAgentInsert = " INSERT INTO queue_member ( `queue_name` , membername , interface )
                             VALUES ( '" . $name . "' , '" . $extn . "' , '" . $extstring . "' )";
            $connection->executeUpdate($livAgentInsert);
        }
    }

    function deletefromqueuemem($campaign_name)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $connection = $em->getConnection();
        $sql = "select * from queue_member where queue_name = '" . $campaign_name . "' ";
        $statement = $connection->prepare($sql);
        $statement->execute();
        $data = $statement->fetchAll();
        if (!empty($data)) {
            $livAgentInsert = " DELETE FROM queue_member  where queue_name =  '" . $campaign_name . "'  ";
            $connection->executeUpdate($livAgentInsert);
        }
    }

    function addtoqueue($name)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $connection = $em->getConnection();
        $sql = "select * from queues where `name` = '" . $name . "' ";
        $statement = $connection->prepare($sql);
        $statement->execute();
        $data = $statement->fetch();
        if (empty($data)) {
            $livAgentInsert = " INSERT INTO queues ( `name`)
                             VALUES ( '" . $name . "' )";
            $connection->executeUpdate($livAgentInsert);
        }
    }

    function deletefromqueue($name)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $connection = $em->getConnection();
        $sql = "select * from queues where `name` = '" . $name . "' ";
        $statement = $connection->prepare($sql);
        $statement->execute();
        $data = $statement->fetch();
        if (!empty($data)) {
            $livAgentInsert = " DELETE FROM queues  where `name` =  '" . $name . "'  ";
            $connection->executeUpdate($livAgentInsert);
        }
    }

    function deletecmextensions($campaign_name)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $connection = $em->getConnection();
        $sql = "select * from extensions where appdata= '" . $campaign_name . "' ";
        $statement = $connection->prepare($sql);
        $statement->execute();
        $data = $statement->fetchAll();
        if (!empty($data)) {
            foreach ($data as $cext) {
                $curid = $cext['id'];
                $deletequery = " DELETE FROM extensions  where id =  " . ($curid + 1) . "  ";
                $connection->executeUpdate($deletequery);
                $deletequery = " DELETE FROM extensions  where id =  " . ($curid) . "  ";
                $connection->executeUpdate($deletequery);
                $deletequery = " DELETE FROM extensions  where id =  " . ($curid - 1) . "  ";
                $connection->executeUpdate($deletequery);
                $deletequery = " DELETE FROM extensions  where id =  " . ($curid - 2) . "  ";
                $connection->executeUpdate($deletequery);
                $deletequery = " DELETE FROM extensions  where id =  " . ($curid - 3) . "  ";
                $connection->executeUpdate($deletequery);
                $deletequery = " DELETE FROM extensions  where id =  " . ($curid - 4) . "  ";
                $connection->executeUpdate($deletequery);
            }
        }
    }

    function addtoextensions($campaign_name, $exten)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $connection = $em->getConnection();
        $sql = "select * from extensions where exten= '" . $exten . "' ";
        $statement = $connection->prepare($sql);
        $statement->execute();
        $data = $statement->fetchAll();
        if (empty($data)) {
            $livAgentInsert = " INSERT INTO extensions ( exten , context , priority , app  )
                             VALUES ( '" . $exten . "' , 'dialer' , 1 , 'Answer' )";
            $connection->executeUpdate($livAgentInsert);

            $livAgentInsert = " INSERT INTO extensions ( exten , context , priority , app , appdata )
                             VALUES ( '" . $exten . "' , 'dialer' , 2 , 'Set' , 'START_TIME=\${STRFTIME(\${EPOCH},,%Y-%m-%d-%H:%M:%S)}' )";
            $connection->executeUpdate($livAgentInsert);

            $livAgentInsert = " INSERT INTO extensions ( exten , context , priority , app , appdata )
                             VALUES ( '" . $exten . "' , 'dialer' , 3 , 'Set' , 'MONITOR_FILENAME=\${campaign}_\${START_TIME}_\${CALLERID(num)}_\${EXTEN}' )";
            $connection->executeUpdate($livAgentInsert);

            $livAgentInsert = " INSERT INTO extensions ( exten , context , priority , app , appdata )
                             VALUES ( '" . $exten . "' , 'dialer' , 4 , 'MixMonitor' , '\${MONITOR_FILENAME}.wav' )";
            $connection->executeUpdate($livAgentInsert);

            $livAgentInsert = " INSERT INTO extensions ( exten , context , priority , app , appdata )
                             VALUES ( '" . $exten . "' , 'dialer' , 5  , 'Queue' , '" . str_replace(' ', '_', $campaign_name) . "' )";
            $connection->executeUpdate($livAgentInsert);

            $livAgentInsert = " INSERT INTO extensions ( exten , context , priority , app  )
                             VALUES ( '" . $exten . "' , 'dialer' , 6 , 'StopMixMonitor' )";
            $connection->executeUpdate($livAgentInsert);
        } else {
            $sql = "select * from extensions where exten= '" . $exten . "' and app = 'Queue' and appdata = '" . str_replace(' ', '_', $campaign_name) . "'  ";
            $statement = $connection->prepare($sql);
            $statement->execute();
            $extensions = $statement->fetch();
            if (empty($extensions)) {
                $maxpriority = 0;
                foreach ($data as $extdata) {
                    if ($extdata['priority'] > $maxpriority) {
                        $maxpriority = $extdata['priority'];
                    }
                }

                $livAgentInsert = " INSERT INTO extensions ( exten , context , priority , app  )
                             VALUES ( '" . $exten . "' , 'dialer' ,  " . ($maxpriority + 1) . " , 'Answer' )";
                $connection->executeUpdate($livAgentInsert);

                $livAgentInsert = " INSERT INTO extensions ( exten , context , priority , app , appdata )
                             VALUES ( '" . $exten . "' , 'dialer' ,  " . ($maxpriority + 2) . " , 'Set' , 'START_TIME=\${STRFTIME(\${EPOCH},,%Y-%m-%d-%H:%M:%S)}' )";
                $connection->executeUpdate($livAgentInsert);

                $livAgentInsert = " INSERT INTO extensions ( exten , context , priority , app , appdata )
                             VALUES ( '" . $exten . "' , 'dialer' ,  " . ($maxpriority + 3) . " , 'Set' , 'MONITOR_FILENAME=\${campaign}_\${START_TIME}_\${CALLERID(num)}_\${EXTEN}' )";
                $connection->executeUpdate($livAgentInsert);

                $livAgentInsert = " INSERT INTO extensions ( exten , context , priority , app , appdata )
                             VALUES ( '" . $exten . "' , 'dialer' ,  " . ($maxpriority + 4) . " , 'MixMonitor' , '\${MONITOR_FILENAME}.wav' )";
                $connection->executeUpdate($livAgentInsert);

                $livAgentInsert = " INSERT INTO extensions ( exten , context , priority , app , appdata )
                             VALUES ( '" . $exten . "' , 'dialer' ,  " . ($maxpriority + 5) . "  , 'Queue' , '" . str_replace(' ', '_', $campaign_name) . "' )";
                $connection->executeUpdate($livAgentInsert);

                $livAgentInsert = " INSERT INTO extensions ( exten , context , priority , app  )
                             VALUES ( '" . $exten . "' , 'dialer' ,  " . ($maxpriority + 6) . " , 'StopMixMonitor' )";
                $connection->executeUpdate($livAgentInsert);
            }
        }
    }

    /**
     * @Route("/admin/edit_campaign/{campaign_id}", name="_admin_edit_campaign",defaults={"campaign_id"=0})
     * @Template("AdminBundle:Campaigns:edit_campaign.html.twig")
     */
    public function editCampaignAction(Request $request, $campaign_id)
    {
        $error = array();
        $days = array();
        $voice_file='';
        $em = $this->getDoctrine()->getEntityManager();
        $connection = $em->getConnection();
        //get campaign data
        $campaign = $em->getRepository('AppBundle:Campaign')->findOneBy(array('campaignId' => $campaign_id));
        $this->data['campaign'] = $campaign;
        if (!$this->data['campaign']) {
            $this->get('session')->getFlashBag()->add('error', 'Campaign you are trying to access does not found.');
            return $this->redirect($this->generateUrl('_admin_campaigns_management'));
        }


        if ($request->getMethod() == 'POST') {
            $campaign_name = trim($request->request->get('campaign_name'));

            $campaign_type = 3; // Campaign Type -Dialing With Preview
            $from_date = trim($request->request->get('from_date'));
            $to_date = trim($request->request->get('to_date'));
            $from_time = trim($request->request->get('from_time'));
            $to_time = trim($request->request->get('to_time'));
            $retry_count = trim($request->request->get('retry_count'));
            // $days = $request->request->get('days');

            if ($campaign_name == '') {
                $error['campaign_name'] = "Please enter a campaign name";
            }
            $existcampaign = $em->getRepository('AppBundle:Campaign')->findOneBy(array('campaignName' => $campaign_name, 'isDeleted' =>0));
            if ($existcampaign && $campaign->getCampaignId() != $existcampaign->getCampaignId()) {
                $error['campaign_name'] = "Campaign already exist please enter a new campaign name.";
            }
            if ($campaign_type == '') {
                $error['campaign_type'] = "Please select a campaign type";
            }
            if ($from_date == '') {
                $error['from_date'] = "Please select a from date";
            }
            if ($to_date == '') {
                $error['to_date'] = "Please select a to date";
            }
            if ($from_time == '') {
                $error['from_time'] = "Please select a from time";
            }
            if ($to_time == '') {
                $error['to_time'] = "Please select a to time";
            }
            if ($retry_count == '') {
                $error['retry_count'] = "Please eneter a retry count";
            }
            $helperHandler = $this->get('helper_handler');
            if(!empty($_FILES['voice_file']['name'])){
                $rootfolder =  $this->get('kernel')->getRootDir();
                if($helperHandler->fileUpload($_FILES['voice_file'],$rootfolder)){
                  $voice_file=$_FILES['voice_file']['name'];
              }
            }
            /*
              if (empty($days)) {
              $error['days'] = "Please choose a day";
              } */
            if (empty($error)) {
               // echo 'from time is '.$from_date . " " . $from_time;
              //  exit;
                $from_date_time = \DateTime::createFromFormat('m/d/Y H:i:s', $from_date . " " . $from_time)->format('y-m-d H:i');
                $to_date_time = \DateTime::createFromFormat('m/d/Y H:i:s', $to_date . " " . $to_time)->format('y-m-d H:i');
                $campaignUpadate="UPDATE campaign SET  campaign_name = '".$campaign_name."',from_date = '".$from_date_time."'
                ,to_date ='". $to_date_time."',from_time ='". $from_time."',to_time = '".$to_time."',monday = 0,
                tuesday = 0,wednesday = 0,thursday = 0,friday = 0,saturday = 0,sunday = 0,campaign_status = 'inactive',is_deleted = 0,updated_on =NOW(),
                retry_count = ".$retry_count;
                if($voice_file){
                   $campaignUpadate.=" ,voice_file = '".$voice_file."'";
                }
                $campaignUpadate.=" where campaign_id=".$campaign_id;
                $connection->executeUpdate($campaignUpadate);
                $campaignDataUpadate="UPDATE campaign_data SET  max_retry_count = '".$retry_count."' where campaign_id=".$campaign_id;
                $connection->executeUpdate($campaignDataUpadate);
                $this->get('session')->getFlashBag()->add('notice', 'Campaign updated successfully');
                return $this->redirect($this->generateUrl('_admin_view_campaign', array('campaign_id' => $campaign_id)));
            }
        }


        $this->data['title'] = 'Create New Campaign | ' . $this->getParameter('site_name');
        $this->data['error'] = $error;
        return $this->data;
    }

    /**
     * @Route("/admin/import_customers/{campaign_id}", name="_import_customers",defaults={"campaign_id"=0})
     * @Template("AdminBundle:popup:editCampaign.html.twig")
     */
    public function importCustomersAction(Request $request, $campaign_id)
    {

        print_r($_FILES);
        exit;
        return $this->data;
    }

    /**
     * @Route("/admin/agent_pdf/{agent_id}/{campaign_id}", name="_agent_customer_pdf",defaults={"agent_id"=0, "campaign_id" = 0 })
     *
     * */
    public function renderedAgentPdf(Request $request, $agent_id, $campaign_id)
    {
        $em = $this->getDoctrine()->getManager();
        $connection = $em->getConnection();
        $helperHandler = $this->get('helper_handler');
        $pdf = $this->get("white_october.tcpdf")->create();
        $res = $helperHandler->getAgenCustomersCalldet($agent_id, $campaign_id);
        $data['res'] = $res;
        $data['agentName'] = isset($_GET['agent_name']) ? $_GET['agent_name'] : "";
        $html = $this->renderView('AdminBundle:Agents:agentPdf.html.twig', $data);
        $pdf->setPrintFooter(false);
        $pdf->setPrintHeader(false);
        // set margins
        $pdf->SetMargins(0, 0, 0, true);
        $pdf->SetFont('aealarabiya', '', 18);
        // set auto page breaks false
        $pdf->SetAutoPageBreak(true, 0);
        // add a page
        $pdf->AddPage('L', 'A5');
        // $html = '<span style="color:white;text-align:center;font-weight:bold;font-size:80pt;">PAGE 3</span>';
        $pdf->writeHTML($html, true, false, true, false, '');

// ---------------------------------------------------------
//Close and output PDF document
        $pdf->Output(md5(uniqid()) . '.pdf', 'I');

        /*
          header('Content-Description: File Transfer');
          header('Content-Type: application/octet-stream');
          header('Content-Disposition: attachment; filename="'.basename($file).'"');
          header('Expires: 0');
          header('Cache-Control: must-revalidate');
          header('Pragma: public');
          readfile($pdf);
          exit; */


        // $pdf->writeHTML($html, true, false, false, false, '');
        //$pdf->Output('In-Bound-Calls-' . '-' . date('d-m-Y') . '.pdf', 'D');
    }

    /**
     * @Route("/admin/campaign_pdf/{campaign_id}/{campaign_name}", name="_camapaign_customer_pdf",defaults={"campaign_id"=0,"campaign_name"=""})
     *
     * */
    public function renderedCampaignPdf(Request $request, $campaign_id, $campaign_name)
    {
        $em = $this->getDoctrine()->getManager();
        $connection = $em->getConnection();
        $helperHandler = $this->get('helper_handler');
        $pdf = $this->get("white_october.tcpdf")->create();
        $res = $helperHandler->getCampaignCustomers($campaign_id);
        $data['res'] = $res;
        $data['campaignName'] = $campaign_name;
        $html = $this->renderView('AdminBundle:Campaigns:campaignPdf.html.twig', $data);
        $pdf->setPrintFooter(false);
        $pdf->setPrintHeader(false);
        // set margins
        $pdf->SetMargins(0, 0, 0, true);
        $pdf->SetFont('aealarabiya', '', 18);
        // set auto page breaks false
        $pdf->SetAutoPageBreak(true, 0);

        $pdf->writeHTML($html, true, false, true, false, '');
        $pdf->Output(md5(uniqid()) . '.pdf', 'I');
    }

}

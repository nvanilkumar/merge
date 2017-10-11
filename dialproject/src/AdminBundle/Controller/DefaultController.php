<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
// these import the "@Route" and "@Template" annotations    
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{

    public $title = "CesDialer Admin";
//    public static $template = "AdminBundle:Templates:admin.html.twig";
    public static $template = "AdminBundle:Templates:admin.htmlv1.twig";
    public $data = array();

    public function __construct()
    {
        $this->data['extend_view'] = self::$template;
        $this->data['section'] = '';
        $this->data['section_item'] = '';
    }

    /**
     * @Route("/admin", name="_admin_dashboard")
     * @Template("AdminBundle:Default:index.html.twig")
     */
    public function dashboardAction()
    {

        $this->data['title'] = 'Admin Panel | ' . $this->getParameter('site_name');
        $error = "Please login";
        $em = $this->getDoctrine()->getEntityManager();
        $connection = $em->getConnection();
        $statement = $connection->prepare("SELECT c.campaign_id,c.campaign_name, c.pdf_report ,ct.campaign_type  FROM campaign c JOIN campaign_type ct ON ct.ct_id=c.ct_id WHERE is_complete=1 and c.is_deleted = 0 ");
        $statement->execute();
        $completedCampaigns = $statement->fetchAll();
        $this->data['error'] = $error;
        // Listing running campaings
        $connection = $em->getConnection();
        $statement = $connection->prepare("SELECT * from campaign where is_running=1 and is_deleted=0");
        $statement->execute();
        $runningCampaigns = $statement->fetchAll();

        $this->data['completedCampaigns'] = $completedCampaigns;
        $this->data['runningCampaigns'] = $runningCampaigns;
        return $this->data;
    }

    /**
     * @Route("/admin/dailermanagement", name="_admin_dialer_management")
     * @Template("AdminBundle:Default:dialer_management.html.twig")
     */
    public function dialerManagementAction(Request $request)
    {
        $redisHandler = $this->get('redis_handler');

        $this->data['title'] = 'Admin Panel | ' . $this->getParameter('site_name');
        $error = array();

        if ($request->getMethod() == 'POST') {

            $noOfAgents = trim($request->request->get('no_of_agents'));
            $extensionNumber = trim($request->request->get('extension'));

            if (is_numeric($noOfAgents)) {

                $redisHandler->setRadisKeyValue("no_of_agents", $noOfAgents);
            }
            if (is_numeric($extensionNumber)) {
                $redisHandler->setRadisKeyValue("extension", $extensionNumber);
            }
        }
        $this->data['no_of_agents'] = $redisHandler->getRadisKeyValue("no_of_agents");
        $this->data['extension'] = $redisHandler->getRadisKeyValue("extension");
        $this->data['error'] = $error;
        $this->data['mode'] = 'add';
        return $this->data;
    }

    /**
     * @Route("/admin/livecampaigns", name="_admin_live_campaign")
     * 
     * @Template("AgentBundle:Default:livecampaign.html.twig")
     */
    public function livecampaignsAction(Request $request)
    {

        $helperHandler = $this->get('helper_handler');
        $campaignId = $request->get('campaignId');
        if ($helperHandler->checkRunningCampaign($campaignId) == 0) {
            $this->get('session')->getFlashBag()->add('success', 'Entered campaign is not running.');
            return $this->redirect($this->generateUrl('_admin_dashboard'));
        }
        $this->data['title'] = 'Admin Panel | ' . $this->getParameter('site_name');
        $error = array();
        $campaign = $helperHandler->getCampaign($campaignId);
        $this->data['error'] = $error;
        $this->data['mode'] = 'add';
        $this->data['campaigns_count'] = '2';
        $this->data['campaignId'] = $campaignId;
        $this->data['campaignPause'] = $campaign[0]['is_paused'];
        $this->data['campaignName'] = $campaign[0]['campaign_name'];
        $this->data['campaigns'] = '';
        return $this->data;
    }

}

<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require_once(APPPATH . 'handlers/user_handler.php');
/**
 * Simple config file based ACL
 *
 * 
 */
require_once(APPPATH . 'handlers/dashboard_handler.php');
require_once(APPPATH . 'handlers/collaborator_handler.php');
require_once(APPPATH . 'handlers/promoter_handler.php');

class Acl {

    private $CI;
    private $acl;
    var $dashboardHandler;
    var $collaboratordHandler;
    var $promoterHandler;

    function __construct() {
        $this->CI = & get_instance();
        $this->CI->load->config('acl', TRUE);
        //$this->acl = $this->CI->config->item('permission', 'acl');
        $this->index();
    }

    /**
     * function that checks that the user has the required permissions
     *
     * @param string $controller
     * @param array $required_permissions
     * @param integer $author_uid
     * @return boolean
     */
    public function index() {
        $displayPageType = $this->CI->uri->segment(1);
        $displayMethodType = $this->CI->uri->segment(2);
        $displayActivityType = $this->CI->uri->segment(3);
        $uid = $this->CI->customsession->getUserId();

        if ($uid > 0) {
            
        }

        $return_url = "?redirect_url=" . uri_string();
        $loginCheck = array("dashboard", "profile", "promoter","currentTicket","pastTicket");
        if (in_array($displayPageType, $loginCheck) && !$uid) {
            redirect(commonHelperGetPageUrl('user-login', $return_url), 'refresh');
        }
        if (($displayPageType == "login" || $displayPageType == "signup") && $uid > 0) {
            $fbcode = $this->CI->input->get('code');
            $qString = $this->CI->input->server('QUERY_STRING');
            $qStringData=  explode('redirect_url=', $qString);
            if(isset($qStringData[1])){
                redirect($qStringData[1]);
            }
        	if($fbcode == null ){
                redirect(commonHelperGetPageUrl('home', ''), 'refresh');
            }
        }
        if ($displayPageType == "dashboard" || (($displayPageType == "profile" && ($displayMethodType == "company" || $displayMethodType == "alert" || $displayMethodType == "bank")) || $displayPageType == "promoter") && $uid > 0) {
            $this->promoterHandler = new Promoter_handler();
            //check to set isPromoter Accesss
            $promoterResponse = $this->promoterHandler->checkPromoterAccess();
            if ($promoterResponse['status'] == TRUE)
                $this->CI->customsession->setData('isPromoter', $promoterResponse['response']['isPromoter']);
            else
                $this->CI->customsession->setData('isPromoter', 0);

            //check to set isCollaborator Accesss
            $this->collaboratorHandler = new Collaborator_handler();
            $collaboratorResponse = $this->collaboratorHandler->checkCollaboratorAccess();
            if ($collaboratorResponse['status'] == TRUE)
                $this->CI->customsession->setData('isCollaborator', $collaboratorResponse['response']['isCollaborator']);
            else
                $this->CI->customsession->setData('isCollaborator', 0);

            $this->accessCheck();
        }
        return TRUE;
    }

    public function accessCheck() {

        $this->dashboardHandler = new Dashboard_handler();
        $this->collaboratordHandler = new Collaborator_handler();
        $displayPageType = $this->CI->uri->segment(1);
        $displayMethodType = $this->CI->uri->segment(2);
        $displayActivityType = $this->CI->uri->segment(3);
        $excludeMethodArray = array("", "pastEventList");
        $excludeActivityArray = array("create", "pastlist", "currentlist");
        $isPromoter = $this->CI->customsession->getData('isPromoter');
        if ($displayPageType == 'promoter' && $isPromoter == 1 && ($displayMethodType == "currentlist" || $displayMethodType == "offlinebooking" || $displayMethodType == "pastlist")) {
            return true;
        }

        if ($displayPageType == "dashboard" && (in_array($displayMethodType, $excludeMethodArray) || in_array($displayActivityType, $excludeActivityArray))) {
            return true;
        }
        if ($displayPageType == "profile" && ($displayMethodType == "company" || $displayMethodType == "alert" || $displayMethodType == "bank")) {
            $isOrganizer = $this->CI->customsession->getData('isOrganizer');
            if ($isOrganizer == 1)
                return true;
            else
                redirect(commonHelperGetPageUrl("user-noaccess", 'NoAccess'), 'refresh');
        }
        $condition1 = ($displayPageType == "dashboard" && ($displayMethodType == "home" || $displayMethodType == "saleseffort" || $displayMethodType == "reports"));
        $condition2 = ($displayPageType == "promoter" && $displayMethodType == "reports" && $isPromoter == 1);
        if ($condition1 || $condition2) {
            $_GET['eventId'] = $this->CI->uri->segment(3);
        } else if (($displayPageType == "promoter" && $displayMethodType == "eventDetailsList" && $isPromoter == 1) || $displayPageType == "dashboard") {
            $_GET['eventId'] = $this->CI->uri->segment(4);
        } else {
            $_GET['eventId'] = $this->CI->uri->segment(4);
        }

        $inputData = array();
        $inputData['eventId'] = $_GET['eventId'];
        $isCollaboratorAccess = 0;
        $this->CI->form_validation->pass_array($inputData);
        $this->CI->form_validation->set_rules('eventId', 'eventId', 'is_natural_no_zero');
        if ($this->CI->form_validation->run() == FALSE) {
            redirect(site_url());
        }

        $userId = getUserId();
        //whether the session user is collaborator or not
        if ($this->CI->customsession->getData('isCollaborator') == 1) {
            $collabData = $this->collaboratordHandler->checkCollaboratorEventAccess($inputData);
            if ($collabData['response']['isEventCollaborator'] == 1) {
                $isCollaboratorAccess = 1;
                // get access levels
                $inputCollaboratorEvents['userids'] = array($userId);
                $inputCollaboratorEvents['eventId'] = $inputData['eventId'];
                $inputCollaboratorEvents['getacesslevel'] = TRUE;
                $collaboratorResponse = $this->collaboratordHandler->getEventByUserIds($inputCollaboratorEvents);
                if ($collaboratorResponse['status']) {
                    $collaboratorAccesLevel = $collaboratorResponse['response']['collaboratorDetail']['module'];
                } else {
                    redirect(commonHelperGetPageUrl("user-noaccess", 'NoAccess'), 'refresh');
                }
            }
        }
        $userType = $this->CI->customsession->getData('userType');
        $eventData = $this->dashboardHandler->eventAccessVerify($inputData);
        if ($eventData['status'] == TRUE && $eventData['response']['total'] > 0) {
            $eventDataArray = $eventData['response']['eventData'];
            $ownerId = $eventDataArray['ownerId'];
            $isCurrentEvent = (strtotime($eventDataArray['endDateTime']) > strtotime(date('Y-m-d H:i:s'))) ? TRUE : FALSE;
            if ($userId == $ownerId || ($userType == 'superadmin' || $userType == 'admin') || $isCollaboratorAccess == 1 || (($displayPageType == "promoter") && $isPromoter == 1)) {
                if ($isPromoter == 1) {
                    $this->promoterHandler = new Promoter_handler();
                    $promoterAccess = $this->promoterHandler->checkPromoterEventAccess($inputData);
                    $isEventPromoter = $promoterAccess['response']['isEventPromoter'];
                    if (($displayPageType == "promoter" && $displayMethodType == "eventDetailsList") || ($displayPageType == "promoter" && $displayMethodType == "reports")) {
                        if ($isEventPromoter == 1) {
                            $this->CI->config->set_item('eventData', array("event" . $eventDataArray['eventId'] => $eventDataArray));
                            return true;
                        } else {
                            redirect(commonHelperGetPageUrl("user-noaccess", 'NoAccess'), 'refresh');
                        }
                    }
                }
                if ($isCollaboratorAccess == 1) {
                    $this->CI->config->set_item('collaboratorAccess', array("collaborator" . $userId => $collaboratorAccesLevel));
                    $this->CI->config->set_item('collaboratorEventAccess', array("collaboratorEvent" . $userId . $eventDataArray['eventId'] => $isCollaboratorAccess));
                    if (strpos($collaboratorAccesLevel, 'manage') !== FALSE) {
                        $this->CI->config->set_item('eventData', array("event" . $eventDataArray['eventId'] => $eventDataArray));
                        //disabling the collaborator tab
                        if ($this->CI->uri->segment(2) == 'collaborator') {
                            redirect(commonHelperGetPageUrl("user-noaccess", 'NoAccess'), 'refresh');
                        }
                        return true;
                    }
                    // only promote permissions
                    else if (strpos($collaboratorAccesLevel, 'promote') !== FALSE && ($displayMethodType == "promote")) {
                        $this->CI->config->set_item('eventData', array("event" . $eventDataArray['eventId'] => $eventDataArray));
                        return true;
                    } else if (strpos($collaboratorAccesLevel, 'report') !== FALSE && ($displayMethodType == "reports" || $displayMethodType == "saleseffort")) {

                        $this->CI->config->set_item('eventData', array("event" . $eventDataArray['eventId'] => $eventDataArray));
                        return true;
                    } else {
                        redirect(commonHelperGetPageUrl("user-noaccess", 'NoAccess'), 'refresh');
                    }
                }
                if ($displayPageType == "dashboard" && $displayActivityType == 'edit' && ($userId == $ownerId || $isCollaboratorAccess == 1)) {
                    if ($isCurrentEvent) {
                        $this->CI->config->set_item('eventData', array("event" . $eventDataArray['eventId'] => $eventDataArray));
                        return true;
                    } else if ($userType == 'superadmin' || $userType == 'admin') {
                        $this->CI->config->set_item('eventData', array("event" . $eventDataArray['eventId'] => $eventDataArray));
                        return true;
                    } else {
                        redirect(commonHelperGetPageUrl("user-noaccess", 'NoAccess'), 'refresh');
                    }
                }
                $this->CI->config->set_item('eventData', array("event" . $eventDataArray['eventId'] => $eventDataArray));
                return true;
            } else {
                redirect(commonHelperGetPageUrl("user-noaccess", 'NoAccess'), 'refresh');
            }
        } else if ($eventData['status'] == TRUE && $eventData['response']['total'] == 0) {
            redirect(site_url());
        }
    }

    public function has_permission($controller, $required_permissions = array('delete all'), $author_uid = NULL) {
        
    }

    /**
     * Function to see if a user is logged in
     */
    public function is_logged_in() {
        $uid = $this->CI->customsession->getUserId();
        if ($uid) {
            return TRUE;
        }
    }

}

/* End of application/libraries/acl.php */
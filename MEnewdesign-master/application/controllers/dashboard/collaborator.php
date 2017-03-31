<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/**
 * Default landing page controller
 *
 * @package     CodeIgniter
 * @author      Qison  Dev Team
 * @copyright   Copyright (c) 2015, MeraEvents.
 * @Version     Version 1.0
 * @Since       Class available since Release Version 1.0 
 * @Created     31-07-2015
 * @Last Modified On  31-07-2015
 * @Last Modified By  Qison  Dev Team
 */
require_once(APPPATH . 'handlers/event_handler.php');
require_once(APPPATH . 'handlers/collaborator_handler.php');

class Collaborator extends CI_Controller {

    var $eventHandler;
    var $ticketHandler;
    var $userHandler;
    var $promoterHandler;

    public function __construct() {
        parent::__construct();
        $this->eventHandler = new Event_handler();
        $_GET['eventId'] = $this->uri->segment(4);
    }

    public function addCollaborator($eventId, $collaboratorId = 0) {
        $data['eventId'] = $eventId;
        $data['pageName'] = 'Add Collaborator';
        $data['pageTitle'] = 'MeraEvents | Add Event Collaborator';
        $data['content'] = 'add_collaborator_view';
        $data['hideLeftMenu'] = 0;
        $input['eventId'] = $eventId;
        $data['eventTitle'] = commonHelperGetEventName($eventId);
        $data['collaboratorId'] = $collaboratorId;
        if ($collaboratorId > 0) {
            $collaboratorHandler = new Collaborator_handler();
            $inputCollaborator['eventid'] = $eventId;
            $inputCollaborator['collaboratorid'] = $collaboratorId;
            $collaboratorList = $collaboratorHandler->getList($inputCollaborator);
            if ($collaboratorList['status'] && $collaboratorList['response']['total'] > 0) {
                $data['collaboratorList'] = $collaboratorList['response']['collaboratorList'][$collaboratorId];
            } else {
                $data['errors'][] = $collaboratorList['response']['messages'][0];
            }
        }
        $data['jsArray'] = array($this->config->item('js_public_path'). 'dashboard/collaborator');
        $this->load->view('templates/dashboard_template', $data);
    }

    public function collaboratorList($eventId) {
        $data['eventId'] = $eventId;
        $data['pageName'] = 'Collaborator List';
        $data['pageTitle'] = 'MeraEvents | Event Collaborators';
        $data['content'] = 'collaborator_list_view';
        $data['hideLeftMenu'] = 0;
        $input['eventId'] = $eventId;
        $data['eventTitle'] = commonHelperGetEventName($eventId);
        $collaboratorHandler = new Collaborator_handler();
        $inputCollaborator['eventid'] = $eventId;
        $collaboratorList = $collaboratorHandler->getList($inputCollaborator);
        if ($collaboratorList['status'] && $collaboratorList['response']['total'] > 0) {
            $data['collaboratorList'] = $collaboratorList['response']['collaboratorList'];
        } else {
            $data['errors'][] = $collaboratorList['response']['messages'][0];
        }
        $data['jsArray'] = array($this->config->item('js_public_path') . 'dashboard/collaboratorlisting');
        $this->load->view('templates/dashboard_template', $data);
    }

}

?>
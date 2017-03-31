<?php

/**
 * dashboard related api's
 *
 * @author    Qison  Dev Team
 * @copyright  2015-2005 The PHP Group
 * @version    CVS: $Id:$
 * @since      Discounts available since Sprint 4
 * @deprecated File deprecated in Release 2.0.0
 */
/*
 * Place includes, constant defines and $_GLOBAL settings here.
 */

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require_once(APPPATH . 'libraries/REST_Controller.php');
require_once(APPPATH . 'handlers/dashboard_handler.php');

class Dashboard extends REST_Controller {

    var $dashboardHandler;

    public function __construct() {
        parent::__construct();
        $this->dashboardHandler = new Dashboard_handler();
    }

    function getEvents_post() {
        $inputArray = $this->input->post();
        if (isset($inputArray['eventtype']) && $inputArray['eventtype'] == 'upcoming') {
            $output = $this->dashboardHandler->getUserUpcomingEvent($inputArray);
        } else {
            $output = $this->dashboardHandler->getUserPastEvent($inputArray);
        }
        $responseArray = array('response' => $output['response']);
        $this->response($responseArray, $output['statusCode']);
    }

}

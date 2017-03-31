<?php

/**
 * Maintaing TimeZone related data
 *
 * @author    Qison  Dev Team
 * @copyright  2015-2005 The PHP Group
 * @version    CVS: $Id:$
 * @since      TimeZone available since Sprint 2
 * @deprecated File deprecated in Release 2.0.0
 */
/*
 * Place includes, constant defines and $_GLOBAL settings here.
 */

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require(APPPATH . 'libraries/REST_Controller.php');
require(APPPATH . 'handlers/timezone_handler.php');

class Timezone extends REST_Controller {

    var $timezoneHandler;

    public function __construct() {
        parent::__construct();
        $this->timezoneHandler = new Timezone_handler();
    }

    public function list_get() {
        $inputArray = $this->get();
        $timeZone = $this->timezoneHandler->timeZoneList($inputArray);
        $resultArray = array('response' => $timeZone['response']);
        $this->response($resultArray, $timeZone['statusCode']);
    }
	
     public function details_get() {
        $inputArray = $this->get();
        $timeZoneDetails = $this->timezoneHandler->details($inputArray);
        $resultArray = array('response' => $timeZoneDetails['response']);
        $this->response($resultArray, $timeZoneDetails['statusCode']);
    }

}

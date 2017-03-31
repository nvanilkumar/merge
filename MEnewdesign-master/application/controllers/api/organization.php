<?php

/**
 * Maintaing Banners related data
 *
 * @author     Lenin <lenin.komatipall@qison.com>
 * @copyright  2015-2005 The PHP Group
 * @version    CVS: $Id:$
 * @since      Banner available since Sprint 1
 * @deprecated File deprecated in Release 2.0.0
 */
/*
 * Place includes, constant defines and $_GLOBAL settings here.
 */

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require(APPPATH . 'libraries/REST_Controller.php');
require(APPPATH . 'handlers/organization_handler.php');

class Organization extends REST_Controller {

    var $organizationHandler;

    public function __construct() {
        parent::__construct();
        $this->organizationHandler = new Organization_handler();
    }

    public function list_post() {
        $inputArray = $this->post();
        $organizationList = $this->organizationHandler->organizationUserEvents($inputArray);
        $resultArray = array('response' => $organizationList['response']);
        $this->response($resultArray, $organizationList['statusCode']);
    }
    //api for getting organizer/user details and send mails accordingly
    public function contactOrg_post() {
        $orgId = $this->post();
        $userids = $this->organizationHandler->contactMailOrganizer($orgId);
        $resultArray = array('response' => $userids['response']);
        $this->response($resultArray, $userids['statusCode']);
    }

}

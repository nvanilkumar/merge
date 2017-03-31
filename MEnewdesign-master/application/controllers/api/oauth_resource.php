<?php

/**
 * Maintaing Tags related data
 *
 * @author    Qison  Dev Team
 * @copyright  2015-2005 The PHP Group
 * @version    CVS: $Id:$
 * @since      Tags available since Sprint 2
 * @deprecated File deprecated in Release 2.0.0
 */
/*
 * Place includes, constant defines and $_GLOBAL settings here.
 */

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require(APPPATH . 'libraries/REST_Controller.php');
require_once (APPPATH . 'handlers/dashboard_handler.php');
require_once(APPPATH . 'handlers/reports_handler.php');
require_once(APPPATH . 'handlers/eventsignup_handler.php');

class Oauth_resource extends REST_Controller {

    var $dashboardHandler;
    var $accessTokenDetails;

    public function __construct() {
        parent::__construct();
        parent:: _oauth_validation_check();


        $this->dashboardHandler = new Dashboard_handler();
        //to Retrive the access token related clinet id & user id
        $this->accessTokenDetails = $this->resource->getAccessTokenDetails();
        $this->reportsHandler = new Reports_handler();
    }

    public function getEventList_post() {

//        $inputArray = $this->post();
        $inputArray['ownerId'] = $this->accessTokenDetails['user_id'];
        $eventList = $this->dashboardHandler->getOrganizerEventList($inputArray);

        $resultArray = $eventList['response'];
        $this->response($resultArray, $eventList['statusCode']);
    }

    public function getTickets_post() {
        $inputArray = $this->post();
        $inputArray['userId'] = $this->accessTokenDetails['user_id'];
        $resultArray = $this->dashboardHandler->getOrganizerEventTicketList($inputArray);

        $this->response($resultArray, $resultArray['statusCode']);
    }

    public function getEvent_post() {
        $inputArray = $this->post();
        $inputArray['userId'] = $this->accessTokenDetails['user_id'];
        $resultArray = $this->dashboardHandler->getOrganizerEventDetails($inputArray);
        $this->response($resultArray, $resultArray['statusCode']);
    }

    public function getEventReport_post() {
        $inputArray = $this->post();
        $inputArray['userId'] = $this->accessTokenDetails['user_id'];
        $resultArray = $this->dashboardHandler->getOrganizerEventReport($inputArray);
        $this->response($resultArray, $resultArray['statusCode']);
    }
    public function getEventAttendees_post() {
	ini_set('memory_limit','800M');
    ini_set('max_execution_time','180');
        $eventArray=array();
        $inputArray = $this->post();
        $inputArray['userId'] = $this->accessTokenDetails['user_id'];
       
        if((!isset($inputArray['eventId']) )&& (isset($inputArray['eventSignupId']))){
            $eventArray['eventsignupId']=$inputArray['eventSignupId'];
            $eventSignupHandler=new Eventsignup_handler();
            $eventsignupData=$eventSignupHandler->getEventSignupData($eventArray);

            if(!isset($eventsignupData['response']['eventSignupData'])){
                print_r(json_encode($eventsignupData));exit;
            }
            $inputArray['eventId']=$eventsignupData['response']['eventSignupData'][0]['eventid'];
        }

        $inputArray['REPORTS_TRANSACTION_LIMIT'] =8000;
        $inputArray['orderStatus'] =TRUE;
        if ($inputArray['reportType'] == 'summaryreport') {
            $inputArray['reportType'] = 'summary';
            $eventAttendeedData = $this->reportsHandler->getEventAttendeesSummary($inputArray);
        }
        else {
            $inputArray['reportType'] = 'detail';
            $eventAttendeedData = $this->reportsHandler->getEventAttendeesDetails($inputArray);
         }
         $resultArray = $eventAttendeedData['response'];
         print_r(json_encode($resultArray));exit;
    }

}

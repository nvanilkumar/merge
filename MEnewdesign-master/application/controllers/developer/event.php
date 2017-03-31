<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require_once(APPPATH . 'libraries/REST_Controller.php');
require_once(APPPATH . 'handlers/developer_handler.php');
require_once(APPPATH . 'handlers/event_handler.php');

class Event extends REST_Controller {

    var $developerHandler;

    public function __construct() {
        parent::__construct();
        parent::_oauth_validation_check();
        $this->developerHandler = new Developer_handler();
       
    }

    /*
     * Function to get the event List
     *
     * @access	public
     * @param
     *           limit - limit the number of records
     *           start - start of the records
     *           offset - -1(optional) If set to -1, all the events will be listed
     * @return	json data that contains all the event list
     */
    public function getEventList_post() {
        $inputArray = $this->post();
        $resultArray=array();
        $resultArray = $this->developerHandler->getEventList($inputArray);
        $this->response($resultArray, $resultArray['statusCode']);
    }

    
    
    /*
     * Function to get the Events Details
     *
     * @access	public
     * @return	json data   
     */

    public function getEventDetails_post() {
        $inputArray = $this->post();
        $resultArray=array();
        $resultArray = $this->developerHandler->getEventDetails($inputArray);
        $this->response($resultArray, $resultArray['statusCode']);
    }

   /*
     * Function to get the Events by search keyword
     * @access	public
     * @param
     *           
     * @return	 
     */
    public function eventSearch_post() {
       $inputArray = $this->post();
       $resultArray = $this->developerHandler->eventSearch($inputArray);
       $this->response($resultArray, $resultArray['statusCode']);
    }
    
    public function getTicketCaluculation_post(){
       $inputArray = $this->post();
       $resultArray = $this->developerHandler->getTicketCaluculation($inputArray);
       $this->response($resultArray, $resultArray['statusCode']);
        
    }
    //for book now
    public function bookNow_post() {
        $inputArray = $this->post();
        $resultArray = $this->developerHandler->bookNow($inputArray);
        $this->response($resultArray, $resultArray['statusCode']);
    }
    //for book now
    public function printPass_post() {
        $inputArray = $this->post();
        $resultArray = $this->developerHandler->printPass($inputArray);
        $this->response($resultArray, $resultArray['statusCode']);
    }
    
    public function createEvent_post() {
        $inputData = $this->input->post();
        $eventHanlder = new Event_handler();
        $accessTokenInfo = $this->developerHandler->setAccessTokenInfo();
        $inputData['ownerId'] = getUserId();
        $createEventData = $eventHanlder->apiCreateEventInputDataFormat($inputData);

        if (!$createEventData['status']) {
            $statusCode = $createEventData['statusCode'];
            $resultArray = array('response' => $createEventData['response']);
        } else {
            $createEventInput = $createEventData['response']['formattedData'];
            $createEventInfo = $eventHanlder->apiCreateEvent($createEventInput);
            $resultArray = array('response' => $createEventInfo['response']);


            $statusCode = $createEventInfo['statusCode'];
        }



        $this->response($resultArray, $createEventInfo['statusCode']);
    }
    
    public function createTicket_post(){
        $inputData = $this->input->post();
        $accessTokenInfo = $this->developerHandler->setAccessTokenInfo();
        $inputData['ownerId'] = getUserId();
        $response = $this->developerHandler->createTicket($inputData);
        $this->response($response, $response['statusCode']);
    }
    
    public function publishOrUnpublishEvent_post(){
        $inputData = $this->input->post();
        $accessTokenInfo = $this->developerHandler->setAccessTokenInfo();
        $inputData['ownerId'] = getUserId();
        $response = $this->developerHandler->publishOrUnpublishEvent($inputData);
        $this->response($response, $response['statusCode']);
        
    }

}

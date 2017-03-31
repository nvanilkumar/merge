<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require_once(APPPATH . 'libraries/REST_Controller.php');
require_once(APPPATH . 'handlers/event_handler.php');
require_once(APPPATH . 'handlers/gallery_handler.php');

class Event extends REST_Controller {

    var $eventHandler;

    public function __construct() {
        parent::__construct();

        $this->eventHandler = new Event_handler();
    }

    public function index_get() {
        
    }

    public function list_get() {
        $inputArray = $this->get();

        $eventList = $this->eventHandler->getEventList($inputArray);
        $resultArray = array('response' => $eventList['response']);

        $this->response($resultArray, $eventList['statusCode']);
    }

    /*
     * Function to get the Events list Citywise
     *
     * @access	public
     * @return	json data
     */

    /* public function getCityEvents_get() {
      $inputArray = $this->get();
      $eventList = $this->eventHandler->getEventListCityWise($inputArray);
      $resultArray = array('response' => $eventList['response']);

      $this->response($resultArray, $eventList['statusCode']);
      } */

    /*
     * Function to get the Events Details
     *
     * @access	public
     * @return	json data   
     */

    public function detail_get() {
        $inputArray = $this->get();

        $eventDetails = $this->eventHandler->getEventDetails($inputArray);
        $resultArray = array('response' => $eventDetails['response']);

        $this->response($resultArray, $eventDetails['statusCode']);
    }

    /*
     * Function to get the Ticketwise Calculations
     *
     * @access	public
     * @param
     *           - ticketArray - array() - contains ticketId and Quantity
     *           - eventId - Integer - Id of the Event
     * @return	Html that contains Total Amount with Calculations
     */

    public function getTicketCalculation_post() {
        $inputArray = $this->post();
        $ticketResultArray = $this->eventHandler->getEventTicketCalculation($inputArray);
        $resultArray = array('response' => $ticketResultArray['response']);
        $this->response($resultArray, $ticketResultArray['statusCode']);
    }

    //for book now
    public function bookNow_post() {
        $inputArray = $this->post();

        $ticketResultArray = $this->eventHandler->bookNow($inputArray);
        $resultArray = array('response' => $ticketResultArray['response']);
        $this->response($resultArray, $ticketResultArray['statusCode']);
    }

    public function contactInfo_get() {
        $inputArray = $this->get();
        $eventContactInfo = $this->eventHandler->getEventContactInfo($inputArray);
        $resultArray = array('response' => $eventContactInfo['response']);

        $this->response($resultArray, $eventContactInfo['statusCode']);
    }

    public function checkUrlExists_get() {
        $inputArray = $this->get();
        $eventContactInfo = $this->eventHandler->checkUrlExists($inputArray);
        $resultArray = array('response' => $eventContactInfo['response']);

        $this->response($resultArray, $eventContactInfo['statusCode']);
    }

    /**
     * To create the event
     */
    public function create_post() {
        $inputData = $this->input->post();
        if(empty(getUserId())){
           $statusCode=STATUS_BAD_REQUEST;
           $output=array();
           $output['response']['messages'][] = ERROR_NO_SESSION;
            $resultArray = array('response' => $output);
           
        }else{ 
            $inputData['ownerId'] = getUserId();
            $statusCode=200;
            $createEventData = $this->eventHandler->createEventInputDataFormat($inputData);
            if(!$createEventData['status']){
                $statusCode=$createEventData['statusCode'];
                $resultArray = array('response' => $createEventData['response']);
            }else{
                    $createEventInput=$createEventData['response']['formattedData'];
                    $createEventInfo = $this->eventHandler->createEvent($createEventInput);
                $resultArray = array('response' => $createEventInfo['response']);
                $eventLink = commonHelperGetPageUrl('preview-event', $resultArray['response']['url']);
                if (isset($createEventInfo['status']) && $createEventInfo['status']) {
                    if ($inputData['submitValue'] == "golive") {
                        $this->customsession->setData('message', 'Your event has been published successfully.');
                        $this->customsession->setData('eventLink', $eventLink);
                        $sendMail = $this->eventHandler->sendEventPublichedEmailToOrg($createEventInfo);            
                    }
                    if ($inputData['submitValue'] == "save") {
                        $this->customsession->setData('message', 'Your event has been created successfully');
                    }
                }
                    $statusCode=$createEventInfo['statusCode'];
            }
        }
        $this->response($resultArray, $statusCode);
    }

    public function gallery_get() {
        $inputArray = $this->get();
        $this->galleryHandler = new Gallery_handler();
        $galleryList = $this->galleryHandler->getEventGalleryList($inputArray);
        $resultArray = array('response' => $galleryList['response']);

        $this->response($resultArray, $galleryList['statusCode']);
    }

    /*
     * To Update the event
     */

    public function edit_post() {
        $inputData = $this->input->post();
        if(empty(getUserId())){
           $statusCode=STATUS_BAD_REQUEST;
           $output=array();
           $output['response']['messages'][] = ERROR_NO_SESSION;
           $resultArray = array('response' => $output);
        }else{
            require_once(APPPATH . 'handlers/dashboard_handler.php');
            $dashboardHandler=new Dashboard_handler();
            $input['eventId'] = $inputData['eventId'];
            $eventResponse=$dashboardHandler->eventAccessVerify($input);
            if($eventResponse['status'] && $eventResponse['response']['total']>0){
                $isOwner=($eventResponse['response']['eventData']['ownerId']==getUserId())?true:false;
                $isCollaboratorForEvent=true;
                if(!$isOwner){
                    require_once(APPPATH . 'handlers/collaborator_handler.php');
                    $collaborator_handler=new Collaborator_handler();
                    $inputCollaborator['eventId']=$inputData['eventId'];
                    $inputCollaborator['getacesslevel']=true;
                    $inputCollaborator['userids']= array(getUserId());
                    $collaboratorResponse=$collaborator_handler->getEventByUserIds($inputCollaborator);
                    //print_r($collaboratorResponse);exit;
                    if($collaboratorResponse['status']){
                        if(($collaboratorResponse['response']['total']>0 && strpos($collaboratorResponse['response']['collaboratorDetail']['module'],'manage')===FALSE) || $collaboratorResponse['response']['total']==0){
                            $isCollaboratorForEvent=false;
                        }
                    }else{
                        $isCollaboratorForEvent=false;
                    }
                }
                if(!$isOwner && !$isCollaboratorForEvent){
                    $statusCode=STATUS_BAD_REQUEST;
                    $output=array();
                    $output['messages'][] = ERROR_NOT_OWNER_FOR_EVENT;
                    $resultArray = array('response' => $output);
                }else{
                    $statusCode=200;
            //         print_r($inputData);
                    $updateEventData = $this->eventHandler->updateEventInputDataFormat($inputData);
                    //print_r($updateEventData);exit;
                    if(!$updateEventData['status']){
                        $statusCode=$updateEventData['statusCode'];
                        $resultArray = array('response' => $updateEventData['response']);
                    }else{
                        $updateEventInputData=$updateEventData['response']['formattedData'];
                        $updateEventInfo = $this->eventHandler->eventUpdate($updateEventInputData);
                        $resultArray = array('response' => $updateEventInfo['response']);
                        $eventLink = commonHelperGetPageUrl('preview-event', $resultArray['response']['url']);
                        if (isset($statusCode['status']) && $updateEventInfo['status']) {
                            if ($inputData['submitValue'] == "golive") {
                                $this->customsession->setData('message', 'Your event has been published successfully.');
                                $this->customsession->setData('eventLink', $eventLink);                
                                $sendMail = $this->eventHandler->sendEventPublichedEmailToOrg($updateEventInfo);            
                            }
                            if ($inputData['submitValue'] == "save") {
                                $this->customsession->setData('message', 'Your event has been updated successfully');
                            }
                        }
                        $statusCode=$updateEventInfo['statusCode'];
                    }
                }
            }else{
                $statusCode=STATUS_BAD_REQUEST;
                $resultArray = array('response' => $eventResponse['response']);
            }
        }
        $this->response($resultArray, $statusCode);
    }

    public function eventCount_get() {
        $inputArray = $this->get();
        $inputArray['ticketSoldout'] = 0;
        $inputArray['status'] = 1;
        $eventCountList = $this->eventHandler->getEventsCountByRegTypes($inputArray);
        $resultArray = array('response' => $eventCountList['response']);
        $statusCode = $eventCountList['statusCode'];
        $this->response($resultArray, $statusCode);
    }

    public function extraCharge_get() {
        $inputArray = $this->get();
        $chargesList = $this->eventHandler->extraCharge($inputArray);
        $resultArray = array('response' => $chargesList['response']);
        $this->response($resultArray, $chargesList['statusCode']);
    }

    public function copyEvent_post() {
        $inputArray = $this->input->post();
        $eventCopyResponse = $this->eventHandler->copyEvent($inputArray);
        $resultArray = array('response' => $eventCopyResponse['response']);
        $this->response($resultArray, $eventCopyResponse['statusCode']);
    }

    public function mailInvitations_post() {
        $inputArray = $this->input->post();
        $mailInvitations = $this->eventHandler->mailInvitation($inputArray);
        $resultArray = array('response' => $mailInvitations['response']);
        $statusCode = $mailInvitations['statusCode'];
        $this->response($resultArray, $statusCode);
    }

    public function changeStatus_post() {
        $eventId = $this->input->post('eventId');
        $output = $this->eventHandler->changeEventStatus($eventId);
        $responseArray = array('response' => $output['response']);
        $this->response($responseArray, $output['statusCode']);
    }

    /*
     * Function to get the Event Payment Gateways
     *
     * @access	public
     * @param
     *           - eventId - Integer - Id of the Event (required)
     *           - paymentGatewayId - Integer (optional)
     * @return	Html that contains Total Amount with Calculations
     */

    public function eventPaymentGateways_get() {
        $inputArray = $this->get();
        $inputArray['gatewayStatus'] = true;
        $ticketResultArray = $this->eventHandler->getEventPaymentGateways($inputArray);
        $resultArray = array('response' => $ticketResultArray['response']);
        $this->response($resultArray, $ticketResultArray['statusCode']);
    }

    //To cancel the event in solr
    public function eventCancel_post() {
        $inputArray = $this->post();
        $eventResultArray = $this->eventHandler->eventCancel($inputArray);
        $resultArray = array('response' => $eventResultArray['response']);
        $this->response($resultArray, $eventResultArray['statusCode']);
    }

    //To change  the event ticket  in solr
    public function eventTicketSoldout_post() {
        $inputArray = $this->post();
        $eventResultArray = $this->eventHandler->eventTicketSoldout($inputArray);
        $resultArray = array('response' => $eventResultArray['response']);
        $this->response($resultArray, $eventResultArray['statusCode']);
    }

    public function solrEventStatus_post() {
        $inputArray = $this->post();
        $eventResultArray = $this->eventHandler->solrEventStatus($inputArray);
        $resultArray = array('response' => $eventResultArray['response']);
        $this->response($resultArray, $eventResultArray['statusCode']);
    }

    public function checkDiscountCodeAvailable_get() {
        $inputArray = $this->get();
        $eventResultArray = $this->eventHandler->checkCodesAvailable($inputArray);
        $resultArray = array('response' => $eventResultArray['response']);
        $this->response($resultArray, $eventResultArray['statusCode']);
    }

    /*
     * Function to get the event list based on the places
     *
     * @access	public
     * @param
     * @return	json event list
     */
    public function eventListByPlace_get() {

        $inputArray = $this->get();
        $eventResultArray = $this->eventHandler->geteventListByPlaces($inputArray);
        $resultArray = array('response' => $eventResultArray['response']);
        $this->response($resultArray, $eventResultArray['statusCode']);
    }
    
    //To change the event popularity value
    public function solrEventPopularityStatus_post() {
        $inputArray = $this->post();
        $eventResultArray = $this->eventHandler->solrEventStatus($inputArray);
        $resultArray = array('response' => $eventResultArray['response']);
        $this->response($resultArray, $eventResultArray['statusCode']);
    }
	
	
	
	public function sitemapevents_post() {
        $inputArray = $this->post();
        $eventList = $this->eventHandler->getSitemapEventList($inputArray);
        $resultArray = array('response' => $eventList['response']);

        $this->response($resultArray, $eventList['statusCode']);
    }
    public function solrAPIStatus_post() {
        $inputArray = $this->post();
        $eventResultArray = $this->eventHandler->solrAPIStatus($inputArray);
        $resultArray = array('response' => $eventResultArray['response']);
        $this->response($resultArray, $eventResultArray['statusCode']);
    }
    
    
}

<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require_once(APPPATH . 'libraries/REST_Controller.php');
require_once(APPPATH . 'handlers/event_handler.php');
require_once(APPPATH . 'handlers/gallery_handler.php');
require_once(APPPATH . 'handlers/currency_handler.php');
require_once(APPPATH . 'handlers/timezone_handler.php');
require_once(APPPATH . 'handlers/developer_handler.php');

class Event extends REST_Controller {

    var $eventHandler;

    public function __construct() {
        parent::__construct();

        $this->eventHandler = new Event_handler();
        $this->emailHandler = new Email_handler();
    }
    
    /*
     * Function to get the Events Details
     *
     * @access	public
     * @return	json data   
     */

    public function index_get() {
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

    public function ticketCalculation_post() {
        $inputArray = $this->post();
        $ticketResultArray = $this->eventHandler->getEventTicketCalculation_structured($inputArray);
        $resultArray = array('response' => $ticketResultArray['response']);
        $this->response($resultArray, $ticketResultArray['statusCode']);
    }

    public function gallery_get() {
        $inputArray = $this->post();
        
        $this->galleryHandler = new Gallery_handler();
        $galleryList = $this->galleryHandler->getEventGalleryList($inputArray);
        $resultArray = array('response' => $galleryList['response']);

        $this->response($resultArray, $galleryList['statusCode']);
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

    public function gateways_get() {
        
        $inputArray = $this->get();
        $inputArray['gatewayStatus'] = true;
        $ticketResultArray = $this->eventHandler->getEventPaymentGateways($inputArray);
        $resultArray = array('response' => $ticketResultArray['response']);
        $this->response($resultArray, $ticketResultArray['statusCode']);
    }

    /*
     * Function to get the event list based on the places
     *
     * @access	public
     * @param
     * @return	json event list
     */
    public function eventListByPlace_post() {

        $inputArray = $this->post();
        $eventResultArray = $this->eventHandler->geteventListByPlaces($inputArray);
        $resultArray = array('response' => $eventResultArray['response']);
        $this->response($resultArray, $eventResultArray['statusCode']);
    }

    public function checkDiscountCodeAvailable_post() {
        $inputArray = $this->post();
        $eventResultArray = $this->eventHandler->checkCodesAvailable($inputArray);
        $resultArray = array('response' => $eventResultArray['response']);
        $this->response($resultArray, $eventResultArray['statusCode']);
    }
    
    public function currencyList_post() {
        $inputArray = $this->post();
        $this->currencyHandler = new Currency_handler();

        $currencyList = $this->currencyHandler->getCurrencyList($inputArray);
        $resultArray = array('response' => $currencyList['response']);
        $this->response($resultArray, $currencyList['statusCode']);
    }
    
    public function timezoneList_post() {
        $inputArray = $this->post();
        $this->timezoneHandler = new Timezone_handler();

        $timezoneList = $this->timezoneHandler->timeZoneList($inputArray);
        $resultArray = array('response' => $timezoneList['response']);
        $this->response($resultArray, $timezoneList['statusCode']);
    }
    
    public function index_post() {
        
        $inputData = $this->input->post();
        $inputData['ownerId'] = getUserId();
        $createEventData = $this->eventHandler->apiCreateEventInputDataFormat($inputData);
        
        if (!$createEventData['status']) {
            $statusCode = $createEventData['statusCode'];
            $resultArray = array('response' => $createEventData['response']);
        } else {
            $createEventInput = $createEventData['response']['formattedData'];
            $createEventInfo = $this->eventHandler->apiCreateEvent($createEventInput);
            $resultArray = array('response' => $createEventInfo['response']);
            $statusCode = $createEventInfo['statusCode'];
        }
        $this->response($resultArray, $createEventInfo['statusCode']);
    }
    
    public function ticket_post(){
        
        $this->developerHandler = new Developer_handler();
        $inputData = $this->input->post();
        $inputData['ownerId'] = getUserId();
        $response = $this->developerHandler->createTicket($inputData);
        $this->response($response, $response['statusCode']);
    }
}

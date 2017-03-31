<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require_once(APPPATH . 'libraries/REST_Controller.php');
require_once(APPPATH . 'handlers/event_handler.php');
require_once(APPPATH . 'handlers/gallery_handler.php');
require_once(APPPATH . 'handlers/currency_handler.php');
require_once(APPPATH . 'handlers/timezone_handler.php');

class Event extends REST_Controller {

    var $eventHandler;

    public function __construct() {
        parent::__construct();

        $this->eventHandler = new Event_handler();
        $this->emailHandler = new Email_handler();
    }

    /*
     * Function to get the event details
     *
     * @access	public
     * @param
     *           limit - limit the number of records
     *           start - start of the records
     *           offset - -1(optional) If set to -1, all the events will be listed
     * @return	json data that contains all the event list
     */
    public function list_post() {
        $inputArray = $this->post();

        $eventList = $this->eventHandler->getEventList_mobile($inputArray);
        $resultArray = array('response' => $eventList['response']);
        $this->response($resultArray, $eventList['statusCode']);
    }

    public function eventCount_post() {
        $inputArray = $this->post();
        $inputArray['ticketSoldout'] = 0;
        $inputArray['status'] = 1;
        $eventCountList = $this->eventHandler->getEventsCountByRegTypes($inputArray);
        $resultArray = array('response' => $eventCountList['response']);
        $statusCode = $eventCountList['statusCode'];
        $this->response($resultArray, $statusCode);
    }
    
    /*
     * Function to get the Events Details
     *
     * @access	public
     * @return	json data   
     */

    public function detail_post() {
        $inputArray = $this->post();

        $eventDetails = $this->eventHandler->getEventDetails_mobile($inputArray);
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

    public function gallery_post() {
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

    public function eventPaymentGateways_post() {
        $inputArray = $this->post();
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

        $currencyList = $this->currencyHandler->getCurrencyList_mobile($inputArray);
        $resultArray = array('response' => $currencyList['response']);
        $this->response($resultArray, $currencyList['statusCode']);
    }
    
    public function timezoneList_post() {
        $inputArray = $this->post();
        $this->timezoneHandler = new Timezone_handler();

        $timezoneList = $this->timezoneHandler->timeZoneList_mobile($inputArray);
        $resultArray = array('response' => $timezoneList['response']);
        $this->response($resultArray, $timezoneList['statusCode']);
    }
}

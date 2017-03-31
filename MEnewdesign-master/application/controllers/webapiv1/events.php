<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require_once(APPPATH . 'libraries/REST_Controller.php');
require_once(APPPATH . 'handlers/event_handler.php');
require_once(APPPATH . 'handlers/gallery_handler.php');
require_once(APPPATH . 'handlers/currency_handler.php');
require_once(APPPATH . 'handlers/timezone_handler.php');
require_once(APPPATH . 'handlers/developer_handler.php');

class Events extends REST_Controller {

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
    public function index_get() {
        $inputArray = $this->get();

        $eventList = $this->eventHandler->getEventList($inputArray);
        $resultArray = array('response' => $eventList['response']);
        $this->response($resultArray, $eventList['statusCode']);
    }
}

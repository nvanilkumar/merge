<?php

/**
 * Maintaing EventSignup Ticket Details related data
 * @package		CodeIgniter
 * @author		Qison  Dev Team
 * @param		eventsignupid - required
 * 
 */
/*
 * Place includes, constant defines and $_GLOBAL settings here.
 */

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require(APPPATH . 'libraries/REST_Controller.php');
require(APPPATH . 'handlers/eventsignupticketdetail_handler.php');

class Usersignup extends REST_Controller {

    var $galleryHandler;

    public function __construct() {
        parent::__construct();
           $this->eventsignupticketdetailHandler = new Eventsignup_Ticketdetail_handler();
    }

    public function list_get() {
        $inputArray = $this->get();
        $EventsignupTicketList = $this->eventsignupticketdetailHandler->getEventGalleryList($inputArray);
        $resultArray = array('response' => $EventsignupTicketList['response']);
        $this->response($resultArray, $EventsignupTicketList['statusCode']);
    }

}

<?php

/**
 * Maintaing Ticket related data
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
require_once(APPPATH . 'libraries/REST_Controller.php');
require_once(APPPATH . 'handlers/ticket_handler.php');
require_once(APPPATH . 'handlers/tickettax_handler.php');
require_once(APPPATH . 'handlers/event_handler.php');

class Ticket extends REST_Controller {

    var $ticketHandler;
    var $eventHandler;
    var $ticketTaxHandler;

    public function __construct() {
        parent::__construct();
        $this->ticketHandler = new Ticket_handler();
        $this->ticketTaxHandler = new Tickettax_handler();
        $this->eventHandler = new Event_handler();
    }

    /*
     * Function to get the Ticket list for the event
     *
     * @access	public
     * @param	taking post values that contains
     * 			eventId - integer
     * 			ticketId - integer (optional)
     * @return	json response with ticket detailed list
     */

    public function detail_post() {
        $inputArray = $this->post();

        $eventTicketDetails = $this->eventHandler->getEventTicketDetails_mobile($inputArray);
        $resultArray = array('response' => $eventTicketDetails['response']);
        $this->response($resultArray, $eventTicketDetails['statusCode']);
    }

    public function getEventTickets_post() {
        $inputArray = $this->input->post();
        $ticketList = $this->ticketHandler->getEventTicketList_mobile($inputArray);
        $resultArray = array('response' => $ticketList['response']);
        $this->response($resultArray, $ticketList['statusCode']);
    }

}

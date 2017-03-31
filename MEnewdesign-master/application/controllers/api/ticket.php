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

   function add_put() {
        $inputArray = $this->put();
        $success=$this->ticketHandler->add($inputArray);
		
        $resultArray = array('response' => $success['response']);
        $this->response($resultArray, $success['statusCode']);
    }
    public function detail_get() {
        $inputArray = $this->get();
        
        $eventTicketDetails = $this->eventHandler->getEventTicketDetails($inputArray);        
        $resultArray = array('response' => $eventTicketDetails['response']);
        $this->response($resultArray, $eventTicketDetails['statusCode']);
    }
    public function delete_post() {
    	$inputArray = $this->post();
    	$result = $this->ticketHandler->deleteTicket($inputArray);
    	$resultArray = array('response' => $result['response']);
   		 $this->response($resultArray, $result['statusCode']);
    }
	
	/*
     * Function to get the Taxes based on country,state,city
     *
     * @access	public
     * @param	taking post values that contains
     * 			countryName - string
     * 			stateName - string (optional)
     * 			cityName - string (optional)
     * @return	json response
     */
    public function calculateTaxes_post() {
        $inputArray = $this->input->post();
        
        $taxDetails = $this->ticketTaxHandler->getTaxes($inputArray);
        $resultArray = array('response' => $taxDetails['response']);
        $this->response($resultArray, $taxDetails['statusCode']);
    }
	
	/*
     * Function to get the all possible calculations for the tickets
     *
     * @access	public
     * @param	taking post values that as below
     * 			ticketId => ticketCount
     * @return	json response
     */
    public function calculateAmount_post() {
        $inputArray = $this->input->post();
        $calculationDetails = $this->ticketHandler->getTicketCalcluations($inputArray);
        $resultArray = array('response' => $calculationDetails['response']);
        $this->response($resultArray, $calculationDetails['statusCode']);
    }

    public function getEventTickets_post() {
        $inputArray = $this->input->post();
        $ticketList=$this->ticketHandler->getEventTicketList($inputArray);
        $resultArray = array('response' => $ticketList['response']);
        $this->response($resultArray, $ticketList['statusCode']);
    }
    
    
        public function getTicketTaxByTicketId_post() {
        $inputArray = $this->input->post();
        
        $ticketList=$this->ticketTaxHandler->getTicketTaxByTicketId($inputArray);
        $resultArray = array('response' => $ticketList['response']);
        $this->response($resultArray, $ticketList['statusCode']);
    }
    
    // Sending tickets Sold Data to Organizer 
    
    public function sendTicketsoldDataToorganizer_post(){
    	$inputArray = $this->input->post();
    	$ticketList=$this->ticketHandler->sendTicketsoldDataToorganizer($inputArray);
    	$resultArray = array('response' => $ticketList['response']);
    	$this->response($resultArray, $ticketList['statusCode']);
    	
    }
    
}

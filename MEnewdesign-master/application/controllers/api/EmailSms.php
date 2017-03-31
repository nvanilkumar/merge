<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require_once(APPPATH . 'libraries/REST_Controller.php');
require_once(APPPATH . 'handlers/event_handler.php');
require_once(APPPATH . 'handlers/messagetemplate_handler.php');
class Email_Sms extends REST_Controller {

    var $eventHandler;
    var $messagetemplateHandler;

    public function __construct() {
        parent::__construct();

        $this->emailHandler = new Email_handler();
    }


    /*
     * Function to send Email to Delegate after Successfull Transaction
     *
     * @access	public
     * @return	json data
     *  Parameters : mode,type
     */

    public function templatedetails_get() {
        $inputArray = $this->get();
        $this->messagetemplateHandler = new Messagetemplate_handler();
        $templateDetails = $this->messagetemplateHandler->getTemplateDetail($inputArray) ;
        $resultArray = array('response' => $templateDetails['response']);
        $this->response($resultArray, $templateDetails['statusCode']);
    }
    
    /*
     * Function to send the transaction successfull Email to Delegate
     *
     * @access	public
     * @return	json data
     *  Parameters : eventsignupid (comma seperated),transactiontype
     */

    public function SendTransactionsuccessEmailtoDelegate_get($inputArray) {
    	$ticketdetails = $this->emailHandler->getListByEventsignupIds($inputArray);
    	$resultArray = array('response' => $ticketdetails['response']);
    	$this->response($resultArray, $ticketdetails['statusCode']);
    
    }
    
    
    /*
     * Function to get the Ticket Details
     *
     * @access	public
     * @return	json data
     *  Parameters : ticketIds (comma seperated),eventId
     */
    public function TicketDetails_get(){
    	$inputArray = $this->get();
    	$inputArray['ticketIds'] = explode(',',$inputArray['ticketIds']);
	    $this->ticketHandler = new Ticket_handler();
	    $ticketdetails = $this->ticketHandler->getTicketsbyIds($inputArray);
	    $resultArray = array('response' => $ticketdetails['response']);
	    $this->response($resultArray, $ticketdetails['statusCode']);
	    
    }
    
    /*
     * Function to get the Tickettax Details By Tax Mapping Id
     *
     * @access	public
     * @return	json data
     *  Parameters : taxmappingid
     */
    public function TickettaxDetails_get(){
    	$inputArray = $this->get();
    	$taxdetails = $this->tickettaxHandler->getTaxes($inputArray);
    	$resultArray = array('response' => $taxdetails['response']);
    	$this->response($resultArray, $taxdetails['statusCode']);
    	 
    }

    
   

}

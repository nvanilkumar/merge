<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require_once(APPPATH . 'libraries/REST_Controller.php');
require_once(APPPATH . 'handlers/confirmation_handler.php');
require_once(APPPATH . 'handlers/email_handler.php');

class Transaction extends REST_Controller {

    var $confirmationHandler;

    public function __construct() {
        parent::__construct();
        $this->confirmationHandler = new Confirmation_handler();
    }
  
    /*
     * Function to get the EventSignup Details By signup Id
     *
     * @access	public
     * @return	json data
     *  Parameters : eventsignupId
     */
    public function resendDelegateEmail_post(){
    	$inputArray = $this->post();
    	$sendEmail = $this->confirmationHandler->resendTransactionsuccessEmail($inputArray);
		$sendEmail['response']['status'] = $sendEmail['status'];
    	$resultArray = array('response' => $sendEmail['response']);
    	$this->response($resultArray, $sendEmail['statusCode']);
    }
	
	/*
     * Function to send the SMS for Delegate Successfull Transaction
     *
     * @access	public
     * @return	json data
     *  Parameters : eventsignupid
     */
    public function resendDelegateSms_post(){
    	$inputArray = $this->post();
		
		$inputArray['eventsignupid'] = $inputArray['eventsignupId'];
    	$sendsms = $this->confirmationHandler->sendSuccessEventsignupsmstoDelegate($inputArray);
		$sendsms['response']['status'] = $sendsms['status'];
    	$resultArray = array('response' => $sendsms['response']);
    	$this->response($resultArray, $sendsms['statusCode']);
    	
    }
}

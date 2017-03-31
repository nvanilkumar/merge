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
     * Function to get the Event Signup Details
     *
     * @access	public
     * @return	json data
     *  Parameters : orderId
     */

    public function transactionsuccessdetails_get() {
        $inputArray = $this->get();
        $eventsignupDetails = $this->confirmationHandler->eventSignupDetailData($inputArray);
        $resultArray = array('response' => $eventsignupDetails['response']);
        $this->response($resultArray, $eventsignupDetails['statusCode']);
    }
    
  
    /*
     * Function to get the EventSignup Details By orderid
     *
     * @access	public
     * @return	json data
     *  Parameters : orderId
     */
    public function resendTransactionSuccessEmailToDelegate_get(){
    	$inputArray = $this->get();
    	$sendEmail = $this->confirmationHandler->resendTransactionsuccessEmail($inputArray);
    	$resultArray = array('response' => $sendEmail['response']);
    	$this->response($resultArray, $sendEmail['statusCode']);
    }
    
    /*
     * Function to get printpass
     *
     * @access	public
     * @return	json data
     *  Parameters : eventsignupid,email
     */
    public function emailPrintpass_get(){
    	$inputArray = $this->get();
    	$sendEmail = $this->confirmationHandler->emailPrintpass($inputArray);
    	$resultArray = array('response' => $sendEmail['response']);
    	$this->response($resultArray, $sendEmail['statusCode']);
    }
    /*
     * Function to send the SMS for Delegate Successfull Transaction
     *
     * @access	public
     * @return	json data
     *  Parameters : eventsignupid,eventtitle,mobile
     */
    
    public function resendSuccessEventsignupsmstoDelegate_get(){
    	$inputArray = $this->get();
    	$sendsms = $this->confirmationHandler->sendSuccessEventsignupsmstoDelegate($inputArray);
    	$resultArray = array('response' => $sendsms['response']);
    	$this->response($resultArray, $sendsms['statusCode']);
    	
    }

    
   

}

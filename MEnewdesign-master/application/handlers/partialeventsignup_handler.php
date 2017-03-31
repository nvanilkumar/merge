<?php

/**
 * Reports related business logic will be defined in this class
 * @package		CodeIgniter
 * @author		Qison  Dev Team
 * @param		
 * @addTicket		
 * @copyright	Copyright (c) 2015, MeraEvents.
 * @Version		Version 1.0
 * @Since       Class available since Release Version 1.0
 * @Created     03-08-2015
 * @Last Modified 03-08-2015
 */


class Partialeventsignup_handler extends Handler {

    var $ci;
  

    //var $refundHandler;
    public function __construct() {
        parent::__construct();
        $this->ci = parent::$CI;
        $this->ci->load->model('Partialeventsignup_model');
       
    }
    
    public function add($inputArray) {
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('eventid', 'event id', 'trim|xss_clean|required_strict');

        if ($this->ci->form_validation->run() === FALSE) {
            $response = $this->ci->form_validation->get_errors('message');
            $output['status'] = FALSE;
            $output['response']['messages'] = $response['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }

        $this->ci->Partialeventsignup_model->resetVariable();
        $createEventSignup[$this->ci->Partialeventsignup_model->eventsignupid] = $inputArray['eventsignupid'];
        $createEventSignup[$this->ci->Partialeventsignup_model->userid] = $inputArray['userid'];
        $createEventSignup[$this->ci->Partialeventsignup_model->signupdate] = $inputArray['signupdate'];
        $createEventSignup[$this->ci->Partialeventsignup_model->eventid] = $inputArray['eventid'];
        $createEventSignup[$this->ci->Partialeventsignup_model->quantity] = $inputArray['quantity'];
        $createEventSignup[$this->ci->Partialeventsignup_model->fromcurrencyid] = $inputArray['fromcurrencyid'];
        $createEventSignup[$this->ci->Partialeventsignup_model->attendeeid] = $inputArray['attendeeid'];
        $createEventSignup[$this->ci->Partialeventsignup_model->transactionstatus] = ($inputArray['transactionstatus'] != '') ? $inputArray['transactionstatus'] : 'pending';
        $createEventSignup[$this->ci->Partialeventsignup_model->paymentgatewayid] = $inputArray['paymentgatewayid'];
        $createEventSignup[$this->ci->Partialeventsignup_model->paymentstatus] = ($inputArray['paymentstatus'] != '') ? $inputArray['paymentstatus'] : 'Captured';
        $createEventSignup[$this->ci->Partialeventsignup_model->totalamount] = $inputArray['totalamount'];
        $createEventSignup[$this->ci->Partialeventsignup_model->transactiontickettype] = $inputArray['transactiontickettype'];
        $createEventSignup[$this->ci->Partialeventsignup_model->paymentmodeid] = $inputArray['paymentmodeid'] ? $inputArray['paymentmodeid'] : 1;

        $createEventSignup[$this->ci->Partialeventsignup_model->discountamount] = $inputArray['discountamount'] ? $inputArray['discountamount'] : 0;
        $createEventSignup[$this->ci->Partialeventsignup_model->referraldiscountamount] = $inputArray['referraldiscountamount'] ? $inputArray['referraldiscountamount'] : 0;
        $createEventSignup[$this->ci->Partialeventsignup_model->tocurrencyid] = $inputArray['tocurrencyid'] ? $inputArray['tocurrencyid'] : 0;
        $createEventSignup[$this->ci->Partialeventsignup_model->discount] = $inputArray['discount'] ? $inputArray['discount'] : 0;
        $createEventSignup[$this->ci->Partialeventsignup_model->discountcodeid] = $inputArray['discountcodeid'] ? $inputArray['discountcodeid'] : 0;
        $createEventSignup[$this->ci->Partialeventsignup_model->paymenttransactionid] = $inputArray['paymenttransactionid'] ? $inputArray['paymenttransactionid'] : 0;
        $createEventSignup[$this->ci->Partialeventsignup_model->transactionresponse] = $inputArray['transactionresponse'] ? $inputArray['transactionresponse'] : 0;
        $createEventSignup[$this->ci->Partialeventsignup_model->settlementdate] = $inputArray['settlementdate'] ? $inputArray['settlementdate'] : 0;
        $createEventSignup[$this->ci->Partialeventsignup_model->ticketwidgettransaction] = $inputArray['ticketwidgettransaction'] ? $inputArray['ticketwidgettransaction'] : 0;
        $createEventSignup[$this->ci->Partialeventsignup_model->depositdate] = $inputArray['depositdate'] ? $inputArray['depositdate'] : 0;
        $createEventSignup[$this->ci->Partialeventsignup_model->referralcode] = $inputArray['referralcode'] ? $inputArray['referralcode'] : 0;
        $createEventSignup[$this->ci->Partialeventsignup_model->promotercode] = $inputArray['promotercode'] ? $inputArray['promotercode'] : 0;
        $createEventSignup[$this->ci->Partialeventsignup_model->barcodenumber] = $inputArray['barcodenumber'] ? $inputArray['barcodenumber'] : 0;
        $createEventSignup[$this->ci->Partialeventsignup_model->eventextrachargeamount] = $inputArray['eventextrachargeamount'] ? $inputArray['eventextrachargeamount'] : 0;
        $createEventSignup[$this->ci->Partialeventsignup_model->eventextrachargeid] = $inputArray['eventextrachargeid'] ? $inputArray['eventextrachargeid'] : 0;

        $this->ci->Partialeventsignup_model->setInsertUpdateData($createEventSignup);
        $eventSignUpId = $this->ci->Partialeventsignup_model->insert_data(); //Inserting into table and getting inserted id
        if ($eventSignUpId) {
            //Inserting record in the ticketdiscount table
            $output['status'] = TRUE;
            $output['response']['messages'] = SUCCESS_EVENTSIGNUP_ADDED;
            $output['response']['eventSignUpId'] = $eventSignUpId;
            $output['statusCode'] = STATUS_OK;
            return $output;
        }
        $output['status'] = FALSE;
        $output['response']['messages'][] = ERROR_EVENTSIGNUP_ADDED;
        $output['statusCode'] = STATUS_SERVER_ERROR;
        return $output;
    }


}

<?php

/**
 * orgnizer bank details related business logic will be defined in this class
 *
 * @package		CodeIgniter
 * @author		Qison  Dev Team
 * @copyright	Copyright (c) 2015, MeraEvents.
 * @Version		Version 1.0
 * @Since       Class available since Release Version 1.0 
 * @Created     13-10-2015
 * @Last Modified  
 * @Last Modified by Qison team 
 */
require_once(APPPATH . 'handlers/handler.php');

class Organizerbankdetail_handler extends Handler {

    var $ci;

    public function __construct() {
        parent::__construct();
        $this->ci = parent::$CI;
        $this->ci->load->model('Organizerbankdetail_model');
    }

  public function getBankDetails($inputArray) {

        parent::$CI->form_validation->pass_array($inputArray);
        parent::$CI->form_validation->set_rules('userId', 'User Id', 'required_strict|is_natural_no_zero');
        if (parent::$CI->form_validation->run() === FALSE) {
            $errorMessages = $this->ci->form_validation->get_errors();
            $output['status'] = FALSE;
            $output['response']['messages'] = $errorMessages['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $this->ci->Organizerbankdetail_model->resetVariable();
        $selectInput['id'] = $this->ci->Organizerbankdetail_model->id;
        $selectInput['userid'] = $this->ci->Organizerbankdetail_model->userid;
        $selectInput['accountname'] = $this->ci->Organizerbankdetail_model->accountname;
        $selectInput['accountnumber'] = $this->ci->Organizerbankdetail_model->accountnumber;
        $selectInput['bankname'] = $this->ci->Organizerbankdetail_model->bankname;
        $selectInput['branch'] = $this->ci->Organizerbankdetail_model->branch;
        $selectInput['ifsccode'] = $this->ci->Organizerbankdetail_model->ifsccode;
        $selectInput['accounttype'] = $this->ci->Organizerbankdetail_model->accounttype;

        $this->ci->Organizerbankdetail_model->setSelect($selectInput);
        $where[$this->ci->Organizerbankdetail_model->userid] = $inputArray['userId'];
        $this->ci->Organizerbankdetail_model->setWhere($where);
        $bankDetals = $this->ci->Organizerbankdetail_model->get();
        if ($bankDetals) {
            $output['status'] = TRUE;
            $output['response']['bankDetails'] = $bankDetals;
            $output['statusCode'] = STATUS_OK;
            $output['response']['total'] = count($bankDetals);
            return $output;
        } else {
            $output['status'] = TRUE;
            $output["response"]["messages"][] = ERROR_NO_DATA;
            $output['statusCode'] = STATUS_OK;
            $output['response']['total'] = 0;
            return $output;
        }
    }

    public function insertBankDetails($inputArray) {
        $this->ci->form_validation->pass_array($inputArray);
        if ($this->ci->form_validation->run('bankDetails') === FALSE) {
            $error_messages = $this->ci->form_validation->get_errors('message');
            $output = parent::createResponse(FALSE, $error_messages, STATUS_BAD_REQUEST);
            return $output;
        }
        $this->ci->Organizerbankdetail_model->resetVariable();
        $accountInfo['userid'] = $inputArray['userId'];
        $accountInfo['accountname'] = $inputArray['accountName'];
        $accountInfo['accountnumber'] = $inputArray['accountNumber'];
        $accountInfo['bankname'] = $inputArray['bankName'];
        $accountInfo['branch'] = $inputArray['branch'];
        $accountInfo['ifsccode'] = $inputArray['ifsccode'];
        $accountInfo['accounttype'] = $inputArray['accountType'];
        $this->ci->Organizerbankdetail_model->setInsertUpdateData($accountInfo);
        $response = $this->ci->Organizerbankdetail_model->insert_data();
        if ($response) {
            $output['status'] = TRUE;
            $output["response"]["messages"][] = UPDATE_BANK_DETAILS;
            $output['statusCode'] = STATUS_CREATED;
            return $output;
        }
        $output['status'] = FALSE;
        $output["response"]["messages"][] = SOMETHING_WENT_WRONG;
        $output['statusCode'] = STATUS_SERVER_ERROR;
        return $output;
    }

    public function updateBankDetails($inputArray) {
        $this->ci->form_validation->reset_form_rules();
        parent::$CI->form_validation->pass_array($inputArray);
        parent::$CI->form_validation->set_rules('accountName', 'Account Name', 'required_strict|accountholdername|max_length[50]');
        parent::$CI->form_validation->set_rules('accountNumber', 'Account Number', 'required_strict|is_natural|max_length[30]');
        parent::$CI->form_validation->set_rules('bankName', 'Bank Name', 'required_strict|bankname');
        parent::$CI->form_validation->set_rules('branch', 'Branch', 'required_strict');
        parent::$CI->form_validation->set_rules('ifsccode', 'Ifsc Code', 'required_strict|alpha_numeric');
        if (parent::$CI->form_validation->run() === FALSE) {
            $errorMessages = $this->ci->form_validation->get_errors();
            $output['status'] = FALSE;
            $output['response']['messages'] = $errorMessages['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
//        if ($this->ci->form_validation->run('bankDetails') == FALSE) {
//            $error_messages = $this->ci->form_validation->get_errors('message');
//            $output = parent::createResponse(FALSE, $error_messages, STATUS_BAD_REQUEST);
//            return $output;
//        }
        $this->ci->Organizerbankdetail_model->resetVariable();
        $accountInfo['accountname'] = $inputArray['accountName'];
        $accountInfo['accountnumber'] = $inputArray['accountNumber'];
        $accountInfo['bankname'] = $inputArray['bankName'];
        $accountInfo['branch'] = $inputArray['branch'];
        $accountInfo['ifsccode'] = $inputArray['ifsccode'];
        $accountInfo['accounttype'] = $inputArray['accountType'];
        $where['userid'] = $inputArray['userId'];
        $this->ci->Organizerbankdetail_model->setInsertUpdateData($accountInfo);
        $this->ci->Organizerbankdetail_model->setWhere($where);
        $response = $this->ci->Organizerbankdetail_model->update_data();
        if ($response) {
            $output['status'] = TRUE;
            $output["response"]["messages"][] = UPDATE_BANK_DETAILS;
            $output['statusCode'] = STATUS_UPDATED;
            return $output;
        }
        $output['status'] = FALSE;
        $output["response"]["messages"][] = SOMETHING_WENT_WRONG;
        $output['statusCode'] = STATUS_SERVER_ERROR;
        return $output;
    }
}

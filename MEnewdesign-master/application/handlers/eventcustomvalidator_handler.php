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
require_once(APPPATH . 'handlers/handler.php');
class Eventcustomvalidator_handler extends Handler {

    var $ci;

    //var $refundHandler;
    public function __construct() {
        parent::__construct();
        $this->ci = parent::$CI;
        $this->ci->load->model('Eventcustomvalidator_model');
    }

    /**
     * To get the event related pmi membership information
     * @param type $inputArray
     * @return int
     */
    public function getPmiChennaiDetails($inputArray) {
        $output = $memberDetails = array();
        $this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('eventId', 'EventId', 'is_natural_no_zero|required_strict');
        $this->ci->form_validation->set_rules('membershipId', 'Membership Id', 'is_natural_no_zero');

        if ($this->ci->form_validation->run() === FALSE) {
            $output['status'] = FALSE;
            $output['response']['messages'] = $this->ci->form_validation->get_errors();
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
       
        $this->ci->Eventcustomvalidator_model->setTableName('eventcustomvalidator');
        $select['id'] = $this->ci->Eventcustomvalidator_model->id;
        $select['value'] = $this->ci->Eventcustomvalidator_model->value;
        $select['name'] = $this->ci->Eventcustomvalidator_model->name;
        $select['eventid'] = $this->ci->Eventcustomvalidator_model->eventid;
        $select['status'] = $this->ci->Eventcustomvalidator_model->status;

        $this->ci->Eventcustomvalidator_model->setSelect($select);

        $where[$this->ci->Eventcustomvalidator_model->eventid] = $inputArray['eventId'];
        if (isset($inputArray['membershipId'])) {
            $where[$this->ci->Eventcustomvalidator_model->value] = $inputArray['membershipId'];
            $where[$this->ci->Eventcustomvalidator_model->status] =0;//Means active records only
        }

        $this->ci->Eventcustomvalidator_model->setWhere($where);

        $memberDetails = $this->ci->Eventcustomvalidator_model->get();

        if (count($memberDetails) > 0) {
            $output['status'] = TRUE;
            $output['response']['memberDetails'] = $memberDetails;
            $output['messages'] = array();
            $output['response']['total'] = count($memberDetails);
            $output['statusCode'] = 200;
        } else if ($memberDetails != FALSE && count($memberDetails) == 0) {//No records are fetched
            $output['status'] = TRUE;
            $output['messages'][] = ERROR_NO_DATA;
            $output['response']['total'] = 0;
            $output['statusCode'] = 200;
        } else {
            $output['status'] = FALSE;
            $output['messages'][] = ERROR_INTERNAL_DB_ERROR;
            $output['response']['total'] = 0;
            $output['statusCode'] = 500;
        }
        return $output;
    }

    //To update the pmi membership record
    public function updateMemberShipDetails($inputArray) {
        $this->ci->load->model('eventcustomvalidator_model');
        $output = $response = $memberDetails = array();
        $this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('eventId', 'EventId', 'is_natural_no_zero|required_strict');
//        $this->ci->form_validation->set_rules('membershipId', 'Membership Id', 'is_natural_no_zero');

        if ($this->ci->form_validation->run() === FALSE) {
            $output['status'] = FALSE;
            $output['response']['messages'] = $this->ci->form_validation->get_errors();
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $whereArray = array();
        $where_in = array();
        $this->ci->eventcustomvalidator_model->setTableName('eventcustomvalidator');
        $createTicket[$this->ci->eventcustomvalidator_model->status] = 1;
        $whereArray[$this->ci->eventcustomvalidator_model->eventid] = $inputArray['eventId'];
        if (isset($inputArray['membershipList']) && is_array($inputArray['membershipList'])) {
            $where_in[$this->ci->eventcustomvalidator_model->value] = $inputArray['membershipList'];
            $this->ci->eventcustomvalidator_model->setWhereIns($where_in);
        }else{
            $whereArray[$this->ci->eventcustomvalidator_model->value] = $inputArray['membershipId'];
        }

        $this->ci->eventcustomvalidator_model->setWhere($whereArray);
        $this->ci->eventcustomvalidator_model->setInsertUpdateData($createTicket);
        $response = $this->ci->eventcustomvalidator_model->update_data();
         
        if ($response) {
            $output['status'] = TRUE;
            $output["response"]["messages"][] = "Updated the membership data";
            $output['statusCode'] = STATUS_CREATED;
            return $output;
        }
        $output['status'] = FALSE;
        $output["response"]["messages"][] = SOMETHING_WENT_WRONG;
        $output['statusCode'] = STATUS_SERVER_ERROR;
        return $output;
    }
	
	
	/*tedx 2016 email check*/
	public function checkTedX2016Email($inputArray) {
		//print_r($inputArray);
        $output = $memberDetails = array();
        $this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('eventid', 'EventId', 'is_natural_no_zero|required_strict');
        $this->ci->form_validation->set_rules('email', 'Email Id', 'email|required_strict');

        if ($this->ci->form_validation->run() === FALSE) {
            $output['status'] = FALSE;
            $output['response']['messages'] = $this->ci->form_validation->get_errors();
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
		
		//echo "here".$this->ci->Eventcustomvalidator_model->status; exit;
		
        //$this->ci->Eventcustomvalidator_model->setTableName('eventcustomvalidator');
        $select['id'] = $this->ci->Eventcustomvalidator_model->id;
        $select['value'] = $this->ci->Eventcustomvalidator_model->value;
        $select['name'] = $this->ci->Eventcustomvalidator_model->name;
        $select['eventid'] = $this->ci->Eventcustomvalidator_model->eventid;
        $select['status'] = $this->ci->Eventcustomvalidator_model->status;

        $this->ci->Eventcustomvalidator_model->setSelect($select);

        $where[$this->ci->Eventcustomvalidator_model->eventid] = $inputArray['eventid'];
        if (isset($inputArray['email'])) {
            $where[$this->ci->Eventcustomvalidator_model->value] = $inputArray['email'];
            $where[$this->ci->Eventcustomvalidator_model->deleted] =0;//Means active records only
        }

        $this->ci->Eventcustomvalidator_model->setWhere($where);

        $tedxEmailDetails = $this->ci->Eventcustomvalidator_model->get();
		//print_r($tedxEmailDetails);

        if (count($tedxEmailDetails) > 0) {
			
			$tedxDBRespStatus = $tedxEmailDetails[0]['status'];
			
			if($tedxDBRespStatus == 1){
				$output['status'] = "error";
				$output['response']['message'] = "Only one transaction allowed per email";
				$output['statusCode'] = 200;
			}
			else{
				$output['status'] = "success";
				$output['response']['message'] = "Valid email id";
				$output['statusCode'] = 200;
			}
			
			
        } else {
            $output['status'] = "error";
            $output['response']['message'] = "Please enter your registered email id to proceed further";
            $output['statusCode'] = 200;
        }
        return $output;
    }
	
	
	/*update tedx 2016 email*/
    public function updateTedX2016Emails($inputArray) {
		
		//echo "hand"; print_r($inputArray); exit;

		//print_r($inputArray);
        $output = $memberDetails = array();
        $this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('eventId', 'EventId', 'is_natural_no_zero|required_strict');
        //$this->ci->form_validation->set_rules('email', 'Email Id', 'email|required_strict');

        if ($this->ci->form_validation->run() === FALSE) {
            $output['status'] = FALSE;
            $output['response']['messages'] = $this->ci->form_validation->get_errors();
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        
		
		$this->ci->Eventcustomvalidator_model->setTableName('eventcustomvalidator');
        $set[$this->ci->Eventcustomvalidator_model->status] = 1;

        $where[$this->ci->Eventcustomvalidator_model->eventid] = $inputArray['eventId'];
		if (isset($inputArray['emailList']) && is_array($inputArray['emailList'])) {
            $where_in[$this->ci->Eventcustomvalidator_model->value] = $inputArray['emailList'];
            $this->ci->Eventcustomvalidator_model->setWhereIns($where_in);
        }else{
            $where[$this->ci->Eventcustomvalidator_model->value] = $inputArray['emailList'];
        }
		
        $this->ci->Eventcustomvalidator_model->setWhere($where);
		$this->ci->Eventcustomvalidator_model->setInsertUpdateData($set);
        $response = $this->ci->Eventcustomvalidator_model->update_data();
		//print_r($response); exit;

        if ($response) {
            $output['status'] = TRUE;
            $output["response"]["messages"][] = "Updated the emails status";
            $output['statusCode'] = STATUS_CREATED;
            return $output;
        }
        $output['status'] = FALSE;
        $output["response"]["messages"][] = SOMETHING_WENT_WRONG;
        $output['statusCode'] = STATUS_SERVER_ERROR;
        return $output;
    }

}

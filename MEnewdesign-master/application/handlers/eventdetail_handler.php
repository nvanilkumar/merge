<?php

/**
 * Reports related business logic will be defined in this class
 * @package		CodeIgniter
 * @author		Qison  Dev Team
 * @param		
 * @addTicket		
 * @copyright	Copyright (c) 2015, Meraevents.
 * @Version		Version 1.0
 * @Since       Class available since Release Version 1.0
 * @Created     03-08-2015
 * @Last Modified 03-08-2015
 */
require_once (APPPATH . 'handlers/handler.php');


class Eventdetail_handler extends Handler {

    var $ci;
    public function __construct() {
        parent::__construct();
        $this->ci = parent::$CI;
        $this->ci->load->model('Eventdetail_model');
    }

    public function geteventDetailsbyId($input) {
        // $transTypeResponse = array('status' => FALSE);
    $output = array();
		if(!isset($request['eventId'] )){
			$output['status'] = FALSE;
			$output['response']['messages'][] = ERROR_INVALID_INPUT;
			$output['statusCode'] = STATUS_BAD_REQUEST;
			return $output;
		}
		$this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($request);
        $this->ci->form_validation->set_rules('eventId', 'EventId', 'is_natural_no_zero|required_strict');
        if (!empty($request) && $this->ci->form_validation->run() == FALSE) {
        	$validationStatus = $this->ci->form_validation->get_errors();
        	$output['status'] = FALSE;
        	$output['response']['messages'] = $validationStatus['message'];
        	$output['statusCode'] = STATUS_BAD_REQUEST;
        	return $output;
        } else {
            $this->ci->Eventdetail_model->resetVariable();
	        $selectDetailsInput['contactdetails'] = $this->ci->Eventdetail_model->eventdetail_contactdetails;
            $selectDetailsInput['bookButtonValue'] = $this->ci->Eventdetail_model->booknowbuttonvalue;
            $selectDetailsInput['facebookLink'] = $this->ci->Eventdetail_model->eventdetail_facebooklink;
            $selectDetailsInput['googleLink'] = $this->ci->Eventdetail_model->eventdetail_googlelink;
            $selectDetailsInput['twitterLink'] = $this->ci->Eventdetail_model->eventdetail_twitterlink;
            //$selectDetailsInput['tnc'] = $this->ci->Eventdetail_model->eventdetail_tnc;
	        $selectDetailsInput['tnctype'] = $this->ci->Eventdetail_model->eventdetail_tnctype;
            $selectDetailsInput['meraeventstnc'] = $this->ci->Eventdetail_model->eventdetail_meraeventstnc;
            $selectDetailsInput['organizertnc'] = $this->ci->Eventdetail_model->eventdetail_organizertnc;
            $selectDetailsInput['contactWebsiteUrl'] = $this->ci->Eventdetail_model->eventdetail_contactwebsiteurl;
	        $selectInput['order'] = $this->ci->Eventdetail_model->order;
			$selectInput['viewcount'] = $this->ci->Eventdetail_model->viewcount;
	        $this->ci->Eventdetail_model->setSelect($selectInput);
	        $where[$this->ci->Eventdetail_model->eventid] = $request['eventId'];
	        $this->ci->Eventdetail_model->setWhere($where);
	//Order by array
	        $orderBy = array();
	        $orderBy[] = $this->ci->Eventdetail_model->order;
	        $this->ci->Eventdetail_model->setOrderBy($orderBy);
	       $eventDetails = $this->ci->Eventdetail_model->get();
	     if(is_array($eventDetails)){
		        if (count($eventDetails) > 0) {
		        	$response = array();
		        	$output['status'] = TRUE;
		        	$output['response']['eventDetail'] = $eventDetails;
		        	$output['response']['eventDetail']['total'] = count($eventDetails);
		        	$output['statusCode'] = STATUS_OK;
		        	return $output;
		        }else {
		            $output['status'] = TRUE;
		            $output['response']['messages'][] = ERROR_NO_DATA;
		            $output['statusCode'] = STATUS_OK;
		            return $output;
		        }
        
	      }else{
	      	$output['status'] = FALSE;
	      	$output['response']['messages'][] = ERROR_INTERNAL_DB_ERROR;
	      	$output['statusCode'] = STATUS_SERVER_ERROR;
	      	return $output;
	        }
        
        }

       
    		
    	}
        
    public function geteventDetailsByList($inputArray) {
        // $transTypeResponse = array('status' => FALSE);
		$this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($request);
        $this->ci->form_validation->set_rules('eventIdList', 'eventIdList', 'is_array|required_strict');
        if (!empty($request) && $this->ci->form_validation->run() == FALSE) {
        	$validationStatus = $this->ci->form_validation->get_errors();
        	$output['status'] = FALSE;
        	$output['response']['messages'] = $validationStatus['message'];
        	$output['statusCode'] = STATUS_BAD_REQUEST;
        	return $output;
        } else {
            $this->ci->Eventdetail_model->resetVariable();
            $selectInput['eventid'] = $this->ci->Eventdetail_model->eventdetail_id;
	    $selectDetailsInput['contactdetails'] = $this->ci->Eventdetail_model->eventdetail_contactdetails;
            $selectInput['viewcount'] = $this->ci->Eventdetail_model->viewcount;
	    $this->ci->Eventdetail_model->setSelect($selectInput);
            $where_in[$this->ci->Eventdetail_model->eventdetail_id] = $inputArray['eventIdList'];
            $this->ci->Eventdetail_model->setWhereIns($where_in);
	    $eventDetails = $this->ci->Eventdetail_model->get();
	     if(is_array($eventDetails)){
		        if (count($eventDetails) > 0) {
		        	$response = array();
		        	$output['status'] = TRUE;
		        	$output['response']['eventDetail'] = $eventDetails;
		        	$output['response']['eventDetail']['total'] = count($eventDetails);
		        	$output['statusCode'] = STATUS_OK;
		        	return $output;
		        }else {
		            $output['status'] = TRUE;
		            $output['response']['messages'][] = ERROR_NO_DATA;
                            $output['response']['eventDetail']['total'] = 0;
		            $output['statusCode'] = STATUS_OK;
		            return $output;
		        }
        
	      }else{
	      	$output['status'] = FALSE;
	      	$output['response']['messages'][] = ERROR_INTERNAL_DB_ERROR;
	      	$output['statusCode'] = STATUS_SERVER_ERROR;
	      	return $output;
	        }       
        }
    	}
     }

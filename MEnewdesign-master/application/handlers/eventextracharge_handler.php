<?php

/**
 * Comment related business logic will be defined in this class
 *
 * @package		CodeIgniter
 * @author		Qison  Dev Team
 * @copyright	Copyright (c) 2015, MeraEvents.
 * @Version		Version 1.0
 * @Since       Class available since Release Version 1.0 
 * @Created     30-07-2015
 * @Last Modified  
 * @Last Modified by Anil Kumar M 
 */
require_once(APPPATH . 'handlers/handler.php');
require_once(APPPATH . 'handlers/currency_handler.php');

class Eventextracharge_handler extends Handler {

    //var $searchHandler;
    var $ci;

    public function __construct() {
        parent::__construct();
        $this->ci = parent::$CI;
        $this->ci->load->model('Eventextracharge_model');
		$this->ci->currencyHandler = new Currency_handler();
    }

    /**
     * To get the Comment
     */
    public function getExtrachargeByEventId($inputArray) {
        $validationStatus = $this->validateGetExtracharge($inputArray);
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('eventid', 'Event Id', 'required_strict|is_natural_no_zero');
        //$this->ci->form_validation->set_rules('commenttype', 'commenttype', 'required_strict');
        if ($this->ci->form_validation->run() === FALSE) {
        	$errorMessages = $this->ci->form_validation->get_errors();
        	return $errorMessages;
        }
        if ($validationStatus['error']) {
            $output['status'] = FALSE;
            $output['response']['messages'] = $validationStatus['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $eventId = $inputArray['eventid'];
        $this->ci->Eventextracharge_model->resetVariable();
        $selectInput['id'] = $this->ci->Eventextracharge_model->id;
        $selectInput['type'] = $this->ci->Eventextracharge_model->type;
        $selectInput['label'] = $this->ci->Eventextracharge_model->label;
        $selectInput['value'] = $this->ci->Eventextracharge_model->value;
		$selectInput['currencyid'] = $this->ci->Eventextracharge_model->currencyid;
        $this->ci->Eventextracharge_model->setSelect($selectInput);
        $where[$this->ci->Eventextracharge_model->eventid] = $eventId;
        $where[$this->ci->Eventextracharge_model->status] = 1;
        $where[$this->ci->Eventextracharge_model->deleted] = 0;
        $this->ci->Eventextracharge_model->setWhere($where);
        //$this->ci->Eventextracharge_model->setRecords(1);
        $eventExtraChargeResponse = $this->ci->Eventextracharge_model->get();
        if (count($eventExtraChargeResponse) == 0) {
            $output['status'] = TRUE;
            $output['response']['messages'][] = ERROR_NO_DATA;
            $output['response']['total'] = 0;
            $output['statusCode'] = STATUS_OK;
            return $output;
        }
		
		foreach($eventExtraChargeResponse as $eventExtraCharge) {
			$currencyArr[] = $eventExtraCharge['currencyid'];
		}
		$ticketList['idList'] = array_unique($currencyArr);
        $currencyListResponse = $this->ci->currencyHandler->getCurrencyList($ticketList);
		
		if($currencyListResponse['status']) {
			$currencyList = commonHelperGetIdArray($currencyListResponse['response']['currencyList'],'currencyId');
		}
		foreach($eventExtraChargeResponse as $eventExtraCharge) {
			$eventExtraCharge['currencycode'] = $currencyList[$eventExtraCharge['currencyid']]['currencyCode'];
			$finalArr[] = $eventExtraCharge;
		}
		
        $output['status'] = TRUE;
        $output['response']['eventExtrachargeList'] = $finalArr;
        $output['response']['messages'] = array();
        $output['response']['total'] = count($eventExtraChargeResponse);
        $output['statusCode'] = STATUS_OK;
        return $output;
    }
    
    public function getExtrachargeById($inputArray) {
    	$this->ci->form_validation->pass_array($inputArray);
		if($inputArray['isExtraIdsArray']) {
			$this->ci->form_validation->set_rules('eventextrachargeId', 'Event extracharge Id', 'required_strict');	
		} else {
			$this->ci->form_validation->set_rules('eventextrachargeId', 'Event extracharge Id', 'required_strict|is_natural_no_zero');	
		}
        
        if ($this->ci->form_validation->run() == FALSE) {
        	$response = $this->ci->form_validation->get_errors('message');
        	$output['status'] = FALSE;
        	$output['response']['messages'] = $response['message'];
        	$output['statusCode'] = STATUS_BAD_REQUEST;
        	return $output;
        }
        $Id = $inputArray['eventextrachargeId'];
        $this->ci->Eventextracharge_model->resetVariable();
        $selectInput['id'] = $this->ci->Eventextracharge_model->id;
        $selectInput['label'] = $this->ci->Eventextracharge_model->label;
        $this->ci->Eventextracharge_model->setSelect($selectInput);
		if($inputArray['isExtraIdsArray']) {
			$whereIn[$this->ci->Eventextracharge_model->id] = explode(',',$Id);
			$this->ci->Eventextracharge_model->setWhereIns($whereIn);
		} else {
			$where[$this->ci->Eventextracharge_model->id] = $Id;
			$this->ci->Eventextracharge_model->setRecords(1);
		}
        
        $where[$this->ci->Eventextracharge_model->deleted] = 0;
        $this->ci->Eventextracharge_model->setWhere($where);
        $eventExtraChargeResponse = $this->ci->Eventextracharge_model->get();
        if (count($eventExtraChargeResponse) == 0) {
            $output['status'] = TRUE;
            $output['response']['messages'][] = ERROR_NO_DATA;
            $output['response']['total'] = 0;
            $output['statusCode'] = STATUS_OK;
            return $output;
        }
        $output['status'] = TRUE;
		if($inputArray['isExtraIdsArray']) {
			$output['response']['eventExtrachargeList'] = $eventExtraChargeResponse;
		} else {
			$output['response']['eventExtrachargeList'] = $eventExtraChargeResponse[0];	
		}
        
        $output['response']['messages'] = array();
        $output['response']['total'] = count($eventExtraChargeResponse);
        $output['statusCode'] = STATUS_OK;
        return $output;
    }

    //To validate the cityStateInsert 
    public function validateGetExtracharge($inputs) {
        $errorMessages = array();
        $this->ci->form_validation->pass_array($inputs);
        $this->ci->form_validation->set_rules('eventid', 'eventid', 'required_strict');
        //$this->ci->form_validation->set_rules('commenttype', 'commenttype', 'required_strict');
        if ($this->ci->form_validation->run() === FALSE) {
            $errorMessages = $this->ci->form_validation->get_errors();
            return $errorMessages;
        }
        $errorMessages['error'] = FALSE;
        return $errorMessages;
    }


	public function extraChargeInsert($inputs) {
        
		$this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($inputs);
        $this->ci->form_validation->set_rules('eventId', 'eventid', 'required_strict|is_natural_no_zero');
        $this->ci->form_validation->set_rules('label', 'label', 'required_strict');
		$this->ci->form_validation->set_rules('value', 'value', 'required_strict');
		
        if ($this->ci->form_validation->run() == FALSE) {
            $response = $this->ci->form_validation->get_errors('message');
            $output['status'] = FALSE;
            $output['response']['messages'] = $response['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }

        $insertCityArray[$this->ci->Eventextracharge_model->deleted] = 0;
        $insertCityArray[$this->ci->Eventextracharge_model->eventid] = $inputs['eventId'];
		$insertCityArray[$this->ci->Eventextracharge_model->label] = $inputs['label'];
		$insertCityArray[$this->ci->Eventextracharge_model->value] = $inputs['value'];
        $this->ci->Eventextracharge_model->setInsertUpdateData($insertCityArray);
        $eventExtraId = $this->ci->Eventextracharge_model->insert_data();
		
		if($eventExtraId > 0) {
			$output['response']['eventExtraId'] = $eventExtraId;
			$output['status'] = TRUE;
			$output['response']['messages'] = array();
			$output['statusCode'] = STATUS_OK;
			return $output;
		} else {
			$output['status'] = FALSE;
			$output['response']['messages'][] = SOMETHING_WRONG;
			$output['statusCode'] = STATUS_BAD_REQUEST;
			return $output;
		}
        
    }
	
	public function extraChargeRemove($inputs) {
        
		$this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($inputs);
        $this->ci->form_validation->set_rules('eventId', 'eventid', 'required_strict|is_natural_no_zero');
		
        if ($this->ci->form_validation->run() == FALSE) {
            $response = $this->ci->form_validation->get_errors('message');
            $output['status'] = FALSE;
            $output['response']['messages'] = $response['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }

        //$insertCityArray[$this->ci->Eventextracharge_model->deleted] = 1;
		$insertCityArray[$this->ci->Eventextracharge_model->status] = 0;
        $where[$this->ci->Eventextracharge_model->eventid] = $inputs['eventId'];
		$this->ci->Eventextracharge_model->setWhere($where);
        $this->ci->Eventextracharge_model->setInsertUpdateData($insertCityArray);
        $eventExtraId = $this->ci->Eventextracharge_model->update_data();
		
		if($eventExtraId > 0) {
			$output['response']['eventExtraId'] = $eventExtraId;
			$output['status'] = TRUE;
			$output['response']['messages'] = array();
			$output['statusCode'] = STATUS_OK;
			return $output;
		} else {
			$output['status'] = FALSE;
			$output['response']['messages'][] = SOMETHING_WRONG;
			$output['statusCode'] = STATUS_BAD_REQUEST;
			return $output;
		}
        
    }
}

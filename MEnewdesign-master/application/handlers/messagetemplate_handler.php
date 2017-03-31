<?php

/**
 * Event Gallery Data will be defined in this class
 * Getting Banners Related data
 * @package		CodeIgniter
 * @author		Qison  Dev Team
 * @param		eventId - required
 * @copyright	Copyright (c) 2015, MeraEvents.
 * @Version		Version 1.0
 * @Since       Class available since Release Version 1.0
 * @Created     21-07-2015
 * @Last Modified 21-06-2015
 */
require_once (APPPATH . 'handlers/handler.php');
class Messagetemplate_handler extends Handler {

    var $ci;
    var $messagetemplateHandler;
    public function __construct() {
        parent::__construct();
        $this->ci = parent::$CI;
        $this->ci->load->library('form_validation');
        $this->ci->load->model('Messagetemplate_model');
    }

    public function getEventsignuptickettemplate($request) {
    	
        $output = array();
		if(!isset($request['type'] )){
			$output['status'] = FALSE;
			$output['response']['messages'][] = ERROR_INVALID_INPUT;
			$output['statusCode'] = STATUS_BAD_REQUEST;
			return $output;
		}
		$this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($request);
        $this->ci->form_validation->set_rules('type', 'type', 'required_strict');
        if (!empty($request) && $this->ci->form_validation->run() == FALSE) {
        	$validationStatus = $this->ci->form_validation->get_errors();
        	$output['status'] = FALSE;
        	$output['response']['messages'] = $validationStatus['message'];
        	$output['statusCode'] = STATUS_BAD_REQUEST;
        	return $output;
        } else {
                $this->ci->Messagetemplate_model->resetVariable();
	        $selectInput['template'] = $this->ci->Messagetemplate_model->template;
	        $selectInput['fromemailid'] = $this->ci->Messagetemplate_model->fromemailid;
	        $this->ci->Messagetemplate_model->setSelect($selectInput);
	//fetching Event signup Email template  & not deleted
	        $where[$this->ci->Messagetemplate_model->type] = $request['type'];
	        $where[$this->ci->Messagetemplate_model->mode] = $request['mode'];
	        $this->ci->Messagetemplate_model->setWhere($where);
	//Order by array
	        $orderBy = array();
	        $orderBy[] = $this->ci->Messagetemplate_model->id;
	        $this->ci->Messagetemplate_model->setOrderBy($orderBy);
	       $templateDetails = $this->ci->Messagetemplate_model->get();
	     if(is_array($templateDetails)){
		        if (count($templateDetails) > 0) {
		        	$output['status'] = TRUE;
		        	$output['response']['eventsignupTemplate'] = $templateDetails;
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

    //Getting template details
    public function getTemplateDetail($inputArray,$multiple='') {
        $this->ci->load->model('Messagetemplate_model');
        //Promoter Code is already there or not
        $this->ci->Messagetemplate_model->resetVariable();
        $select['id'] = $this->ci->Messagetemplate_model->id;
        $select['template'] = $this->ci->Messagetemplate_model->template;
        $select['fromemailid'] = $this->ci->Messagetemplate_model->fromemailid;
        $select['params'] = $this->ci->Messagetemplate_model->params;
        $this->ci->Messagetemplate_model->setSelect($select);
        $where[$this->ci->Messagetemplate_model->type] = $inputArray['type'];
        $where[$this->ci->Messagetemplate_model->mode] = $inputArray['mode'];
        if(isset($inputArray['eventid']) && $inputArray['eventid']!=''){
            $where[$this->ci->Messagetemplate_model->eventid] = $inputArray['eventid'];
        }
        $where[$this->ci->Messagetemplate_model->deleted] = 0;
        $this->ci->Messagetemplate_model->setWhere($where);
        $this->ci->Messagetemplate_model->setWhereIns(array());
        $templateDetail = $this->ci->Messagetemplate_model->get();
        if($multiple==1){
            $templateDetail = parent::createResponse(TRUE, array(), STATUS_OK, 0, 'templateDetail', $templateDetail);
        }else{
            $templateDetail = parent::createResponse(TRUE, array(), STATUS_OK, 0, 'templateDetail', $templateDetail[0]);
        }
        return $templateDetail;
    }
    
}

?>
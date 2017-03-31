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

class Sentmessage_handler extends Handler {

    var $ci;
    var $sentmessageHandler;

    public function __construct() {
        parent::__construct();
        $this->ci = parent::$CI;
        $this->ci->load->library('form_validation');
        $this->ci->load->model('Sentmessage_model');
        $this->ci->load->model('Messagetemplate_model');
    }

    public function getEventsignupSentMessages($request) {
        $output = array();
        if (!isset($request['eventsignupid'])) {
            $output['status'] = FALSE;
            $output['response']['messages'][] = ERROR_INVALID_INPUT;
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($request);
        $this->ci->form_validation->set_rules('eventsignupid', 'eventsignupid', 'required_strict');
        if (!empty($request) && $this->ci->form_validation->run() == FALSE) {
            $validationStatus = $this->ci->form_validation->get_errors();
            $output['status'] = FALSE;
            $output['response']['messages'] = $validationStatus['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        } else {
            $selectInput['messageid'] = $this->ci->Sentmessage_model->messageid;
            $this->ci->Sentmessage_model->setSelect($selectInput);
            //fetching Event signup Email template  & not deleted
            $where[$this->ci->Sentmessage_model->eventsignupid] = $request['eventsignupid'];
            $groupBy = array("messageid");
            $this->ci->Sentmessage_model->setWhere($where);
         //   $this->ci->Sentmessage_model->setGroupBy($groupBy);
            //Order by array
            $orderBy = array();
            $orderBy[] = $this->ci->Sentmessage_model->id;
            $this->ci->Sentmessage_model->setOrderBy($orderBy);
            $messageDetails = $this->ci->Sentmessage_model->get();
            if ($messageDetails && count($messageDetails) > 0) {
                foreach ($messageDetails as $key => $value) {
                    $messageIds[] = $value['messageid'];
                }
                $messageIds = array_unique($messageIds);
                $messageTemplate['type'] = $this->ci->Messagetemplate_model->type;
                $this->ci->Messagetemplate_model->setSelect($messageTemplate);
                //fetching Event signup Email template  & not deleted
                $wherein[$this->ci->Messagetemplate_model->id] = $messageIds;
                $this->ci->Messagetemplate_model->setWhereIns($wherein);
                $templateDetails = $this->ci->Messagetemplate_model->get();
            }else{
            	$output['status'] = TRUE;
            	$output['response']['messages'][] = ERROR_NO_DATA;
            	$output['statusCode'] = STATUS_NO_DATA;
            	return $output;
            }
            if (is_array($templateDetails)) {
                if (count($templateDetails) > 0) {
                    $output['status'] = TRUE;
                    $output['response']['sentmessages'] = $templateDetails;
                    $output['statusCode'] = STATUS_OK;
                    return $output;
                } else {
                    $output['status'] = TRUE;
                    $output['response']['messages'][] = ERROR_NO_DATA;
                    $output['statusCode'] = STATUS_OK;
                    return $output;
                }
            } else {
                $output['status'] = FALSE;
                $output['response']['messages'][] = ERROR_INTERNAL_DB_ERROR;
                $output['statusCode'] = STATUS_SERVER_ERROR;
                return $output;
            }
        }
    }
    
    //Creating sent message details in sentmessage table
    public function insertSentMessageDetail($inputArray) {
        $this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('userId','user Id','is_natural_no_zero');    
        $this->ci->form_validation->set_rules('messageid','message Id','is_natural_no_zero');   
        if ($this->ci->form_validation->run() == FALSE) {            
            $response = $this->ci->form_validation->get_errors();
            $output['status'] = FALSE;
            $output['response']['messages'] = $response['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }        
        $this->ci->load->model('Sentmessage_model');
        //If user id is passed it will take, otherwise it will take from session
        $createMessage[$this->ci->Sentmessage_model->userid] = (isset($inputArray['userId']))?($inputArray['userId']):getSessionUserId();
        
        //If it take from messsage template,then messageid will be there
        if (isset($inputArray['messageid'])) {
            $createMessage[$this->ci->Sentmessage_model->messageid] = $inputArray['messageid'];
        }
        //If a transcation happens then eventsignupid will be there
        if (isset($inputArray['eventsignupid'])) {
            $createMessage[$this->ci->Sentmessage_model->eventsignupid] = $inputArray['eventsignupid'];
        }
        $createMessage[$this->ci->Sentmessage_model->status] = $inputArray['status'];
        $this->ci->Sentmessage_model->setInsertUpdateData($createMessage);
        $createdMessageId = $this->ci->Sentmessage_model->insert_data();
        if($createdMessageId){
            $output['status'] = TRUE;
            $output['response']['messages'][] = SUCCESS_ADDED_IN_SENTMESSAGE;
            $output['statusCode'] = STATUS_CREATED;
            return $output;
        }else{
            $output['status'] = FALSE;
            $output['response']['messages'][] = ERROR_SOMETHING_WENT_WRONG;
            $output['statusCode'] = STATUS_SERVER_ERROR;
            return $output;
        }        
    }    
    
}

?>
<?php

/**
 * alerts related business logic will be defined in this class
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

class Alert_handler extends Handler {

    var $ci;

    public function __construct() {
        parent::__construct();
        $this->ci = parent::$CI;
        $this->ci->load->model('Alert_model');
    }

    /**
     * To get the Comment
     */
    public function add() {
        $userId = $this->ci->customsession->getData('userId');
        if ($userId > 0) {
            $enableAlertArr = $this->ci->config->item('default_alert_options');
            $insertAlerts = array();
            foreach ($enableAlertArr as $type => $value) {
                $data[$this->ci->Alert_model->userid] = $userId;
                $data[$this->ci->Alert_model->type] = $type;
                $data[$this->ci->Alert_model->status] = $value;
                $insertAlerts[] = $data;
            }
            $this->ci->Alert_model->setInsertUpdateData($insertAlerts);
            $insertCount = $this->ci->Alert_model->insertMultiple_data();
//            echo $this->ci->db->last_query();
//            exit;
        } else {
            $output['status'] = false;
            $output['response']['messages'] = array(ERROR_NO_SESSION);
            $output['response']['total'] = 0;
            $output['statusCode'] = STATUS_INVALID_SESSION;
            return $output;
        }
        $output['status'] = TRUE;
        //$output['response']['commentList'] = $commentResponse;
        $output['response']['messages'] = array(SUCCESS_ALERT_SET);
        $output['response']['total'] = $insertCount;
        $output['statusCode'] = STATUS_CREATED;
        return $output;
    }

    public function getAlerts($inputArray) {
        parent::$CI->form_validation->pass_array($inputArray);
        parent::$CI->form_validation->set_rules('userId', 'User Id', 'required_strict|is_natural_no_zero');
        if (parent::$CI->form_validation->run() === FALSE) {
            $errorMessages = $this->ci->form_validation->get_errors();
            $output['status'] = FALSE;
            $output['response']['messages'] = $errorMessages['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $this->ci->Alert_model->resetVariable();
        $selectInput['id'] = $this->ci->Alert_model->id;
        $selectInput['userid'] = $this->ci->Alert_model->userid;
        $selectInput['type'] = $this->ci->Alert_model->type;
        $selectInput['status'] = $this->ci->Alert_model->status;
        $this->ci->Alert_model->setSelect($selectInput);
        $where[$this->ci->Alert_model->userid] = $inputArray['userId'];
        $this->ci->Alert_model->setWhere($where);
        $alertDetals = $this->ci->Alert_model->get();
        $Info = commonHelperGetIdArray($alertDetals, 'type');
        if ($Info) {
            $output['status'] = TRUE;
            $output['response']['total'] = count($alertDetals);
            $output['response']['alertDetails'] = $Info;
            $output['statusCode'] = STATUS_OK;
            return $output;
        } else {
            $output['status'] = TRUE;
            $output['response']['total'] = 0;
            $output["response"]["messages"][] = ERROR_NO_DATA;
            $output['statusCode'] = STATUS_OK;
            return $output;
        }
    }

    public function alertInsert($inputArray) {
        $alertInsert['userid'] = $inputArray['userid'];
        $alertInsert['type'] = $inputArray['type'];
        $alertInsert['status'] = $inputArray['status'];

        $this->ci->Alert_model->setInsertUpdateData($alertInsert);
        $response = $this->ci->Alert_model->insert_data();
        if ($response) {
            $output['status'] = TRUE;
            $output["response"]["messages"][] = "Alert options are inserted";
            $output['statusCode'] = STATUS_CREATED;
            return $output;
        }
        $output['status'] = FALSE;
        $output["response"]["messages"][] = SOMETHING_WENT_WRONG;
        $output['statusCode'] = STATUS_SERVER_ERROR;
        return $output;
    }

    public function alertUpdate($inputArray) {
        $this->ci->Alert_model->resetVariable();
        $alertUpdate['status'] = $inputArray['status'];
        $this->ci->Alert_model->setInsertUpdateData($alertUpdate);
        $where[$this->ci->Alert_model->userid] = $inputArray['userid'];
        $where[$this->ci->Alert_model->type] = $inputArray['type'];
        $this->ci->Alert_model->setWhere($where);
        $response = $this->ci->Alert_model->update_data();
        if ($response) {
            $output['status'] = TRUE;
            $output["response"]["messages"][] = UPDATE_ALERTS;
            $output['statusCode'] = STATUS_UPDATED;
            return $output;
        }
        $output['status'] = FALSE;
        $output["response"]["messages"][] = SOMETHING_WENT_WRONG;
        $output['statusCode'] = STATUS_SERVER_ERROR;
        return $output;
    }

}

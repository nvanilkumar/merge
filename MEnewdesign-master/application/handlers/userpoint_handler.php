<?php

/**
 * User point related business logic will be defined in this class
 *
 * @package		CodeIgniter
 * @author		Qison  Dev Team
 * @copyright	Copyright (c) 2015, MeraEvents.
 * @Version		Version 1.0
 * @Since       Class available since Release Version 1.0 
 * @Created     18-08-2015
 * @Last Modified 18-08-2015
 */
require_once (APPPATH . 'handlers/handler.php');


class Userpoint_handler extends Handler {

    var $ci;

    public function __construct() {
        parent::__construct();
        $this->ci = parent::$CI;
        $this->ci->load->model('Userpoint_model');
    }

    /*
     * Function to add the user point data
     *
     * @access	public
     * @param	$inputArray contains
     *          - userid (integer)
     *          - eventsignupid (integer)
     *          - points (float)
     */

    public function addUserPoint($inputArray) {        
        $this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('userId', 'user id', 'trim|xss_clean|required_strict');
        $this->ci->form_validation->set_rules('eventSignupId', 'event signup id', 'trim|xss_clean|required_strict');

        if ($this->ci->form_validation->run() === FALSE) {
            $response = $this->ci->form_validation->get_errors('message');
            $output['status'] = FALSE;
            $output['response']['messages'] = $response['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $this->ci->Userpoint_model->resetVariable();	
        $createEventSignup[$this->ci->Userpoint_model->userid] = $inputArray['userId'];
        $createEventSignup[$this->ci->Userpoint_model->eventsignupid] = $inputArray['eventSignupId'];
        $createEventSignup[$this->ci->Userpoint_model->points] = $inputArray['points'];
        if(isset($inputArray['type'])){
            $createEventSignup[$this->ci->Userpoint_model->type] = $inputArray['type'];
        }

        $this->ci->Userpoint_model->setInsertUpdateData($createEventSignup);
        $userPointId = $this->ci->Userpoint_model->insert_data(); //Inserting into table and getting inserted id
        if ($userPointId) {
            //Inserting record in the userpoint table
            $output['status'] = TRUE;
            $output['response']['messages'][] = SUCCESS_USERPOINT_ADDED;
            $output['response']['userPointId'] = $userPointId;
            $output['statusCode'] = STATUS_OK;
            return $output;
        }
        $output['status'] = FALSE;
        $output['response']['messages'][] = SOMETHING_WRONG;
        $output['statusCode'] = STATUS_SERVER_ERROR;
        return $output;
    }

    // Get User points For the Event Signup
    public function getAffiliateUserpoints($inputArray) {

        $this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('eventsignupId', 'event signup id', 'trim|xss_clean');
        $this->ci->form_validation->set_rules('userId', 'user id', 'is_natural_no_zero');
        if ($this->ci->form_validation->run() === FALSE) {
            $response = $this->ci->form_validation->get_errors('message');
            $output['status'] = FALSE;
            $output['response']['messages'] = $response['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $this->ci->Userpoint_model->resetVariable();	
        $select['id'] = $this->ci->Userpoint_model->id;
        $select['points'] = $this->ci->Userpoint_model->points;
        $select['type'] = $this->ci->Userpoint_model->type;
        $select['eventsignupid'] = $this->ci->Userpoint_model->eventsignupid;
        $select['userid'] = $this->ci->Userpoint_model->userid;
        if(isset($inputArray['userId'])){
        $where[$this->ci->Userpoint_model->userid] = $inputArray['userId'];
        }
        if(isset($inputArray['eventsignupId'])){
        $where[$this->ci->Userpoint_model->eventsignupid] = $inputArray['eventsignupId'];
        }
        if(isset($inputArray['type'])){
            $where[$this->ci->Userpoint_model->type] = $inputArray['type'];
        }
        $this->ci->Userpoint_model->setSelect($select);
        $this->ci->Userpoint_model->setWhere($where);
        //$this->ci->Userpoint_model->setRecords(1);
        $userPoints = $this->ci->Userpoint_model->get();
        if (count($userPoints) > 0) {
            $output['status'] = true;
            $output['response']['userPoints'] = $userPoints;
            $output['response']['messages'] = '';
            $output['response']['total'] = count($userPoints);
            $output['statusCode'] = STATUS_OK;
            return $output;
        }
        $output['status'] = true;
        $output['response']['messages'][] = ERROR_NO_DATA;
        $output['statusCode'] = STATUS_OK;
        $output['response']['total'] = 0;
        return $output;
    }
    
}

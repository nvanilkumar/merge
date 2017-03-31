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

class Comment_handler extends Handler {

    //var $searchHandler;
    var $ci;

    public function __construct() {
        parent::__construct();
        $this->ci = parent::$CI;
        $this->ci->load->model('Comment_model');
    }

    /**
     * To get the Comment
     */
    public function getCommentByEventsignupIds($inputArray) {
        $validationStatus = $this->validateGetComment($inputArray);
        if ($validationStatus['error']) {
            $output['status'] = FALSE;
            $output['response']['messages'] = $validationStatus['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $eventsignupIds = $inputArray['eventsignupids'];
        $commentType = $inputArray['commenttype'];
        $this->ci->Comment_model->resetVariable();
        $selectInput['id'] = $this->ci->Comment_model->id;
        $selectInput['type'] = $this->ci->Comment_model->type;
        $selectInput['comment'] = $this->ci->Comment_model->comment;
        $selectInput['eventsignupid'] = $this->ci->Comment_model->eventsignupid;
        $this->ci->Comment_model->setSelect($selectInput);
        $where_in[$this->ci->Comment_model->eventsignupid] = $eventsignupIds;
        $where[$this->ci->Comment_model->type] = $commentType;
        $this->ci->Comment_model->setWhere($where);
        $this->ci->Comment_model->setWhereIns($where_in);
        $commentResponse = $this->ci->Comment_model->get();
        //echo $this->ci->db->last_query();exit;
        if (count($commentResponse) == 0) {
            $output['status'] = TRUE;
            $output['response']['messages'][] = ERROR_NO_DATA;
            $output['response']['total'] = 0;
            $output['statusCode'] = STATUS_OK;
            return $output;
        }

        $output['status'] = TRUE;
        $output['response']['commentList'] = $commentResponse;
        $output['response']['messages'] = array();
        $output['response']['total'] = count($commentResponse);
        $output['statusCode'] = STATUS_OK;
        return $output;
    }

    //To validate the cityStateInsert 
    public function validateGetComment($inputs) {
        $errorMessages = array();
        $this->ci->form_validation->pass_array($inputs);
        $this->ci->form_validation->set_rules('eventsignupids', 'eventsignupids', 'required_strict');
        $this->ci->form_validation->set_rules('commenttype', 'commenttype', 'required_strict');
        if ($this->ci->form_validation->run() === FALSE) {
            $errorMessages = $this->ci->form_validation->get_errors();
            return $errorMessages;
        }
        $errorMessages['error'] = FALSE;
        return $errorMessages;
    }
}

<?php

/**
 * Collaborator related business logic will be defined in this class
 *
 * @package		CodeIgniter
 * @author		Qison  Dev Team
 * @copyright	Copyright (c) 2015, MeraEvents.
 * @Version		Version 1.0
 * @Since       Class available since Release Version 1.0 
 * @Created     11-06-2015
 * @Last Modified 11-06-2015
 */
require_once (APPPATH . 'handlers/handler.php');

class Collaboratoraccess_handler extends Handler {

    var $ci, $userHandler;

    public function __construct() {
        parent::__construct();
        $this->ci = parent::$CI;
        $this->ci->load->model('Collaboratoraccess_model');
//        $this->eventHandler = new Event_handler();
        $this->userHandler = new User_handler();
//        $this->ticketHandler = new Ticket_handler();
    }

    public function insert($inputArray) {

        $collaboratorId = $inputArray['collaboratorid'];
        $moduleData = $inputArray['modules'];
        $insertData = array();
        $insertCount = 0;
        foreach ($moduleData as $key => $value) {
            $data['collaboratorid'] = $collaboratorId;
            $data['module'] = $key;
            $data['status'] = $value;
            $insertData[] = $data;
        }
        if (count($insertData) > 0) {
            $this->ci->Collaboratoraccess_model->resetVariable();
            $this->ci->Collaboratoraccess_model->setInsertUpdateData($insertData);
            $insertCount = $this->ci->Collaboratoraccess_model->insertMultiple_data();
        }
        if ($insertCount > 0) {
            $this->ci->customsession->setData('collaborateAddFlashMessage',SUCCESS_ADDED_COLLABORATORACCESS);
            $output['status'] = TRUE;
            $output['response']['collaboratoraccessData'] = array('insertStatus' => TRUE);
            $output['response']['total'] = $insertCount;
            $output['response']['messages'][] = SUCCESS_ADDED_COLLABORATORACCESS;
            $output['statusCode'] = STATUS_OK;
            return $output;
        }
        $output = parent::createResponse(FALSE, ERROR_SOMETHING_WENT_WRONG, STATUS_SERVER_ERROR);
        return $output;
    }

    public function get($inputArray) {
        $this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('collaboratorids', 'collaboratorids', 'required_strict|is_array');
        if ($this->ci->form_validation->run() == FALSE) {
            $response = $this->ci->form_validation->get_errors('message');
            $output['status'] = FALSE;
            $output['response']['messages'] = $response['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $collaboratorIds = $inputArray['collaboratorids'];
        //$select['id'] = $this->ci->Collaboratoraccess_model->id;
        $this->ci->Collaboratoraccess_model->resetVariable();
        $select['modules'] = 'GROUP_CONCAT(' . $this->ci->Collaboratoraccess_model->module . ')';
        $select['collaboratorid'] = $this->ci->Collaboratoraccess_model->collaboratorid;
        $whereIn[$this->ci->Collaboratoraccess_model->collaboratorid] = $collaboratorIds;
        $where[$this->ci->Collaboratoraccess_model->status] = 1;
        $this->ci->Collaboratoraccess_model->setSelect($select);
        $this->ci->Collaboratoraccess_model->setWhere($where);
        $this->ci->Collaboratoraccess_model->setWhereIns($whereIn);
        $groupBy[] = $this->ci->Collaboratoraccess_model->collaboratorid;
        $this->ci->Collaboratoraccess_model->setGroupBy($groupBy);
        $collaboratorAcessList = $this->ci->Collaboratoraccess_model->get();
        if (count($collaboratorAcessList) == 0) {
            $output['status'] = TRUE;
            $output['response']['total'] = 0;
            $output['response']['messages'][] = ERROR_NO_RECORDS;
            return $output;
        }
        $output['status'] = TRUE;
        $output['response']['collaboratorAcessList'] = ($collaboratorAcessList);
        $output['response']['total'] = count($collaboratorAcessList);
        $output['response']['messages'] = [];
        return $output;
    }

    public function update($inputArray) {
        $this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('collaboratorid', 'collaboratorid', 'is_natural_no_zero');
        $this->ci->form_validation->set_rules('modules', 'modules', 'required_strict|is_array');
        if ($this->ci->form_validation->run() == FALSE) {
            $response = $this->ci->form_validation->get_errors('message');
            $output['status'] = FALSE;
            $output['response']['messages'] = $response['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $collaboratorId = $inputArray['collaboratorid'];
        $moduleData = $inputArray['modules'];
        $updateData = array();
        $updateCount = 0;
        foreach ($moduleData as $key => $value) {
            //$data['collaboratorid'] = $collaboratorId;
            $data['module'] = $key;
            $data['status'] = $value;
            $updateData[] = $data;
        }
        if (count($updateData) > 0) {
            $this->ci->Collaboratoraccess_model->resetVariable();
            $this->ci->Collaboratoraccess_model->setInsertUpdateData($updateData);
            $this->ci->Collaboratoraccess_model->setUpdateBatchColumn($this->ci->Collaboratoraccess_model->module);
            $where[$this->ci->Collaboratoraccess_model->collaboratorid] = $collaboratorId;
            $this->ci->Collaboratoraccess_model->setWhere($where);
            $updateCount = $this->ci->Collaboratoraccess_model->updateMultiple_data();
        }
        // if ($updateCount > 0)
        $this->ci->customsession->setData('collaborateEditedFlashMessage',SUCCESS_UPDATED_COLLABORATORACCESS);        
        $output['status'] = TRUE;
        $output['response']['collaboratoraccessData'] = array('updateStatus' => TRUE);
        $output['response']['total'] = $updateCount;
        $output['response']['messages'][] = SUCCESS_UPDATED_COLLABORATORACCESS;
        $output['statusCode'] = STATUS_OK;
        return $output;
        //  }
    }

}

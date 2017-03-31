<?php

require_once (APPPATH . 'handlers/handler.php');

class Userdevicedetail_handler extends Handler {

    var $ci;

    public function __construct() {
        parent::__construct();
        $this->ci = parent::$CI;
        $this->ci->load->model('Userdevicedetail_model');
    }

    public function getDeviceDetails($inputArray) {
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('deviceId', 'Device Id', 'alphanumeric|min_length[1]|max_length[60]');
        $this->ci->form_validation->set_rules('deviceType', 'Device Type', 'specialname|min_length[1]|max_length[50]');
        if ($this->ci->form_validation->run() === FALSE) {
            $error_messages = $this->ci->form_validation->get_errors('message');
            $output['status'] = FALSE;
            $output['response']['messages'] = $error_messages;
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $this->ci->Userdevicedetail_model->resetVariable();	
        $selectArray['id'] = $this->ci->Userdevicedetail_model->id;
        $selectArray['userid'] = $this->ci->Userdevicedetail_model->userid;

        $this->ci->Userdevicedetail_model->setSelect($selectArray);
        //setting where condition
        $whereArray = array();
        $whereArray[$this->ci->Userdevicedetail_model->userid] = $inputArray['userId'];
        if (isset($inputArray['deviceId'])) {
            $whereArray[$this->ci->Userdevicedetail_model->name] = 'deviceid';
            $whereArray[$this->ci->Userdevicedetail_model->value] = $inputArray['deviceId'];
        }
        $this->ci->Userdevicedetail_model->setWhere($whereArray);
        $deviceDetails = $this->ci->Userdevicedetail_model->get();
        if ($deviceDetails) {
            $output['status'] = TRUE;
            $output['response']['detail'] = $deviceDetails;
            $output['response']['messages'] = array();
            $output['response']['total'] = count($deviceDetails);
            $output['statusCode'] = 200;
            return $output;
        } else {
            $output['status'] = TRUE;
            $output['response']['messages'][] = ERROR_NO_DATA;
            $output['response']['total'] = 0;
            $output['statusCode'] = STATUS_NO_DATA;
            return $output;
        }
    }

    public function insertDeviceDetails($inputArray) {
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('deviceId', 'Device Id', 'alphanumeric|min_length[1]|max_length[60]');
        $this->ci->form_validation->set_rules('deviceType', 'Device Type', 'specialname|min_length[1]|max_length[50]');
        if ($this->ci->form_validation->run() === FALSE) {
            $error_messages = $this->ci->form_validation->get_errors('message');
            $output['status'] = FALSE;
            $output['response']['messages'] = $error_messages;
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $this->ci->Userdevicedetail_model->resetVariable();	
        if (isset($inputArray['deviceId'])) {
            $insertArray[$this->ci->Userdevicedetail_model->name] = 'deviceid';
            $insertArray[$this->ci->Userdevicedetail_model->value] = $inputArray['deviceId'];
        }
        if (isset($inputArray['deviceType'])) {
            $insertArray[$this->ci->Userdevicedetail_model->name] = 'devicetype';
            $insertArray[$this->ci->Userdevicedetail_model->value] = $inputArray['deviceType'];
            $insertArray[$this->ci->Userdevicedetail_model->parentid] = $inputArray['parentId'];
        }
        $insertArray[$this->ci->Userdevicedetail_model->userid] = $inputArray['userId'];
        $this->ci->Userdevicedetail_model->setInsertUpdateData($insertArray);
        $insertDeviceDetails = $this->ci->Userdevicedetail_model->insert_data();
        if ($insertDeviceDetails) {
            $output = parent::createResponse(TRUE, array(),STATUS_CREATED,1,'insertedId',$insertDeviceDetails);
            return $output;
        } else {
            $output = parent::createResponse(FALSE, ERROR_SOMETHING_WENT_WRONG, STATUS_SERVER_ERROR);
            return $output;
        }
    }

    public function manipulateUserDeviceDetails($inputArray) {
        if (isset($inputArray['deviceType'])) {
            if (!isset($inputArray['deviceId'])) {
                $output = parent::createResponse(FALSE, "Please enter device id", STATUS_BAD_REQUEST);
                return $output;
            }
        }
        $userDeviceData = $this->getDeviceDetails($inputArray);
        if ($userDeviceData['response']['total'] == 0) {//If device Id and that device type is not there
            if (isset($inputArray['deviceId'])) {
                $inputs['deviceId'] = $inputArray['deviceId'];
                $inputs['userId'] = $inputArray['userId'];
                $userDeviceOutput = $this->insertDeviceDetails($inputs);
                if (isset($inputArray['deviceType'])) {
                    $inputArray1['deviceType'] = $inputArray['deviceType'];
                    $inputArray1['userId'] = $inputArray['userId'];
                    $inputArray1['parentId'] = $userDeviceOutput['response']['insertedId'];
                    $userDeviceOutput = $this->insertDeviceDetails($inputArray1);
                }
            }
            $output = parent::createResponse(TRUE, "Successfully inserted the device details", STATUS_OK);
            return $output;
        }else{
            $output = parent::createResponse(TRUE, "User device details are already inserted", STATUS_OK);
            return $output;
        }
    }
}

?>
<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require_once(APPPATH . 'libraries/REST_Controller.php');
require_once(APPPATH . 'handlers/userdevicedetail_handler.php');

class Configure extends REST_Controller {

    var $configureHandler;

    public function __construct() {
        parent::__construct();
    }
    
    public function saveMobileDeviceToken_post() {
        
        $inputArray = $this->input->post();
        $this->userdevicedetailHandler = new Userdevicedetail_handler();
        $saveDeviceTokenResonse = $this->userdevicedetailHandler->manipulateUserDeviceData($inputArray);

        $resultArray = array('response' => $saveDeviceTokenResonse['response']);
        $statusCode = $saveDeviceTokenResonse['statusCode'];
        $this->response($resultArray, $statusCode);
    }
}

?>
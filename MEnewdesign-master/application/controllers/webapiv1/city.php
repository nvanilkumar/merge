<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require_once (APPPATH . 'libraries/REST_Controller.php');
require_once (APPPATH . 'handlers/city_handler.php');
require_once (APPPATH . 'handlers/file_handler.php');

class City extends REST_Controller {

    public $cityHandler, $response, $fileHandler;

    public function __construct() {
        parent::__construct();
        $this->cityHandler = new City_handler();
        $this->fileHandler = new File_handler();
    }

    public function list_post() {
        $inputArray = $this->post();
        $cityList = $this->cityHandler->getCityList($inputArray);
        $resultArray = array('response' => $cityList['response']);

        $this->response($resultArray, $cityList['statusCode']);
    }

}

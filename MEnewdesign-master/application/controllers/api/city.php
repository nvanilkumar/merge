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

    public function list_get() {
        $inputArray = $this->get();
        $cityList = $this->cityHandler->getCityList($inputArray);
        $resultArray = array('response' => $cityList['response']);

        $this->response($resultArray, $cityList['statusCode']);
    }

    /*
     * Function to get the Cities based on Country and Keyword
     *
     * @access	public
     * @param	$inputArray contains
     * 				countryId - Integer
     * 				keyWord - varchar
     * @return	json encoded array
     */

    public function search_get() {
        $inputArray = $this->get();
        $cityList = $this->cityHandler->getCitySearch($inputArray);
        $resultArray = array('response' => $cityList['response']);
        $this->response($resultArray, $cityList['statusCode']);
    }

    public function eventCount_get() {
        $eventCountList = array();
        $inputArray = $this->get();
        if (!isset($inputArray['major'])) {
            $inputArray['major'] = 1;
        }
        $inputArray['ticketSoldout']=0;
        $inputArray['status']=1;
        $eventCountList = $this->cityHandler->getEventsCountByCites($inputArray);
        $resultArray = array('response' => $eventCountList['response']);
        $this->response($resultArray, $eventCountList['statusCode']);
    }

    public function file_post() {
        // print_r($_FILES['file']);exit;
        $input = array('sourcepath' => $_FILES['file']['tmp_name'], 'filename' => $_FILES['file']['name'], 'filetype' => $_FILES['file']['type'], 'destinationpath' => $this->post('destinationpath'));
        //print_r($input);
        $fileResponse = $this->fileHandler->uploadToS3($input);
        $resultArray = array('response' => $fileResponse['response']);
        $this->response($resultArray, $fileResponse['statusCode']);
    }

    // geting citys based on stateids from statecity table
    public function citysByState_get() {
        $inputArray = $this->get();
        $cityList = $this->cityHandler->getcitiesXstate($inputArray);
        $resultArr = array();
        $eventList = $cityList['response']['cityStateList']['response']['cityName'];
        $i = 0;
        foreach ($eventList as $event) {
            $resultArr[$i]['id'] = $event['id'];
            $resultArr[$i]['value'] = $event['name'];
            $i++;
        }
        $statusCode = 200;
        if ($resultArr['statusCode'] != '') {
            $statusCode = $resultArr['statusCode'];
        }
        $this->response($resultArr, $statusCode);
    }

}

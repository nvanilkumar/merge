<?php

/**
 * State related business logic will be defined in this class
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
require_once (APPPATH . 'handlers/country_handler.php');

class State_handler extends Handler {

    var $ci;

    public function __construct() {
        parent::__construct();
        $this->ci = parent::$CI;
        $this->ci->load->model('State_model');
    }

    public function getStateList($inputArray) {

        if (!isset($inputArray['countryId']) && !isset($inputArray['countryName'])) {
            $output['status'] = FALSE;
            $output['response']['messages'][] = ERROR_NO_COUNTRY_ID_AND_NAME;
            $output['statusCode'] = STATUS_NO_DATA;
            return $output;
        }

        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('countryId', 'countryId', 'is_natural_no_zero');
        $this->ci->form_validation->set_rules('countryName', 'countryName', 'name');
        if ($this->ci->form_validation->run() == FALSE) {
            $errorMessages = $this->ci->form_validation->get_errors('message');
            $output['status'] = FALSE;
            $output['response']['messages'] = $errorMessages['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }

        if (isset($inputArray['countryName']) && !isset($inputArray['countryId'])) {
            $this->countryHandler = new Country_handler();
            $countryId = $this->countryHandler->getCountryIdByName($inputArray);
            if ($countryId) {
                $inputArray['countryId'] = $countryId[0]['id'];
            } else {
                $output['status'] = FALSE;
                $output['response']['messages'][] = ERROR_INVALID_COUNTRY;
                $output['statusCode'] = STATUS_INVALID;
                return $output;
            }
        }
        $this->ci->State_model->resetVariable();
        //for selecting and retrieving the state table data
        $this->ci->State_model->setTableName('state');
        $selectArray[$this->ci->State_model->id] = $this->ci->State_model->id;
        $selectArray[$this->ci->State_model->name] = $this->ci->State_model->name;
        //$this->ci->State_model->setSelect($selectArray);

        $whereArray[$this->ci->State_model->status] = 1;
        $whereArray[$this->ci->State_model->deleted] = 0;
        $whereArray[$this->ci->State_model->countryId] = $inputArray['countryId'];
        //$this->ci->State_model->setWhere($whereArray);

        $returnArray = TRUE;
        $stateList = $this->ci->State_model->get($selectArray, $whereArray, $returnArray);

        if ($stateList) {
            $output['status'] = TRUE;
            $output['response']['stateList'] = $stateList;
            $output['response']['messages'] = array();
            $output['response']['total'] = count($stateList);
            $output['statusCode'] = STATUS_OK;
            return $output;
        } else {
            $output['status'] = TRUE;
            $output['response']['messages'][] = ERROR_NO_STATES;
            $output['statusCode'] = STATUS_OK;
            return $output;
        }
    }

    /*
     * Function to get the State Details based on State Id
     *
     * @access	public
     * @param	$inputArray contains
     * 				stateid - Integer
     * @return	array
     */

    public function getStateListById($inputArray) {
        $this->ci->load->library('memcached_library');
        $selectInput = array();
        $this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('stateId', 'StateId', 'required_strict|is_natural_no_zero');

        if (!empty($inputArray) && $this->ci->form_validation->run() == FALSE) {
            $errorMsg = $this->ci->form_validation->get_errors('message');
            $resultArray = array('message' => $errorMsg['message'][0], 'status' => false);
        } else {
            $this->ci->State_model->resetVariable();
            $selectInput['id'] = $this->ci->State_model->id;
            $selectInput['name'] = $this->ci->State_model->name;
            $selectInput['countryid'] = $this->ci->State_model->countryId;
            $where = array();
            if (!isset($inputArray['nostatus']) || !$inputArray['nostatus']) {
                $where[$this->ci->State_model->status] = 1;
            }
            $where[$this->ci->State_model->id] = $inputArray['stateId'];

            $resultArray = array();
            $response = $this->ci->State_model->get($selectInput, $where);

            if ($response) {
                //converting object's to array's
                foreach ($response as $v) {
                    $responseArray[] = (array) $v;
                }
                $data['stateList'] = $responseArray;
                $resultArray = array('response' => $data, 'status' => true);
                $resultArray['response']['total'] = count($response);
                $resultArray['statusCode'] = STATUS_OK;
            } else {
                $resultArray['status'] = TRUE;
                $resultArray['message'] = ERROR_NO_DATA;
                $resultArray['response']['total'] = 0;
                $resultArray['response'] = array();
                $output['statusCode'] = STATUS_OK;
            }
        }
        return $resultArray;
    }

    /*
     * Function to get the State list
     *
     * @access	public
     * @param	$inputArray contains
     * 				KeyWord - String
     * 				Limit - Integer
     * @return	array
     */

    public function searchByKeyword($inputArray) {
        $result = $select = $where = $stateList = $likeArray = array();

        //Validating the input parameter keyword
        $keyword = $inputArray['keyWord'];
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('countryId', 'countryId', 'is_natural_no_zero');
        $this->ci->form_validation->set_rules('countryName', 'countryName', 'name');
        $this->ci->form_validation->set_rules('keyWord', 'keyWord', 'required_strict|keyWordRule');

        if ($this->ci->form_validation->run() == FALSE) {
            $errorMsg = $this->ci->form_validation->get_errors('message');
            $output['status'] = FALSE;
            $output['response']['messages'] = $errorMsg['message'];
            if ($inputArray['countryName'] == '' && $inputArray['countryId'] == '') {
                $output['response']['messages'][] = ERROR_NO_COUNTRY_ID_AND_NAME;
            }
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }

        if (isset($inputArray['countryName']) && $inputArray['countryName'] == '' && isset($inputArray['countryId']) && $inputArray['countryId'] == '') {
            $output['status'] = FALSE;
            $output['response']['messages'][] = ERROR_NO_COUNTRY_ID_AND_NAME;
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }

        $countryId = 0;

        // If `countryId` passed then directly put it in where
        //	Or search with `countryName` and get the `countryId`
        if (isset($inputArray['countryId']) && $inputArray['countryId'] != '') {
            $countryId = $inputArray['countryId'];
        } elseif (isset($inputArray['countryName']) && $inputArray['countryName'] != '') {
            $this->countryHandler = new Country_handler();
            $countryRequest = array('keyWord' => $inputArray['countryName'], 'isNameExact' => true);
            $CountryArr = $this->countryHandler->searchByKeyword($countryRequest);
            if (!$CountryArr['status']) {
                $output['status'] = FALSE;
                $output['response']['messages'][] = ERROR_INVALID_COUNTRY;
                $output['statusCode'] = STATUS_INVALID_INPUTS;
                return $output;
            } else {
                $countryId = $CountryArr['response']['countryList'][0]['id'];
            }
        }

        //Getting the data from database
        $this->ci->State_model->resetVariable();
        $select['id'] = parent::$CI->State_model->id;
        $select['name'] = parent::$CI->State_model->name;
        $select['value'] = parent::$CI->State_model->name;
        //At the time of add event no need to check the status
        /* 	if (isset($inputArray['addEventCheck']) && $inputArray['addEventCheck'] === TRUE) {
          $where[parent::$CI -> State_model -> countryId] = $countryId;
          $where[parent::$CI -> State_model -> status] = 1;
          } else {
          $where[parent::$CI -> State_model -> status] = 1;
          $where[parent::$CI -> State_model -> countryId] = $countryId;
          $where[parent::$CI -> State_model -> deleted] = 0;
          } */
        $where[parent::$CI->State_model->status] = 1;
        $where[parent::$CI->State_model->countryId] = $countryId;
        $where[parent::$CI->State_model->deleted] = 0;
        // If we want to search the state name with exact match
        if (isset($inputArray['isNameExact']) && $inputArray['isNameExact']) {
            $where[parent::$CI->State_model->name] = $keyword;
        } else {
            $likeArray[parent::$CI->State_model->name] = $keyword;
        }

        $limit = isset($inputArray['limit']) ? trim($inputArray['limit']) : 0;
        $retrunArray = TRUE;
        $stateList = parent::$CI->State_model->get($select, $where, $retrunArray, $likeArray, $limit);
        //Setting the result
        if ($stateList) {
            $output['status'] = TRUE;
            $output['response']['stateList'] = $stateList;
            $output['response']['messages'] = array();
            $output['response']['total'] = count($stateList);
            $output['statusCode'] = STATUS_OK;
            return $output;
        } else {
            $output['status'] = TRUE;
            $output['response']['messages'][] = ERROR_NO_DATA;
            $output['response']['total'] = 0;
            $output['statusCode'] = STATUS_OK;
            return $output;
        }
    }

    //To Insert the state details
    public function stateInsert($inputs) {
        $validationStatus = $this->stateInsertValidation($inputs);
        if ($validationStatus['error'] == TRUE) {
            $output['status'] = FALSE;
            $output['response']['messages'] = $validationStatus['message'];
            $output['statusCode'] = 400;
            return $output;
        }

        //check the state name in our db
        $inputs['addEventCheck'] = TRUE;
        $inputs['countryId'] = $inputs['countryId'];
        $inputs['keyWord'] = $inputs['state'];
        $stateList = $this->searchByKeyword($inputs);
        if ($stateList['status'] && $stateList['response']['total'] > 0) {
            $output['status'] = TRUE;
            $output['response']['stateId'] = $stateList['response']['stateList'][0]['id'];
            $output['response']['message'] = array();
            $output['statusCode'] = STATUS_CREATED;
            return $output;
        }
        //Prepare the country insert array
        $insertStateArray = array();
        $this->ci->State_model->resetVariable();
        $insertStateArray[$this->ci->State_model->name] = $inputs['state'];
        $insertStateArray[$this->ci->State_model->countryId] = $inputs['countryId'];
        if(isset($inputs['googleapistate']) && $inputs['googleapistate'] == 1){
            $insertStateArray[$this->ci->State_model->status] = 1;//Directly inserting if it is from google api
        }else{
            $insertStateArray[$this->ci->State_model->status] = 2;//For review purpose
        }
        $insertStateArray[$this->ci->State_model->deleted] = 0;
        $this->ci->State_model->setInsertUpdateData($insertStateArray);
        $stateId = $this->ci->State_model->insert_data();
        $output['status'] = TRUE;
        $output['response']['stateId'] = $stateId;
        $output['response']['message'] = array();
        $output['statusCode'] = STATUS_CREATED;
        return $output;
    }

    //validate the input data
    public function stateInsertValidation($inputs) {
        $errorMessages = array();
        $this->ci->form_validation->pass_array($inputs);
        $this->ci->form_validation->set_rules('state', 'State Name', 'required_strict');
        $this->ci->form_validation->set_rules('countryId', 'Country Id', 'required_strict');
        if ($this->ci->form_validation->run() === FALSE) {
            $errorMessages = $this->ci->form_validation->get_errors();
            return $errorMessages;
        }

        $errorMessages['error'] = FALSE;
        return $errorMessages;
    }

}

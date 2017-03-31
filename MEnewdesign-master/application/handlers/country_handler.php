<?php

/**
 * Country related business logic will be defined in this class
 *
 * @package		CodeIgniter
 * @author		Qison  Dev Team
 * @copyright	Copyright (c) 2015, MeraEvents.
 * @Version		Version 1.0
 * @Since       Class available since Release Version 1.0
 * @Created     11-06-2015
 * @Last Modified 03-07-2015
 * @Last Modified by Sridevi
 */
require_once (APPPATH . 'handlers/handler.php');

class Country_handler extends Handler {

    var $ci;
    var $timezoneHandler;
    var $currencyHandler;
    var $fileHandler;

    public function __construct() {
        parent::__construct();
        $this->ci = parent::$CI;
        $this->ci->load->model('Country_model');
    }

    /*
     * Function to get the Country list
     *
     * @access	public
     * @param	$inputArray contains
     * 				string (major - 1 or 0)
     * @return	array
     */

    public function getCountryList($inputArray) {

        $selectInput = array();
        //$response = array();
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('major', 'major', 'enable|required_strict');

        if ($this->ci->form_validation->run() == FALSE) {
            $response = $this->ci->form_validation->get_errors('message');
            $output['status'] = FALSE;
            $output['response']['messages'] = $response['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }

        $featured = isset($inputArray['major']) ? $inputArray['major'] : '1';

        $cacheResults = FALSE;
        $memcacheEnabled = $this->ci->config->item('memcacheEnabled');
        if ($memcacheEnabled == TRUE) {//memcache is enabled
            $this->ci->load->library('memcached_library');
            //fetching data from memcache
            if ($featured == 1) {// major cities only
                $cacheResults = $this->ci->memcached_library->get(MEMCACHE_MAJOR_COUNTRY);
            } else {
                $cacheResults = $this->ci->memcached_library->get(MEMCACHE_ALL_COUNTRY);
            }
        }

        if ($cacheResults != FALSE) {// Data is not availeble in memcache.
            $total = count($cacheResults);
            $output['status'] = TRUE;
            $output['response']['countryList'] = $cacheResults;
            $output['response']['total'] = $total;
            $output['response']['messages'] = array();
            $output['statusCode'] = STATUS_OK;
            //return the output with the data, which is fetched from memecache
            return $output;
        }
        /*
         *  Data is not availeble in memcache, Fetching from Model.
         */
        //Select values
        $this->ci->Country_model->resetVariable();
        $selectInput['id'] = $this->ci->Country_model->id;
        $selectInput['name'] = $this->ci->Country_model->name;
        $selectInput['code'] = $this->ci->Country_model->code;
        $selectInput['shortName'] = $this->ci->Country_model->shortName;
        $selectInput['logoFileId'] = $this->ci->Country_model->logoFileId;
        $selectInput['timezoneId'] = $this->ci->Country_model->timezoneId;
        $selectInput['default'] = $this->ci->Country_model->default;
        $selectInput['defaultCurrencyId'] = $this->ci->Country_model->defaultCurrencyId;
        //where conditions
        $where = array($this->ci->Country_model->status => 1, $this->ci->Country_model->deleted => 0);
        if ($featured == 1) {
            $where[$this->ci->Country_model->featured] = 1;
        }
        //executing the query.
        $response = $this->ci->Country_model->get($selectInput, $where, true);

        if (count($response) == 0) {//No records are fetched
            $output['status'] = TRUE;
            $output['response']['messages'][] = ERROR_NO_DATA;
            $output['response']['total'] = 0;
            $output['statusCode'] = STATUS_OK;
            return $output;
        }
        $fileIdListAll = $timezoneIdListAll = $currencyIdListAll = array();
        // geting file path from file table through file handler


        foreach ($response as $tempResponse) {
            if ($tempResponse['logoFileId'] > 0) {
                $fileIdListAll[] = $tempResponse['logoFileId'];
            }
            if ($tempResponse['timezoneId'] > 0) {
                $timezoneIdListAll[] = $tempResponse['timezoneId'];
            }
            if ($tempResponse['defaultCurrencyId'] > 0) {
                $currencyIdListAll[] = $tempResponse['defaultCurrencyId'];
            }
        }

        $loop = 0;
        $fileIdList = array_unique($fileIdListAll);
        if (count($fileIdList) > 0) {
            require_once (APPPATH . 'handlers/file_handler.php');
            $this->fileHandler = new File_handler();
            $fileData = $this->fileHandler->getFileData(array('id', $fileIdList));
            if ($fileData['status'] && $fileData['response']['total'] > 0) {
                $fileDataTemp = commonHelperGetIdArray($fileData['response']['fileData']);
            }
        }

        $currencyIdList = array_unique($currencyIdListAll);
        if (count($currencyIdList) > 0) {
            require_once (APPPATH . 'handlers/currency_handler.php');
            $this->currencyHandler = new Currency_handler();
            $currencyData = $this->currencyHandler->getCurrencyList(array('idList' => $currencyIdList));
            if ($currencyData['status'] && $currencyData['response']['total'] > 0) {
                $currencyData = commonHelperGetIdArray($currencyData['response']['currencyList'], 'currencyId');
            }
        }

        $timezoneIdList = array_unique($timezoneIdListAll);
        if (count($timezoneIdList) > 0) {
            require_once (APPPATH . 'handlers/timezone_handler.php');
            $this->timezoneHandler = new Timezone_handler();
            $timezoneData = $this->timezoneHandler->timeZoneList(array('idList' => $timezoneIdList));
            if ($timezoneData['status'] && $timezoneData['response']['total'] > 0) {
                $timezoneData = commonHelperGetIdArray($timezoneData['response']['timeZoneList']);
            }
        }
        foreach ($response as $tempVal) {

            $response[$loop]['defaultCurrencyCode'] = '';
            $response[$loop]['timeZone'] = '';
            $response[$loop]['logoFilePath'] = "";

            // Getting the Currency Data
            if ($tempVal['defaultCurrencyId'] != '' && $tempVal['defaultCurrencyId'] != 0 && isset($currencyData[$tempVal['defaultCurrencyId']]['currencyCode'])) {
                $response[$loop]['defaultCurrencyCode'] = $currencyData[$tempVal['defaultCurrencyId']]['currencyCode'];
            }

            // Getting the TimeZone data
            if ($tempVal['timezoneId'] != '' && $tempVal['timezoneId'] != 0) {
                $response[$loop]['timeZone'] = $timezoneData[$tempVal['timezoneId']]['zone'];
            }
            if (isset($fileDataTemp[$tempVal['logoFileId']]['path'])) {
                $response[$loop]['logoFilePath'] = $this->ci->config->item('images_content_path') . $fileDataTemp[$tempVal['logoFileId']]['path'];
            }

            unset($response[$loop]['logoFileId']);
            $loop++;
        }
        $total = count($response);
        //Setting country list in memcache
        if ($memcacheEnabled == TRUE) {
            if ($featured == 1) {
                $this->ci->memcached_library->add(MEMCACHE_MAJOR_COUNTRY, $response, MEMCACHE_MAJOR_COUNTRY_TTL);
            } else {
                $this->ci->memcached_library->add(MEMCACHE_ALL_COUNTRY, $response, MEMCACHE_ALL_COUNTRY_TTL);
            }
        }

        $output['status'] = TRUE;
        $output['response']['countryList'] = $response;
        $output['response']['total'] = $total;
        $output['response']['messages'] = array();
        $output['statusCode'] = STATUS_OK;
        return $output;
    }

        /*
     * Function to get the Country Details based on Country Id
     *
     * @access	public
     * @param	$inputArray contains
     * 				countryid - Integer
     * @return	array
     */

    public function getCountryListById($inputArray) {

        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('countryId', 'country id', 'required_strict|is_natural_no_zero');

        if ($this->ci->form_validation->run() == FALSE) {
            $errorMsg = $this->ci->form_validation->get_errors('message');
            $resultArray = array('message' => $errorMsg['message'][0], 'status' => false);
            return $resultArray;
        }
        $countryId = $inputArray['countryId'];
        $countryDetail = array();
        $memcacheEnabled = $this->ci->config->item('memcacheEnabled');
        if ($memcacheEnabled == TRUE) {//memcache is enabled
            $this->ci->load->library('memcached_library');
            $input = array('major' => '0');
            $countryListResponse = $this->getCountryList($input);
            $countryListTemp = array();

            if ($countryListResponse['status'] && $countryListResponse['response']['total'] > 0) {
                $countryListTemp = commonHelperGetIdArray($countryListResponse['response']['countryList']);
                $countryDetail = $countryListTemp[$countryId];
                $output['status'] = TRUE;
                $output['response']['detail'] = $countryDetail;
                $output['response']['total'] = 1;
                $output['messages'] = array();
                $output['statusCode'] = STATUS_OK;
                return $output;
            }
            return $countryListResponse;
        }
        $this->ci->Country_model->resetVariable();
        $selectInput['id'] = $this->ci->Country_model->id;
        $selectInput['name'] = $this->ci->Country_model->name;
        $selectInput['shortName'] = $this->ci->Country_model->shortName;
        $selectInput['logoFileId'] = $this->ci->Country_model->logoFileId;
        $selectInput['timezoneId'] = $this->ci->Country_model->timezoneId;
        $selectInput['default'] = $this->ci->Country_model->default;
        $selectInput['defaultCurrencyId'] = $this->ci->Country_model->defaultCurrencyId;
        //where conditions
        $where [$this->ci->Country_model->status] = 1;
        $where [$this->ci->Country_model->deleted] = 0;
        $where [$this->ci->Country_model->id] = $countryId;
        //executing the query.
        $response = $this->ci->Country_model->get($selectInput, $where, true);

        if (count($response) > 0) {//No records are fetched
            $countryDetail = $response[0];
            $output['status'] = TRUE;
            $output['response']['detail'] = $countryDetail;
            $output['response']['total'] = 1;
            $output['messages'] = array();
            $output['statusCode'] = STATUS_OK;
            return $output;
        }
        $output['status'] = TRUE;
        $output['response']['messages'][] = ERROR_NO_DATA;
        $output['response']['total'] = 0;
        $output['statusCode'] = STATUS_OK;
        return $output;
    }

    //To search countries which matches the entered keyword
    public function searchByKeyword($inputArray) {
        $result = $select = $countryList = $likeArray = array();
        $keyword = $inputArray['keyWord'];

        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('keyWord', 'keyWord', 'required_strict|keyWordRule');
        if ($this->ci->form_validation->run() === FALSE) {
            $response = $this->ci->form_validation->get_errors('message');
            $output['status'] = FALSE;
            $output['response']['messages'] = $response['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        //	print_r($inputArray);
        //Getting the data from database
        $this->ci->Country_model->resetVariable();
        $select['id'] = parent::$CI->Country_model->id;
        $select['name'] = parent::$CI->Country_model->name;
        $select['value'] = parent::$CI->Country_model->name;
        //At the time of add event no need to check the status
        if (isset($inputArray['addEventCheck']) && $inputArray['addEventCheck'] === TRUE) {
            $where = array();
        } else {
            $where[parent::$CI->Country_model->status] = 1;
            $where[parent::$CI->Country_model->deleted] = 0;
        }

        // If we want to search the country name with exact match
        if (isset($inputArray['isNameExact']) && $inputArray['isNameExact']) {
            $where[parent::$CI->Country_model->name] = $keyword;
        } else {
            $likeArray[parent::$CI->Country_model->name] = $keyword;
        }

        $limit = isset($inputArray['limit']) ? trim($inputArray['limit']) : 0;
        $retrunArray = TRUE;
        $countryList = parent::$CI->Country_model->get($select, $where, $retrunArray, $likeArray, $limit);

        //Setting the result db will return false (no records found)
        if ($countryList) {
            $output['status'] = TRUE;
            $output['response']['countryList'] = $countryList;
            $output['response']['total'] = count($countryList);
            $output['response']['messages'] = array();
            $output['statusCode'] = STATUS_OK;
            return $output;
        } else {
            $output['status'] = TRUE;
            $output['response']['messages'][] = ERROR_NO_DATA;
            $output['response']['total'] = 0;
            $output['statusCode'] = STATUS_OK;
        }
        return $output;
    }

    //To Insert the country details
    public function countryInsert($inputs) {
        $validationStatus = $this->countryInsertValidation($inputs);
        if ($validationStatus['error'] == TRUE) {
            $output['status'] = FALSE;
            $output['response']['messages'] = $validationStatus['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }

        //check the country name in our db
        $inputs['addEventCheck'] = TRUE;
        $inputs['keyWord'] = $inputs['country'];
        $inputs['isNameExact'] = TRUE;
        $countryList = $this->searchByKeyword($inputs);
        if ($countryList['status'] && $countryList['response']['total'] > 0) {

            $output['status'] = TRUE;
            $output['response']['countryId'] = $countryList['response']['countryList'][0]['id'];
            $output['response']['message'] = array();
            $output['statusCode'] = STATUS_OK;
            return $output;
        }
        $output['status'] = FALSE;
        $output['response']['messages'][] = ERROR_INVALID_COUNTRY;
        $output['statusCode'] = STATUS_INVALID_INPUTS;
        return $output;

        // as of now we are not insearting country
        //Prepare the country insert array
        /* $insertCountryArray=array();
          $insertCountryArray[$this->ci->Country_model->name]=$inputs['country'];
          $insertCountryArray[$this->ci->Country_model->status]=2;//For review purpose
          $insertCountryArray[$this->ci->Country_model->deleted]=0;
          $insertCountryArray[$this->ci->Country_model->timezoneId]=1;//Need to change this value later
          $insertCountryArray[$this->ci->Country_model->logofileid]=1;//Need to change this value later
          $this->ci->Country_model->setInsertUpdateData($insertCountryArray);
          $countryId = $this->ci->Country_model->insert_data();
          $output['status'] = TRUE;
          $output['response']['countryId'] = $countryId;
          $output['response']['message'] = array();
          $output['statusCode'] = 201;
          return $output; */
    }

    //validate the input data
    public function countryInsertValidation($inputs) {
        $errorMessages = array();
        $this->ci->form_validation->pass_array($inputs);
        $this->ci->form_validation->set_rules('country', 'Country Name', 'required_strict');
        if ($this->ci->form_validation->run() === FALSE) {
            $errorMessages = $this->ci->form_validation->get_errors();
            return $errorMessages;
        }

        $errorMessages['error'] = FALSE;
        return $errorMessages;
    }

    public function getCountryIdByName($inputArray) {
        //for selecting and retrieving the country table data
        $this->ci->Country_model->setTableName('country');
        $this->ci->Country_model->resetVariable();
        $select[$this->ci->Country_model->id] = $this->ci->State_model->id;

        //$this->ci->Country_model->setSelect($selectArray);
        $where[$this->ci->Country_model->status] = 1;
        $where[$this->ci->Country_model->deleted] = 0;
        $where[$this->ci->Country_model->name] = $inputArray['countryName'];

        //$this->ci->State_model->setWhere($whereArray);

        $returnArray = TRUE;
        $countryId = $this->ci->Country_model->get($select, $where, $returnArray);
        return $countryId;
    }

    public function isValidCountry($inputArray) {
        $this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('countryId', 'Country Id', 'is_natural_no_zero|required_strict');
        if ($this->ci->form_validation->run() == FALSE) {
            $response = $this->ci->form_validation->get_errors();
            $output['status'] = FALSE;
            $output['response']['messages'] = $response['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $this->ci->Country_model->resetVariable();
        $select['id'] = 'COUNT( ' . $this->ci->Country_model->id . ' )';
        $whereCountry[$this->ci->Country_model->id] = $inputArray['countryId'];
        $responseCountry = $this->ci->Country_model->get($select, $whereCountry, true, array(), 1);
        if ($responseCountry[0]['id'] > 0) {
            $output = parent::createResponse(TRUE, SUCCESS_VALID_COUNTRY, STATUS_OK);
            return $output;
        } else {
            $output = parent::createResponse(FALSE, ERROR_INVALID_COUNTRY, STATUS_BAD_REQUEST);
            return $output;
        }
    }

}

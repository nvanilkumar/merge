<?php

/**
 * City related business logic will be defined in this class
 *
 * @package		CodeIgniter
 * @author		Qison  Dev Team
 * @copyright	Copyright (c) 2015, MeraEvents.
 * @Version		Version 1.0
 * @Since       Class available since Release Version 1.0 
 * @Created     11-06-2015
 * @Last Modified 08-07-2015
 * @Last Modified by Sridevi 
 */
require_once(APPPATH . 'handlers/handler.php');
require_once(APPPATH . 'handlers/search_handler.php');
require_once(APPPATH . 'handlers/state_handler.php');

class City_handler extends Handler {

    var $searchHandler;
    var $ci;

    public function __construct() {
        parent::__construct();
        $this->ci = parent::$CI;
        $this->ci->load->model('City_model');
        $this->ci->load->model('Country_model');
        $this->searchHandler = new Search_handler();
    }

    //returns all cities based on given countryId,optional parameters like major and keyword can also be passed
    public function getCityList($inputArray) {
        $selectInput = array();
        $selelctCountry = array();
        $whereIn = array();
        $inputArray['countryId'] = (string) $inputArray['countryId'];
        // $inputArray['major']=(string)$inputArray['major'];

        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('countryId', 'Country Id', 'is_natural_no_zero|required_strict');
        $this->ci->form_validation->set_rules('major', 'major', 'enable');
        $this->ci->form_validation->set_rules('keyWord', 'KeyWord', 'keyWordRule');
        $this->ci->form_validation->set_rules('limit', 'Limit', 'is_natural_no_zero');
        if ($this->ci->form_validation->run() == FALSE) {
            $response = $this->ci->form_validation->get_errors();
            $output['status'] = FALSE;
            $output['response']['messages'] = $response['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        //check whethere the country is exists or not in DB 

        $this->countryHandler = new Country_handler();
        $responseCountry = $this->countryHandler->isValidCountry($inputArray);
        if (!$responseCountry['status']) {
            $output['status'] = FALSE;
            $output['response']['messages'][] = ERROR_INVALID_COUNTRY;
            $output['statusCode'] = STATUS_INVALID_INPUTS;
            return $output;
        }

        $countryId = $inputArray['countryId'];
        $featured = isset($inputArray['major']) ? $inputArray['major'] : 1;
        $keyWord = isset($inputArray['keyWord']) ? trim($inputArray['keyWord']) : '';
        $limit = isset($inputArray['limit']) ? trim($inputArray['limit']) : 0;
        $like = array();
        $cacheResults = FALSE;

        if (empty($keyWord) && $featured == 1 && !isset($limit)) {
            if ($this->ci->config->item('memcacheEnabled')) { //memcache is enabled
                $this->ci->load->library('memcached_library');
                //fetching data from memcache
                $cacheResults = $this->ci->memcached_library->get(MEMCACHE_MAJOR_CITY . $countryId);
            }
        } else if (strlen($keyWord) > 0) {
            $like = array('name' => $keyWord);
        }
        if ($cacheResults != FALSE) { // Data is availeble in memcache.
            $total = count($cacheResults);
            $output['status'] = TRUE;
            $output['response']['cityList'] = $cacheResults;
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
        $this->ci->City_model->resetVariable();
        $selectInput['id'] = $this->ci->City_model->id;
        $selectInput['name'] = $this->ci->City_model->name;
        $selectInput['order'] = $this->ci->City_model->order;
        $selectInput['countryid'] = $this->ci->City_model->countryId;
        $selectInput['splcitystateid'] = $this->ci->City_model->splCityStateId;
        $selectInput['aliascityid'] = $this->ci->City_model->aliascityid;
        //where conditions
        $where[$this->ci->City_model->status] = 1;
        $where[$this->ci->City_model->deleted] = 0;
        if (isset($inputArray['stateId']) && $inputArray['stateId'] != '') {
            // If stateId is there then need to get the cityId from `statecity` table
            $whereStateCity[$this->ci->City_model->statecityStateId] = $inputArray['stateId'];
            $selectInputStateCity['cityid'] = $this->ci->City_model->statecityCityId;

            $responseStateCity = $this->ci->City_model->getStateCity($selectInputStateCity, $whereStateCity);
            $tempCityArr = array();
            foreach ($responseStateCity as $cityId) {
                $tempCityArr[] = $cityId['cityid'];
            }
            $whereIn[$this->ci->City_model->id] = $tempCityArr;
        }
        if ($featured == 1) {
            $where[$this->ci->City_model->featured] = $featured;
        }
        $where[$this->ci->City_model->countryId] = $countryId;

        $response = $this->ci->City_model->getdata($selectInput, $where, $whereIn, $like, $limit);
        if (isset($inputArray['specialStateName']) && $inputArray['specialStateName'] && $response) {
            $this->stateHandler = new State_handler();
            foreach ($response as $key => $value) {
                if ($response[$key]['splcitystateid'] > 0) {
                    $stateDetailInput['stateId'] = $response[$key]['splcitystateid'];
                    $stateDetails = $this->stateHandler->getStateListById($stateDetailInput); //Only few cities will be special cities
                    $response[$key]['name'] = $stateDetails['response']['stateList'][0]['name'];
                }
            }
        }
        if ($response == FALSE) { //No records are fetched
            $output['status'] = TRUE;
            $output['response']['messages'][] = ERROR_NO_CITIES;
            $output['response']['total'] = 0;
            $output['statusCode'] = STATUS_OK;
            return $output;
        }
        $total = count($response);
        //set cache when cache for major cities is not available and when no keyword is passed(as cache is not maintained when keyword is passed)
        if ($cacheResults == FALSE && $featured == 1 && empty($keyWord) && $total > 0 && $this->ci->config->item('memcacheEnabled') && !isset($limit)) {
            $this->ci->memcached_library->add(MEMCACHE_MAJOR_CITY . $countryId, $response, MEMCACHE_MAJOR_CITY_TTL);
        }
        $output['status'] = TRUE;
        $output['response']['cityList'] = $response;
        $output['response']['total'] = $total;
        $output['response']['messages'] = array();
        $output['statusCode'] = STATUS_OK;
        return $output;
    }

    //return events count by city wise
    public function getEventsCountByCites($inputArray) {

        $stateResultArray = $cityResultArray = $cityList = $searchInputs = $facetvalues = $data = $result = $cityResult = $stateResult = $eventTypeResult = array();
        $resultData = $cityDiffArray = $stateDiffArray = $cityRemainingListArray = $stateRemainingListArray = array();
        $cityList = $this->getCityList($inputArray);
        $cityList = json_decode(json_encode($cityList), TRUE);
        $searchInputs = $inputArray;

        if (empty($cityList['response']['cityList'])) {
            return $cityList;
        }
        $allAliasCities = $indexedAliasCity = [];
        if ($cityList['status'] == 1 && $cityList['response']['total'] >= 1) {
            foreach ($cityList['response']['cityList'] as $cityListKey => $cityListValue) {
                //storing aliascity in cityList
                if (strlen($cityListValue['aliascityid']) > 0) {
                    $aliasCities = explode(',', $cityListValue['aliascityid']);
                    $allAliasCities = array_merge($allAliasCities, $aliasCities);
                    foreach ($aliasCities as $aliascityId) {
                        $indexedAliasCity[$aliascityId] = $cityListValue['id'];
                        $indexedAliasState[$aliascityId] = $cityListValue['splcitystateid'];
                    }
                }
                if ($cityListValue['splcitystateid'] == 0) {
                    $facetvalues['cityList'][] = $cityListValue['id'];
                } else {
                    $facetvalues['stateList'][] = $cityListValue['splcitystateid'];
                }
            }
            $facetvalues['cityList'] = array_merge($facetvalues['cityList'], $allAliasCities);
            if (isset($facetvalues['cityList']) && count($facetvalues['cityList']) >= 1) {
                $searchInputs['facetType'] = 'city';
                $searchInputs['facetValues'] = $facetvalues['cityList'];
                $cityResult = $this->facetCount($searchInputs);
            }

            if (isset($facetvalues['stateList'])) {
                $searchInputs['facetType'] = 'state';
                $searchInputs['facetValues'] = $facetvalues['stateList'];
                $stateResult = $this->facetCount($searchInputs);
            }
            if (count($cityResult) > 0 && $cityResult['response']['error'] == 'true') {
                $output['status'] = FALSE;
                $output['response']['messages'] = $cityResult['response']['messages'];
                $output['statusCode'] = STATUS_BAD_REQUEST;
                return $output;
            }
            if (count($stateResult) > 0 && $stateResult['response']['error'] == 'true') {
                $output['status'] = FALSE;
                $output['response']['messages'] = $stateResult['response']['messages'];
                $output['statusCode'] = STATUS_BAD_REQUEST;
                return $output;
            }
            if (isset($cityResult['response']['result']['facetCounts']['cityId'])) {
                foreach ($cityResult['response']['result']['facetCounts']['cityId'] as $cityKey => $cityValue) {
                    $key = $cityValue[0];
                    if (!in_array($cityValue[0], $allAliasCities)) {
                        $cityResultArray[$key]['type'] = 'city';
                        $cityResultArray[$key]['id'] = $cityValue[0];
                        $cityDiffArray[] = $cityValue[0];
                        $cityResultArray[$key]['count'] += $cityValue[1];
                    } else {
                        $cityResultArray[$indexedAliasCity[$key]]['type'] = 'city';
                        $cityResultArray[$indexedAliasCity[$key]]['id'] = $indexedAliasCity[$key];
                        $cityDiffArray[] = $indexedAliasCity[$key];
                        $cityResultArray[$indexedAliasCity[$key]]['count'] += $cityValue[1];
                    }

                    $cityIndexedCount[$key] = $cityValue[1];
                }
            }
            //  print_r($cityResultArray);exit;
            if (!empty($stateResult['response']['result']['facetCounts']['stateId'])) {
                foreach ($stateResult['response']['result']['facetCounts']['stateId'] as $stateKey => $stateValue) {
                    $key = $stateValue[0];
                    $stateResultArray[$key]['id'] = $stateValue[0];
                    $stateResultArray[$key]['count'] = $stateValue[1];
                    $stateResultArray[$key]['type'] = 'state';
                    $stateDiffArray[] = $stateValue[0];
                    if (in_array($stateValue[0], $indexedAliasState)) {//If the state contains alias cities
                        foreach ($allAliasCities as $value) {
                            if ($indexedAliasState[$value] == $key) {
                                $stateResultArray[$key]['count'] += $cityIndexedCount[$value];
                            }
                        }
                    }
                }
            }


            foreach ($facetvalues['stateList'] as $statekeys => $statevalues) {

                if (in_array($statevalues, $indexedAliasState) && !in_array($statevalues, array_keys($stateResultArray))) {//If the state contains alias cities
                    foreach ($allAliasCities as $value) {
                        if (($indexedAliasState[$value] == $statevalues)) {
                            $stateResultArray[$statevalues]['count'] += isset($cityIndexedCount[$value]) ? $cityIndexedCount[$value] : 0;
                            $stateResultArray[$statevalues]['id'] = $statevalues;
                            $stateResultArray[$statevalues]['type'] = 'state';
                        }
                    }
                }
            }
            //} 
            // Unsetting the Values of States having count '0'
            if (!isset($facetvalues['cityList'])) {
                $facetvalues['cityList'] = array();
            }


            $cityRemainingList = array_diff($facetvalues['cityList'], $cityDiffArray);
            if (count($cityRemainingList) >= 1) {
                foreach ($cityRemainingList as $crKey => $crValue) {
                    $cityRemainingListArray[$crKey]['id'] = $crValue;
                    $cityRemainingListArray[$crKey]['count'] = 0;
                    $cityRemainingListArray[$crKey]['type'] = 'city';
                }
            }
            $cityResultArray = array_merge($cityResultArray, $cityRemainingListArray);


            if (!isset($facetvalues['stateList'])) {
                $facetvalues['stateList'] = array();
            }
            $stateRemainingList = array_diff($facetvalues['stateList'], array_keys($stateResultArray));
            if (count($stateRemainingList) >= 1) {
                foreach ($stateRemainingList as $srKey => $srValue) {
                    $stateResultArray[$srValue]['id'] = $srValue;
                    $stateResultArray[$srValue]['count'] = 0;
                    $stateResultArray[$srValue]['type'] = 'state';
                }
            }
            /*   $stateResultArray = array_unique(array_merge($stateResultArray,$stateRemainingListArray )); 
             */
            $resultData = array_merge($cityResultArray, $stateResultArray);
            /* All cities count */
            $allCityCountInput = array();
            $allCityCountInput['countryId'] = $inputArray['countryId'];
            if (isset($inputArray['subcategoryId']) && $inputArray['subcategoryId'] != '') {
                $allCityCountInput['subcategoryId'] = $inputArray['subcategoryId'];
            }
            if (isset($inputArray['categoryId']) && $inputArray['categoryId'] != '') {
                $allCityCountInput['categoryId'] = $inputArray['categoryId'];
            }
            if (isset($inputArray['day']) && $inputArray['day'] != '') {
                $allCityCountInput['day'] = $inputArray['day'];
                if ($inputArray['day'] == 7) {
                    $allCityCountInput['dateValue'] = $inputArray['dateValue'];
                }
            } else {
                $allCityCountInput['day'] = 6;
            }

            if (isset($inputArray['eventType']) && $inputArray['eventType'] != '') {
                $registrationTypeArray = eventType($inputArray['eventType']);
                $allCityCountInput['registrationType'] = $registrationTypeArray['registrationType'];
                if ($registrationTypeArray['registrationType'] == 4) {
                    $allCityCountInput['eventMode'] = $registrationTypeArray['eventMode'];
                    unset($allCityCountInput['registrationType']);
                }
            }
            $allCityCountInput['limit'] = 0;
            if (isset($inputArray['keyword'])) {
                $inputArray['keyWord'] = $inputArray['keyword'];
            }
            if (isset($inputArray['keyWord'])) {
                $allCityCountInput['keyWord'] = $inputArray['keyWord'];
            }
            if (isset($inputArray['ticketSoldout'])) {
                $allCityCountInput['ticketSoldout'] = $inputArray['ticketSoldout'];
            }
            if (isset($inputArray['status'])) {
                $allCityCountInput['status'] = $inputArray['status'];
            }
            if (isset($inputArray['webinar'])) {
                $allCityCountInput['webinar'] = $inputArray['webinar'];
            }
            if (isset($inputArray['eventMode'])) {
                $allCityCountInput['eventMode'] = $inputArray['eventMode'];
            }

            $allCityCountInput['private'] = 0;
            $allCountResponse = json_decode($this->searchHandler->allCount('city', $allCityCountInput), true);
            $allCount = $allCountResponse['response']['total'];
            /* end of All cities count */

            $output['status'] = TRUE;
            $output['response']['count'] = $resultData;
            $output['response']['total'] = count($resultData);
            $output['response']['allCount'] = $allCount;
            $output['response']['messages'] = array();
            $output['statusCode'] = STATUS_OK;
            return $output;
        }
    }

    public function facetCount($inputArray) {
        $searchInputs = $data = $result = $eventTypeResult = array();
        $searchInputs = $inputArray;
        if (isset($searchInputs['eventType']) && $searchInputs['eventType'] != '') {
            $eventTypeResult = eventType($searchInputs['eventType']);
            if ($eventTypeResult['registrationType'] == 4) {
                unset($eventTypeResult['registrationType']);
            }
            $searchInputs = array_merge($searchInputs, $eventTypeResult);
        }
        if (!isset($searchInputs['day'])) {
            $searchInputs['day'] = 6;
        }
        unset($searchInputs['eventType']);
        unset($searchInputs['major']);
        // unset($searchInputs['keyWord']);
        if (isset($searchInputs['keyword'])) {
            $searchInputs['keyWord'] = $searchInputs['keyword'];
            unset($searchInputs['keyword']);
        }
        $data = $this->searchHandler->citesEventCount($searchInputs);
        $result = json_decode($data, true);

        return $result;
    }

    /*
     * Function to get the City Details based on City Id
     *
     * @access	public
     * @param	$inputArray contains
     * 				cityid - Integer
     * @return	array
     * @Last Modified by Sridevi on 08-07-15 for fetching the data from DB
     */

    public function getCityDetailById($inputArray) {
        $inputArray['cityId'] = (string) $inputArray['cityId'];
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('cityId', 'City Id', 'required_strict|is_natural_no_zero');
        $this->ci->form_validation->set_rules('countryId', 'Country Id', 'required_strict|is_natural_no_zero');

        if (!empty($inputArray) && $this->ci->form_validation->run() == FALSE) {
            $errorMsg = $this->ci->form_validation->get_errors('message');
            $resultArray = array('message' => $errorMsg['message'][0], 'status' => false);
            return $resultArray;
        }
        $cityId = $inputArray['cityId'];
        $countryId = $inputArray['countryId'];
        $input = array('cityId' => $cityId, 'countryId' => $countryId, 'major' => 0);
        $selelctCity = array();
        $whereCity = array();
        $this->ci->City_model->resetVariable();
        $selelctCity['id'] = $this->ci->City_model->id;
        $selelctCity['name'] = $this->ci->City_model->name;
        $selelctCity['specialStateId'] = $this->ci->City_model->splCityStateId;
        $selelctCity['aliascityid'] = $this->ci->City_model->aliascityid;

        $whereCity[$this->ci->City_model->id] = $inputArray['cityId'];
        $whereCity[$this->ci->City_model->deleted] = 0;
        //$whereCity[$this->ci->City_model->status] = 1;
        $responseCity = $this->ci->City_model->getdata($selelctCity, $whereCity);
        if (isset($inputArray['specialStateName']) && $inputArray['specialStateName'] && $responseCity) {
            $this->stateHandler = new State_handler();
            if ($responseCity[0]['specialStateId'] > 0) {
                $stateDetailInput['stateId'] = $responseCity[0]['specialStateId'];
                $stateDetails = $this->stateHandler->getStateListById($stateDetailInput); //Only few cities will be special cities
                $responseCity[0]['name'] = $stateDetails['response']['stateList'][0]['name'];
            }
        }
        if (count($responseCity) > 0) {//No records are fetched
            $output['status'] = TRUE;
            $output['response']['detail'] = $responseCity[0];
            $output['messages'] = array();
            $output['response']['total'] = 1;
            $output['statusCode'] = STATUS_OK;
        } else if (count($responseCity) == 0) {//No records are fetched
            $output['status'] = TRUE;
            $output['messages'][] = ERROR_NO_DATA;
            $output['response']['total'] = 0;
            $output['statusCode'] = STATUS_OK;
        } else {
            $output['status'] = FALSE;
            $output['messages'][] = ERROR_INTERNAL_DB_ERROR;
            $output['response']['total'] = 0;
            $output['statusCode'] = STATUS_SERVER_ERROR;
        }

        return $output;
    }

    public function getCitySearch($inputArray) {
        $selectInput = array();
        $selelctCountry = array();
        $whereIn = array();
        $resultArray = array();

        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('countryName', 'Country Name', 'name');
        $this->ci->form_validation->set_rules('countryId', 'Country Id', 'is_natural_no_zero');
        $this->ci->form_validation->set_rules('major', 'Major', 'enable');
        $this->ci->form_validation->set_rules('keyWord', 'KeyWord', 'required_strict|keyWordRule');
        $this->ci->form_validation->set_rules('limit', 'Limit', 'is_natural_no_zero');

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

        //check whethere the country is exists or not in DB

        $countryId = 0;

        if (isset($inputArray['countryId']) && $inputArray['countryId'] != '') {
            $countryId = $inputArray['countryId'];
        } else {
            $this->countryHandler = new Country_handler();
            $countryRequest = array('keyWord' => $inputArray['countryName'], 'isNameExact' => true);
            $CountryArr = $this->countryHandler->searchByKeyword($countryRequest);
            if (!$CountryArr['status']) {
                $resultArray['status'] = FALSE;
                $resultArray['response']['messages'][] = ERROR_INVALID_COUNTRY;
                $resultArray['statusCode'] = STATUS_INVALID_INPUTS;
                return $resultArray;
            } else {
                $countryId = $CountryArr['response']['countryList'][0]['id'];
            }
        }


        $featured = isset($inputArray['major']) ? $inputArray['major'] : 1;
        $keyWord = isset($inputArray['keyWord']) ? trim($inputArray['keyWord']) : '';
        $limit = isset($inputArray['limit']) ? trim($inputArray['limit']) : 0;
        $like = array();
        //Select values
        $this->ci->City_model->resetVariable();
        $selectInput['id'] = $this->ci->City_model->id;
        $selectInput['name'] = $this->ci->City_model->name;
        $selectInput['value'] = $this->ci->City_model->name;
        $selectInput['order'] = $this->ci->City_model->order;
        $selectInput['countryid'] = $this->ci->City_model->countryId;
        $selectInput['splcitystateid'] = $this->ci->City_model->splCityStateId;
        //where conditions
        //At the time of add event no need to check the status
        if (isset($inputArray['addEventCheck']) && $inputArray['addEventCheck'] == TRUE) {
             $where[$this->ci->City_model->status] = 1;
            $where[$this->ci->City_model->deleted] = 0;
        } else {
            $where[$this->ci->City_model->status] = 1;
            $where[$this->ci->City_model->deleted] = 0;
            if ($featured == 1) {
                $where[$this->ci->City_model->featured] = $featured;
            }
        }


        // If stateId is present then directly put in where condition
        //	Or based on `stateName` get the `stateId`
        if (isset($inputArray['stateId']) && $inputArray['stateId'] != '') {
            $stateId = $inputArray['stateId'];
        } elseif (isset($inputArray['stateName']) && $inputArray['stateName'] != '') {
            $this->stateHandler = new State_handler();
            $stateRequest = array('countryId' => $countryId, 'keyWord' => $inputArray['stateName'], 'isNameExact' => true);
            $stateArr = $this->stateHandler->searchByKeyword($stateRequest);

            if ($stateArr['status'] == TRUE && $stateArr['response']['total'] > 0) {
                $stateId = (isset($stateArr['response']['stateList'][0]['id'])) ? $stateArr['response']['stateList'][0]['id'] : 0;
            } else {
                $resultArray['status'] = FALSE;
                $resultArray['response']['messages'][] = ERROR_NO_STATE;
                $resultArray['response']['total'] = 0;
                $resultArray['statusCode'] = STATUS_INVALID_INPUTS;
                return $resultArray;
            }
        }

        // If stateId is there then need to get the cityId from `statecity` table
        $whereStateCity[$this->ci->City_model->statecityStateId] = $stateId;
        $selectInputStateCity['cityid'] = $this->ci->City_model->statecityCityId;
        $tempCityArr = array();
        //if we passing from addevent we need to check for exact city name
        if (isset($inputArray['addEventCheck'])) {
            $responseStateCity = $this->ci->City_model->getStateCity($selectInputStateCity, $whereStateCity);
            if ($responseStateCity) {
                foreach ($responseStateCity as $cityId) {
                    $tempCityArr[] = $cityId['cityid'];
                }
            }
            if (count($tempCityArr) > 0) {
                $whereIn[$this->ci->City_model->id] = $tempCityArr;
            } else {
                $where[$this->ci->City_model->id] = 0;
            }
            if ($inputArray['isNameExact']) {
                $where[$this->ci->City_model->name] = $keyWord;
            } else {
                $like[$this->ci->City_model->name] = $keyWord;
            }
        } else {
            //checking the match of city name
            //$where[$this->ci->City_model->name] = $keyWord;
            if ($inputArray['isNameExact']) {
                $where[$this->ci->City_model->name] = $keyWord;
            } else {
                $like[$this->ci->City_model->name] = $keyWord;
            }
        }

        $where[$this->ci->City_model->countryId] = $countryId;
        $response = $this->ci->City_model->getdata($selectInput, $where, $whereIn, $like, $limit);

        if ($response == FALSE) { //No records are fetched
            $output['status'] = TRUE;
            $output['response']['messages'][] = ERROR_NO_CITIES;
            $output['response']['total'] = 0;
            $output['statusCode'] = STATUS_OK;
            return $output;
        }
        $total = count($response);
        $output['status'] = TRUE;
        $output['response']['cityList'] = $response;
        $output['response']['messages'] = array();
        $output['response']['total'] = $total;
        $output['statusCode'] = STATUS_OK;
        return $output;
    }

    //To Insert the country details
    //@param $inputs array[ ciy, countryId]
    public function cityInsert($inputs) {
        $validationStatus = $this->cityInsertValidation($inputs);
        if ($validationStatus['error'] == TRUE) {
            $output['status'] = FALSE;
            $output['response']['messages'] = $validationStatus['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }

        //check the country name in our db
        $inputs['addEventCheck'] = TRUE;
        $inputs['keyWord'] = $inputs['city'];

        $cityList = $this->getCitySearch($inputs);
        if ($cityList['status'] && $cityList['response']['total'] > 0) {
            $cityXStateArray['cityId'] = $cityList['response']['cityList'][0]['id'];

            //cross state city table check
            $cityXStateList = $this->cityXState($cityXStateArray);
            $stateId = 0;
            if ($cityXStateList['status'] && $cityXStateList['response']['total'] > 0) {
                $stateId = $cityXStateList['response']['countryStateList'][0]['stateId'];
            }
            //user sended state id is different than mapped state id
            if ($stateId != $inputs['stateId']) {
                $insertCityStateArray['cityId'] = $cityXStateArray['cityId'];
                $insertCityStateArray['stateId'] = $inputs['stateId'];
                $this->cityStateInsert($insertCityStateArray);
            }

            $output['status'] = TRUE;
            $output['response']['cityId'] = $cityXStateArray['cityId'];
            $output['response']['messages'] = array();
            $output['statusCode'] = STATUS_UPDATED;
            return $output;
        }

        //Prepare the country insert array
        $insertCityArray = array();
        $this->ci->City_model->resetVariable();
        $insertCityArray[$this->ci->City_model->name] = $inputs['city'];
        if(isset($inputs['googleapicity']) && $inputs['googleapicity'] == 1){
            $insertCityArray[$this->ci->City_model->status] = 1; //directly enabling
        }else{
            $insertCityArray[$this->ci->City_model->status] = 2; //For review purpose
        }
        $insertCityArray[$this->ci->City_model->deleted] = 0;
        $insertCityArray[$this->ci->City_model->countryId] = $inputs['countryId'];
        $insertCityArray[$this->ci->City_model->splCityStateId] = 0;
        $this->ci->City_model->setInsertUpdateData($insertCityArray);
        $cityId = $this->ci->City_model->insert_data();

        //Insert the mapping statecity table
        $insertCityStateArray['cityId'] = $cityId;
        $insertCityStateArray['stateId'] = $inputs['stateId'];
        $this->cityStateInsert($insertCityStateArray);
        $output['status'] = TRUE;
        $output['response']['cityId'] = $cityId;
        $output['response']['messages'] = array();
        $output['statusCode'] = STATUS_UPDATED;
        return $output;
    }

    //validate the input data
    public function cityInsertValidation($inputs) {
        $errorMessages = array();
        $this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($inputs);
        $this->ci->form_validation->set_rules('city', 'City Name', 'required_strict');
        $this->ci->form_validation->set_rules('stateId', 'State Id', 'required_strict');
        $this->ci->form_validation->set_rules('countryId', 'Country Id', 'required_strict');
        if ($this->ci->form_validation->run() === FALSE) {
            $errorMessages = $this->ci->form_validation->get_errors();
            return $errorMessages;
        }

        $errorMessages['error'] = FALSE;
        return $errorMessages;
    }

    //To Bring the city id related mapped state id 
    //@param $input[cityId] 
    public function cityXState($inputs) {
        $this->ci->load->model('Statecity_model');
        $selectInput = $where = $output = array();

        $validationStatus = $this->validateCityXState($inputs);
        if ($validationStatus['error'] == TRUE) {
            $output['status'] = FALSE;
            $output['response']['messages'] = $validationStatus['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $this->ci->Statecity_model->resetVariable();
        $selectInput['stateId'] = $this->ci->Statecity_model->stateid;
        $selectInput['cityId'] = $this->ci->Statecity_model->cityid;
        $this->ci->Statecity_model->setSelect($selectInput);

        //fetching active tickets & not deleted
        $where[$this->ci->Statecity_model->cityid] = $inputs['cityId'];
        $this->ci->Statecity_model->setWhere($where);
        $stateCityList = $this->ci->Statecity_model->get();

        if ($stateCityList) {
            $output['status'] = TRUE;
            $output['response']['countryStateList'] = $stateCityList;
            $output['response']['total'] = count($stateCityList);
            $output['response']['messages'] = array();
            $output['statusCode'] = STATUS_OK;
            return $output;
        }

        $output['status'] = FALSE;
        $output['response']['total'] = 0;
        $output['response']['messages'][] = ERROR_NO_DATA;
        $output['statusCode'] = STATUS_OK;
        return $output;
    }

    //To validate the cityRelatedStateid
    public function validateCityXState($inputs) {
        $errorMessages = array();
        $this->ci->form_validation->pass_array($inputs);
        $this->ci->form_validation->set_rules('cityId', 'City ID', 'required_strict');
        if ($this->ci->form_validation->run() === FALSE) {
            $errorMessages = $this->ci->form_validation->get_errors();
            return $errorMessages;
        }
        $errorMessages['error'] = FALSE;
        return $errorMessages;
    }

    //To insert the city & state id mapping table details
    public function cityStateInsert($inputs) {
        $this->ci->load->model('Statecity_model');

        $validationStatus = $this->validatecityStateInsert($inputs);
        if ($validationStatus['error'] == TRUE) {
            $output['status'] = FALSE;
            $output['response']['messages'] = $validationStatus['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }

        //Prepare the country insert array
        $insertCountryArray = array();
        $this->ci->Statecity_model->resetVariable();
        $insertCountryArray[$this->ci->Statecity_model->cityid] = $inputs['cityId'];
        $insertCountryArray[$this->ci->Statecity_model->stateid] = $inputs['stateId'];
        $this->ci->Statecity_model->setInsertUpdateData($insertCountryArray);
        return $this->ci->Statecity_model->insert_data();
    }

    //To validate the cityStateInsert 
    public function validatecityStateInsert($inputs) {
        $errorMessages = array();
        $this->ci->form_validation->pass_array($inputs);
        $this->ci->form_validation->set_rules('cityId', 'City ID', 'is_natural_no_zero|required_strict');
        $this->ci->form_validation->set_rules('stateId', 'State ID', 'is_natural_no_zero|required_strict');
        if ($this->ci->form_validation->run() === FALSE) {
            $errorMessages = $this->ci->form_validation->get_errors();
            return $errorMessages;
        }
        $errorMessages['error'] = FALSE;
        return $errorMessages;
    }

    //To Bring the city ids 
    //@param $input[stateId] 

    public function getcitiesXstate($inputArray) {
        parent::$CI->form_validation->pass_array($inputArray);
        parent::$CI->form_validation->set_rules('stateId', 'State Id', 'required_strict|is_natural_no_zero');
        if (parent::$CI->form_validation->run() === FALSE) {
            $errorMessages = $this->ci->form_validation->get_errors();
            $output['status'] = FALSE;
            $output['response']['messages'] = $errorMessages['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $this->ci->load->model('Statecity_model');
        $selectInput = $where = $output = array();
        $this->ci->Statecity_model->resetVariable();
        $selectInput['stateId'] = $this->ci->Statecity_model->stateid;
        $selectInput['cityId'] = $this->ci->Statecity_model->cityid;
        $this->ci->Statecity_model->setSelect($selectInput);
        $where[$this->ci->Statecity_model->stateid] = $inputArray['stateId'];
        $this->ci->Statecity_model->setWhere($where);
        $cityStateList = $this->ci->Statecity_model->get();

        $citys = commonHelperGetIdArray($cityStateList, 'cityId');
        $cityId = array_keys($citys);
        $inputArray['cityIds'] = $cityId;
        $cityData = $this->getNames($inputArray);

        if ($cityData['status'] == true && $cityData['response']['total'] > 0) {
            $output['status'] = TRUE;
            $output['response']['cityStateList'] = $cityData;
            $output['response']['total'] = count($cityData);
            $output['response']['messages'] = array();
            $output['statusCode'] = STATUS_OK;
            return $output;
        }

        $output['status'] = FALSE;
        $output['response']['total'] = 0;
        $output['response']['messages'][] = ERROR_NO_DATA;
        $output['statusCode'] = STATUS_OK;
        return $output;
    }

    public function getCityNames($cityIds) {
        // commented by sridevi on 13-08-15
        // $citys= commonHelperGetIdArray($cityStateList,'cityId');
        //$cityIds=array_keys($citys);
        $this->ci->load->model('City_Model');
        $this->ci->City_Model->resetVariable();
        $selectInput['id'] = $this->ci->City_Model->id;
        $selectInput['name'] = $this->ci->City_Model->name;
        $this->ci->City_Model->setSelect($selectInput);
        $this->ci->City_Model->setWhereIn(array('id', $cityIds));
        $cityData = $this->ci->City_Model->get();
        if ($cityData) {
            $output['status'] = TRUE;
            $output['response']['cityName'] = $cityData;
            $output['statusCode'] = STATUS_OK;
            return $output;
        }
        $output['status'] = FALSE;
        $output["response"]["messages"][] = ERROR_NO_CITIES;
        $output['statusCode'] = STATUS_OK;
        return $output;
    }

    public function getNames($inputArray) {
        $this->ci->load->model('City_Model');
        $selectInput['id'] = $this->ci->City_Model->id;
        $selectInput['name'] = $this->ci->City_Model->name;
        $selectInput['status'] = $this->ci->City_Model->status;
        $this->ci->City_Model->setSelect($selectInput);
        $where[$this->ci->City_Model->status] = 1;
        $this->ci->City_Model->setWhere($where);
        $like[$this->ci->City_Model->name] = $inputArray['term'];
        $this->ci->City_Model->setWhereIn(array('id', $inputArray['cityIds']));
        $this->ci->City_Model->setLike($like);
        $cityData = $this->ci->City_Model->get();
        if ($cityData) {
            $output['status'] = TRUE;
            $output['response']['cityName'] = $cityData;
            $output['response']['total'] = count($cityData);
            $output['statusCode'] = STATUS_OK;
            return $output;
        }
        $output['status'] = TRUE;
        $output["response"]["messages"][] = ERROR_NO_CITIES;
        $output['statusCode'] = STATUS_OK;
        $output['response']['total'] = 0;
        return $output;
    }

    ///Get citu details with the Name
    public function getCityDetailsByName($inputArray) {
        $this->ci->load->model('City_Model');
        $this->ci->City_Model->resetVariable();
        $selectInput['id'] = $this->ci->City_Model->id;
        $selectInput['name'] = $this->ci->City_Model->name;
        $selectInput['status'] = $this->ci->City_Model->status;
        $selectInput['countryId'] = $this->ci->City_Model->countryId;
        //$selectInput['statecityStateId'] = $this->ci->City_Model->splcitystateid;
        $selectInput['order'] = $this->ci->City_Model->order;
        $selectInput['featured'] = $this->ci->City_Model->featured;
        $this->ci->City_Model->setSelect($selectInput);
        $where[$this->ci->City_Model->status] = 1;
        $where[$this->ci->City_Model->deleted] = 0;
        $this->ci->City_Model->setWhere($where);

        $like[$this->ci->City_Model->name] = $inputArray['name'];
        $this->ci->City_Model->setLike($like);
        $this->ci->City_Model->setRecords(1);
        $cityData = $this->ci->City_Model->get();
        if ($cityData) {
            $output['status'] = TRUE;
            $output['response']['cityDetails'] = $cityData;
            $output["response"]['total'] = count($cityData);
            $output['statusCode'] = STATUS_OK;
            return $output;
        }
        $output['status'] = TRUE;
        $output["response"]["messages"][] = ERROR_NO_CITIES;
        $output['statusCode'] = STATUS_OK;
        $output["response"]['total'] = 0;
        return $output;
    }

    public function checkSpecialcityState($inputArray) {
        $this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('stateId', 'State Id', 'is_natural_no_zero|required_strict');
        if ($this->ci->form_validation->run() == FALSE) {
            $response = $this->ci->form_validation->get_errors();
            $output['status'] = FALSE;
            $output['response']['messages'] = $response['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $this->ci->City_model->resetVariable();
        $select['id'] = $this->ci->City_model->id;
        $this->ci->City_model->setSelect($select);
        $where[$this->ci->City_model->splCityStateId] = $inputArray['stateId'];
        $this->ci->City_model->setWhere($where);
        $responseCountry = $this->ci->City_model->get();
        if (count($responseCountry) > 0) {
            $output = parent::createResponse(TRUE, "", STATUS_OK, '', 'cityId', $responseCountry[0]['id']);
            return $output;
        } else {
            $output = parent::createResponse(FALSE, 'No corresponding special city for this state', STATUS_BAD_REQUEST);
            return $output;
        }
    }

}

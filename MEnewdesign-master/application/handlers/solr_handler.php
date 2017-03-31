<?php

/**
 * Solr events related business logic will be defined in this class
 *
 * @package		CodeIgniter
 * @author		Qison  Dev Team
 * @copyright	Copyright (c) 2015, Meraevents.
 * @Version		Version 1.0
 * @Since       Class available since Release Version 1.0 
 * @Created     11-06-2015
 * @Last Modified 26-06-2015
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require_once(APPPATH . 'handlers/handler.php');

class Solr_handler extends Handler {

    var $ci;

    public function __construct() {
        parent::__construct();
        $this->ci = parent::$CI;
        $this->ci->load->library('Solrlibrary');
    }

    /*
     * Default published event and it's not a private event
     * Default orderedby is popularity is in desc and startdatetime is in asc
     */

    public function getSolrEvents($inputs) {
        //print_r($inputs); exit;
        $sort = $facet = '';
        $start = 0;
        $limit = 12;
        $solrFiledArray = $dateResponse = array();
        $sort['popularity'] = 'desc';
        $sort['totalsoldtickets'] = 'desc';
        $sort['startDateTime'] = 'asc';

        //validations
        $this->ci->form_validation->pass_array($inputs);
        $eventIdsArray = $onlyOrCondition = array();
        if (isset($inputs['eventIdsArray']) && count($inputs['eventIdsArray']) > 0) {

            $eventIdsArray['id'] = $inputs['eventIdsArray'];
            unset($inputs['eventIdsArray']);
            $this->ci->form_validation->set_rules('countryId', 'CountryId', 'is_natural_no_zero');
        } elseif (isset($inputs['mobile']) && $inputs['mobile']) {
            $this->ci->form_validation->set_rules('countryId', 'CountryId', 'is_natural_no_zero');
            unset($inputs['mobile']);
        } else {
            $this->ci->form_validation->set_rules('countryId', 'CountryId', 'required_strict|is_natural_no_zero');
        }

        $this->ci->form_validation->set_rules('stateId', 'StateId', 'is_natural_no_zero');
        $this->ci->form_validation->set_rules('cityId', 'CityId', 'is_natural_no_zero');
        $this->ci->form_validation->set_rules('categoryId', 'CategoryId', 'is_natural_no_zero');
        $this->ci->form_validation->set_rules('subcategoryId', 'SubcategoryId', 'is_natural_no_zero');

        $this->ci->form_validation->set_rules('start', 'Start', 'is_natural');
        $this->ci->form_validation->set_rules('limit', 'Limit', 'is_natural');
        if (isset($inputs['cityId']) && $inputs['cityId'] == 0)
            unset($inputs['cityId']);
        $orConditionInputs = array();
        if (!empty($inputs) && $this->ci->form_validation->run() == FALSE) {

            $output['response'] = $this->ci->form_validation->get_errors();
            $output = json_encode($output);
            return $output;
        }

        if (!empty($inputs)) {
            foreach ($inputs as $inputKey => $inputValue) {
                // Default published event and it's not a private event

                if ($inputKey == 'start') {
                    $start = (int) $inputValue;
                } elseif ($inputKey == 'limit') {
                    $limit = $inputValue;
                } elseif ($inputKey == 'registrationType' && $inputValue != '') {
                    $solrFiledArray['registrationType'] = $inputValue;
                    if ($inputValue == 4) {
                        unset($solrFiledArray['registrationType']);
                    }
                } elseif ($inputKey == 'day') {
                    $dateResponse = '';
                    if ($inputValue == 7) {
                        $dateResponse = $this->getStartEndDateFormat($inputValue, $inputs['dateValue']);
                    } else {
                        $dateResponse = $this->getStartEndDateFormat($inputValue, '');
                    }
                    if (!empty($dateResponse)) {
                        $solrFiledArray['startDateTime'] = $dateResponse['startDateTime'];
                        $solrFiledArray['endDateTime'] = $dateResponse['endDateTime'];
                    }
                } elseif ($inputKey == 'dateValue') {
                    
                } elseif ($inputKey == 'timezoneId') {
                    
                } elseif ($inputKey == 'timeStamp') {
                    $dayValue = gmdate('Y-m-d H:i:s', strtotime($inputValue));
                    $customDateStart = date_format(date_create($dayValue), "Y-m-d\TH:i:s\Z");

                    $solrFiledArray['mts'] = '[' . $customDateStart . ' TO *]';
                } elseif ($inputKey == 'facetType') {
                    $facetname = $this->facetName($inputValue);
                    if (isset($facetname) && $facetname != '') {
                        $facet = $facetname;
                    } else {
                        return json_encode(array('response' => array("error" => "true", "status" => false, "messages" => array(ERROR_FACET_TYPE))));
                    }
                } elseif ($inputKey == 'facetValues') {
                    $inputValue_facet = '';
                    if (is_array($inputValue) && !empty($inputValue)) {
                        foreach ($inputValue as $fkey => $fvalue) {
                            $inputValue_facet.=$fvalue . ' ';
                        }
                        $inputValue_facet = rtrim($inputValue_facet, ' ');
                    } else {
                        return json_encode(array('response' => array("error" => "true", "status" => false, "messages" => array(ERROR_FACET_VALUE))));
                    }
                    $solrFiledArray['facetIds'] = '(' . $inputValue_facet . ')';
                } elseif ($inputKey == 'keyWord') {
                    //   $keyWord = str_replace(" ","\ ",$inputValue);
                    $queryParts = explode(' ', $inputValue);
                    $queryEscaped = array();

                    foreach ($queryParts as $queryPart) {
                        $queryEscaped[] = self::escapeSolrValue($queryPart);
                    }
                    $queryEscaped = join(' ', $queryEscaped);
                    $keyWord = $queryEscaped;
                    $orConditionInputs = array();
                    // Cheking in either `title` or `id` or `` seoKeyword
                    if ($keyWord != '') {
                        $orConditionInputs['title'] = '"' . $keyWord . '"';
                        $orConditionInputs['id'] = '"' . $keyWord . '"';
                        $orConditionInputs['seoKeywords'] = '"' . $keyWord . '"';
                        $orConditionInputs['eventTags'] = '"' . $keyWord . '"';
                    }
                } elseif ($inputKey == 'cityId') {
                    //$inputValue_facet = $inputValue;
                    $cityHandler = new City_handler();
                    $inputCity['cityId'] = $inputValue;
                    $inputCity['countryId'] = $inputs['countryId'];
                    $cityResponse = $cityHandler->getCityDetailById($inputCity);
                    if ($cityResponse['status'] && $cityResponse['response']['total'] > 0) {
                        $aliasCityIdStr = $cityResponse['response']['detail']['aliascityid'];
                    } else {
                        return $cityResponse;
                    }
                    $inputValue_facet = '';
                    if (isset($aliasCityIdStr) && strlen($aliasCityIdStr) > 0) {
                        $explodeAliasCityId = explode(',', $aliasCityIdStr);
                        $inputValue_facet = implode(' ', $explodeAliasCityId);
                    }
                    if (strlen($inputValue_facet) > 0) {
                        $inputValue_facet.= ' ' . $inputValue;
                    } else {
                        $inputValue_facet = $inputValue;
                    }
                    $solrFiledArray['cityId'] = '(' . $inputValue_facet . ')';
                } else if ($inputKey == 'stateId') {
                    $cityHandler = new City_handler();
                    $inputArray['stateId'] = $inputValue;
                    $specialCity = $cityHandler->checkSpecialcityState($inputArray);
                    if ($specialCity['status'] == TRUE && isset($specialCity['response']['cityId'])) {
                        $cityId = $specialCity['response']['cityId'];
                        $inputCity['cityId'] = $cityId;
                        $inputCity['countryId'] = $inputs['countryId'];
                        $cityResponse = $cityHandler->getCityDetailById($inputCity);
                        if ($cityResponse['status'] && $cityResponse['response']['total'] > 0) {
                            $aliasCityIdStr = $cityResponse['response']['detail']['aliascityid'];
                        } else {
                            return $cityResponse;
                        }
                        $inputValue_facet = '';
                        if (isset($aliasCityIdStr) && strlen($aliasCityIdStr) > 0) {
                            $explodeAliasCityId = explode(',', $aliasCityIdStr);
                            $inputValue_facet = implode(' ', $explodeAliasCityId);
                            $onlyOrCondition['cityId'] = '"' . $inputValue_facet . '"';
                            $onlyOrCondition[$inputKey] = '"' . $inputValue . '"';
                            ;
                        } else {
                            $solrFiledArray[$inputKey] = $inputValue;
                        }
                    }
                } else {
                    $solrFiledArray[$inputKey] = $inputValue;
                }
            }
            if ($facet != '' && isset($solrFiledArray['facetIds'])) {
                $solrFiledArray[$facet] = $solrFiledArray['facetIds'];
                unset($solrFiledArray['facetIds']);
            }
        } else {
            if (isset($eventIdsArray) && count($eventIdsArray) > 0) {
                $solrFiledArray['private'] = 0;
                $solrFiledArray['status'] = 1;
            }
        }

        if (isset($solrFiledArray['mts']) && $solrFiledArray['mts'] != '') {
            $mts = $solrFiledArray['mts'];
            $solrFiledArray = array();
            $solrFiledArray['mts'] = $mts;
        }
        $output = $this->ci->solrlibrary->getSolrResults($solrFiledArray, $sort, $facet, $start, $limit, 'event', $orConditionInputs, $eventIdsArray, $onlyOrCondition);

        return $output;
    }

    //facetName() for checking facet names
    public function facetName($facet) {
        $facetname = '';
        if ($facet == "city") {
            $facetname = 'cityId';
        } elseif ($facet == "state") {
            $facetname = 'stateId';
        } elseif ($facet == "category") {
            $facetname = 'categoryId';
        } elseif ($facet == "subcategory") {
            $facetname = 'subcategoryId';
        } elseif ($facet == "registrationType") {
            $facetname = 'registrationType';
        } elseif ($facet == "registrationType,eventMode") {
            $facetname = 'registrationType,eventMode';
        } else {
            $facetname = '';
        }
        return $facetname;
    }

//  getStartEndDateFormat() for start and end date convertions for solr query
    public function getStartEndDateFormat($day, $dayValue) {
        $result = array();
        // today
        if ($day == 1) {
            $today = date("Y-m-d\TH:i:s\Z");
            $todayStart = date("Y-m-d\T00:00:00\Z");
            $todayEnd = date("Y-m-d\T23:59:59\Z");
            $result['startDateTime'] = '[' . $todayStart . '/DAY-10DAY TO ' . $todayEnd . ']';
            $result['endDateTime'] = '[' . $today . ' TO *]';
        }
        // tomorrow
        elseif ($day == 2) {
            $tomorrow = date("Y-m-d\T00:00:00\Z", time() + 86400);
            $tomorrowEnd = date("Y-m-d\T23:59:59\Z", time() + 86400);
            $result['startDateTime'] = '[' . $tomorrow . '/DAY-10DAY TO ' . $tomorrowEnd . ']';
            $result['endDateTime'] = '[' . $tomorrow . ' TO *]';
        }
        // this week
        elseif ($day == 3) {
            $today = date("Y-m-d\T00:00:00\Z");
            $todayEnd = date("Y-m-d\TH:i:s\Z");
            $weeklastday = date("Y-m-d\T23:59:59\Z", strtotime('Sunday'));
            $result['startDateTime'] = '[' . $today . '/DAY-10DAY TO ' . $weeklastday . ']';
            $result['endDateTime'] = '[' . $todayEnd . ' TO *]';
        }
        // this weekend
        elseif ($day == 4) {
            $today = date("Y-m-d\TH:i:s\Z");
            $weekendSaturday = date('Y-m-d\T00:00:00\Z', strtotime("Saturday"));
            $weekendSaturdayEnd = date('Y-m-d\TH:i:s\Z', strtotime("Saturday"));
            $weekendSunday = date('Y-m-d\T23:59:59\Z', strtotime("Sunday"));
            $weekendSundayEnd = date('Y-m-d\TH:i:s\Z', strtotime("Sunday"));
            $sunday = date('D');
            if (strcmp($sunday, 'Sun') == 0) {
                $result['startDateTime'] = '[' . $weekendSaturday . '/DAY-10DAY TO ' . $weekendSunday . ']';
                $result['endDateTime'] = '[' . $today . ' TO *]';
            } else {
                $result['startDateTime'] = '[' . $weekendSaturday . '/DAY-10DAY TO ' . $weekendSunday . ']';
                $result['endDateTime'] = '[' . $weekendSaturday . ' TO *]';
            }
        }
        // this month
        elseif ($day == 5) {
            $today = date("Y-m-d\T00:00:00\Z");
            $todayNow = date("Y-m-d\TH:i:s\Z");
            $monthend = date('Y-m-t\T23:59:59\Z');
            $result['startDateTime'] = '[' . $today . '/DAY-10DAY TO ' . $monthend . ']';
            $result['endDateTime'] = '[' . $todayNow . ' TO *]';
        }
        // all time
        elseif ($day == 6) {
            $today = date("Y-m-d\TH:i:s\Z");
            $result['startDateTime'] = '[' . $today . '/DAY-10DAY TO *]';
            $result['endDateTime'] = '[' . $today . ' TO *]';
        }
        // custom date
        elseif ($day == 7) {
            $dayValue = gmdate('Y-m-d H:i:s', strtotime($dayValue));
            $customDateStart = date_format(date_create($dayValue), "Y-m-d\T00:00:00\Z");
            //$customDateEnd = date_format(date_create($dayValue), "Y-m-d\T23:59:59\Z");
            //$result['startDateTime'] = '[' . $customDateStart . '/DAY-10DAY TO ' . $customDateEnd . ']';
            //MND-2434
            $result['startDateTime'] = '[' . $customDateStart . ' TO *]';
            $result['endDateTime'] = '[' . $customDateStart . ' TO *]';
        }
        return $result;
    }

    // getFacetResult() for all facet data
    public function getFacetResult($input) {
        $solrResponce = $this->getSolrEvents($input);
        return $solrResponce;
    }

    // getDayFacetResult() for getting event count by day
    public function getDayFacetResult($input) {
        $result = array();
        $solrResponce = $solrResponce_result = '';
        $inputValues = $input;
        $days = array(1, 2, 3, 4, 5, 6);
        foreach ($days as $daysKey => $dayValue) {
            $inputValues['day'] = $dayValue;
            $inputValues['limit'] = 0;
            $solrResponce = $this->getSolrEvents($inputValues);
            $solrResponce_result = json_decode($solrResponce, true);
            if (isset($solrResponce_result['response']['error']) && $solrResponce_result['response']['error'] == 'true') {
                return $solrResponce;
            }
            $result['response']['error'] = "false";
            $result['response']['status'] = true;
            $result['response']['result'][$dayValue] = $solrResponce_result['response']['total'];
        }


        return json_encode($result);
    }

    public function getEventByUrl($dataInput) {
        if ($dataInput['id'] > 0)
            $inputs['id'] = '"' . $dataInput['id'] . '"';
        else
            $inputs['url'] = '"' . $dataInput['eventUrl'] . '"';

        if ($dataInput['private']) {
            $inputs['private'] = TRUE;
        }
        $sort = $facet = '';
        $start = 0;
        $limit = 13;
        $solrFiledArray = $dateResponse = array();
        $sort['popularity'] = 'desc';
        $sort['totalsoldtickets'] = 'desc';
        $sort['startDateTime'] = 'asc';

        //validations
        $this->ci->form_validation->pass_array($inputs);
        if ($dataInput['id'] > 0)
            $this->ci->form_validation->set_rules('id', 'Id', 'required_strict');
        else
            $this->ci->form_validation->set_rules('url', 'EventUrl', 'required_strict');

        if (!empty($inputs) && $this->ci->form_validation->run() == FALSE) {

            $output['response'] = $this->ci->form_validation->get_errors();
            $output['response'] = $output['response'];
            $output = json_encode($output);
            return $output;
        }

        if (!empty($inputs)) {
            if (isset($dataInput['status'])) {
                $solrFiledArray['status'] = $dataInput['status'];
            }


            foreach ($inputs as $inputKey => $inputValue) {
                // Default published event and it's not a private event


                if ($inputKey == 'start') {
                    $start = (int) $inputValue;
                } elseif ($inputKey == 'limit') {
                    $limit = $inputValue;
                } elseif ($inputKey == 'day') {
                    $dateResponse = '';
                    $dateResponse = $this->getStartEndDateFormat($inputValue);
                    if (!empty($dateResponse)) {
                        $solrFiledArray['startDateTime'] = $dateResponse['startDateTime'];
                        $solrFiledArray['endDateTime'] = $dateResponse['endDateTime'];
                    }
                } elseif ($inputKey == 'dateValue') {
                    $dateResponse = '';
                    $dateResponse = $this->getStartEndDateFormat($inputValue);
                    if (!empty($dateResponse)) {
                        $solrFiledArray['startDateTime'] = $dateResponse['startDateTime'];
                        $solrFiledArray['endDateTime'] = $dateResponse['endDateTime'];
                    }
                } elseif ($inputKey == 'facetType') {
                    $facetname = $this->facetName($inputValue);
                    if (isset($facetname) && $facetname != '') {
                        $facet = $facetname;
                    } else {
                        return json_encode(array('response' => array("error" => "true", "status" => false, "messages" => array(ERROR_FACET_TYPE))));
                    }
                } elseif ($inputKey == 'facetValues') {
                    $inputValue_facet = '';
                    if (is_array($inputValue) && !empty($inputValue)) {
                        foreach ($inputValue as $fkey => $fvalue) {
                            $inputValue_facet.=$fvalue . ' ';
                        }
                        $inputValue_facet = rtrim($inputValue_facet, ' ');
                    } else {
                        return json_encode(array('response' => array("error" => "true", "status" => false, "messages" => array(ERROR_FACET_VALUE))));
                    }
                    $solrFiledArray['facetIds'] = '(' . $inputValue_facet . ')';
                } else if ($inputKey == 'private') {
                    unset($solrFiledArray[$inputKey]);
                } else {
                    $solrFiledArray[$inputKey] = $inputValue;
                }
            }
            if ($facet != '') {
                $solrFiledArray[$facet] = $solrFiledArray['facetIds'];
                unset($solrFiledArray['facetIds']);
            }
        }
        $output = $this->ci->solrlibrary->getSolrResults($solrFiledArray, $sort, $facet, $start, $limit, 'event');
        $solrResponce_result = json_decode($output, true);

        if (isset($solrResponce_result['response']['error']) && $solrResponce_result['response']['error'] == 'true' || isset($solrResponce_result['Error'])) {
            $result['status'] = false;
            $result ['messages'] = $solrResponce_result['Error'];
            return $result;
        }


        $eventsCount = count($solrResponce_result['response']['events']);
        //Zeror records found
        if ($eventsCount == 0) {
            return array("status" => false, "messages" => array(ERROR_NO_DATA));
        }
        //More than two event ids are found
        if ($eventsCount > 1) {
            return array("status" => false, "messages" => array(ERROR_EVENT_URL));
        }

        $result['status'] = true;
        $result['response']['eventId'] = $solrResponce_result['response']['events'][0]['id'];
        return $result;
    }

    /* Add Event in solr     

     * Data Input: id,stateId,venueName,timezoneId,status,registrationType,startDateTime,seoTitle,endDateTime,
      countryId,url,private,cityId,seoKeywords,title,categoryId,thumbImage,eventMode,ticketSoldout,subcategoryId,seoDescription,
      popularity;
     * id is required field
     */

    public function solrAddEvent($dataInput) {
        $output = array();
        $this->ci->form_validation->pass_array($dataInput);
        $this->ci->form_validation->set_rules('id', 'Id', 'required_strict|is_natural_no_zero');
        $this->ci->form_validation->set_rules('stateId', 'StateId', 'is_natural_no_zero');
        $this->ci->form_validation->set_rules('timezoneId', 'TimezoneId', 'is_natural_no_zero');
        $this->ci->form_validation->set_rules('registrationType', 'RegistrationType', 'is_natural_no_zero');
        $this->ci->form_validation->set_rules('countryId', 'CountryId', 'is_natural_no_zero');
        $this->ci->form_validation->set_rules('private', 'Private', 'enable');
        $this->ci->form_validation->set_rules('cityId', 'CityId', 'is_natural_no_zero');
        $this->ci->form_validation->set_rules('categoryId', 'CategoryId', 'is_natural_no_zero');
        $this->ci->form_validation->set_rules('eventMode', 'EventMode', 'enable');
        $this->ci->form_validation->set_rules('subcategoryId', 'SubcategoryId', 'is_natural_no_zero');
        $this->ci->form_validation->set_rules('popularity', 'Popularity', 'is_natural');
        if ($this->ci->form_validation->run() == FALSE) {
            $response = $this->ci->form_validation->get_errors();
            $output['status'] = FALSE;
            $output['response']['messages'] = $response['message'];
            return $output;
        } else {
            if (isset($dataInput['startDateTime'])) {
                $dataInput['startDateTime'] = date("Y-m-d\TH:i:s\Z", strtotime($dataInput['startDateTime']));
            }
            if (isset($dataInput['endDateTime'])) {
                $dataInput['endDateTime'] = date("Y-m-d\TH:i:s\Z", strtotime($dataInput['endDateTime']));
            }
            $solrInputArray = array();
            $solrInputArray['id'] = $dataInput['id'];

            // Event id is existed or not in solr
            $soalrEvent = $this->ci->solrlibrary->getSolrResults($solrInputArray, '', '', 0, 10, 'event');
            $soalrEvent = json_decode($soalrEvent, true);
            if (isset($soalrEvent['Error'])) {
                $output['status'] = FALSE;
                $output['response']['messages'] = $soalrEvent['Error'];
                return $output;
            }
            // if Event id is existed need to updated data
            if (isset($soalrEvent['response']['total']) && $soalrEvent['response']['total'] == 1) {
                $response = $this->solrUpdateEvent($dataInput);
                return $response;
            }
            // Event id is found need to inseart data
            if (isset($soalrEvent['response']['total']) && $soalrEvent['response']['total'] == 0) {
                $response = $this->ci->solrlibrary->addSolr($dataInput, 'event');
                if (isset($response['http_code']) && $response['http_code'] == 200) {
                    $output['status'] = TRUE;
                    $output['response']['messages'][] = SOLR_EVENT_CREATE;
                    $output['statusCode'] = STATUS_CREATED;
                } else {
                    $output['status'] = FALSE;
                    $output['response']['messages'][] = ERROR_SOMETHING_WENT_WRONG;
                    $output['statusCode'] = $response['http_code'];
                }
                return $output;
            }
        }
    }

    /* Delete Event from solr
     * Data Input: id
     * id is required
     */

    public function solrDeleteEvent($dataInput) {
        $this->ci->form_validation->pass_array($dataInput);
        $this->ci->form_validation->set_rules('id', 'Id', 'required_strict|is_natural_no_zero');
        if ($this->ci->form_validation->run() == FALSE) {
            $response = $this->ci->form_validation->get_errors();
            $output['status'] = FALSE;
            $output['response']['messages'] = $response['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }

        $response = $this->ci->solrlibrary->deleteSolr($dataInput['id'], 'event');
        if (isset($response['http_code']) && $response['http_code'] == 200) {
            $output['status'] = TRUE;
            $output['response']['messages'][] = SOLR_EVENT_DELETE;
            $output['statusCode'] = $response['http_code'];
            return $output;
        } else {
            $output['status'] = FALSE;
            $output['response']['messages'][] = ERROR_SOMETHING_WENT_WRONG;
            $output['statusCode'] = $response['http_code'];
            return $output;
        }
    }

    /* Update Event in solr     

     * Data Input: id,stateId,venueName,timezoneId,status,registrationType,startDateTime,seoTitle,endDateTime,
      countryId,url,private,cityId,seoKeywords,title,categoryId,thumbImage,eventMode,ticketSoldout,subcategoryId,seoDescription,
      popularity;
     * id is required field
     */

    public function solrUpdateEvent($dataInput) {
        $updatedInput = $output = array();

        $this->ci->form_validation->pass_array($dataInput);
        $this->ci->form_validation->set_rules('id', 'Id', 'required_strict|is_natural_no_zero');
        $this->ci->form_validation->set_rules('stateId', 'StateId', 'is_natural_no_zero');
        $this->ci->form_validation->set_rules('timezoneId', 'TimezoneId', 'is_natural_no_zero');
        $this->ci->form_validation->set_rules('registrationType', 'RegistrationType', 'is_natural_no_zero');
        $this->ci->form_validation->set_rules('countryId', 'CountryId', 'is_natural_no_zero');
        $this->ci->form_validation->set_rules('private', 'Private', 'enable');
        $this->ci->form_validation->set_rules('cityId', 'CityId', 'is_natural_no_zero');
        $this->ci->form_validation->set_rules('categoryId', 'CategoryId', 'is_natural_no_zero');
        $this->ci->form_validation->set_rules('eventMode', 'EventMode', 'enable');
        $this->ci->form_validation->set_rules('subcategoryId', 'SubcategoryId', 'is_natural_no_zero');
        $this->ci->form_validation->set_rules('popularity', 'Popularity', 'is_natural');
        if ($this->ci->form_validation->run() == FALSE) {
            $response = $this->ci->form_validation->get_errors();
            $output['status'] = FALSE;
            $output['response']['messages'] = $response['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
        } else {

            if (isset($dataInput['startDateTime'])) {
                $dataInput['startDateTime'] = date("Y-m-d\TH:i:s\Z", strtotime($dataInput['startDateTime']));
            }
            if (isset($dataInput['endDateTime'])) {
                $dataInput['endDateTime'] = date("Y-m-d\TH:i:s\Z", strtotime($dataInput['endDateTime']));
            }

            if (!empty($dataInput)) {
                foreach ($dataInput as $dataInputKey => $dataInputValue) {
                    if ($dataInputKey == 'id') {
                        $updatedInput[$dataInputKey] = $dataInputValue;
                    } else {
                        $updatedInput[$dataInputKey] = array("set" => $dataInputValue);
                    }
                }
            }
            $response = $this->ci->solrlibrary->updateSolr($updatedInput, 'event');
            if (isset($response['http_code']) && $response['http_code'] == 200) {
                $output['status'] = TRUE;
                $output['response']['messages'][] = SOLR_EVENT_UPDATE;
                //$output['response']['status']=TRUE;
                $output['statusCode'] = STATUS_OK;
            } else {
                $output['status'] = FALSE;
                $output['response']['messages'][] = ERROR_SOMETHING_WENT_WRONG;
                $output['statusCode'] = $response['http_code'];
            }
        }
        return $output;
    }

    /*
     * Getting Solr Tags  
     * Ipnputs: keyword, limit
     */

    public function getSolrTags($inputs = '') {
        $start = 0;
        $limit = 5;
        $solrInput = array();
        if (isset($inputs) && !empty($inputs)) {
            foreach ($inputs as $inputKey => $inputValue) {
                if ($inputKey == 'start') {
                    $start = (int) $inputValue;
                } elseif ($inputKey == 'limit') {
                    $limit = $inputValue;
                } elseif ($inputKey == 'keyword') {
                    $solrInput['name'] = '*' . $inputValue . '*';
                }
            }
        }
        $solrResults = $this->ci->solrlibrary->getSolrResults($solrInput, '', '', $start, $limit, 'tag');
        $solrResults = json_decode($solrResults, true);
        if ((isset($solrResults["response"]["error"])) && $solrResults["response"]["error"] == true) {
            return $solrResults;
        }
        $solrtagsList = $tagsList = array();
        $solrtagsList = $solrResults["response"]["events"];
        if (count($solrtagsList) > 0) {
            $tagsList['status'] = TRUE;
            $tagsList['response']['total'] = $solrResults["response"]['total'];
            foreach ($solrtagsList as $recordKey => $recordValue) {
                $tagsList['response']['tags'][$recordKey]['id'] = $recordValue["id"];
                $tagsList['response']['tags'][$recordKey]['name'] = $recordValue["name"];
            }

            return $tagsList;
        } else {
            $tagsList['status'] = TRUE;
            $tagsList['response']['total'] = 0;
            $tagsList['response']['tags'] = array();
            $tagsList['response']['messages'][] = ERROR_NO_DATA;
            return $tagsList;
        }
    }

    /* Add Tags in solr     

     * Data Input: id,name
     * id, name is required field
     */

    public function solrAddTag($dataInput) {
        $this->ci->form_validation->pass_array($dataInput);
        $this->ci->form_validation->set_rules('id', 'Id', 'required_strict|is_natural_no_zero');
        $this->ci->form_validation->set_rules('name', 'Name', 'tag|required_strict');

        if ($this->ci->form_validation->run() == FALSE) {
            $response = $this->ci->form_validation->get_errors();
            $output['status'] = FALSE;
            $output['response']['messages'] = $response['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        } else {
            if (isset($dataInput['startDateTime'])) {
                $dataInput['startDateTime'] = date("Y-m-d\TH:i:s\Z", strtotime($dataInput['startDateTime']));
            }
            if (isset($dataInput['endDateTime'])) {
                $dataInput['endDateTime'] = date("Y-m-d\TH:i:s\Z", strtotime($dataInput['endDateTime']));
            }
            $response = $this->ci->solrlibrary->addSolr($dataInput, 'tag');

            if (isset($response['http_code']) && $response['http_code'] == 200) {
                $output['status'] = TRUE;
                $output['response']['messages'][] = SOLR_TAG_CREATE;
                $output['response']['total'] = 1;
                $output['statusCode'] = STATUS_OK;
                return $output;
            } else {
                $output['status'] = FALSE;
                $output['response']['messages'][] = ERROR_SOMETHING_WENT_WRONG;
                $output['statusCode'] = $response['http_code'];
                return $output;
            }
        }
    }

    /* Delete tag in solr
     *  Data Input: id
     * id is required
     */

    public function solrDeleteTag($dataInput, $collectionType) {
        $output = array();

        $this->ci->form_validation->pass_array($dataInput);
        $this->ci->form_validation->set_rules('id', 'Id', 'required_strict|is_natural_no_zero');
        if ($this->ci->form_validation->run() == FALSE) {
            $response = $this->ci->form_validation->get_errors();
            $output['status'] = FALSE;
            $output['response']['messages'] = $response['message'];
            return $output;
        }

        $response = $this->ci->solrlibrary->deleteSolr($dataInput['id'], $collectionType);
        if (isset($response['http_code']) && $response['http_code'] == 200) {
            $output['status'] = TRUE;
            $output['response']['messages'][] = SOLR_TAG_DELETE;
            $output['response']['status'] = TRUE;
        }
        $output['status'] = FALSE;
        $output['response']['messages'] = array("error_code" => $response['http_code']);
        $output['response']['status'] = FALSE;
        return $output;
    }

    /* Update Tags in solr     

     * Data Input: id,name
     * id,name is required field
     */

    public function solrUpdateTag($dataInput) {
        $updatedInput = $output = array();

        $this->ci->form_validation->pass_array($dataInput);
        $this->ci->form_validation->set_rules('id', 'Id', 'required_strict|is_natural_no_zero');
        $this->ci->form_validation->set_rules('name', 'Name', 'tag|required_strict');

        if ($this->ci->form_validation->run() == FALSE) {
            $response = $this->ci->form_validation->get_errors();
            $output['status'] = FALSE;
            $output['response']['messages'] = $response['message'];
        } else {

            if (!empty($dataInput)) {
                foreach ($dataInput as $dataInputKey => $dataInputValue) {
                    if ($dataInputKey == 'id') {
                        $updatedInput[$dataInputKey] = $dataInputValue;
                    } else {
                        $updatedInput[$dataInputKey] = array("set" => $dataInputValue);
                    }
                }
            }
            $response = $this->ci->solrlibrary->updateSolr($updatedInput, 'tag');
            if (isset($response['http_code']) && $response['http_code'] == 200) {
                $output['status'] = TRUE;
                $output['response']['messages'][] = SOLR_TAG_UPDATE;
            } else {
                $output['status'] = FALSE;
                $output['response']['messages'] = array("error_code" => $response['http_code']);
            }
        }
        return $output;
    }

    public function solrUpdateEventLimitOne($dataInput) {
        $updatedInput = $output = array();
        //print_r($dataInput);exit;
        $this->ci->form_validation->pass_array($dataInput);
        $this->ci->form_validation->set_rules('id', 'Id', 'required_strict|is_natural_no_zero');
        $this->ci->form_validation->set_rules('limitsingletickettype', 'limitsingletickettype', 'enable');

        if ($this->ci->form_validation->run() == FALSE) {
            $response = $this->ci->form_validation->get_errors();
            $output['status'] = FALSE;
            $output['response']['messages'] = $response['message'];
            return $output;
        }

        foreach ($dataInput as $dataInputKey => $dataInputValue) {
            if ($dataInputKey == 'id') {
                $updatedInput[$dataInputKey] = $dataInputValue;
            } else {
                $updatedInput[$dataInputKey] = array("set" => $dataInputValue);
            }
        }
        $response = $this->ci->solrlibrary->updateSolr($updatedInput, 'event');
        //print_r($response);exit;
        if (isset($response['http_code']) && $response['http_code'] == 200) {
            $output['status'] = TRUE;
            $output['response']['messages'][] = SOLR_EVENT_UPDATE;
            return $output;
        }
        $output['status'] = FALSE;
        $output['response']['messages'] = array("error_code" => $response['http_code']);
        return $output;
    }

    /*
     * Function to search the events
     *
     * @access	public
     * @param
     *      	`keyWord` - Mandatory
     *      	`countryId` - optional
     *      	`stateId` - optional
     *      	`cityId` - optional
     *      	`categoryId` - optional
     *      	`subcategoryId` - optional
     * 			`day` - optional
     * 					- `1` (today)
     * 					- `2` (tomorrow)
     * 					- `3` (this week)
     * 					- `4` (this weekend)
     * 					- `5` (this month)
     * 					- `6` (all time)
     * 					- `7` (custom date)
     * 			`dateValue` - optional for custom date
     *      	`start` - optional
     *      	`limit` - optional (By default it takes 12)
     * @return	json formatted event result array based on search criteria
     */

    public function getSolrEventsBySearch($inputs) {
        $recordsPerPage = (isset($inputs['limit'])) ? $inputs['limit'] : 12;
        $inputs['start'] = (isset($inputs['page'])) ? ($inputs['page'] - 1) * $recordsPerPage : 0;
        unset($inputs['page']);
        $sort = $facet = '';
        $start = 0;
        $limit = 12;
        if (!isset($inputs['day'])) {
            $inputs['day'] = 6;
        }
        $solrFiledArray = $dateResponse = array();
        $sort['popularity'] = 'desc';
        $sort['totalsoldtickets'] = 'desc';
        $sort['startDateTime'] = 'asc';

        //validations
        $this->ci->form_validation->pass_array($inputs);
        $this->ci->form_validation->set_rules('keyWord', 'keyWord', 'required_strict');
        $this->ci->form_validation->set_rules('countryId', 'CountryId', 'is_natural_no_zero');
        $this->ci->form_validation->set_rules('stateId', 'StateId', 'is_natural_no_zero');
        $this->ci->form_validation->set_rules('cityId', 'CityId', 'is_natural_no_zero');
        $this->ci->form_validation->set_rules('categoryId', 'CategoryId', 'is_natural_no_zero');
        $this->ci->form_validation->set_rules('subcategoryId', 'SubcategoryId', 'is_natural_no_zero');

        $this->ci->form_validation->set_rules('start', 'Start', 'is_natural');
        $this->ci->form_validation->set_rules('limit', 'Limit', 'is_natural');
        if (isset($inputs['cityId']) && $inputs['cityId'] == 0)
            unset($inputs['cityId']);



        if ($this->ci->form_validation->run() == FALSE) {

            $output['status'] = FALSE;
            $output['statusCode'] = 400;
            $output['response'] = $this->ci->form_validation->get_errors();
            $output = json_encode($output);
            return $output;
        }
        if (!empty($inputs)) {
            $queryParts = explode(' ', $inputs['keyWord']);
            $queryEscaped = array();
            foreach ($queryParts as $queryPart) {
                $queryEscaped[] = self::escapeSolrValue($queryPart);
            }
            $queryEscaped = join(' ', $queryEscaped);
            $keyWord = $queryEscaped;
            unset($inputs['keyWord']);

            foreach ($inputs as $inputKey => $inputValue) {
                // Default published event and it's not a private event
                $solrFiledArray['private'] = 0;
                $solrFiledArray['status'] = 1;
                if ($inputKey == 'start') {
                    $start = (int) $inputValue;
                } elseif ($inputKey == 'limit') {
                    $limit = $inputValue;
                } elseif ($inputKey == 'registrationType' && $inputValue != '') {
                    $solrFiledArray['registrationType'] = $inputValue;
                } elseif ($inputKey == 'day') {
                    $dateResponse = '';
                    if ($inputValue == 7) {
                        $dateResponse = $this->getStartEndDateFormat($inputValue, $inputs['dateValue']);
                    } else {
                        $dateResponse = $this->getStartEndDateFormat($inputValue, '');
                    }
                    if (!empty($dateResponse)) {
                        $solrFiledArray['startDateTime'] = $dateResponse['startDateTime'];
                        $solrFiledArray['endDateTime'] = $dateResponse['endDateTime'];
                    }
                } elseif ($inputKey == 'dateValue') {
                    
                } elseif ($inputKey == 'timezoneId') {
                    
                } elseif ($inputKey == 'facetType') {
                    $facetname = $this->facetName($inputValue);
                    if (isset($facetname) && $facetname != '') {
                        $facet = $facetname;
                    } else {
                        return json_encode(array('response' => array("error" => "true", "status" => false, "messages" => array(ERROR_FACET_TYPE))));
                    }
                } elseif ($inputKey == 'facetValues') {
                    $inputValue_facet = '';
                    if (is_array($inputValue) && !empty($inputValue)) {
                        foreach ($inputValue as $fkey => $fvalue) {
                            $inputValue_facet.=$fvalue . ' ';
                        }
                        $inputValue_facet = rtrim($inputValue_facet, ' ');
                    } else {
                        return json_encode(array('response' => array("error" => "true", "status" => false, "messages" => array(ERROR_FACET_VALUE))));
                    }
                    $solrFiledArray['facetIds'] = '(' . $inputValue_facet . ')';
                } elseif ($inputKey == 'cityId') {
                    //$inputValue_facet = $inputValue;
                    $cityHandler = new City_handler();
                    $inputCity['cityId'] = $inputValue;
                    $inputCity['countryId'] = $inputs['countryId'];
                    $cityResponse = $cityHandler->getCityDetailById($inputCity);
                    if ($cityResponse['status'] && $cityResponse['response']['total'] > 0) {
                        $aliasCityIdStr = $cityResponse['response']['detail']['aliascityid'];
                    } else {
                        return $cityResponse;
                    }
                    $inputValue_facet = '';
                    if (isset($aliasCityIdStr) && strlen($aliasCityIdStr) > 0) {
                        $explodeAliasCityId = explode(',', $aliasCityIdStr);
                        $inputValue_facet = implode(' ', $explodeAliasCityId);
                    }
                    if (strlen($inputValue_facet) > 0) {
                        $inputValue_facet.= ' ' . $inputValue;
                    } else {
                        $inputValue_facet = $inputValue;
                    }
                    $solrFiledArray['cityId'] = '(' . $inputValue_facet . ')';
                } else if ($inputKey == 'stateId') {
                    $cityHandler = new City_handler();
                    $inputArray['stateId'] = $inputValue;
                    $specialCity = $cityHandler->checkSpecialcityState($inputArray);
                    if ($specialCity['status'] == TRUE && isset($specialCity['response']['cityId'])) {
                        $cityId = $specialCity['response']['cityId'];
                        $inputCity['cityId'] = $cityId;
                        $inputCity['countryId'] = $inputs['countryId'];
                        $cityResponse = $cityHandler->getCityDetailById($inputCity);

                        if ($cityResponse['status'] && $cityResponse['response']['total'] > 0) {
                            $aliasCityIdStr = $cityResponse['response']['detail']['aliascityid'];
                        } else {
                            return $cityResponse;
                        }
                        $inputValue_facet = '';
                        if (isset($aliasCityIdStr) && strlen($aliasCityIdStr) > 0) {
                            $explodeAliasCityId = explode(',', $aliasCityIdStr);
                            $inputValue_facet = implode(' ', $explodeAliasCityId);
                            $onlyOrCondition['cityId'] = '"' . $inputValue_facet . '"';
                            $onlyOrCondition[$inputKey] = '"' . $inputValue . '"';
                            ;
                        } else {
                            $solrFiledArray[$inputKey] = $inputValue;
                        }
                    }
                } else {
                    $solrFiledArray[$inputKey] = $inputValue;
                }
            }
            if ($facet != '' && isset($solrFiledArray['facetIds'])) {
                $solrFiledArray[$facet] = $solrFiledArray['facetIds'];
                unset($solrFiledArray['facetIds']);
            }
            //	$keyWord = str_replace(" ","\ ",$keyWord);

            $orConditionInputs = array();
            // Cheking in either `title` or `id` or `` seoKeyword
            $orConditionInputs['title'] = '"' . $keyWord . '"';
            $orConditionInputs['id'] = '"' . $keyWord . '"';
            $orConditionInputs['seoKeywords'] = '"' . $keyWord . '"';
            $orConditionInputs['eventTags'] = '"' . $keyWord . '"';
            if (is_numeric($keyWord)) {
                //$solrFiledArray['private'] = 0;
                unset($solrFiledArray['private']);
                $solrFiledArray['status'] = 1;
                unset($solrFiledArray['endDateTime']);
                unset($solrFiledArray['startDateTime']);
            } else {
                $today = date("Y-m-d\TH:i:s\Z");
                $solrFiledArray['endDateTime'] = '[' . $today . ' TO *]';
                $solrFiledArray['private'] = 0;
                $solrFiledArray['ticketSoldout'] = 0;
                $solrFiledArray['status'] = 1;
            }
        }
        if (isset($inputs['isAutoComplete']) && $inputs['isAutoComplete']) {
            unset($solrFiledArray['startDateTime']);
            unset($solrFiledArray['isAutoComplete']);
            unset($orConditionInputs['seoKeywords']);
            unset($orConditionInputs['eventTags']);
            $sort = "";
        }
        $output = $this->ci->solrlibrary->getSolrResults($solrFiledArray, $sort, $facet, $start, $limit, 'event', $orConditionInputs, $eventIdsArray, $onlyOrCondition);

        return $output;
    }

    /**
     * To get the passed tags information
     * @param type $inputs
     * @return type
     */
    public function getTagsDetails($inputs) {
        $start = 0;
        $limit = 20;
        $solrInput = array();

        if (isset($inputs) && !empty($inputs)) {
            foreach ($inputs as $inputKey => $inputValue) {
                if ($inputKey == 'start') {
                    $start = (int) $inputValue;
                } elseif ($inputKey == 'limit') {
                    $limit = $inputValue;
                } elseif ($inputKey == 'id') {
                    $solrInput['id'] = $inputValue;
                }
            }
        }
        $solrResults = $this->ci->solrlibrary->getSolrResults($solrInput, '', '', $start, $limit, 'tag');
        $solrResults = json_decode($solrResults, true);

        if ((isset($solrResults["response"]["error"])) && $solrResults["response"]["error"] == true) {
            return $solrResults;
        }
        $solrtagsList = $tagsList = array();
        $solrtagsList = $solrResults["response"]["events"];

        if (count($solrtagsList) > 0) {
            $tagsList['status'] = TRUE;
            $tagsList['response']['total'] = $solrResults["response"]['total'];
            $tagsList['response']['tags'] = $solrResults["response"]['events'];
        } else {
            $tagsList['status'] = TRUE;
            $tagsList['response']['total'] = 0;
            $tagsList['response']['tags'] = array();
            $tagsList['response']['messages'][] = ERROR_NO_DATA;
        }
        return $tagsList;
    }

    static public function escapeSolrValue($string) {
        $match = array('\\', '+', '-', '&', '|', '!', '(', ')', '{', '}', '[', ']', '^', '~', '*', '?', ':', '"', ';', ' ');
        $replace = array('\\\\', '\\+', '\\-', '\\&', '\\|', '\\!', '\\(', '\\)', '\\{', '\\}', '\\[', '\\]', '\\^', '\\~', '\\*', '\\?', '\\:', '\\"', '\\;', '\\ ');
        $string = str_replace($match, $replace, $string);

        return $string;
    }

    /*
     * Function to search the events
     *
     * @access	public
     * @param
     *      	`countryId` - optional
     *      	`stateId` - optional
     *      	`cityId` - optional
     *      	`categoryId` - optional
     *      	`subcategoryId` - optional
     * 			`day` - optional
     * 					- `1` (today)
     * 					- `2` (tomorrow)
     * 					- `3` (this week)
     * 					- `4` (this weekend)
     * 					- `5` (this month)
     * 					- `6` (all time)
     * 					- `7` (custom date)
     * 			`dateValue` - optional for custom date
     *      	`start` - optional
     *      	`limit` - optional (By default it takes 12)
     * @return	json formatted event result array based on search criteria
     */

    public function getSolrEventsByPlaces($inputs) {
        $recordsPerPage = (isset($inputs['limit'])) ? $inputs['limit'] : 12;
        $inputs['start'] = (isset($inputs['page'])) ? ($inputs['page'] - 1) * $recordsPerPage : 0;
        unset($inputs['page']);
        $sort = $facet = '';
        $start = 0;
        $limit = 12;
        $solrFiledArray = $dateResponse = array();
        $sort['popularity'] = 'desc';
        $sort['totalsoldtickets'] = 'desc';
        $sort['startDateTime'] = 'asc';

        //validations
        $this->ci->form_validation->pass_array($inputs);
        $this->ci->form_validation->set_rules('countryId', 'CountryId', 'is_natural_no_zero');
        $this->ci->form_validation->set_rules('stateId', 'StateId', 'is_natural_no_zero');
        $this->ci->form_validation->set_rules('cityId', 'CityId', 'is_natural_no_zero');

        $this->ci->form_validation->set_rules('start', 'Start', 'is_natural');
        $this->ci->form_validation->set_rules('limit', 'Limit', 'is_natural');
        if (isset($inputs['cityId']) && $inputs['cityId'] == 0)
            unset($inputs['cityId']);



        if ($this->ci->form_validation->run() == FALSE) {
            $output['status'] = FALSE;
            $output['statusCode'] = 400;
            $output['response'] = $this->ci->form_validation->get_errors();
            $output = json_encode($output);
            return $output;
        }
        if (!empty($inputs)) {

            foreach ($inputs as $inputKey => $inputValue) {
                // Default published event and it's not a private event
                $solrFiledArray['private'] = 0;
                $solrFiledArray['status'] = 1;
                if ($inputKey == 'start') {
                    $start = (int) $inputValue;
                } elseif ($inputKey == 'limit') {
                    $limit = $inputValue;
                } elseif ($inputKey == 'registrationType' && $inputValue != '') {
                    $solrFiledArray['registrationType'] = $inputValue;
                } elseif ($inputKey == 'day') {
                    $dateResponse = '';
                    if ($inputValue == 7) {
                        $dateResponse = $this->getStartEndDateFormat($inputValue, $inputs['dateValue']);
                    } else {
                        $dateResponse = $this->getStartEndDateFormat($inputValue, '');
                    }
                    if (!empty($dateResponse)) {
                        $solrFiledArray['startDateTime'] = $dateResponse['startDateTime'];
                        $solrFiledArray['endDateTime'] = $dateResponse['endDateTime'];
                    }
                } elseif ($inputKey == 'dateValue') {
                    
                } elseif ($inputKey == 'timezoneId') {
                    
                } elseif ($inputKey == 'facetType') {
                    $facetname = $this->facetName($inputValue);
                    if (isset($facetname) && $facetname != '') {
                        $facet = $facetname;
                    } else {
                        return json_encode(array('response' => array("error" => "true", "status" => false, "messages" => array(ERROR_FACET_TYPE))));
                    }
                } elseif ($inputKey == 'facetValues') {
                    $inputValue_facet = '';
                    if (is_array($inputValue) && !empty($inputValue)) {
                        foreach ($inputValue as $fkey => $fvalue) {
                            $inputValue_facet.=$fvalue . ' ';
                        }
                        $inputValue_facet = rtrim($inputValue_facet, ' ');
                    } else {
                        return json_encode(array('response' => array("error" => "true", "status" => false, "messages" => array(ERROR_FACET_VALUE))));
                    }
                    $solrFiledArray['facetIds'] = '(' . $inputValue_facet . ')';
                } elseif ($inputKey == 'cityId') {

                    $cityHandler = new City_handler();
                    $inputCity['cityId'] = $inputValue;
                    $inputCity['countryId'] = $inputs['countryId'];
                    $cityResponse = $cityHandler->getCityDetailById($inputCity);

                    if ($cityResponse['status'] && $cityResponse['response']['total'] > 0) {
                        $aliasCityIdStr = $cityResponse['response']['detail']['aliascityid'];
                    } else {
                        return $cityResponse;
                    }
                    $inputValue_facet = '';
                    if (isset($aliasCityIdStr) && strlen($aliasCityIdStr) > 0) {
                        $explodeAliasCityId = explode(',', $aliasCityIdStr);
                        $inputValue_facet = implode(' ', $explodeAliasCityId);
                    }
                    if (strlen($inputValue_facet) > 0) {
                        $inputValue_facet.= ' ' . $inputValue;
                    } else {
                        $inputValue_facet = $inputValue;
                    }
                    $solrFiledArray['cityId'] = '(' . $inputValue_facet . ')';
                } else {
                    $solrFiledArray[$inputKey] = $inputValue;
                }
            }
            if ($facet != '' && isset($solrFiledArray['facetIds'])) {
                $solrFiledArray[$facet] = $solrFiledArray['facetIds'];
                unset($solrFiledArray['facetIds']);
            }

            $today = date("Y-m-d\TH:i:s\Z");
            $solrFiledArray['endDateTime'] = '[' . $today . ' TO *]';
            $solrFiledArray['private'] = 0;
            $solrFiledArray['ticketSoldout'] = 0;
            $solrFiledArray['status'] = 1;
        }
        $output = $this->ci->solrlibrary->getSolrResults($solrFiledArray, $sort, $facet, $start, $limit, 'event', $orConditionInputs);
        return $output;
    }

    public function getSolrEvents_mobile($inputs) {

        $sort = $facet = '';
        $start = 0;
        $limit = 12;
        $solrFiledArray = $dateResponse = array();
        $sort['popularity'] = 'desc';
        $sort['totalsoldtickets'] = 'desc';
        $sort['startDateTime'] = 'asc';

        //validations
        if (isset($inputs['timeStamp']) && $inputs['timeStamp'] != '') {
            $limit = $inputs['limit'];
        } else {
            $this->ci->form_validation->pass_array($inputs);
            $eventIdsArray = array();
            if (isset($inputs['eventIdsArray']) && count($inputs['eventIdsArray']) > 0) {

                $eventIdsArray['id'] = $inputs['eventIdsArray'];
                unset($inputs['eventIdsArray']);
                $this->ci->form_validation->set_rules('countryId', 'CountryId', 'is_natural_no_zero');
            } elseif (isset($inputs['mobile']) && $inputs['mobile']) {
                $this->ci->form_validation->set_rules('countryId', 'CountryId', 'is_natural_no_zero');
                unset($inputs['mobile']);
            } else {
                $this->ci->form_validation->set_rules('countryId', 'CountryId', 'required_strict|is_natural_no_zero');
            }

            $this->ci->form_validation->set_rules('stateId', 'StateId', 'is_natural_no_zero');
            $this->ci->form_validation->set_rules('cityId', 'CityId', 'is_natural_no_zero');
            $this->ci->form_validation->set_rules('categoryId', 'CategoryId', 'is_natural_no_zero');
            $this->ci->form_validation->set_rules('subcategoryId', 'SubcategoryId', 'is_natural_no_zero');

            $this->ci->form_validation->set_rules('start', 'Start', 'is_natural');
            $this->ci->form_validation->set_rules('limit', 'Limit', 'is_natural');
            if (isset($inputs['cityId']) && $inputs['cityId'] == 0)
                unset($inputs['cityId']);
            $orConditionInputs = array();
            if (!empty($inputs) && $this->ci->form_validation->run() == FALSE) {

                $output['response'] = $this->ci->form_validation->get_errors();
                $output = json_encode($output);
                return $output;
            }
        }


        if (!empty($inputs)) {
            foreach ($inputs as $inputKey => $inputValue) {
                // Default published event and it's not a private event

                if ($inputKey == 'start') {
                    $start = (int) $inputValue;
                } elseif ($inputKey == 'limit') {
                    $limit = $inputValue;
                } elseif ($inputKey == 'registrationType' && $inputValue != '') {
                    $solrFiledArray['registrationType'] = $inputValue;
                    if ($inputValue == 4) {
                        unset($solrFiledArray['registrationType']);
                    }
                } elseif ($inputKey == 'day') {
                    $dateResponse = '';
                    if ($inputValue == 7) {
                        $dateResponse = $this->getStartEndDateFormat($inputValue, $inputs['dateValue']);
                    } else {
                        $dateResponse = $this->getStartEndDateFormat($inputValue, '');
                    }
                    if (!empty($dateResponse)) {
                        $solrFiledArray['startDateTime'] = $dateResponse['startDateTime'];
                        $solrFiledArray['endDateTime'] = $dateResponse['endDateTime'];
                    }
                } elseif ($inputKey == 'dateValue') {
                    
                } elseif ($inputKey == 'timezoneId') {
                    
                } elseif ($inputKey == 'timeStamp') {
                    $dayValue = gmdate('Y-m-d H:i:s', strtotime($inputValue));
                    $customDateStart = date_format(date_create($dayValue), "Y-m-d\TH:i:s\Z");

                    $solrFiledArray['mts'] = '[' . $customDateStart . ' TO *]';
                } elseif ($inputKey == 'facetType') {
                    $facetname = $this->facetName($inputValue);
                    if (isset($facetname) && $facetname != '') {
                        $facet = $facetname;
                    } else {
                        return json_encode(array('response' => array("error" => "true", "status" => false, "messages" => array(ERROR_FACET_TYPE))));
                    }
                } elseif ($inputKey == 'facetValues') {
                    $inputValue_facet = '';
                    if (is_array($inputValue) && !empty($inputValue)) {
                        foreach ($inputValue as $fkey => $fvalue) {
                            $inputValue_facet.=$fvalue . ' ';
                        }
                        $inputValue_facet = rtrim($inputValue_facet, ' ');
                    } else {
                        return json_encode(array('response' => array("error" => "true", "status" => false, "messages" => array(ERROR_FACET_VALUE))));
                    }
                    $solrFiledArray['facetIds'] = '(' . $inputValue_facet . ')';
                } elseif ($inputKey == 'keyWord') {
                    $queryParts = explode(' ', $inputValue);
                    $queryEscaped = array();

                    foreach ($queryParts as $queryPart) {
                        $queryEscaped[] = self::escapeSolrValue($queryPart);
                    }
                    $queryEscaped = join(' ', $queryEscaped);
                    $keyWord = $queryEscaped;
                    $orConditionInputs = array();
                    if ($keyWord != '') {
                        $orConditionInputs['title'] = '"' . $keyWord . '"';
                        $orConditionInputs['id'] = '"' . $keyWord . '"';
                        $orConditionInputs['seoKeywords'] = '"' . $keyWord . '"';
                        $orConditionInputs['eventTags'] = '"' . $keyWord . '"';
                    }
                } elseif ($inputKey == 'cityId') {
                    $cityHandler = new City_handler();
                    $inputCity['cityId'] = $inputValue;
                    $inputCity['countryId'] = $inputs['countryId'];
                    $cityResponse = $cityHandler->getCityDetailById($inputCity);
                    if ($cityResponse['status'] && $cityResponse['response']['total'] > 0) {
                        $aliasCityIdStr = $cityResponse['response']['detail']['aliascityid'];
                    } else {
                        return $cityResponse;
                    }
                    $inputValue_facet = '';
                    if (isset($aliasCityIdStr) && strlen($aliasCityIdStr) > 0) {
                        $explodeAliasCityId = explode(',', $aliasCityIdStr);
                        $inputValue_facet = implode(' ', $explodeAliasCityId);
                    }
                    if (strlen($inputValue_facet) > 0) {
                        $inputValue_facet.= ' ' . $inputValue;
                    } else {
                        $inputValue_facet = $inputValue;
                    }
                    $solrFiledArray['cityId'] = '(' . $inputValue_facet . ')';
                } else if ($inputKey == 'stateId') {
                    $cityHandler = new City_handler();
                    $inputArray['stateId'] = $inputValue;
                    $specialCity = $cityHandler->checkSpecialcityState($inputArray);
                    if ($specialCity['status'] == TRUE && isset($specialCity['response']['cityId'])) {
                        $cityId = $specialCity['response']['cityId'];
                        $inputCity['cityId'] = $cityId;
                        $inputCity['countryId'] = $inputs['countryId'];
                        $cityResponse = $cityHandler->getCityDetailById($inputCity);
                        if ($cityResponse['status'] && $cityResponse['response']['total'] > 0) {
                            $aliasCityIdStr = $cityResponse['response']['detail']['aliascityid'];
                        } else {
                            return $cityResponse;
                        }
                        $inputValue_facet = '';
                        if (isset($aliasCityIdStr) && strlen($aliasCityIdStr) > 0) {
                            $explodeAliasCityId = explode(',', $aliasCityIdStr);
                            $inputValue_facet = implode(' ', $explodeAliasCityId);
                            $onlyOrCondition['cityId'] = '"' . $inputValue_facet . '"';
                            $onlyOrCondition[$inputKey] = '"' . $inputValue . '"';
                            ;
                        } else {
                            $solrFiledArray[$inputKey] = $inputValue;
                        }
                    }
                } else {
                    $solrFiledArray[$inputKey] = $inputValue;
                }
            }
            if ($facet != '' && isset($solrFiledArray['facetIds'])) {
                $solrFiledArray[$facet] = $solrFiledArray['facetIds'];
                unset($solrFiledArray['facetIds']);
            }
        }
        unset($solrFiledArray['ticketSoldout']);
        unset($solrFiledArray['private']);

        if (isset($solrFiledArray['mts']) && $solrFiledArray['mts'] != '') {
            $mts = $solrFiledArray['mts'];
            $solrFiledArray = array();
            $solrFiledArray['mts'] = $mts;
        }
        $output = $this->ci->solrlibrary->getSolrResults($solrFiledArray, $sort, $facet, $start, $limit, 'event', $orConditionInputs, $eventIdsArray, $onlyOrCondition);

        return $output;
    }

    public function getSitemapSolrEvents($inputs) {
        $sort = $facet = '';
        //$start = 0;
        //$limit = 12;
        $solrFiledArray = $dateResponse = array();
        $sort['popularity'] = 'desc';
        $sort['totalsoldtickets'] = 'desc';
        $sort['startDateTime'] = 'asc';

        //validations
        //$this->ci->form_validation->pass_array($inputs);
        $eventIdsArray = $onlyOrCondition = array();

        $orConditionInputs = array();

        if (!empty($inputs)) {
            foreach ($inputs as $inputKey => $inputValue) {
                // Default published event and it's not a private event

                if ($inputKey == 'start') {
                    $start = (int) $inputValue;
                } elseif ($inputKey == 'limit') {
                    $limit = $inputValue;
                } elseif ($inputKey == 'selectFields') {
                    $selectFields = $inputValue;
                } elseif ($inputKey == 'day') {
                    $dateResponse = '';
                    if ($inputValue == 7) {
                        $dateResponse = $this->getStartEndDateFormat($inputValue, $inputs['dateValue']);
                    } else {
                        $dateResponse = $this->getStartEndDateFormat($inputValue, '');
                    }
                    if (!empty($dateResponse)) {
                        $solrFiledArray['startDateTime'] = $dateResponse['startDateTime'];
                        $solrFiledArray['endDateTime'] = $dateResponse['endDateTime'];
                    }
                } elseif ($inputKey == 'year') {
                    unset($solrFiledArray['startDateTime']);
                    $solrFiledArray['endDateTime'] = '[' . $inputValue . '-01-01T00:00:00Z TO *]';
                } else {
                    $solrFiledArray[$inputKey] = $inputValue;
                }
            }
        }


        /* if(isset($solrFiledArray['mts']) && $solrFiledArray['mts'] != '') {
          $mts = $solrFiledArray['mts'];
          $solrFiledArray = array();
          $solrFiledArray['mts'] = $mts;
          } */
        $start = 0;
        $output = $this->ci->solrlibrary->getSolrEventsSelectedFields($solrFiledArray, $selectFields, $sort, $facet, $start, $limit, 'event', $orConditionInputs, $eventIdsArray, $onlyOrCondition);

        //print_r(json_decode($output,true));

        return $output;
    }

}
?>
				  

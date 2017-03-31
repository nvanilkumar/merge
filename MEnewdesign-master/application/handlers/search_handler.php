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
 * @Last Modified 15-06-2015
 */

require_once(APPPATH . 'handlers/handler.php');
require_once(APPPATH . 'handlers/country_handler.php');
require_once(APPPATH . 'handlers/category_handler.php');
require_once(APPPATH . 'handlers/solr_handler.php');
require_once(APPPATH . 'handlers/timezone_handler.php');
require_once(APPPATH . 'handlers/bookmark_handler.php');
require_once(APPPATH . 'handlers/city_handler.php');

class Search_handler extends Handler {

	var $ci;
	var $solrHandler;
    public function __construct() 
	{
        parent::__construct();
        $this->ci = parent::$CI;
        $this->solrHandler= new Solr_handler();
	$this->timezoneHandler = new Timezone_handler();
    }
 
//  customDateEventCount() for getting custom date's event count   
    public function customDateEventCount($requests) {
        $fields=array("status","ticketSoldout","keyWord","countryId","stateId","cityId","categoryId","subcategoryId","day","dateValue","registrationType","eventMode");
        $this->checkFields($fields,$requests);
        parent::$CI->form_validation->pass_array($requests);
        parent::$CI->form_validation->set_rules('countryId', 'countryId', 'required_strict|is_natural_no_zero');
        parent::$CI->form_validation->set_rules('cityId', 'cityId', 'is_natural_no_zero');
        parent::$CI->form_validation->set_rules('categoryId', 'categoryId', 'is_natural_no_zero');
        parent::$CI->form_validation->set_rules('subcategoryId', 'subcategoryId', 'is_natural_no_zero');
        
        if (!empty($requests) && parent::$CI->form_validation->run() == FALSE) {
            $errorMessages = parent::$CI->form_validation->get_errors();
			$output['response']['status'] = FALSE;
            $output['response']['messages'] = $errorMessages['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $requests['private'] = 0;
        $resultJ = $this->solrHandler->getDayFacetResult($requests);
        $result = json_decode($resultJ, true);
        if($result['response']['status']==false)
        {
            $output['status'] = FALSE;
            $output['response']['messages'] = $errorMessages;
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;       	
        }
			
			return $result;
    }

//  citesEventCount() for getting ctiy's event count
    public function citesEventCount($requests) {
        $result=array();
        $fields=array("status","ticketSoldout","keyWord","countryId","facetType","facetValues","categoryId","subcategoryId","day","dateValue","stateId","cityId","registrationType","eventMode");
        $checkResponce=$this->checkFields($fields,$requests);
        if($checkResponce==0){
            return json_encode(array('response' => array("error" => "true", "status" => false, "message" => ERROR_WRONG_PARAMETER)));   
        }
        parent::$CI->form_validation->pass_array($requests);
        parent::$CI->form_validation->set_rules('countryId', 'countryId', 'required_strict|is_natural_no_zero');
        parent::$CI->form_validation->set_rules('facetType', 'facetType', 'required_strict');
        parent::$CI->form_validation->set_rules('facetValues', 'facetValues', 'required_strict');
        parent::$CI->form_validation->set_rules('categoryId', 'categoryId', 'is_natural_no_zero');
        parent::$CI->form_validation->set_rules('subcategoryId', 'subcategoryId', 'is_natural_no_zero');
        parent::$CI->form_validation->set_rules('stateId', 'stateId', 'is_natural_no_zero');
        parent::$CI->form_validation->set_rules('cityId', 'cityId', 'is_natural_no_zero');
        if (!empty($requests) && parent::$CI->form_validation->run() == FALSE) {
            $output['response'] = parent::$CI->form_validation->get_errors();
            $output = json_encode($output);
            return $output;
        }else{
        $requests['private'] = 0;
        $response = $this->solrHandler->getFacetResult($requests);
        $response=json_decode($response);
        $result['response']['error']="false";
        $result['response']['status']=true;
        $result['response']['result']= $response->response;
        return json_encode($result);
        }
    }
    /*
     * This method is to get All Cities count, All catogories count, All Time Count
     */
    public function allCount($filed, $inputs) {
        
        $response = $this->solrHandler->getFacetResult($inputs);
        return $response;
    }

//  categotiesEventCount() for getting category's event count  
    public function categotiesEventCount($requests) {
        $result=array();
        $fields=array("status","ticketSoldout","keyWord","countryId","facetType","facetValues","cityId","stateId","categoryId","subcategoryId","day","dateValue","registrationType","eventMode");
        $checkResponce=$this->checkFields($fields,$requests);
        if($checkResponce==0){
            return json_encode(array('response' => array("error" => "true", "status" => false, "message" => ERROR_WRONG_PARAMETER)));   
        }
        parent::$CI->form_validation->pass_array($requests);
        parent::$CI->form_validation->set_rules('countryId', 'countryId', 'required_strict|is_natural_no_zero');
        parent::$CI->form_validation->set_rules('facetType', 'facetType', 'required_strict');
        //parent::$CI->form_validation->set_rules('facetValues', 'facetValues', 'required_strict');
        parent::$CI->form_validation->set_rules('stateId', 'stateId', 'is_natural_no_zero');
        parent::$CI->form_validation->set_rules('cityId', 'cityId', 'is_natural');
        parent::$CI->form_validation->set_rules('categoryId', 'categoryId', 'is_natural');
        parent::$CI->form_validation->set_rules('subcategoryId', 'subcategoryId', 'is_natural');

        if (!empty($requests) && parent::$CI->form_validation->run() == FALSE) {
            $output['response'] = parent::$CI->form_validation->get_errors();
            $output = json_encode($output);
            return $output;
        }else{
            $requests['private'] = 0;
        $response = $this->solrHandler->getFacetResult($requests);
        $response=json_decode($response);
        $result['response']['error']="false";
        $result['response']['status']=true;
        if(!is_null($response)){
            $result['response']['result']= $response->response;
        }
        return json_encode($result);
        }
    }
    
    public function checkFields($fieldsList, $fieldsListToCompare){
        if(isset($fieldsListToCompare) && !empty($fieldsListToCompare)){
            $output=1;
            foreach ($fieldsListToCompare as $fielsKey => $fielsValue) {
                if (in_array($fielsKey, $fieldsList)) {
                }else{
                    $output=0;
                }
            }
            return $output;           
        }
    }
	
	public function searchEvents($inputArray) {
		
		$this->cityHandler = new City_handler();
		
        if (isset($inputArray['page']) && $inputArray['page'] <= 0) {
            $inputArray['page'] = 1;
        }
		if($inputArray['term'] != '') {
			$inputArray['keyWord'] = $inputArray['term'];
			unset($inputArray['term']);
		}
		
		$this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('keyWord', 'keyWord', 'required_strict');
        if ($this->ci->form_validation->run() == FALSE) {
            $response = $this->ci->form_validation->get_errors();
            $output['status'] = FALSE;
            $output['response']['messages'] = $response['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
		
		$solrResults = $this->solrHandler->getSolrEventsBySearch($inputArray);
		$solrResults = json_decode($solrResults,true);
		$eventList = array();
        $solrEventList = $solrResults["response"]["events"];
		
        if (count($solrEventList) > 0) {
            $categoryIdList = array();
            $timezoneIdList = array();
			$eventidList = array();
            foreach ($solrEventList as $rKey => $rValue) {
                $categoryIdList[] = $rValue["categoryId"];
                $timezoneIdList[] = $rValue["timezoneId"];
				$cityIdList[] = $rValue["cityId"];
            }
            $categoryIdList = array_unique($categoryIdList);
            $timezoneIdList = array_unique($timezoneIdList);
			$cityIdList = array_unique($cityIdList);
			
            $timezoneData = array();
            $timezoneData = $this->timezoneHandler->timeZoneList(array('idList' => $timezoneIdList));
            if ($timezoneData['status'] && $timezoneData['response']['total'] > 0) {
                $timezoneData = commonHelperGetIdArray($timezoneData['response']['timeZoneList']);
            }
			
            $categoryData = array();
			$this->categoryHandler= new Category_handler();
            $categoryData = $this->categoryHandler->getCategoryList(array('major' => 1));
            if ($categoryData['status'] && $categoryData['response']['total'] > 0) {
                $categoryData = commonHelperGetIdArray($categoryData['response']['categoryList']);
            }
			
			if (count($cityIdList) > 0) {
                $cityListData = $this->cityHandler->getCityNames($cityIdList);
            }

            if ($cityListData['status'] == TRUE && count($cityListData['response']['cityName']) > 0) {
                $cityObject = $cityListData['response']['cityName'];
                $cityListData = commonHelperGetIdArray($cityListData['response']['cityName']);
            }
			
			$this->bookmarkHandler = new Bookmark_handler();
            $bookmarkEvents = array();
            $userId = $this->ci->customsession->getUserId();
            if($userId != '') {
                $bookmarkInputs['userId'] = $userId;
                $bookmarkInputs['returnEventIds'] = true;
                $bookmarkEvents = $this->bookmarkHandler->getUserBookmarks($bookmarkInputs);
                if($bookmarkEvents['status'] && $bookmarkEvents['response']['total'] > 0) {
                    $bookmarkEventsArray = $bookmarkEvents['response']['bookmarkedEvents'];
                }
            }
			
            foreach ($solrEventList as $recordKey => $recordValue) {
                $eventList[$recordKey]['id'] = $recordValue["id"];
                $eventList[$recordKey]['title'] = $recordValue["title"];
                    $eventList[$recordKey]['thumbImage'] = $this->ci->config->item('images_content_cloud_path') . $recordValue["thumbImage"];
                    $eventList[$recordKey]['bannerImage'] = $this->ci->config->item('images_content_cloud_path') . $recordValue["bannerImage"];

                $eventList[$recordKey]['startDate'] = allTimeFormats($recordValue["startDateTime"],11);
                $eventList[$recordKey]['endDate'] = allTimeFormats($recordValue["endDateTime"],11);
                $eventList[$recordKey]['venueName'] = $recordValue["venueName"];
                $eventList[$recordKey]['eventUrl'] = commonHelperEventDetailUrl($recordValue["url"]);
                if(isset($recordValue["externalurl"]) && !empty($recordValue["externalurl"])){
                    $eventList[$recordKey]['eventExternalUrl'] = $recordValue["externalurl"];
                }
                if ($recordValue["categoryId"] > 0) {
                    $catDetails = $categoryData[$recordValue["categoryId"]];

                    $eventList[$recordKey]['categoryName'] = $catDetails['name'];
                    $eventList[$recordKey]['categoryIcon'] = '';
                    $eventList[$recordKey]['themeColor'] = $catDetails['themecolor'];
                } else {
                    $eventList[$recordKey]['categoryName'] = "";
                    $eventList[$recordKey]['categoryIcon'] = "";
                    $eventList[$recordKey]['themeColor'] = "";
                }
				
				
				if ($eventList[$recordKey]['thumbImage'] == '') {
                    $eventList[$recordKey]['thumbImage'] = $catDetails['categorydefaultthumbnailid'];
                }
				
                if ($eventList[$recordKey]['bannerImage'] == '') {
                    $eventList[$recordKey]['bannerImage'] = $catDetails['categorydefaultbannerid'];
                }
                $eventList[$recordKey]['defaultBannerImage'] = $catDetails['categorydefaultbannerid'];
                $eventList[$recordKey]['defaultThumbImage'] = $catDetails['categorydefaultthumbnailid'];
				
                $eventList[$recordKey]['timeZone'] = "";
                $timezoneId = $recordValue['timezoneId'];
                if ($timezoneId > 0) {
                    $eventList[$recordKey]['timeZone'] = $timezoneData[$timezoneId]['timezone'];
                }
				
				$eventList[$recordKey]['bookMarked'] = 0;
                if($userId != '') {
                    if(in_array($recordValue["id"],$bookmarkEventsArray)) {
                        $eventList[$recordKey]['bookMarked'] = 1;
                    }   
                }
				
				if ($recordValue['cityId'] > 0) {
                    if (isset($cityListData[$recordValue['cityId']]['name']))
                        $eventList[$recordKey]['cityName'] = $cityListData[$recordValue['cityId']]['name'];
                    else
                        $eventList[$recordKey]['cityName'] = "";
                }
            }
            if (!isset($inputArray['page'])) {
                $inputArray['page'] = 1;
            }
            if (!isset($solrInputArray['limit'])) {
                $solrInputArray['limit'] = 12;
            }
            $nextPageStatus = false;
            if ((($solrResults["response"]["total"] / $solrInputArray['limit'])) > $inputArray['page']) {
                $nextPageStatus = true;
            }
            $output['status'] = TRUE;
            $output['response']['eventList'] = $eventList;
            $output['response']['page'] = $inputArray['page'];
            $output['response']['limit'] = isset($inputArray['limit']) ? $inputArray['limit'] : $solrInputArray['limit'];
            $output['response']['nextPage'] = $nextPageStatus;
            $output['response']['total'] = $solrResults["response"]["total"];
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
}

<?php

require_once (APPPATH . 'handlers/handler.php');

class Timezone_handler extends Handler {

    var $ci;

    public function __construct() {
        parent::__construct();
        $this->ci = parent::$CI;
        $this->ci->load->model('Timezone_model');
    }

    //To get the all fields of TimezoneList or to pass id and get related fields 
    public function timeZoneList($inputArray =NULL) {
  		$memcacheEnable = $this->ci->config->item('memcacheEnabled');
		if(isset($inputArray['idList']) && count($inputArray['idList']) > 0)
		{
			//Commented by Sridevi, Pla enable below in case of supporting multiple timezones
			//$memcacheEnable = FALSE;
		}
		if ($memcacheEnable) {
            // Get the TimeZone data from Memcached 
            $this->ci->load->library('memcached_library');
            $cacheResults = $this->ci->memcached_library->get(MEMCACHE_TIME_ZONE);
            if (($cacheResults) != FALSE ) {
                $output['status'] = TRUE;
                $output['response']['timeZoneList'] = $cacheResults;
                $output['response']['messages'] = array();					
                $output['response']['total'] = count($cacheResults);
                $output['statusCode'] = STATUS_OK;
                return $output;
            }
       }
	$this->ci->Timezone_model->resetVariable();   
        $selectArray['id'] = $this->ci->Timezone_model->id;
        $selectArray['name'] = $this->ci->Timezone_model->name;
        $selectArray['zone'] = $this->ci->Timezone_model->zone;
        $selectArray['timezone'] = $this->ci->Timezone_model->timezone;
        $selectArray['status'] = $this->ci->Timezone_model->status;

        $this->ci->Timezone_model->setSelect($selectArray);
        $whereArray[$this->ci->Timezone_model->status] = (isset($inputArray['status'])) ? ($inputArray['status']) : 1;
        $whereArray[$this->ci->Timezone_model->deleted] = 0;
		if(isset($inputArray['idList']) && count($inputArray['idList']) > 0)
		{
			$this->ci->Timezone_model->setWhereIn(array("id" => $inputArray['idList']));
			$memcacheEnable = FALSE;
		}
		if (isset($inputArray['timeStamp']) && count($inputArray['timeStamp']) > 0) {
		
			$whereArray[$this->ci->Timezone_model->mts.' >='] = allTimeFormats($inputArray['timeStamp'], 11);
		}
		
        $this->ci->Timezone_model->setWhere($whereArray);
        $response = array();
        $response = $this->ci->Timezone_model->get();
        if ($response) {
            $response = commonHelperGetIdArray($response);
            if ($memcacheEnable == TRUE) {
                $this->ci->memcached_library->add(MEMCACHE_TIME_ZONE, $response, MEMCACHE_TIME_ZONE_TTL);
            }
            $output['status'] = TRUE;
            $output['response']['timeZoneList'] = $response;
            $output['response']['messages'] = array();			
            $output['response']['total'] = count($response);
            $output['statusCode'] = STATUS_OK;
            return $output;
        } else {
            $output['status'] = TRUE;
            $output['response']['messages'][] = ERROR_NO_TIMEZONE;
            $output['response']['total'] = 0;
            $output['statusCode'] = STATUS_OK;
            return $output;
        }
    }

    public function details($inputArray) {	
	$this->ci->form_validation->reset_form_rules();
        $validationStatus = $this->validateTimezoneInputs($inputArray);
        if ($validationStatus['error'] == true) {
            $output['status'] = FALSE;
            $output['response']['messages'] = $validationStatus['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $memcacheEnable = $this->ci->config->item('memcacheEnabled');
        if ($memcacheEnable) {
            // Get the TimeZone data from Memcached 
            $this->ci->load->library('memcached_library');
            $cacheResults = $this->ci->memcached_library->get(MEMCACHE_TIME_ZONE_DETAIL);
            if (($cacheResults) != FALSE ) {
                $output['status'] = TRUE;
                $output['response']['detail'] = $cacheResults;
                $output['response']['messages'] = array();					
                $output['response']['total'] = count($cacheResults);
                $output['statusCode'] = STATUS_OK;
			    return $output;
            }
       }
		
	$this->ci->Timezone_model->resetVariable();	
	$selectArray['id'] = $this->ci->Timezone_model->id;
        $selectArray['name'] = $this->ci->Timezone_model->name;
        $selectArray['zone'] = $this->ci->Timezone_model->zone;
        $selectArray['timezone'] = $this->ci->Timezone_model->timezone;
        $selectArray['status'] = $this->ci->Timezone_model->status;

        $this->ci->Timezone_model->setSelect($selectArray);
        //setting where condition
        $whereArray = array();
        if (isset($inputArray['timezoneId'])) {
            $whereArray[$this->ci->Timezone_model->id] = $inputArray['timezoneId'];
        }
        if (isset($inputArray['name'])) {
            $whereArray[$this->ci->Timezone_model->name] = $inputArray['name'];
        }
        $whereArray[$this->ci->Timezone_model->status] = (isset($inputArray['status'])) ? ($inputArray['status']) : 1;
        $whereArray[$this->ci->Timezone_model->deleted] = 0;
        $this->ci->Timezone_model->setWhere($whereArray);
         $timeZoneDetails = array();
         $timeZoneDetails  = $this->ci->Timezone_model->get();
        if ($timeZoneDetails) {
            $timeZoneDetails = commonHelperGetIdArray($timeZoneDetails);
            if ($memcacheEnable == TRUE) {
                $this->ci->memcached_library->add(MEMCACHE_TIME_ZONE_DETAIL, $timeZoneDetails, MEMCACHE_TIME_ZONE_DETAIL_TTL);
            }
			
			$output['status'] = TRUE;
            $output['response']['detail'] = $timeZoneDetails;
			$output['response']['messages'] =array();			
            $output['response']['total'] = count($timeZoneDetails);
            $output['statusCode'] = STATUS_OK;
            return $output;
           
        } else {
            $output['status'] = FALSE;
            $output['response']['messages'][] = ERROR_NO_TIMEZONE;
            $output['response']['total'] = 0;
            $output['statusCode'] = STATUS_NO_DATA;
            return $output;
        }
    }
	
	

    //validating the input parameters
    public function validateTimezoneInputs($inputs) {
        $this->ci->form_validation->pass_array($inputs);
        $this->ci->form_validation->set_rules('timezoneId', 'timezoneId', 'is_natural_no_zero|required_strict');
        $this->ci->form_validation->set_rules('name', 'TimeZone Name', 'alpha');
        if ($this->ci->form_validation->run() === FALSE) {
            $error_messages = $this->ci->form_validation->get_errors('message');
            return $error_messages;
        }
    }
	
		}

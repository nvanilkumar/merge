<?php
/**
 * Connected to MAXMIND GEO IP 3rd party data( from local) to get the IP corresponding country name & city name
 *
 * @package		CodeIgniter
 * @author		Qison  Dev Team
 * @copyright	Copyright (c) 2015, MeraEvents.
 * @Version		Version 1.0
 * @Since       Class available since Release Version 1.0 
 * @Created     12-06-2015
 * @Last Modified 12-06-2015
 */

require_once(APPPATH . 'handlers/handler.php');
require_once(APPPATH . 'handlers/country_handler.php');
require_once(APPPATH . 'handlers/city_handler.php');


class Geo_handler extends Handler {

	var $ci;
	
    public function __construct() 
	{
        parent::__construct();
		$this->ci = parent::$CI;
		$this->ci->load->library('form_validation');
    }
	
	/*
    * Function to get the UserLocation like Country & City based on the IP
    *
    * @access	public
    * @param	$inputArray contains
    * 				string (IP )
    * @return	array
    */
	function getUserLocation($clientIpAddress) 
	{
		if($this->ci->config->item('geoLocation') == true){
			require_once(APPPATH . 'handlers/city_handler.php');
			$cityHandler = new City_handler();
			$userCityDetails = $this->getclientIPCityLocation($clientIpAddress);
			if($userCityDetails && $userCityDetails['response']['total']>0){
				$cityName = $userCityDetails['response']['cityResponse'][0]['city'];
				if(strlen(trim($cityName))>0){
					$cityName = strtolower($cityName);
					$inputArray['name']=$cityName;
					$userCity= $cityHandler->getCityDetailsByName($inputArray);
					if($userCity['status'] && $userCity['response']['total']>0 ){
						$cityDetails['cityId'] = $userCity['response']['cityDetails'][0]['id'];
						$cityDetails['countryId'] = $userCity['response']['cityDetails'][0]['countryId'];
				        $output['status'] = TRUE;
				        $output['statusCode'] = STATUS_OK;
				        $output['response'] = $cityDetails;
				        return $output;   
					} 	
				}
					$output['status'] = TRUE;
					$output['statusCode'] = STATUS_OK;
					$output['response'] = array('countryId'=>14, 'cityId'=>'');
					return $output;
					
				}	
				$output['status'] = TRUE;
				$output['statusCode'] = STATUS_OK;
				$output['response'] = array('countryId'=>14, 'cityId'=>'');
				return $output;
		}
				$output['status'] = TRUE;
				$output['statusCode'] = STATUS_OK;
				$output['response'] = array('countryId'=>14, 'cityId'=>'');
				return $output;
	}
	
	public function getclientIPCityLocation($clientIpAddress){
		//$clientIpAddress =  '203.91.193.5';
		$cookie_explode = explode('.', $clientIpAddress);
		$inbinary = NULL;
		$count = 3;
		$ipnumber = 0;
		foreach ($cookie_explode as $value) {
			$ipnumber+=$value * (pow(256, $count));
			$count--;
		}
		$basetenValue = $ipnumber;
		$cityNameDetails = $this->getCityName($basetenValue);
		return $cityNameDetails;
		 
	}
	
	public function getCityName($basetenValue){
		$this->ci->load->model('Geolocation_model');
		$this->ci->load->model('Geoblock_model');
		$locselect['locId'] = $this->ci->Geoblock_model->locId;
		$locwhere['startIpNum <='] = $basetenValue;
		$locwhere['endIpNum >='] = $basetenValue;
		$this->ci->Geoblock_model->setWhere($locwhere);
		$this->ci->Geoblock_model->setSelect($locselect);
		$this->ci->Geoblock_model->setRecords(1);
		$locationResponse = $this->ci->Geoblock_model->get();
		if($locationResponse && count($locationResponse>0)){
			$select['city'] = $this->ci->Geolocation_model->city;
			$where['locId'] = $locationResponse[0]['locId'];
			$this->ci->Geolocation_model->setWhere($where);
			$this->ci->Geolocation_model->setSelect($select);
			$this->ci->Geolocation_model->setRecords(1);
			$cityResponse = $this->ci->Geolocation_model->get();
			if($cityResponse){
				$output['status'] = TRUE;
				$output["response"]["messages"] = array();
				$output["response"]["total"] = count($cityResponse);
				$output['statusCode'] = STATUS_OK;
				$output['response']['cityResponse'] = $cityResponse;
				if (count($cityResponse) == 0) {
					$output["response"]["messages"][] = ERROR_NO_DATA;
					$output['response']['cityResponse'] = array();
					$output['response']['total'] = 0;
				}
				return $output;
			}
		}
		$output['status'] = FALSE;
		$output["response"]["messages"][] = ERROR_NO_DATA;
		$output['statusCode'] = STATUS_OK;
		return $output;
	}

}

<?php
/**
 * dayType event count related business logic will be defined in this class
 *
 * @package		CodeIgniter
 * @author		Qison  Dev Team
 * @copyright	Copyright (c) 2015, MeraEvents.
 * @Version		Version 1.0
 * @Since       Class available since Release Version 1.0 
 * @Created     16-06-2015
 * @Last Modified 16-06-2015
 */
require_once(APPPATH . 'handlers/handler.php');

require_once(APPPATH . 'handlers/search_handler.php');

class Day_type_handler extends Handler {
	
	var $ci;
	var $searchHandler;
	
    public function __construct() {
        parent::__construct();
		$this->ci = parent::$CI;
        $this->searchHandler = new Search_handler();
    }
    
    public function getEventsCountByDayType($inputArray){
        $result=$resultData=$days=array();
        $inputArray['keyWord']= $inputArray['keyword'];
        unset($inputArray['keyword']);
        $searchInputs = $inputArray;
         if(isset($searchInputs['eventType']) && $searchInputs['eventType']!=''){
        //$eventTypeResult =  eventType($searchInputs['eventType']);
			$registrationTypeArray =eventType($searchInputs['eventType']);
			$eventTypeResult['registrationType'] = $registrationTypeArray['registrationType'];
                        if($registrationTypeArray['registrationType']==4){
                            $eventTypeResult['eventMode']=$registrationTypeArray['eventMode'];
                            unset($eventTypeResult['registrationType']); 
                        }
			$searchInputs=array_merge($searchInputs, $eventTypeResult); 
         }
         if(!isset($searchInputs['day'])){
          $searchInputs['day']=6;  
        }
        unset($searchInputs['eventType']); 
        
       $result = $this->searchHandler->customDateEventCount($searchInputs);
       //print_r($result);
       $days=  commonHelperCustomFilterArray();

       if($result['response']['status']){
           foreach ($days as $dayKey => $dayValue) {
               if($dayValue['id']<=6){
             $resultData[$dayKey]['id']=$dayValue['id'];  
             $resultData[$dayKey]['name']=$dayValue['name'];
             $resultData[$dayKey]['count']=$result['response']['result'][$dayValue['id']];
               }
           }
		   	$output['status'] = TRUE;
			$output['response']['count'] = $resultData;
			$output['response']['messages'] = array();
			$output['statusCode'] = STATUS_OK;
			return $output;
       }   
	   else
	   {
			$output['status'] = $result['response']['status'];
			$output['response']['messages'] = $result['response']['messages'];
			$output['statusCode'] = $result['statusCode'];
			return $output;
	   }    
    }
    
}
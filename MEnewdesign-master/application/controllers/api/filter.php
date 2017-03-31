<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require_once(APPPATH . 'libraries/REST_Controller.php');
require_once(APPPATH . 'handlers/day_type_handler.php');

class Filter extends REST_Controller {
    var $dayTypeHandler;
       public function __construct() {
        parent::__construct();
        
        $this->dayTypeHandler = new Day_type_handler();
    }
   
    // get Events count by daytype
        public function eventCount_get() {
            $inputArray = $this->get();
            $inputArray['ticketSoldout']=0;
            $inputArray['status']=1;
            $eventCountList = $this->dayTypeHandler->getEventsCountByDayType($inputArray);
			
			$resultArray = array('response' => $eventCountList['response']);
			$statusCode = $eventCountList['statusCode'];
			$this -> response($resultArray, $statusCode);
    }
}
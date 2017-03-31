<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require_once(APPPATH . 'libraries/REST_Controller.php');
require_once(APPPATH . 'handlers/category_handler.php');

class Category extends REST_Controller {

    public function __construct() {
        parent::__construct();
        
        $this->categoryHandler = new Category_handler();
        
    }

    /*
    * Function to get the Category list
    *
    * @access	public
    * @param	string (major - 1 or 0)
    * @return	array
    */
    public function list_get() {
        $inputArray = $this->get();
        $categoryList = $this->categoryHandler->getCategoryList($inputArray);
		
        $resultArray = array('response' => $categoryList['response']);
        $statusCode = $categoryList['statusCode'];
        $this -> response($resultArray, $statusCode);
    }
	
	public function details_get() {
            $inputArray = $this->get();
            $categoryDetails = $this->categoryHandler->getCategoryDetails($inputArray);
            
            $resultArray = array('response' => $categoryDetails['response']);
            $statusCode = $categoryDetails['statusCode'];
            $this -> response($resultArray, $statusCode);
	}
        
        public function eventCount_get() {
            $inputArray = $this->get();
            $inputArray['ticketSoldout']=0;
            $inputArray['status']=1;
            $eventCountList = $this->categoryHandler->getEventsCountByCategories($inputArray);
            $resultArray = array('response' => $eventCountList['response']);
            $statusCode = $eventCountList['statusCode'];
            $this -> response($resultArray, $statusCode);        	
    }
    public function cityEventsCount_get() {
            $inputArray = $this->get();
            $inputArray['ticketSoldout']=0;
            $inputArray['status']=1;
            $categoryEventsCountList = $this->categoryHandler->getCategoryEventsCountByCity($inputArray);
            $resultArray = array('response' => $categoryEventsCountList['response']);
            $this -> response($resultArray, $categoryEventsCountList['statusCode']);
    }
}

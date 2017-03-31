<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require_once(APPPATH . 'libraries/REST_Controller.php');
require_once(APPPATH . 'handlers/subcategory_handler.php');

class Subcategory extends REST_Controller {

    public function __construct() {
        parent::__construct();

        $this->subcategoryHandler = new Subcategory_handler();
    }

    /*
    * Function to get the Sub Category list
    *
    * @access	public
    * @param
    *       keyword : "alpha", "space", "&",",","-","."
    *       category Id : Number
    * @return	array
    */
    public function list_get() {
        $inputArray = $this->get();
        $subcategoryList = $this->subcategoryHandler->getSubCategories($inputArray);
        
        $resultArray = array('response' => $subcategoryList['response']);
        $this -> response($resultArray, $subcategoryList['statusCode']);
    }

    // get Events count by subcategory
    
      
        public function eventsCount_get() {
            $eventCountList=array();
            $inputArray = $this->get();
            $inputArray['ticketSoldout']=0;
            $inputArray['status']=1;
            $eventCountList = $this->subcategoryHandler->getEventsCountBySubcategories($inputArray);
            
            $resultArray = array('response' => $eventCountList['response']);
            $this -> response($resultArray, $eventCountList['statusCode']);
    }
		
     public function search_get() {
        $inputArray = $this->get();
        $subcategoryList = $this->subcategoryHandler->getSubCategories($inputArray);
        $resultArray = array('response' => $subcategoryList['response']);
        $this -> response($resultArray, $subcategoryList['statusCode']);
    }
     
}

<?php
/**
 * Sub category related business logic will be defined in this class
 *
 * @package		CodeIgniter
 * @author		Qison  Dev Team
 * @copyright	Copyright (c) 2015, MeraEvents.
 * @Version		Version 1.0
 * @Since       Class available since Release Version 1.0
 * @Created     11-06-2015
 * @Last Modified 11-06-2015
 */
require_once (APPPATH . 'handlers/handler.php');
require_once (APPPATH . 'handlers/category_handler.php');
require_once (APPPATH . 'handlers/search_handler.php');

class Subcategory_handler extends Handler {

	var $ci;

	public function __construct() {
		parent::__construct();
		$this -> ci = parent::$CI;
		$this -> ci -> load -> model('Subcategory_model');
		$this -> ci -> load -> model('Category_model');
		$this -> searchHandler = new Search_handler();
		$this -> categoryHandler = new category_handler();
	}

	/*
	 * Function to get the Sub Category list
	 *
	 * @access	public
	 * @param    $inputArray contains
	 *               keyword : "alpha", "space", "&",",","-","."
	 *               category Id : Number
	 * @return	array
	 */
	function getSubCategories($inputArray) {
        $this->ci->form_validation->pass_array($inputArray);
        $this -> ci -> form_validation -> set_rules('countryId', 'countryId', 'is_natural_no_zero');
        $this->ci->form_validation->set_rules('categoryId', 'categoryId', 'is_natural_no_zero');
        $this->ci->form_validation->set_rules('limit', 'limit', 'is_natural_no_zero');
        $this->ci->form_validation->set_rules('keyword', 'keyword', 'keyWordRule');
		// If the categoryid parameter is invalid
        if ($this->ci->form_validation->run() == FALSE) {
            $response = $this->ci->form_validation->get_errors();
			$output['status'] = FALSE;
			$output['response']['messages'] = $response['message'];
			$output['statusCode'] = STATUS_BAD_REQUEST;
			return $output;
	}
                
        $catId = isset($inputArray['categoryId']) ? $inputArray['categoryId'] : '';
        $searchKey = isset($inputArray['keyword']) ? $inputArray['keyword'] : '';
        
        if ($catId != '') {
            //Cross checking the category id
            $mainCategoryData = array();
            $mainCategoryData = $this->categoryHandler->getCategoryDetails($inputArray);
                if (!$mainCategoryData['status']) {
                        $output['status'] = FALSE;
                        $output['response']['messages'][] = ERROR_INVALID_CATEGORY_ID;
                        $output['statusCode'] = STATUS_INVALID_INPUTS;
                        return $output;
                }
        }
        $this->ci->Subcategory_model->resetVariable();
        $selectInput['id'] = $this->ci->Subcategory_model->id;
        $selectInput['name'] = $this->ci->Subcategory_model->name;
        $selectInput['categoryid'] = $this->ci->Subcategory_model->categoryid;
        $selectInput['value'] = $this->ci->Subcategory_model->value;
        if(isset($inputArray['getValue']) && $inputArray['getValue']){
            $selectInput['value'] = $this->ci->Subcategory_model->name;
        }
        $limit = isset($inputArray['limit']) ? trim($inputArray['limit']) : 0;

        //we need the exact string match
        if (isset($inputArray['addEventCheck']) && $inputArray['addEventCheck'] === TRUE) {
            $whereArray[$this->ci->Subcategory_model->name] = $searchKey;
            $whereArray[$this->ci->Subcategory_model->status." != "] = 0;
            $searchKey = "";
        }else{
            $whereArray[$this->ci->Subcategory_model->status] = 1;
        }
        if ($catId != '') {
            $whereArray[$this->ci->Subcategory_model->categoryid] = $catId;        
        }
        
        $whereArray[$this->ci->Subcategory_model->deleted] = 0;
		if(trim($searchKey) == "") {
			$orderArr[$this->ci->Subcategory_model->order] = 'DESC';
		} else {
			$orderArr[$this->ci->Subcategory_model->name] = 'ASC';
		}
        // Getting the Sub category data for the main category
        $response = $this->ci->Subcategory_model->get($selectInput, $whereArray, $searchKey, true, $limit,$orderArr);

		if ($response) {
			$output['status'] = TRUE;
			$output['response']['subCategoryList'] = $response;
			$output['response']['total'] = count($response);
			$output['response']['messages'] = array();
			$output['statusCode'] = STATUS_OK;
			return $output;
		} else {
			$output['status'] = FALSE;
			$output['response']['messages'][] = ERROR_NO_SUBCATEGORIES;
			$output['response']['total'] = 0;
			$output['statusCode'] = STATUS_OK;
			return $output;

		}

	}

	//get events count by subcategories

	function getEventsCountBySubcategories($inputArray) {
          	$subcategoriesData = $subcategoryListRemainingArray = $subcategoryListRemaining = $subcategoriesIdsArray = $resultData = $searchInputs = $data = $result = $eventTypeResult = array();
		$totalCount = 0;
               
		$searchInputs = $inputArray;
                 if(isset($inputArray['keyword'])){
                    $searchInputs['keyWord']=$inputArray['keyword'];
                    unset($inputArray['keyword']);
                    unset($searchInputs['keyword']);
                }
                if(isset($inputArray['ticketSoldout'])){
                    unset($inputArray['ticketSoldout']);
                }
                if(isset($inputArray['status'])){
                    unset($inputArray['status']);
                }
		if (isset($searchInputs['eventType']) && $searchInputs['eventType'] != '') {
			$eventTypeResult = eventType($searchInputs['eventType']);
                        if($eventTypeResult['registrationType']==4){
                            unset($eventTypeResult['registrationType']);
                        }
			$searchInputs = array_merge($searchInputs, $eventTypeResult);
		}
		if (!isset($searchInputs['day'])) {
			$searchInputs['day'] = 6;
		}
		unset($searchInputs['eventType']);
		unset($searchInputs['major']);
		$searchInputs['facetType'] = 'subcategory';
                $subcategoryList = $this -> getSubCategories($inputArray);
                if (isset($subcategoryList['status']) && $subcategoryList['status'] == false) {
                        return $subcategoryList;
                }
                $subcategoryList = json_decode(json_encode($subcategoryList), true);
//                if (isset($inputArray['categoryId']) && $inputArray['categoryId'] != '') {
//                    if ($subcategoryList['response']['total'] >= 1) {
//                            foreach ($subcategoryList['response']['subCategoryList'] as $subcategoryListKey => $subcategoryListValue) {
//                                    $searchInputs['facetValues'][$subcategoryListKey] = $subcategoryListValue['id'];
//                            }
//                    }
//                }
                $data = $this -> searchHandler -> categotiesEventCount($searchInputs);
                $result = json_decode($data, true);
                
                if (isset($result['response']['error']) && $result['response']['error'] == 'false') {
                        if (isset($result['response']['result']['facetCounts']['subcategoryId']) && !empty($result['response']['result']['facetCounts']['subcategoryId'])) {
                                foreach ($result['response']['result']['facetCounts']['subcategoryId'] as $subcategorykey => $subcategoryValue) {
                                        $subcategoriesData[$subcategorykey]['id'] = $subcategoryValue[0];
                                        $subcategoriesData[$subcategorykey]['count'] = $subcategoryValue[1];
                                        $subcategoriesIdsArray[] = $subcategoryValue[0];
                                }
                                $subCategoryCountListTemp = commonHelperGetIdArray($subcategoriesData);
                                $subCategoryListTemp = commonHelperGetIdArray($subcategoryList['response']['subCategoryList']);

                                foreach ($subCategoryListTemp as $eventNameVal) {
                                        if (isset ($subCategoryCountListTemp[$eventNameVal['id']])) {
                                            $subcategoriesDbIdsArray[] = $eventNameVal['id'];
                                        }
                                }
                                $finalids=array_intersect_key($subcategoriesDbIdsArray, $subcategoriesIdsArray); 
                                $finalSubcategoryList=array();
                                foreach ($finalids as $value) {
                                        $finalSubcategoryList[$value]['id'] = $value;
                                        $finalSubcategoryList[$value]['count']=$subCategoryCountListTemp[$value]['count'];
                                        $finalSubcategoryList[$value]['name']=$subCategoryListTemp[$value]['name'];
                                        $finalSubcategoryList[$value]['categoryId']=$subCategoryListTemp[$value]['categoryid'];
                                        $totalCount += $subCategoryCountListTemp[$value]['count'];
                                }
                                $resultData['status'] = TRUE;                                       
                                $resultData['response']['count'] = $finalSubcategoryList;
                                $resultData['response']['totalCount'] = $totalCount;
                                $resultData['response']['messages'] = array();
                                $resultData['statusCode'] = STATUS_OK;
                        } 
                        else {                            
                                $resultData['status'] = TRUE; 
                                $resultData['response']['messages'][] = ERROR_NO_EVENTS_SUBCATEGORIES;
                                $resultData['response']['totalCount'] = $totalCount;
                                $resultData['statusCode'] = STATUS_OK;
                        }

                        $result = $resultData;
                }                
		return $result;
	}
    /*
     * To insert the subcategory, we cross checking with passed category,
     * if not present related that category then inserting
     */

    public function subcategoryInsert($inputs) { 
        $validationStatus = $this->subcategoryInsertValidation($inputs);
        if ($validationStatus['error'] == TRUE) {
            $output['status'] = FALSE;
            $output['response']['messages'][] = $validationStatus['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
}

        //check the state name in our db
        $inputs['addEventCheck'] = TRUE;
        $inputs['categoryId'] = $inputs['categoryId'];
        $inputs['keyword'] = $inputs['subcategoryName'];
        $subcategoryList = $this->getSubCategories($inputs);
        
        if ($subcategoryList['status'] && $subcategoryList['response']['total'] > 0) {
            $output['status'] = TRUE;
            $output['response']['subcategoryId'] = $subcategoryList['response']['subCategoryList'][0]['id'];
            $output['response']['message'] = array();
            $output['statusCode'] = STATUS_OK;
            return $output;
        }
        //Prepare the country insert array
        $inputs['subcatvalue'] = cleanUrl($inputs['subcategoryName']);
        $insertsubcategoryArray = array();
        $this->ci->Subcategory_model->resetVariable();
        $insertsubcategoryArray[$this->ci->Subcategory_model->name] = $inputs['subcategoryName'];
        $insertsubcategoryArray[$this->ci->Subcategory_model->categoryid] = $inputs['categoryId'];
        $insertsubcategoryArray[$this->ci->Subcategory_model->value] = $inputs['subcatvalue'];
        $insertsubcategoryArray[$this->ci->Subcategory_model->status] = 2;//For review purpose
        $insertsubcategoryArray[$this->ci->Subcategory_model->deleted] = 0;
        
        $this->ci->Subcategory_model->setInsertUpdateData($insertsubcategoryArray);
        $subcategoryId = $this->ci->Subcategory_model->insert_data();
        $output['status'] = TRUE;
        $output['response']['subcategoryId'] = $subcategoryId;
        $output['response']['message'] = array();
        $output['statusCode'] = STATUS_CREATED;
        return $output;
    }

    /*
     * To validate the subcategory insert related validations
     */

    public function subcategoryInsertValidation($inputs) {
        $errorMessages = array();
        $this->ci->form_validation->pass_array($inputs);
        $this->ci->form_validation->set_rules('categoryId', 'Category Id', 'required_strict | is_natural_no_zero');
        $this->ci->form_validation->set_rules('subcategoryName', 'subcategory Name', 'required_strict');
        if ($this->ci->form_validation->run() === FALSE) {
            $errorMessages = $this->ci->form_validation->get_errors();
            return $errorMessages;
        }

        $errorMessages['error'] = FALSE;
        return $errorMessages;
    }
    /**
     * To get the id related subcategory details
     * @param type $inputArray
     * @return int
     */
    public function getSubcategoryDetails($inputArray) {
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('subcategoryId', 'subcategory Id', 'is_natural_no_zero');
        if ($this->ci->form_validation->run() == FALSE) {
            $response = $this->ci->form_validation->get_errors();
            $output['status'] = FALSE;
            $output['response']['messages'] = $response['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $this->ci->Subcategory_model->resetVariable();
        $selectInput['id'] = $this->ci->Subcategory_model->id;
        $selectInput['name'] = $this->ci->Subcategory_model->name;
        $whereArray[$this->ci->Subcategory_model->id] = $inputArray['subcategoryId'];
        $whereArray[$this->ci->Subcategory_model->deleted] = 0;
        // Getting the Sub category data for the main category
        $response = $this->ci->Subcategory_model->get($selectInput, $whereArray, "", true, "");

        if ($response) {
            $output['status'] = TRUE;
            $output['response']['subCategoryList'] = $response;
            $output['response']['total'] = count($response);
            $output['response']['messages'] = array();
            $output['statusCode'] = STATUS_OK;
            return $output;
        } else {
            $output['status'] = TRUE;
            $output['response']['messages'][] = ERROR_NO_SUBCATEGORIES;
            $output['response']['total'] = 0;
            $output['statusCode'] = STATUS_OK;
            return $output;
        }
    }

}

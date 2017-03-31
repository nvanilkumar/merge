<?php

/**
 * Category related business logic will be defined in this class
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
require_once (APPPATH . 'handlers/file_handler.php');
require_once (APPPATH . 'handlers/search_handler.php');

class Category_handler extends Handler {

    var $ci;

    public function __construct() {

        parent::__construct();
        $this->ci = parent::$CI;
        $this->ci->load->model('Category_model');
        $this->searchHandler = new Search_handler();
    }

    /*
     * Function to get the Category list
     *

     * @access	public
     * @param	$inputArray contains
     * 				string (major - 1 or 0)
     * @return	array
     */

    function getCategoryList($inputArray) {
		
		$noImages = false;
		if(isset($inputArray['noImages']) && $inputArray['noImages']) {
			$noImages = true;
		}
        $this->ci->load->library('memcached_library');
        $major = $inputArray['major'];
        $this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('major', 'major', 'required_strict|enable');
        
		// If the Major parameter is missing
        if ($this->ci->form_validation->run() == FALSE) {
            $response = $this->ci->form_validation->get_errors();

            $output['status'] = FALSE;
            $output['response']['messages'] = $response['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }

        if ($this->ci->config->item('memcacheEnabled')) {
            $memcacheKey = ($major == 1) ? MEMCACHE_MAJOR_CATEGORY : MEMCACHE_ALL_CATEGORY;
            // Get the Category data from the Memcached based on $major
            $cacheResults = $this->ci->memcached_library->get($memcacheKey);

            if ($cacheResults != FALSE) {// Data is not availeble in memcache.
                $total = count($cacheResults);
                $output['status'] = TRUE;
                $output['response']['categoryList'] = $cacheResults;
                $output['response']['total'] = $total;
                $output['response']['messages'] = array();
                $output['statusCode'] = STATUS_OK;
                //return the output with the data, which is fetched from memecache
                return $output;
            }
        }

        $selectInput = $data = $responseData = $fileResponse = $fileResponseData = $finalCategoryDataArray = array();
        $this->ci->Category_model->resetVariable();
        $selectInput['id'] = $this->ci->Category_model->id;
        $selectInput['name'] = $this->ci->Category_model->name;
        $selectInput['value'] = $this->ci->Category_model->value;
        $selectInput['categorydefaultthumbnailid'] = $this->ci->Category_model->categorydefaultthumbnailid;
         if (!isset($inputArray['categoryidsArray'])) {
            $selectInput['imagefileid'] = $this->ci->Category_model->imagefileid;
            $selectInput['themecolor'] = $this->ci->Category_model->themecolor;
            $selectInput['ticketsetting'] = $this->ci->Category_model->ticketsetting;
            $selectInput['categorydefaultbannerid'] = $this->ci->Category_model->categorydefaultbannerid;
            $selectInput['blogfeedurl'] = $this->ci->Category_model->blogfeedurl;
        }
        $where = array($this->ci->Category_model->status => 1, $this->ci->Category_model->deleted => 0);
        if ($major == 1) {
            $where = array($this->ci->Category_model->status => 1, $this->ci->Category_model->featured => $major, $this->ci->Category_model->deleted => 0);
        }

        $responseData = $this->ci->Category_model->get($selectInput, $where, true);
        if ($responseData == FALSE) {//No records are fetched
            $output['status'] = TRUE;
            $output['response']['messages'][] = ERROR_NO_DATA;
            $output['response']['total'] = 0;
            $output['statusCode'] = STATUS_OK;
            return $output;
        } else {
			
			if(!$noImages || $this->ci->config->item('memcacheEnabled')) {
				foreach ($responseData as $key => $value) {
					$fileIdList[] = $value[$this->ci->Category_model->imagefileid];
					$fileIdList[] = $value[$this->ci->Category_model->categorydefaultbannerid];
					$fileIdList[] = $value[$this->ci->Category_model->categorydefaultthumbnailid];
				}
				$this->fileHandler = new File_handler();
				$loop = 0;
			// Getting the Banner and Thumbnail images from File Handler
				$fileIdListNew = array_unique($fileIdList);
				$fileImageData = $this->fileHandler->getFileData(array('id',$fileIdListNew));
				if ($fileImageData['status'] && $fileImageData['response']['total'] > 0) {
					$fileImageDataTemp = commonHelperGetIdArray($fileImageData['response']['fileData']);
				}
			}
            
			foreach ($responseData as $rkey => $rvalue) {
				
				$finalCategoryDataArray[$rkey][$this->ci->Category_model->id] = $rvalue[$this->ci->Category_model->id];
				$finalCategoryDataArray[$rkey][$this->ci->Category_model->name] = $rvalue[$this->ci->Category_model->name];
				$finalCategoryDataArray[$rkey][$this->ci->Category_model->themecolor] = $rvalue[$this->ci->Category_model->themecolor];
				$finalCategoryDataArray[$rkey][$this->ci->Category_model->ticketsetting] = $rvalue[$this->ci->Category_model->ticketsetting];
				$finalCategoryDataArray[$rkey][$this->ci->Category_model->categorydefaultbannerid] = $rvalue[$this->ci->Category_model->categorydefaultbannerid];
				$finalCategoryDataArray[$rkey][$this->ci->Category_model->categorydefaultthumbnailid] = $rvalue[$this->ci->Category_model->categorydefaultthumbnailid];
                                $finalCategoryDataArray[$rkey][$this->ci->Category_model->value] = $rvalue[$this->ci->Category_model->value];
				if(!$noImages || $this->ci->config->item('memcacheEnabled')) {
					if (isset($rvalue[$this->ci->Category_model->imagefileid]) && isset($fileImageDataTemp[$rvalue[$this->ci->Category_model->imagefileid]]['path']))
					{
						$finalCategoryDataArray[$rkey][$this->ci->Category_model->imagefileid] = $this->ci->config->item('images_content_path') . $fileImageDataTemp[$rvalue[$this->ci->Category_model->imagefileid]]['path'];
					}
					if (isset($rvalue[$this->ci->Category_model->categorydefaultbannerid]) && isset($fileImageDataTemp[$rvalue[$this->ci->Category_model->categorydefaultbannerid]]['path']))
					{
						$finalCategoryDataArray[$rkey][$this->ci->Category_model->categorydefaultbannerid] = $this->ci->config->item('images_content_path') . $fileImageDataTemp[$rvalue[$this->ci->Category_model->categorydefaultbannerid]]['path'];                                               
					}
					if (isset($rvalue[$this->ci->Category_model->categorydefaultthumbnailid]) && isset($fileImageDataTemp[$rvalue[$this->ci->Category_model->categorydefaultthumbnailid]]['path']))
					{
						$finalCategoryDataArray[$rkey][$this->ci->Category_model->categorydefaultthumbnailid] = $this->ci->config->item('images_content_path') . $fileImageDataTemp[$rvalue[$this->ci->Category_model->categorydefaultthumbnailid]]['path'];
					}
				}
				
			}  
			$response = $finalCategoryDataArray;
            $memcacheTtl = ($major == 1) ? MEMCACHE_MAJOR_CATEGORY_TTL : MEMCACHE_ALL_CATEGORY_TTL;
            if (!empty($memcacheKey)) {
                $this->ci->memcached_library->add($memcacheKey, $response, $memcacheTtl);
            }
        }
        $output['status'] = TRUE;
        $output['response']['categoryList'] = $response;
        $output['response']['total'] = count($response);
        $output['response']['messages'] = array();
        $output['statusCode'] = STATUS_OK;
        return $output;
    }

    /*
     * Function to get the Category Details
     *
     * @access	public
     * @param	$inputArray contains
     * 				categoryId - integer
     * @return	array
     */

    function getCategoryDetails($inputArray) {
        $this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('categoryId', 'category id', 'required_strict|is_natural_no_zero');

        if (!empty($inputArray) && $this->ci->form_validation->run() == FALSE) {
            $response = $this->ci->form_validation->get_errors('message');
            $output['status'] = FALSE;
            $output['response']['messages'] = $response['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }

        $categoryId = $inputArray['categoryId'];
        $input = array('categoryId' => $categoryId, 'major' => '0');
		
		$input['noImages'] = false;
		if(isset($inputArray['noImages']) && $inputArray['noImages']) {
			$input['noImages'] = true;
		}
		
        $categoryList = $this->getCategoryList($input);
        $categoryIdExists = false;
        if ($categoryList['status'] && $categoryList['response']['total'] > 0) {
            $categoryListTemp = commonHelperGetIdArray($categoryList['response']['categoryList']);
            $categoryIdExists = array_key_exists($categoryId, $categoryListTemp);
        } else {
            return $categoryList;
        }

        if ($categoryIdExists) {
            $output['status'] = TRUE;
            $output['response']['detail'] = $categoryListTemp[$categoryId];
            $output['response']['total'] = 1;
            $output['response']['messages'] = array();
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

    // get events count by category
    function getEventsCountByCategories($inputArray) {
        $inputArray['keyWord']= $inputArray['keyword'];
        unset($inputArray['keyword']);
        $categoryListRemainingArray = $categoryTotalList = $categoryListRemaining = $categoriesIdsArray = $categoriesData = $searchInputs = $data = $result = $eventTypeResult = array();

        $categoryList = $this->getCategoryList($inputArray);

        if (isset($categoryList['status']) && $categoryList['status'] == false) {
            return $categoryList;
        }
        $searchInputs = $inputArray;
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
        if (isset($searchInputs['cityId']) && $searchInputs['cityId'] == 0)
            unset($searchInputs['cityId']);
        
        if (isset($searchInputs['stateId']) && $searchInputs['stateId'] == 0)
            unset($searchInputs['stateId']);

        unset($searchInputs['eventType']);
        unset($searchInputs['major']);
        $searchInputs['facetType'] = 'category';
        if ($categoryList['status'] == 1 && $categoryList['response']['total'] >= 1) {
            foreach ($categoryList['response']['categoryList'] as $categoryListKey => $categoryListValue) {
                $searchInputs['facetValues'][$categoryListKey] = $categoryListValue['id'];
            }
        }

        if (empty($searchInputs['facetValues'])) {
            $output['status'] = FALSE;
            $output['response']['messages'][] = ERROR_NO_CATEGORIES;
            $output['response']['total'] = 0;
            $output['statusCode'] = STATUS_OK;
            return $output;
        }

        $data = $this->searchHandler->categotiesEventCount($searchInputs);
        $resultdata = json_decode($data, true);
        if ($resultdata['response']['error'] == 'true') {
            $output['status'] = FALSE;
            $output['response']['messages'] = $resultdata['response']['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        if (isset($resultdata['response']['result']['facetCounts']['categoryId']))
        {
        foreach ($resultdata['response']['result']['facetCounts']['categoryId'] as $catgoryKey => $catgoryValue) {
            $categoriesData[$catgoryKey]['id'] = $catgoryValue[0];
            $categoriesData[$catgoryKey]['count'] = $catgoryValue[1];
            $categoriesIdsArray[] = $catgoryValue[0];
        }
        }
        if (!isset($searchInputs['facetValues'])) {
            $searchInputs['facetValues'] = array();
        }

        $categoryListRemaining = array_diff($searchInputs['facetValues'], $categoriesIdsArray);
        if (count($categoryListRemaining) >= 1) {
            foreach ($categoryListRemaining as $crKey => $crValue) {
                $categoryListRemainingArray[$crKey]['id'] = $crValue;
                $categoryListRemainingArray[$crKey]['count'] = 0;
            }
        }
        /* All categories count */
        $allCityCountInput = array();
        $allCityCountInput['countryId'] = $inputArray['countryId'];
        if (isset($inputArray['cityId']) && $inputArray['cityId'] != '') {
            $allCityCountInput['cityId'] = $inputArray['cityId'];
        }
        if (isset($inputArray['stateId']) && $inputArray['stateId'] != '') {
            $allCityCountInput['stateId'] = $inputArray['stateId'];
        }
        if (isset($inputArray['day']) && $inputArray['day'] != '') {
            $allCityCountInput['day'] = $inputArray['day'];
            if ($inputArray['day'] == 7) {
                $allCityCountInput['dateValue'] = $inputArray['dateValue'];
            }
        }
        else {
            $allCityCountInput['day'] = 6;
        }
        if (isset($inputArray['eventType']) && $inputArray['eventType'] != '') {
            $registrationTypeArray = eventType($inputArray['eventType']);
			$allCityCountInput['registrationType'] = $registrationTypeArray['registrationType'];
                        if($registrationTypeArray['registrationType']==4){
                            $allCityCountInput['eventMode']=$registrationTypeArray['eventMode'];
                            unset($allCityCountInput['registrationType']);
                        }
        }
        $allCityCountInput['limit'] = 0;
        if(isset($inputArray['keyword'])){
                $inputArray['keyWord']=$inputArray['keyword'];
            }
            if (isset($inputArray['keyWord'])) {
                $allCityCountInput['keyWord'] = $inputArray['keyWord'];
            }
            if(isset($inputArray['ticketSoldout'])){
                $allCityCountInput['ticketSoldout']=$inputArray['ticketSoldout'];
            }
            if(isset($inputArray['status'])){
                $allCityCountInput['status']=$inputArray['status'];
            }
            $allCityCountInput['private']=0;
         
        $allCountResponse = json_decode($this->searchHandler->allCount('category', $allCityCountInput), true);
        $allCount = $allCountResponse['response']['total'];
        if(!isset($allCount) || $allCount == NULL){
        	$allCount =0;
        }
        /* end of All categories count */
        $categoryTotalList['status'] = TRUE;
        $categoryTotalList['response']['count'] = array_merge($categoriesData, $categoryListRemainingArray);         
        $categoryTotalList['response']['allCount'] = $allCount;
        $categoryTotalList['response']['messages'] = array();
        $categoryTotalList['statusCode'] = STATUS_OK;

        return $categoryTotalList;
    }

    /*
     * Function to get the Category Details
     *
     * @access	public
     * @param	$inputArray contains
     * 				keyword - character
     * @return	array
     */

    function getCategoryDetailsByKeyword($inputArray) {

        $keyword = $inputArray['keyword'];

        // If the Keyword parameter is missing
        if (trim($keyword) == '') {
            $final['message'] = ERROR_NOT_ACCEPTABLE;
        } else {
            $selectInput = $data = $responseData = $fileResponse = $fileResponseData = $finalCategoryDataArray = array();
            $this->ci->Category_model->resetVariable();
            $selectInput['id'] = $this->ci->Category_model->id;
            $selectInput['name'] = $this->ci->Category_model->name;
            //$selectInput['iconimagefileid'] = $this->ci->Category_model->iconimagefileid;
            $selectInput['imagefileid'] = $this->ci->Category_model->imagefileid;
            $selectInput['themecolor'] = $this->ci->Category_model->themecolor;

            $where = array($this->ci->Category_model->status => 1, $this->ci->Category_model->deleted => 0, $this->ci->Category_model->name => $keyword);
            $responseData = $this->ci->Category_model->get($selectInput, $where, true);

            $categoryCount = count($responseData);
            if ($categoryCount <= 0 || !$responseData) {
                $final = array('message' => ERROR_NO_DATA, 'total' => 0);
            } else {
                foreach ($responseData as $key => $value) {
                    //$catIconimagefileid[$key] = $value[$this->ci->Category_model->iconimagefileid];
                    $catImagefileid[$key] = $value[$this->ci->Category_model->imagefileid];
                }
                $mergedCategoryFileFields = $catImagefileid; //array_merge($catIconimagefileid, $catImagefileid);
                $catFileidslist = array_unique($mergedCategoryFileFields);

                $this->fileHandler = new File_handler();

                $loop = 0;
                $fileImageData = $this->fileHandler->getFileData(array('id', $catFileidslist));
                if ($fileImageData['status'] && $fileImageData['response']['total'] > 0) {
                    $fileImageDataTemp = commonHelperGetIdArray($fileImageData['response']['fileData']);
                }

                foreach ($responseData as $rkey => $rvalue) {
                    $finalCategoryDataArray[$rkey][$this->ci->Category_model->id] = $rvalue[$this->ci->Category_model->id];
                    $finalCategoryDataArray[$rkey][$this->ci->Category_model->name] = $rvalue[$this->ci->Category_model->name];
                    $finalCategoryDataArray[$rkey][$this->ci->Category_model->themecolor] = $rvalue[$this->ci->Category_model->themecolor];
                    //$finalCategoryDataArray[$rkey][$this->ci->Category_model->iconimagefileid] = $this->ci->config->item('images_content_path') . $fileImageDataTemp[$rvalue[$this->ci->Category_model->iconimagefileid]]['path'];
                    $finalCategoryDataArray[$rkey][$this->ci->Category_model->imagefileid] = $this->ci->config->item('images_content_path') . $fileImageDataTemp[$rvalue[$this->ci->Category_model->imagefileid]]['path'];
                }
                $response = $finalCategoryDataArray;

                $data['categoryList'] = $response;
                $data['total'] = count($response);
            }
            $data['status'] = true;
            $final['response'] = $data;
        }
        return $final;
    }

    /*
      Function to get Category wise events count with in a city
     *
     * @access	public
     * @param	$inputArray contains
     * 				keyword - character
     * @return	array
     */

    function getCategoryEventsCountByCity($inputArray) {
        $data = array();
        $response = array();
        $catEventsCount = array();
        $categoryList = array();
        $finalCategoryList = array();
        $totalCntSum = 0;
        $status = FALSE;
        $catEventsCount = $this->getEventsCountByCategories($inputArray);
        if ($catEventsCount['status'] === FALSE) {
            $output['status'] = FALSE;
            $output['response']['messages'] = $catEventsCount['response']['messages'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $categoryListResult = $this->getCategoryList($inputArray);
        if ($categoryListResult['status'] == TRUE) {
            $categoryList = $categoryListResult['response']['categoryList'];
        }
        if ($catEventsCount['status'] == TRUE) {
            $categoryCountListTemp = commonHelperGetIdArray($catEventsCount['response']['count']);
            $categoryListTemp = commonHelperGetIdArray($categoryList);
            foreach ($categoryCountListTemp as $eventCntVal) {
                $categoryListTemp[$eventCntVal['id']]['eventCount'] = $eventCntVal['count'];
            }
            foreach ($categoryListTemp as $finalTempVal) {
                $totalCntSum += $finalTempVal['eventCount'];
            }

            $finalCategoryList = $categoryListTemp;
            $status = TRUE;
            $response = $finalCategoryList;
        }
        $data['status'] = $status;
        $data['response']['count'] = $response;
        $data['response']['total'] = $totalCntSum;
        $data['statusCode'] = STATUS_OK;
        return $data;
    }
	
		}

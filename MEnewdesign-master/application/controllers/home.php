<?php  
 
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/**
 * Default landing page controller
 *
 * @package		CodeIgniter
 * @author		Qison  Dev Team
 * @copyright	Copyright (c) 2015, MeraEvents.
 * @Version		Version 1.0
 * @Since       Class available since Release Version 1.0 
 * @Created     11-06-2015
 * @Last Modified On 25-06-2015
 * @Last Modified By Sridevi
 */


require_once(APPPATH . 'handlers/banner_handler.php');
require_once(APPPATH . 'handlers/city_handler.php');
require_once(APPPATH . 'handlers/category_handler.php');
require_once(APPPATH . 'handlers/event_handler.php');
require_once(APPPATH . 'handlers/blog_handler.php');
require_once(APPPATH . 'handlers/common_handler.php');
require_once(APPPATH . 'handlers/seodata_handler.php');


class Home extends CI_Controller {

    var $bannerHandler;
    var $cityHandler;
    var $categoryHandler;
    //var $subCategoryHandler;
    var $eventHandler;
    var $blogHandler;
	var $commonHandler;
    

    public function __construct() {
        parent::__construct();
        $this->bannerHandler = new Banner_handler();
        $this->cityHandler = new City_handler();
        $this->categoryHandler = new Category_handler();        
        //$this->subCategoryHandler = new Subcategory_handler();
        $this->eventHandler = new Event_handler();
        $this->blogHandler= new blog_Handler();
        $this->commonHandler = new Common_handler();
        

    }

    public function index($id,$keyword,$type) { 
        $data = array(); 
        $data['subCategoryList'] = array();
        $inputArray = $this->input->get();
        //$this->benchmark->mark('top');
        $seoForUrl=$this->uri->uri_string;
//        $uri_city = strtolower($this->uri->segment(1));
//        $uri_catg = $this->uri->segment(2);
//        $uri_array = array();
//        if (preg_match('/[A-Z]/', $uri_city)) {
//            redirect(site_url().strtolower($uri_city));
//        }
		//echo $uri_city; exit;
        if ($id != "") { 
            $filterData = array();
            $footerValues = $this->commonHandler->footerValues();
            $data['categoryList'] = $footerValues['categoryList'];
            $data['cityList'] = $footerValues['cityList'];
            
            $cityIds = commonHelperGetIdArray($data['cityList'], 'id');
            $cityId = array_keys(array_change_key_case($cityIds));
            
            $catgIds = commonHelperGetIdArray($data['categoryList'], 'id');
            $catgId = array_keys(array_change_key_case($catgIds));
            
            //for only one segment having cityid 
            if (in_array($id, $cityId)) {
                foreach ($cityIds as $key => $value) {
                    if ($value['id'] == $id) {
                        if ($value['splcitystateid'] > 0) {
                                $filterData['SplCityStateId'] = $value['splcitystateid'];
                }
                            $filterData['countryId'] = $value['countryid'];
                            $filterData['cityId'] = $value['id'];
                            $filterData['city'] = $value['name'];
                }

                    }
                    $breadCrumbUrl = strtolower($filterData['city'])."-events";
                }
            //for only one segment having categoryid
            if (in_array($id, $catgId)) {
                foreach ($catgIds as $key => $value) {
                    if ($value['id'] == $id) {
                            $filterData['categoryId'] = $value['id'];
                            $filterData['category'] = $value['name'];
                }
                        
                }
                    $breadCrumbUrl = strtolower($filterData['category']);
                }

            if($id == 0){
                  $filterData['countryId'] = 14;
                  $filterData['cityId'] = 0;
            } 
            if ($type != "") {
                //array("lookuptype" => array("category","subcategory","customfilter"));
                if (is_array($type)) {
                    foreach ($type as $key => $lookup) {
            
                        if ($lookup == 'category') {
                            $filterData += $this->prepareFilterData($lookup,$data['categoryList'],$keyword[$key]);
                            }
                        
                        if ($lookup == 'subcategory') {
                            $inputData['countryId'] = isset($filterData['countryId']) ? $filterData['countryId'] :14;
                            if(isset($filterData['categoryId'])){
                                $inputData['categoryId'] = $filterData['categoryId'];
                    }
                            $this->subCategHandler = new Subcategory_handler();
                            $subCategListResponse = $this->subCategHandler->getSubCategories($inputData);
                    
                            if (count($subCategListResponse) > 0 && $subCategListResponse['status'] && $subCategListResponse['response']['total'] > 0) {
                                $data['subcategoryList'] = $subCategListResponse['response']['subCategoryList'];
                }
                             $filterData += $this->prepareFilterData($lookup,$data['subcategoryList'],$keyword[$key]);
                        }
                        
                        if ($lookup == 'customfilter') {
                            $filterData += $this->prepareFilterData($lookup,commonHelperCustomFilterArray(),$keyword[$key]);
                    }
                        
                        if ($lookup == 'city') {
                            $filterData += $this->prepareFilterData($lookup,$data['cityList'],$keyword[$key]);
                }
                            }
                        
                    }
                else{
                    if ($type == 'category') { 
                        $filterData += $this->prepareFilterData($type, $data['categoryList'], $keyword);
                    } else if ($type == 'subcategory') { 
                        $inputData['countryId'] = isset($filterData['countryId']) ? $filterData['countryId'] :14;
                            if(isset($filterData['categoryId'])){
                                $inputData['categoryId'] = $filterData['categoryId'];
                        }
                            $this->subCategHandler = new Subcategory_handler();
                            $subCategListResponse = $this->subCategHandler->getSubCategories($inputData);
                        
                            if (count($subCategListResponse) > 0 && $subCategListResponse['status'] && $subCategListResponse['response']['total'] > 0) {
                                $data['subcategoryList'] = $subCategListResponse['response']['subCategoryList'];
                    }
                         $filterData += $this->prepareFilterData($lookup,$data['subcategoryList'],$keyword);
                    } else if ($type == 'customfilter') {
                          $filterData += $this->prepareFilterData($lookup,commonHelperCustomFilterArray(),$keyword);
                    } else if ($type == 'city') {
                        $filterData += $this->prepareFilterData($lookup, $data['cityList'], $keyword);
                }
            }
        }
            $headerValues = $this->commonHandler->headerCityValues($filterData);
            $headerValues['breadCrumb'] = $breadCrumbUrl;
        }
        else {
            $headerValues = $this->commonHandler->headerValues($inputArray);
            $footerValues = $this->commonHandler->footerValues();
        }
        
        //$this->benchmark->mark('citylist');
        $data['countryList'] = array();
        $data = $headerValues;      
        $data['categoryList'] = array();
        $data['topBannerList'] = array();
        $data['bottomBannerList'] = array();
        $data['cityList'] = array();
        $data['categoryList'] = array();
        //$data['subCategoryList'] = array();
        $data['stateList'] = array();
        $data['eventsHappeningCounts'] = array();
        $data['eventsHappeningTotal'] = 0;
        $data['eventsList'] = array();
        $page = $limit =0;
		
        $inputArray = $this->input->get();
        $inputArray['major'] = 1;
        $inputArray['countryId'] = $data['defaultCountryId'];
        $inputArray['specialStateName']=true;
        //Removing this as we already getting city list from footer values
//        $cityList = $this->cityHandler->getCityList($inputArray);
//        
//	    if ($cityList['status'] == TRUE && $cityList['response']['total']>0) {
//            $data['cityList'] = $cityList['response']['cityList'];
//        }
        $data['cityList'] = $footerValues['cityList'];
        //$this->benchmark->mark('footer');
        $inputArray['cityId'] = $data['defaultCityId'];
        $inputArray['categoryId'] = $data['defaultCategoryId'];
        //$footerValues = $this->commonHandler->footerValues();
        $data['categoryList'] = $footerValues['categoryList'];
        if (count($data['categoryList']) > 0) { 
            $categoryListTemp = commonHelperGetIdArray($data['categoryList']);
            $data['defaultCategory'] = ($data['defaultCategoryId'] > 0 ) ? $categoryListTemp[$data['defaultCategoryId']]['name'] : LABEL_ALL_CATEGORIES;
        }
		
        $allCityinput = array();
        $allCityinput['countryId'] = $inputArray['countryId'] ;
        $allCityinput['cityId'] = $data['defaultCityId'];
        if($data['defaultCityId'] > 0)
        {
            $allCityinput['specialStateName']=true;
            $allCityList = $this->cityHandler->getCityDetailById($allCityinput);
            if ($allCityList['status'] == TRUE && $allCityList['response']['total']>0) {
                $data['defaultCityName'] = $allCityList['response']['detail']['name'];
            }
        }
        else
            $data['defaultCityName'] = LABEL_ALL_CITIES;
			
        $bannerInputArray = array();
        $bannerInputArray['type'] = 1; // 1-Top, 2-Bottom
        $bannerInputArray['limit'] = 10;
        $bannerInputArray['countryId'] = $inputArray['countryId'];
        if($inputArray['cityId'] > 0)
            $bannerInputArray['cityId'] = $inputArray['cityId'];
        if($inputArray['categoryId'] > 0)
            $bannerInputArray['categoryId'] = $inputArray['categoryId'];

        $topBannerList = $this->bannerHandler->getBannerList($bannerInputArray);
        if ($topBannerList['status'] == true && $topBannerList['response']['total']>0) {
            $data['topBannerList'] = $topBannerList['response']['bannerList'];
        }
        $bannerInputArray['type'] = 2;// 1-Top, 2-Bottom
        $bannerInputArray['limit'] = 1;
        $bottomBannerList = $this->bannerHandler->getBannerList($bannerInputArray);
        if ($bottomBannerList['status'] == true && $bottomBannerList['response']['total']>0) {
            $data['bottomBannerList'] = $bottomBannerList['response']['bannerList'];
        }
        //$data['subCategoryList'] = array();
        
	//Feaching the events list
        $eventListArray = array();
        $eventListArray['countryId'] = $inputArray['countryId'];
        if($inputArray['cityId'] > 0){
            if($data['defaultSplCityStateId']!=0){
                $eventListArray['stateId'] = $data['defaultSplCityStateId'];
            }else{
                $eventListArray['cityId'] = $inputArray['cityId'];
            }
            
        }
        if($inputArray['categoryId'] > 0)
            $eventListArray['categoryId'] = $inputArray['categoryId'];
        if(isset($data['defaultSubCategoryId']) && $data['defaultSubCategoryId'] > 0){
            $eventListArray['subcategoryId'] = $data['defaultSubCategoryId'];
        }
        $eventListArray['limit'] = 12;
        $eventListArray['day']=$data['defaultCustomFilterId'];
        $eventListArray['eventMode']=0;
        $eventListArray['ticketSoldout']=0;
        $eventListResponse = $this->eventHandler->getEventList($eventListArray);
        if ($eventListResponse['status'] == TRUE && $eventListResponse['response']['total']>0) {
            $data['eventsList'] = $eventListResponse["response"];
            $page = $eventListResponse["response"]["page"];
            $limit = $eventListResponse["response"]["limit"];        
        }
        
        $data['page']=$page;
        $data['limit']=$limit;
        $inputArray['major'] = 0;
        unset($inputArray['categoryId']);
        unset($inputArray['type']);
        unset($inputArray['limit']);
        $eventCountInputArray = array();
        $eventCountInputArray['countryId'] = $inputArray['countryId'];
        if($inputArray['cityId'] > 0){
            if($data['defaultSplCityStateId']!=0){
                $eventCountInputArray['stateId'] = $data['defaultSplCityStateId'];
            }else{
            $eventCountInputArray['cityId'] = $inputArray['cityId'];
            }
        }
        $eventCountInputArray['major'] = 1;
        $eventCountInputArray['eventType']='nonwebinar';
        $eventCountInputArray['ticketSoldout']=0;
        $eventCountInputArray['status']=1;
        $eventsHappeningCounts = $this->categoryHandler->getCategoryEventsCountByCity($eventCountInputArray);
        if ($eventsHappeningCounts['status'] == true && $eventsHappeningCounts['response']['total']>0) {
            $data['eventsHappeningCounts'] = $eventsHappeningCounts['response']['count'];
            $data['eventsHappeningTotal'] = $eventsHappeningCounts['response']['total'];
        }
	/* Getting subcategory events count for 'events happening in' block*/
        $subcatEventCountInputArray= array();
        
        $subcatEventCountInputArray['countryId'] = $inputArray['countryId'];
        $subcatEventCountInputArray['cityId'] = $inputArray['cityId'];
       // $subcatEventHappeningCount = $this->subCategoryHandler->getEventsCountBySubcategories($subcatEventCountInputArray);
       /*  echo "<pre>";print_r($subcatEventHappeningCount);exit;
        if ($subcatEventHappeningCount['status'] == TRUE && $subcatEventHappeningCount['response']['totalCount'] > 0) {
            $data['subcatEventHappeningCount'] = $subcatEventHappeningCount['response']['count'];
            $data['subcatEventHappeningTotal'] = $subcatEventHappeningCount['response']['totalCount'];
        }
        else {
            $data['subcatEventHappeningError'] = $subcatEventHappeningCount['response']['messages'];
            $data['subcatEventHappeningTotal'] = $subcatEventHappeningCount['response']['totalCount'];
        } */
        
        /* End of subcategory events count */
         
        //Getting CustomFilter Data
        $data['customFilterList'] = commonHelperCustomFilterArray();
        $data['stateList'] = array();
        $data['moduleName'] = 'homeModule';
        $data['content'] = 'home_view';
        $data['pageName'] = 'home';
        $data['pageTitle'] = 'Book tickets online for music concerts, live shows and professional events. Be informed about upcoming events in India.';
        $seoHandler = new Seodata_handler();
        $seoArray['url']=$seoForUrl;
        $seoKeys=$seoHandler->getSeoData($seoArray);
		$data['blogData'] =   $this->blogHandler->getBlogData();      
        if ($data['defaultCityId'] > 0) {
            $data['pageTitle'] = 'Explore latest, upcoming and most happening events in '.$data['defaultCityName'].'. Book your tickets now.';
        }
        $data['seoStaus']=false;
        if(count($seoKeys['response']['seoData']) > 0){
            $sData=$seoKeys['response']['seoData'][0];
            $data['seoStaus']=true;
            $data['pageTitle'] =$sData['seotitle'];
            $data['pageDescription']=$sData['seodescription'];
            $data['pageKeywords'] =$sData['seokeywords'];
            $data['canonicalurl'] =$sData['canonicalurl'];
        }
        
        $data['jsArray'] = array($this->config->item('js_angular_path').'user/homeModule' ,
            //$this->config->item('js_angular_path').'user/controllers/homeControllers' ,
            $this->config->item('js_angular_path').'user/controllers/homeControllers',
            $this->config->item('js_angular_path').'user/filters/homeFilters' ,
            $this->config->item('js_angular_path').'user/directives/homeDirectives' ,
            $this->config->item('js_angular_path').'common/commonModule' ,
           // $this->config->item('js_angular_path').'common/services/cookieService' ,
           // $this->config->item('js_angular_path').'common/controllers/countryController' ,
            $this->config->item('js_angular_path').'autocomplete/angucomplete-alt' ,
            $this->config->item('js_public_path') .'jquery.validate' ,
            $this->config->item('js_public_path') .'home' ,
            $this->config->item('js_public_path') .'common' );
            $this->load->view('templates/user_template', $data);		
    }
    public function download() {
        $file = $this->input->get("filePath");
        commonHelperDownload($file);
    }
    public function prepareFilterData($lookup,$data,$keyword){
        $input = array();
        if ($lookup == 'category') {
            $catValues = commonHelperGetIdArray($data, 'value');
            $catValue = array_keys(array_change_key_case($catValues));
            //compare keyword with category value
            if (in_array($keyword, $catValue)) {
                foreach ($catValues as $key => $value) {
                    if (strtolower($value['value']) == $keyword) {
                        $input['categoryId'] = $value['id'];
                        $input['category'] = $value['name'];
}
                }
            }
        } 
        if ($lookup == 'subcategory') {
            $subcatValues = commonHelperGetIdArray($data, 'value');
            $subcatValue = array_keys(array_change_key_case($subcatValues));
            //compare keyword with subcat value 
            if (in_array($keyword, $subcatValue)) {
                foreach ($subcatValues as $key => $value) {
                    if (strtolower($value['value']) == $keyword) {
                        $input['subcategoryId'] = $value['id'];
                        $input['subcategory'] = $value['name'];
                    }
                }
            }
        } 
        if ($lookup == 'customfilter') {
            $customfilterData = commonHelperGetIdArray($data, 'value');
            $customfilter = array_keys(array_change_key_case($customfilterData));
            if (in_array($keyword, $customfilter)) {
                foreach ($customfilterData as $key => $value) {
                    if (strtolower($value['value']) == $keyword) {
                        $input['customFilterId'] = $value['id'];
//                        if (strpos($value['name'], '-') !== false){
//                            $input['customFilter'] = str_replace('-', ' ', $value['name']);
//                        }else{
                            $input['customFilter'] = $value['name'];
//                        }
                    }
                }
            }
        } 
        if ($lookup == 'city') {
            $cityNames = commonHelperGetIdArray($data, 'name');
            $cityName = array_keys(array_change_key_case($cityNames));
            if (in_array($keyword, $cityName)) {
                foreach ($cityNames as $key => $value) {
                    if (strtolower($value['name']) == $keyword) {
                        if ($value['splcitystateid'] > 0) {
                            $input['SplCityStateId'] = $value['splcitystateid'];
                        }
                        $input['cityId'] = $value['id'];
                        $input['city'] = $value['name'];
                        $input['countryId'] = $value['countryid'];
                    }
                }
            }
        }
        return $input;
    }
}
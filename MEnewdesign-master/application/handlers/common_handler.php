<?php

/**
 * Common logic will be defined in this class
 * @package		CodeIgniter
 * @author		Qison  Dev Team
 * @param		CountryId - required
 *                      cityId,categoryId,type (optional)
 * @copyright	Copyright (c) 2015, MeraEvents.
 * @Version		Version 1.0
 * @Since       Class available since Release Version 1.0 
 * @Created     24-06-2015
 * @Last Modified On 24-06-2015
 * @Last Modified By Sridevi
 */
require_once(APPPATH . 'handlers/handler.php');
require_once(APPPATH . 'handlers/country_handler.php');
require_once(APPPATH . 'handlers/city_handler.php');

class Common_handler extends Handler {

    var $countryHandler;
    var $cityHandler;
    var $ci;
    var $countryList;
    var $defaultCategory;
    var $defaultCustomFilterName;
    var $defaultCountryId;
    var $defaultCityId;
    var $defaultSplCityStateId;
    var $defaultCategoryId;
    var $defaultSubCategoryId;
    var $defaultSubCategoryName;
    var $defaultCustomFilterId;
    var $defaultCityName;
    var $categoryHandler;
    var $defaultCountryName;

    public function __construct() {
        parent::__construct();
        $this->ci = parent::$CI;
        $this->countryList = array();
        $this->defaultCategory = LABEL_ALL_CATEGORIES;
        $this->defaultCustomFilterName = CF_ALL_TIME;
        $this->defaultCountryId = $this->defaultCityId = $this->defaultCategoryId = $this->defaultSplCityStateId = 0;
        $this->defaultCustomFilterId = 6;
        $this->defaultCountryName = "";
        $this->defaultCityName = LABEL_ALL_CITIES;
    }

    public function headerCityValues($city) {
        //Country Lsit
        $inputArray['major'] = 1;
        $this->countryHandler = new Country_handler();
     
        $countryList = $this->countryHandler->getCountryList($inputArray);
        
        if ($countryList['status'] == TRUE) {
            $this->countryList = commonHelperGetIdArray($countryList['response']['countryList']);
        }
        $this->defaultSubCategoryId =  0;
        $this->defaultSubCategoryName = 'All SubCategories';
        //if(is_array($city)){ 
        if(isset($city['cityId']) && $city['categoryId']){ 
                //Default City Id
                $this->defaultCityId = $city['cityId'];
                //default category Id
                $this->defaultCategoryId = $city['categoryId'];
                //Default country Id
                $this->defaultCountryId = $city['countryId'];
                if(isset($city['SplCityStateId'])){
                    $this->defaultSplCityStateId = $city['SplCityStateId'];
                }
                if(isset($city['customFilterId'])){
                    $this->defaultCustomFilterId = $city['customFilterId'];
                    $this->defaultCustomFilterName = $city['customFilter'];
                }
                if(isset($city['subcategoryId'])){ 
                    $this->defaultSubCategoryId = $city['subcategoryId'];
                    //$this->defaultSubCategoryName = $city['subcategory'];
                }
        }else{ 
            if (isset($city['cityId'])) { 
                //Default City Id
                $this->defaultCityId = $city['cityId'];
                //Default country Id
                $this->defaultCountryId = $city['countryId'];
                //default category Id
                $this->defaultCategoryId = 0;
                if(isset($city['SplCityStateId'])){
                    $this->defaultSplCityStateId = $city['SplCityStateId'];
                }

            }
            if (isset($city['categoryId'])) { 
                $this->defaultCategoryId = $city['categoryId'];
                $this->defaultCityId = 0;
                $this->defaultCityName = LABEL_ALL_CITIES;
                $this->defaultCountryId = 14;
            }
            //for all case
            if(isset($city['countryId'])){
                $this->defaultCountryId = $city['countryId'];
            }
        }
        $cityCookie = array('name' => 'cityId', 'value' => $this->defaultCityId, 'expire' => COOKIE_EXPIRATION_TIME);
        set_cookie($cityCookie);
        
        //if ($this->defaultCategoryId > 0) {
            $categCookie = array('name' => 'categoryId', 'value' => $this->defaultCategoryId, 'expire' => COOKIE_EXPIRATION_TIME);
            set_cookie($categCookie);
        //}
        
        //if ($this->defaultSplCityStateId > 0) {
            $splcityCookie = array('name' => 'splCityStateId', 'value' => $this->defaultSplCityStateId, 'expire' => COOKIE_EXPIRATION_TIME);
            set_cookie($splcityCookie);
        //}
            $subcatgCookie = array('name' => 'subCategoryId', 'value' => $this->defaultSubCategoryId, 'expire' => COOKIE_EXPIRATION_TIME);
            set_cookie($subcatgCookie);
        
        //Default country name, Default country phone code
        if (isset($this->countryList) && count($this->countryList) > 0) {
            foreach ($this->countryList as $countryIndex => $countryValue) {
                if ($countryValue['id'] == $this->defaultCountryId) {
                    $this->defaultCountryName = $countryValue['name'];
                    $this->defaultCountryPhoneCode = $countryValue['code'];
                }
            }
        }
//           $this->defaultCustomFilterId = 6;
//           $this->defaultCustomFilterName = CF_ALL_TIME;
            
        return array('countryList' => $this->countryList, 'defaultCountryId' => $this->defaultCountryId,
            'defaultCityId' => $this->defaultCityId, 'defaultCategoryId' => $this->defaultCategoryId,
            'defaultCategory' => $this->defaultCategory, 'defaultCustomFilterId' => $this->defaultCustomFilterId,
            'defaultCustomFilterName' => $this->defaultCustomFilterName, "defaultCountryPhoneCode" => $this->defaultCountryPhoneCode,
            'defaultCityName' => $this->defaultCityName, 'defaultCountryName' => $this->defaultCountryName,
            'defaultSplCityStateId' => $this->defaultSplCityStateId,'defaultSubCategoryId' => $this->defaultSubCategoryId,
            'defaultSubCategoryName' => $this->defaultSubCategoryName,'defaultCustomFreePaid' => $this->defaultCustomFreePaid);
        
       //print_r($arr);exit;
    }

    public function headerValues($inputArray = "") {

        // $inputArray = $this->input->get();
        $cookieCountryId = get_cookie('countryId');
        $cookieCityId = get_cookie('cityId');
        $cookieCategoryId = get_cookie('categoryId');
        $cookiePhoneCode = get_cookie('phoneCode');
        $cookieSplCityStateId = get_cookie('splCityStateId');
        $cookieSubCategoryId = get_cookie('subCategoryId');
        $cookieSubCategoryName = get_cookie('subCategoryName');
        //$cookieDayFilter = get_cookie('customFilterId');
        $inputArray['major'] = 1;
        $this->countryHandler = new Country_handler();
        $countryList = $this->countryHandler->getCountryList($inputArray);
        //  echo "<pre>";print_r($countryList);
        if ($countryList['status'] == TRUE) {
            $this->countryList = commonHelperGetIdArray($countryList['response']['countryList']);
        }
        if ($cookieCountryId > 0) {
            $this->defaultCountryId = $cookieCountryId;
            $this->defaultCityId = $cookieCityId > 0 ? $cookieCityId : 0;
            $this->defaultSplCityStateId = $cookieSplCityStateId > 0 ? $cookieSplCityStateId : 0;
            $this->defaultCategoryId = $cookieCategoryId > 0 ? $cookieCategoryId : 0;
            $this->defaultSubCategoryId = $cookieSubCategoryId > 0 ? $cookieSubCategoryId : 0;
            $this->defaultSubCategoryName = $cookieSubCategoryName != '' ? $cookieSubCategoryName : 'All SubCategories';
            $this->defaultCountryPhoneCode = $cookiePhoneCode > 0 ? $cookiePhoneCode : '';
        } else {
            $this->defaultCountryId = 14;
            $this->defaultCityId = "";
            $countryListTemp = $this->countryList;
            if (!isset($countryListTemp[$this->defaultCountryId])) {
                foreach ($countryListTemp as $ckey => $cval) {
                    if ($cval['default'] == 1) {
                        $this->defaultCountryId = $cval['id'];
                        $this->defaultCountryPhoneCode = $cval['code'];
                    }
                }
            } else if (!isset($this->defaultCountryPhoneCode) || $this->defaultCountryPhoneCode === '') {
                $this->defaultCountryPhoneCode = $countryListTemp[$this->defaultCountryId]['code'];
            }
            $countryCookie = array('name' => 'countryId', 'value' => $this->defaultCountryId, 'expire' => COOKIE_EXPIRATION_TIME);
            set_cookie($countryCookie);
            //setting default country phone code
            $phonecodeCookie = array('name' => 'phoneCode', 'value' => $this->defaultCountryPhoneCode, 'expire' => COOKIE_EXPIRATION_TIME);
            set_cookie($phonecodeCookie);
            if ($this->defaultCityId > 0) {
                $cityCookie = array('name' => 'cityId', 'value' => $this->defaultCityId, 'expire' => COOKIE_EXPIRATION_TIME);
                set_cookie($cityCookie);
            }
        }
        //To set the default country Name
        if (isset($this->countryList) && count($this->countryList) > 0) {
            foreach ($this->countryList as $countryIndex => $countryValue) {
                if ($countryValue['id'] == $this->defaultCountryId) {
                    $this->defaultCountryName = $countryValue['name'];
                    $this->defaultCountryPhoneCode = $countryValue['code'];
                }
            }
        }
        return array('countryList' => $this->countryList, 'defaultCountryId' => $this->defaultCountryId,
            'defaultCityId' => $this->defaultCityId,
            'defaultSplCityStateId' => $this->defaultSplCityStateId,
            'defaultCategoryId' => $this->defaultCategoryId,
            'defaultSubCategoryId' => $this->defaultSubCategoryId,
            'defaultSubCategoryName' => $this->defaultSubCategoryName,
            'defaultCategory' => $this->defaultCategory, 'defaultCustomFilterId' => $this->defaultCustomFilterId,
            'defaultCustomFilterName' => $this->defaultCustomFilterName, "defaultCountryPhoneCode" => $this->defaultCountryPhoneCode,
            'defaultCityName' => $this->defaultCityName, 'defaultCountryName' => $this->defaultCountryName);
    }

    public function footerValues() { 
        require_once(APPPATH . 'handlers/category_handler.php');
        $categoryList = $categoryListResponse = array();
        $this->categoryHandler = new Category_handler();
        $categoryListResponse = $this->categoryHandler->getCategoryList(array('major' => 1, 'noImages' => true));
        if (count($categoryListResponse) > 0 && $categoryListResponse['status'] && $categoryListResponse['response']['total'] > 0) {
            $categoryList = $categoryListResponse['response']['categoryList'];
        }
            $cookieCountryId = get_cookie('countryId');
            $countryId = ($cookieCountryId > 0 )?  $cookieCountryId : 14;
            $cityListResponse = $cityList = array();
            $this->cityHandler = new City_handler();
            $inputArray['major'] = 1;
            $inputArray['countryId'] = $countryId;
            $inputArray['specialStateName']=true;
            $cityListResponse = $this->cityHandler->getCityList($inputArray);
        
	    if (count($cityListResponse)>0 && $cityListResponse['status']&& $cityListResponse['response']['total']>0) {
                $cityList = $cityListResponse['response']['cityList'];
            }
       
        return array('categoryList' => $categoryList,'cityList' => $cityList);
    }

}

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
 * @Last Modified On  15-07-2015
 * @Last Modified By  Gautam
 */
require_once(APPPATH . 'handlers/common_handler.php');
require_once(APPPATH . 'handlers/city_handler.php');
require_once(APPPATH . 'handlers/event_handler.php');
require_once(APPPATH . 'handlers/subcategory_handler.php');

class Search extends CI_Controller {

    var $commonHandler;
    var $cityHandler;
    var $eventHandler;

    public function __construct() {
        parent::__construct();
        $this->commonHandler = new Common_handler();
        $this->eventHandler = new Event_handler();
        $this->cityHandler = new City_handler();
        $this->defaultCountryId = $this->defaultCityId = $this->defaultCategoryId = 0;
        $this->defaultCustomFilterId = 1;
    }

    public function index() {

        $data = array();
        $inputArray = $this->input->get();
        $this->benchmark->mark('top');
        $headerValues = $this->commonHandler->headerValues($inputArray);
        if (isset($inputArray['keyword'])) {
            $headerValues['defaultCityId'] = 0;
            $headerValues['defaultSplCityStateId'] = 0;
            $headerValues['defaultCategoryId'] = 0;
            $headerValues['defaultSubCategoryId'] = 0;
            $headerValues['defaultSubCategoryName'] = 'Subcategories';
            $headerValues['defaultCategory'] = 'All Categories';
            $headerValues['defaultCustomFilterId'] = 6;
            $headerValues['defaultCustomFilterName'] = 'time';
            $headerValues['defaultCityName'] = 'All Cities';
            $headerValues['keyword'] = $inputArray['keyword'];
        }
        $data = $headerValues;
        $this->benchmark->mark('citylist');
        $data['eventsList'] = array();
        $page = $limit = 0;
        //$inputArray = $this->input->get();
        $inputArray['major'] = 1;
        $inputArray['countryId'] = $data['defaultCountryId'];
        $inputArray['specialStateName'] = true;
        $cityList = $this->cityHandler->getCityList($inputArray);

        if ($cityList['status'] == TRUE && $cityList['response']['total'] > 0) {
            $data['cityList'] = $cityList['response']['cityList'];
        }

        $this->benchmark->mark('footer');
        $inputArray['cityId'] = $data['defaultCityId'];
        $inputArray['categoryId'] = $data['defaultCategoryId'];
        $footerValues = $this->commonHandler->footerValues();
        $data['categoryList'] = $footerValues['categoryList'];
        if (count($data['categoryList']) > 0) {
            $categoryListTemp = commonHelperGetIdArray($data['categoryList']);
            $data['defaultCategory'] = ($data['defaultCategoryId'] > 0 ) ? $categoryListTemp[$data['defaultCategoryId']]['name'] : LABEL_ALL_CATEGORIES;
        }

        $allCityinput = array();
        $allCityinput['countryId'] = $inputArray['countryId'];
        $allCityinput['cityId'] = $data['defaultCityId'];
        if ($data['defaultCityId'] > 0) {
            $allCityinput['specialStateName'] = true;
            $allCityList = $this->cityHandler->getCityDetailById($allCityinput);
            if ($allCityList['status'] == TRUE && $allCityList['response']['total'] > 0) {
                $data['defaultCityName'] = $allCityList['response']['detail']['name'];
            }
        } else {
            $data['defaultCityName'] = LABEL_ALL_CITIES;
        }

        //Feaching the events list
        $eventListArray = $data['eventsList'] = array();
        $eventListArray['countryId'] = $inputArray['countryId'];
        if ($inputArray['cityId'] > 0) {
            if ($data['defaultSplCityStateId'] != 0) {
                $eventListArray['stateId'] = $data['defaultSplCityStateId'];
            } else {
                $eventListArray['cityId'] = $inputArray['cityId'];
            }
        }

        if ($inputArray['categoryId'] > 0)
            $eventListArray['categoryId'] = $inputArray['categoryId'];

        if ($data['defaultSubCategoryId'] > 0) {
            $eventListArray['subcategoryId'] = $data['defaultSubCategoryId'];
            
            $this->subcategoryHandler = new Subcategory_handler();
            $subcategoryInput['subcategoryId'] = $data['defaultSubCategoryId'];
            $subcategoryDetails = $this->subcategoryHandler->getSubcategoryDetails($subcategoryInput);
            $data['defaultSubCategoryName'] = $subcategoryDetails['response']['subCategoryList'][0]['name'];
        }
        $eventListArray['limit'] = 12;
        $eventListArray['ticketSoldout'] = 0;
        $eventListArray['day'] = $data['defaultCustomFilterId'];
        //Search page contains keyword parameter
        if (isset($inputArray['keyword'])) {
            $eventListArray['keyWord'] = $inputArray['keyword'];
            unset($eventListArray['keyword']);
        }
        $eventListArray['private'] = 0;
        $eventListResponse = $this->eventHandler->getEventList($eventListArray);
        if ($eventListResponse['status'] == TRUE && $eventListResponse['response']['total'] > 0) {
            $data['eventsList'] = $eventListResponse["response"];
            $page = $eventListResponse["response"]["page"];
            $limit = $eventListResponse["response"]["limit"];
        }

        //Getting CustomFilter Data
        $data['customFilterList'] = commonHelperCustomFilterArray();
        $data['stateList'] = array();
        $data['moduleName'] = 'searchModule';
        $data['content'] = 'search_view';
        $data['pageName'] = 'search';
        $data['pageTitle'] = 'MeraEvents | Search';
        $data['jsArray'] = array(
            $this->config->item('js_angular_path') . 'user/searchModule',
            $this->config->item('js_angular_path') . 'user/controllers/searchControllers',
            $this->config->item('js_angular_path') . 'user/filters/searchFilters',
            $this->config->item('js_angular_path') . 'user/directives/searchDirectives',
            $this->config->item('js_angular_path') . 'common/commonModule',
          //  $this->config->item('js_angular_path') . 'common/services/cookieService',
          //  $this->config->item('js_angular_path') . 'common/controllers/countryController',
            $this->config->item('js_angular_path') . 'autocomplete/angucomplete-alt',
            $this->config->item('js_public_path') . 'common',
            $this->config->item('js_public_path') . 'fixto',
            $this->config->item('js_public_path') . 'search');
        $this->load->view('templates/user_template', $data);
    }

}

?>
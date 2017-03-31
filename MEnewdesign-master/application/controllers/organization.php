<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/**
 * Organization controller (Grouping of events Page)
 *
 * @package		CodeIgniter
 * @author		Qison  Dev Team
 * @copyright	Copyright (c) 2015, Meraevents.
 * @Version		Version 1.0
 * @Since       Class available since Release Version 1.0 
 * @Created     07-12-2015
 * @Last Modified On  07-12-2015
 * @Last Modified By  Raviteja
 */
require_once(APPPATH . 'handlers/organization_handler.php');
require_once(APPPATH . 'handlers/common_handler.php');
class Organization extends CI_Controller {

    var $organizationHandler;
    var $commonHandler;

    public function __construct() {
        parent::__construct();
        $this->organizationHandler = new organization_handler();
        $this->commonHandler = new Common_handler();
        $this->defaultCountryId = $this->defaultCityId = $this->defaultCategoryId = 0;
        $this->defaultCustomFilterId = 1;
    }

    public function index() {
        $getVar['orgid'] = $this->uri->segment(3);
        $data['countryList']='';
        $data['categoryList']='';
        $cookieData = $this->commonHandler->headerValues();
        $footerValues = $this->commonHandler->footerValues();
        $data['eventsData']=array();
        $data['organizationDetails']= array();
        if (count($cookieData) > 0) {
        	$data['countryList'] = isset($cookieData['countryList']) ? $cookieData['countryList'] : array();
        	$this->defaultCountryId = isset($cookieData['defaultCountryId']) ? $cookieData['defaultCountryId'] : $this->defaultCountryId;
        }
        $getVar['type'] = 'upcoming';
        $organizationDetails = $this->organizationHandler->getOrganizationDetails($getVar);
        if($organizationDetails['status'] && $organizationDetails['response']['totalcount'] == 0){
        	$getVar['type'] = 'past';
        	$organizationDetails = $this->organizationHandler->getOrganizationDetails($getVar);
        }
        if( !$organizationDetails['status'] || empty($organizationDetails['response']['organizationData'])  ){
        	$data['content'] = 'error_view';
        	$data['message'] = ERROR_NO_RECORDS;
        }else{
        	$data['content'] = 'organization_view';
        }
       	$organizationList = $organizationDetails['response']['organizationData'];
        $updatedViews = $this->organizationHandler->updateViewCount($organizationList[0]);
        if($updatedViews['status']){
            $organizationList[0]['viewcount'] = $organizationList[0]['viewcount']+1;
        }
       	$data['organizationDetails']= $organizationList[0]; 
       	$data['eventsData'] =$organizationDetails['response']['OrganizationEventsData'] ;
       	$data['totalCount'] =$organizationDetails['response']['totalcount'];
       	$data['pageType']= $getVar['type'];
       	$data['jsArray'] = array(
             $this->config->item('js_public_path') . 'jquery.validate',
             $this->config->item('js_public_path') . 'organization',
             $this->config->item('js_public_path') . 'inviteFriends'
       	);
      
        $data['categoryList'] = $footerValues['categoryList'];
        $data['defaultCountryId'] = $this->defaultCountryId;
        $this->load->view('templates/user_template', $data);
    }

}

?>
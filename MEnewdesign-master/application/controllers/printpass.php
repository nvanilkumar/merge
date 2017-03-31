<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/**
 * Event signup Confirmation  page controller
 *
 * @package		CodeIgniter
 * @author		Qison  Dev Team
 * @copyright	Copyright (c) 2015, MeraEvents.
 * @Version		Version 1.0
 * @Since       Class available since Release Version 1.0 
 * @Created     11-06-2015
 * @Last Modified On  03-08-2015
 * @Last Modified By  Raviteja
 */
require_once(APPPATH . 'handlers/event_handler.php');
require_once(APPPATH . 'handlers/common_handler.php');
require_once(APPPATH . 'handlers/printpass_handler.php');
require_once(APPPATH . 'handlers/user_handler.php');

class Printpass extends CI_Controller {
	//var $ci;
    var $eventHandler;
    var $commonHandler;
    var $printpassHandler;
    var $userHandler;
    public function __construct() {
        parent::__construct();
        $this->eventHandler = new Event_handler();
        $this->commonHandler = new Common_handler();
        $this->printpassHandler = new Printpass_handler();
        $this->userHandler = new User_handler();
        $this->defaultCountryId = $this->defaultCityId = $this->defaultCategoryId = 0;
        $this->defaultCustomFilterId = 1;
      
    }

    public function index() {
   			$userId = $this->customsession->getData('userId');
   	    	if($this->input->server('REQUEST_METHOD') == 'POST'){
    		$userEmail = $this->input->post('useremail');
    		$eventsignupId = $this->input->post('regno');
    	}
    	if($this->input->server('REQUEST_METHOD') == 'GET'){
    		$eventsignupId = $this->uri->segment(2);
    	}
    	$cookieData = $this->commonHandler->headerValues();
    	$eventsignup['eventsignupId'] = isset($eventsignupId)?$eventsignupId:'';
    	$eventsignup['userEmail'] = isset($userEmail)?$userEmail:$this->customsession->getData('userEmail');
    	$view = 'printpass_view';
    	if($eventsignupId != ''){
    		$eventsignpudata = $this->printpassHandler->getUserEventsignup($eventsignup);
    		if($eventsignpudata['status'] && count($eventsignpudata['response']['eventSignupDetailData'])>0){
    			$data = $eventsignpudata['response']['eventSignupDetailData'];
    		}else{
    			$data['message'] = ERROR_INVALID_DATA;
    		}
    	}
    	$data['pageName'] = 'Print Pass';
        $data['pageTitle'] = 'Print your ticket';
    	if(isset($data['message']) && $this->input->server('REQUEST_METHOD') == 'GET'){
    		$view = 'error_view';
    	}
      	$data['content'] = $view;
      	//$data['moduleName']='eventModule';
      	$data['jsArray'] = array(//$this->config->item('js_angular_path') . 'common/jQuery'  ,
      			//$this->config->item('js_angular_path') . 'user/eventModule'  ,
      			//$this->config->item('js_angular_path') . 'common/commonModule'  ,
      		//	$this->config->item('js_angular_path') . 'common/services/cookieService'  ,
      		//	$this->config->item('js_angular_path') . 'common/controllers/countryController'  ,
      			$this->config->item('js_public_path') . 'fixto'  ,
      			$this->config->item('js_public_path') . 'jquery.validate'  ,
      			$this->config->item('js_public_path') . 'event'  ,
      			$this->config->item('js_public_path'). 'printpass'  
      	);
      	$data['cssArray'] = array( 
            $this->config->item('css_public_path') . 'print_tickets'  ,
            $this->config->item('css_public_path') . 'onscroll-specific');
      	
      	if (count($cookieData) > 0) {
      		$data['countryList'] = isset($cookieData['countryList']) ? $cookieData['countryList'] : array();
      		$this->defaultCountryId = isset($cookieData['defaultCountryId']) ? $cookieData['defaultCountryId'] : $this->defaultCountryId;
      	}
        
      	$footerValues = $this->commonHandler->footerValues();
      	$data['categoryList'] = $footerValues['categoryList'];
        $data['cityList'] = $footerValues['cityList'];
      	$data['defaultCountryId'] = $this->defaultCountryId;
        $data['defaultCityId'] = $this->defaultCityId;
        $this->load->view('templates/user_template', $data);
    }
    

}

?>
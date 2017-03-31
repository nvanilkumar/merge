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
require_once(APPPATH . 'handlers/common_handler.php');
require_once(APPPATH . 'handlers/confirmation_handler.php');
require_once(APPPATH . 'handlers/eventsignup_handler.php');
require_once (APPPATH . 'handlers/orderlog_handler.php');

class Confirmation extends CI_Controller {

    var $commonHandler;
    var $confirmationHandler;
    var $eventsignupHandler;
    public function __construct() {
        parent::__construct();
        $this->commonHandler = new Common_handler();
        $this->confirmationHandler = new Confirmation_handler();
        $this->defaultCountryId = $this->defaultCityId = $this->defaultCategoryId = 0;
        $this->defaultCustomFilterId = 1;
        $this->eventsignupHandler = new Eventsignup_handler();
    }

    public function index() {
        
    	$userId = $this->customsession->getData('userId');
        $cookieData = $this->commonHandler->headerValues();
        $footerValues = $this->commonHandler->footerValues();
        $orderid = $this->input->get('orderid');
        if (!isset($orderid) && $orderid == '' || $userId == '') {
            redirect(commonHelperGetPageUrl("user-noaccess", 'NoAccess'), 'refresh');
            exit;
        }
		$orderLogInput['orderId'] = $orderid;
        $this->orderlogHandler = new Orderlog_handler();
        $orderLogData = $this->orderlogHandler->getOrderlog($orderLogInput);
        $oldOrderLogData = $orderLogData['response']['orderLogData'];
        $orderLogCalculationData = unserialize($orderLogData['response']['orderLogData']['data']);
        $eventsignupArray['eventsignupId'] = $oldOrderLogData['eventsignup'];
        $eventsignupArray['userId'] = $userId;
        $eventsignupArray['orderId'] = $orderid;
        $eventsignupArray['orderlogData'] = $orderLogCalculationData;
		$eventsignupArray['isExtraIdsArray'] = true;
		$eventSignupdata = $this->eventsignupHandler->getEventsignupDetailData($eventsignupArray);
         if(isset($eventSignupdata['response']['eventSignupDetailData'])){
        	$data = $eventSignupdata['response']['eventSignupDetailData'];
                //To validate the event specific validations after sucessfull booking
                $this->confirmationHandler->eventSpecificValidations($eventSignupdata);
        }else{
        	$data = $eventSignupdata;
        }  
		$data['widgetRedirectUrl'] = '';
		$usedReferalCode = '';
		if($orderLogCalculationData['referralcode'] != '') {
			$usedReferalCode = $orderLogCalculationData['referralcode'];
		}
		$data['usedReferalCode'] = $usedReferalCode;
		$data['previewPagebooking']=0;
		if(isset($orderLogCalculationData['pageType']) && $orderLogCalculationData['pageType'] == 'preview' ){
			$data['previewPagebooking'] =1; 
		}
		// If it is 20-80 event,with considering old signup id, redirecting to its calculation page
		if(isset($orderLogCalculationData['oldsignupid']) && $orderLogCalculationData['widgetredirecturl'] != '' && $orderLogCalculationData['oldsignupid'] > 0) {
			$redirectUrl = $orderLogCalculationData['widgetredirecturl'];
			header("Location: ".$redirectUrl);
			exit;
		} elseif($orderLogCalculationData['widgetredirecturl'] != '' && $orderLogCalculationData['oldsignupid'] == '') {
			$data['widgetRedirectUrl'] = urldecode($orderLogCalculationData['widgetredirecturl']).'?orderId='.$orderid;
		}
        $data['moduleName'] = 'eventModule';
        $data['pageName'] = 'Payment Confirmation';
        $data['pageTitle'] = $data['eventData']['title'].' | '. 'Payment Confirmation';
        $data['jsArray'] = array(
            $this->config->item('js_public_path') . 'fixto'  ,
        	$this->config->item('js_public_path') . 'inviteFriends'  ,
        	$this->config->item('js_public_path') . 'jquery.validate' ,
                $this->config->item('js_public_path') . 'event');
        	$data['cssArray'] = array(
                    $this->config->item('css_public_path') . 'print_tickets'  ,
                    $this->config->item('css_public_path') . 'onscroll-specific');
	        $smsData['eventtitle'] = $data['eventData']['title'];
       	 $data['orderid'] = $orderid;
         $smsData['mobile'] = $data['userDetail']['mobile'];
         $data['countryList']='';
         $data['categoryList']='';
      	if (count($cookieData) > 0) {
      		$data['countryList'] = isset($cookieData['countryList']) ? $cookieData['countryList'] : array();
      		$this->defaultCountryId = isset($cookieData['defaultCountryId']) ? $cookieData['defaultCountryId'] : $this->defaultCountryId;
      	}
		if(is_array($orderLogCalculationData['paymentResponse']) && count($orderLogCalculationData['paymentResponse']) > 0 && $orderLogCalculationData['paymentResponse']['MerchantRefNo'] > 0) {
			
		} else {
			$data['response']['messages'] = array(ERROR_INVALID_DATA);
		}
        if(isset($data['response']['messages']) && count($data['response']['messages'])>0){
        	$data['content'] = 'error_view';
        	$data['message'] = "Something Went wrong. Please Try Again";
        }else{
        	$data['content'] = 'confirmation_view';
        }
                $data['categoryList'] = $footerValues['categoryList'];
                $data['cityList'] = $footerValues['cityList'];
		$categoryArr = commonHelperGetIdArray($footerValues['categoryList']);
		$data['eventData']['categoryName'] = $categoryArr[$data['eventData']['categoryId']];
        $data['defaultCountryId'] = $this->defaultCountryId;
        $this->load->view('templates/user_template', $data);
    }
    

// Function to Print the Delegate Pass for Eventsignup

    public function delegatepass() {
     	$eventsignupId =  $this->uri->segment(2);
     	$userEmail =  urldecode($this->uri->segment(3));
     	$footerValues = $this->commonHandler->footerValues();
     	$cookieData = $this->commonHandler->headerValues();
        	$eventsignupArray['eventsignupId'] = $eventsignupId;
        	$eventsignupArray['userId'] = $this->customsession->getData('userId');
        	$this->confirmationHandler->printPass($eventsignupArray);
        if(isset($eventsignupId) && $eventsignupId != '' ){
        	$inputArray['eventsignupId'] = $eventsignupId;
        	$inputArray['userEmail'] = $userEmail;
        	//$eventsignupArray['userId'] = $this->customsession->getData('userId');
        	$checkEventsignup = $this->eventsignupHandler->checkEventsignup($inputArray);
    		if($checkEventsignup['status'] && $checkEventsignup['response']['total']>0){
    			$eventsignupArray['eventsignupId'] = $inputArray['eventsignupId'];
    			$eventsignupArray['userId'] = $checkEventsignup['response']['userId'];
    			$this->confirmationHandler->printPass($eventsignupArray);
    		}
    		$data['moduleName'] = 'eventModule';
    		$data['pageName'] = 'Print Pass';
    		$data['pageTitle'] = 'Print Pass';
    		$data['jsArray'] = array(
    				$this->config->item('js_public_path') . 'fixto'  ,
    				$this->config->item('js_public_path') . 'inviteFriends'  ,
    				$this->config->item('js_public_path') . 'jquery.validate'  ,
    				$this->config->item('js_public_path') . 'event'  );
    		$data['cssArray'] = array(
    				$this->config->item('css_public_path') . 'print_tickets'  ,
    				$this->config->item('css_public_path') . 'onscroll-specific');
    		$data['countryList']='';
    		$data['categoryList']='';
    		
    		if (count($cookieData) > 0) {
    			$data['countryList'] = isset($cookieData['countryList']) ? $cookieData['countryList'] : array();
    			$this->defaultCountryId = isset($cookieData['defaultCountryId']) ? $cookieData['defaultCountryId'] : $this->defaultCountryId;
    		}
    		if(isset($checkEventsignup['response']['messages']) && count($checkEventsignup['response']['messages'])>0){
    			$data['content'] = 'error_view';
    			$data['message'] = ERROR_INVALID_DATA;
    		}
    		$data['categoryList'] = $footerValues['categoryList'];
    		$data['defaultCountryId'] = $this->defaultCountryId;
    		$this->load->view('templates/user_template', $data);
        }
        
    }

    // Function to resend the Eventsignup DelegateEmail

    public function resendTransactionSuccessEmailToDelegate() {
        $eventsignupArray = $this->input->get();
        $this->confirmationHandler->resendTransactionsuccessEmail($eventsignupArray);
    }
        // print multiple passes
        public function getMultipleOfflinePasses($uniqueId, $eventId) {
        
        $uniqueId=trim($this->input->get('uniqueId'));
        $eventId=trim($this->input->get('eventId'));
        if (strlen($uniqueId) == 0 || strlen($eventId) == 0) {
            echo 'Invalid Url';
        }
        $inputArray['eventId'] = $eventId;
        $inputArray['extrafield'] = $uniqueId;
        $signupIds = $this->eventsignupHandler->getEventSignupId($inputArray);
        $eventsignId = $signupIds['response']['eventsignupids']['0'];
        $this->load->library('pdf');
        $mpdf = $this->pdf->load();
        $i = 0;
        $esId=count($eventsignId);
        foreach ($eventsignId as $value) {
            $eventsignupArray['eventsignupId'] = $value['id'];
            $eventsignupDetails[] = $this->eventsignupHandler->getEventsignupDetailData($eventsignupArray);
            $data = $eventsignupDetails[$i]['response']['eventSignupDetailData'];
            $delegatepassTemplateData = $this->eventsignupHandler->getdelegatepassHtml($data);
            $data = utf8_encode($delegatepassTemplateData);
            $mpdf->WriteHTML($data);
           if(($esId - 1)   != $i){
            $mpdf->AddPage();
            $i++;
           }
        }
        $mpdf->Output('E-ticket.pdf', 'I');
    }
}

?>
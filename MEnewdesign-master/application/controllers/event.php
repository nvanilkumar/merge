<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/**
 * Default landing page controller
 *
 * @package		CodeIgniter
 * @author		Qison  Dev Team
 * @copyright	Copyright (c) 2015, Meraevents.
 * @Version		Version 1.0
 * @Since       Class available since Release Version 1.0 
 * @Created     11-06-2015
 * @Last Modified On  24-06-2015
 * @Last Modified By  Sridevi
 */
require_once(APPPATH . 'handlers/event_handler.php');
require_once(APPPATH . 'handlers/solr_handler.php');
require_once(APPPATH . 'handlers/ticket_handler.php');
require_once(APPPATH . 'handlers/common_handler.php');
require_once(APPPATH . 'handlers/gallery_handler.php');
require_once(APPPATH . 'handlers/eventsignup_handler.php');
require_once(APPPATH . 'handlers/promoter_handler.php');
require_once(APPPATH . 'handlers/salesperson_handler.php');
require_once(APPPATH . 'handlers/discount_handler.php');

class Event extends CI_Controller {

    var $eventHandler;
    var $ticketHandler;
    var $geoHandler;
    var $solrHandler;
    var $defaultCountryId;
    var $commonHandler;
    var $salespersonHandler;

    public function __construct() {
        parent::__construct();
        $this->solrHandler = new Solr_handler();
        $this->eventHandler = new Event_handler();
        $this->ticketHandler = new Ticket_handler();
        $this->commonHandler = new Common_handler();
        $this->salespersonHandler = new Salesperson_handler();
        $this->defaultCountryId = $this->defaultCityId = $this->defaultCategoryId = 0;
        $this->defaultCustomFilterId = 1;
    }

    public function index($eventUrl) {
        
        //$this->output->enable_profiler(TRUE);
        $isError = false;
        $getVar = $this->input->get();
		
        if(strtolower($this->uri->segment(1))=='previewevent' && !isset($getVar['EventId']) && !isset($getVar['eventId'])){
            redirect(commonHelperGetPageUrl('home'));
        }
		
        if(strtolower($this->uri->segment(1))=='previewevent' && isset($getVar['EventId'])){
            commonHelperRedirect(commonHelperGetPageUrl('event-preview', '', '?view=preview&eventId=' . $getVar['EventId']));
        }
		elseif(strtolower($this->uri->segment(1))=='pricingtab.php' && isset($getVar['eventid'])){
            $wcode=$getVar['wcode'];
            commonHelperRedirect(commonHelperGetPageUrl('pricingtab', '', '?EventId=' . $getVar['eventid'].'&wcode='.$wcode));
        }
		   
		
		$eventUrlEx = explode("&",$eventUrl);
		  if(count($eventUrlEx) > 0){ 
			$eventUrl = $eventUrlEx[0];
			
		   $eventUrlParamEx = explode("=",$eventUrlEx[1]);
				if($eventUrlParamEx[0]=='ucode'){
					$getVar['ucode'] = $eventUrlParamEx[1];
				}
				}
		        $this->customsession->setData('booking_message', '');
        // If the Preview button was clicked on create event page
        $pageType = ($getVar['view']) ? $getVar['view'] : '';
        $eventId = $eventIdGet = ($getVar['eventId']) ? $getVar['eventId'] : '';
        $referralCode = isset($getVar['reffCode']) ? $getVar['reffCode'] : '';
        $promoterCode = isset($getVar['ucode']) ? $getVar['ucode'] : '';
        $aCode = isset($getVar['acode']) ? $getVar['acode'] : '';
        $ticketWidget = ($getVar['ticketWidget']) ? $getVar['ticketWidget'] : '';
        if( !empty($pageType) && 'preview' != strtolower($pageType)){
            redirect(site_url());
        }
        if ($pageType == 'preview' && $eventIdGet != '') {
            $eventId = $eventIdGet;
            if((empty($eventId) || !(is_int($eventId)||is_numeric($eventId)))){
                redirect(site_url());
            }
        } elseif (($getVar['eventId'] != '' || $getVar['EventId'] != '') && $pageType == '') {
            $eventId = ($getVar['eventId'] != '') ? $getVar['eventId'] : $getVar['EventId'];
            $ticketWidget = 'Yes';
        } elseif ($eventUrl != '') {
            $solrRequestArray = array();
            $solrRequestArray['private'] = TRUE; // Need to get the private events also from solr
            $solrRequestArray['eventUrl'] = $eventUrl;
            $solrRequestArray['status'] = 1;
            //Check URL is exists? and check whether it is published or not
            $eventId = $this->solrHandler->getEventByUrl($solrRequestArray);
            if (!$eventId['status']) {
                $cookieData = $this->commonHandler->headerValues();
                if (count($cookieData) > 0) {
                    $data['countryList'] = isset($cookieData['countryList']) ? $cookieData['countryList'] : array();
                    $this->defaultCountryId = isset($cookieData['defaultCountryId']) ? $cookieData['defaultCountryId'] : $this->defaultCountryId;
                }

                $footerValues = $this->commonHandler->footerValues();
                $data['categoryList'] = $footerValues['categoryList'];
                $data['cityList'] = $footerValues['cityList'];
                $data['defaultCountryId'] = $this->defaultCountryId;
                $isError = true;
            } else {
                $eventId = $eventId['response']['eventId'];
            }
        }
        if(!$isError) {
			
			/*updating event view count*/
			$this->eventHandler->updateEventViewCount($eventId);
			
            $request['eventId'] = $eventId;
            // Getting the Event Details
            $eventDataArr = $this->eventHandler->getEventPageDetails($request);
			
			//$eventData['viewcount'] = $eventDataArr['response']['details']['eventDetails']['viewcount'];


            // if event is canceled redirect to home page
            if ($eventDataArr['status'] && $eventDataArr['response']['details']['status'] == 3) {
                $redirectUrl = site_url();
                redirect($redirectUrl);
            }
            if ($eventDataArr['status'] && $eventDataArr['response']['total'] > 0) {
                $eventData = $eventDataArr['response']['details'];
            }else if($eventDataArr['status'] && $eventDataArr['response']['total'] == 0){
                redirect(site_url());
            }

            if (isset($eventData['eventDetails']['salespersonid']) && $eventData['eventDetails']['salespersonid'] > 0) {
                $input['salesPersonId'] = $eventData['eventDetails']['salespersonid'];
                $salesDataArr = $this->salespersonHandler->getSalesPersonDetails($input);
                if ($salesDataArr['status'] && $salesDataArr['response']['total'] > 0) {
                    $eventData['salesDetails'] = $salesDataArr['response']['details'][0];
                }
            }
        }
        if (!empty($referralCode) && !$isError) {
            $eventsignupHandler = new Eventsignup_handler();
            $inputViral['type'] = 'referral';
            $inputViral['code'] = $referralCode;
            $isValidReffCode = $eventsignupHandler->isValidCode($inputViral);
        }
        if (!empty($promoterCode) && !$isError) {
            if ($promoterCode == 'organizer') {
                $promoterOutputResponse['status'] = TRUE;
                $promoterOutputResponse['response']['total'] = 1;
            } else {
                $inputCode['type'] = 'affliate';
                $inputCode['code'] = array($promoterCode);
                $inputCode['eventId'] = $eventId;
                $this->promoterHandler = new Promoter_handler();
                $promoterOutputResponse = $this->promoterHandler->getPromoterList($inputCode);
            }
        }
        if (!empty($aCode) && !$isError) {
            $inputCode['type'] = 'global';
            $inputCode['promoterCode'] = ($aCode);
            $this->promoterHandler = new Promoter_handler();
            $aCodeResponse = $this->promoterHandler->checkPromoterCode($inputCode);
        }

        if (isset($isValidReffCode) && $isValidReffCode['status'] && !$isValidReffCode['response']['codeData']['valid']) {
            redirect(commonHelperGetPageUrl('preview-event', $eventUrl));
        }

        if (isset($promoterOutputResponse['status']) && isset($promoterOutputResponse['response']['total']) && $promoterOutputResponse['response']['total'] == 0) {
            redirect(commonHelperGetPageUrl('preview-event', $eventUrl));
        }
        if (isset($aCodeResponse['status']) && isset($aCodeResponse['response']['total']) && $aCodeResponse['response']['total'] == 0) {
            redirect(commonHelperGetPageUrl('preview-event', $eventUrl));
        }
        if($this->customsession->getData('userId')>0 && $eventData['private']==0){
            //print_r($eventDataArr);exit;
                $this->promoterHandler = new Promoter_handler();
                $inputGlobalCode['userid']=$this->customsession->getData('userId');
                $promoterOutputResponse = $this->promoterHandler->getGlobalCode($inputGlobalCode);
                //print_r($promoterOutputResponse);exit;
        }
        $data = array();
        if(isset($promoterOutputResponse) && $promoterOutputResponse['status'] && $promoterOutputResponse['response']['total']>0){
            $data['globalPromoterCode']=$promoterOutputResponse['response']['promoterList'][0]['code'];
        }
        
        //country list
        $data['countryList'] = array();
        $data['eventData'] = $ticketDetails = $ticketIds = array();
        if ($ticketWidget != 'Yes') {
            
            $cookieData = $this->commonHandler->headerValues();
            if (count($cookieData) > 0) {
                $data['countryList'] = isset($cookieData['countryList']) ? $cookieData['countryList'] : array();
                $this->defaultCountryId = isset($cookieData['defaultCountryId']) ? $cookieData['defaultCountryId'] : $this->defaultCountryId;
            }
            $footerValues = $this->commonHandler->footerValues();
            $data['categoryList'] = $footerValues['categoryList'];
            $data['cityList'] = $footerValues['cityList'];
            $data['defaultCountryId'] = $this->defaultCountryId;

            $galleryHandler = new Gallery_handler();
            $galleryImages = $galleryHandler->getEventGalleryList($request);
            if ($galleryImages['status'] && $galleryImages['response']['total'] > 0) {
                $data['gallery'] = $galleryImages["response"]["galleryList"];
            }
        }
        if ($eventData['registrationType'] != 3) {
            $requestTickets = $request;
            $requestTickets['allTickets'] = 0;
            // Getting the Tickets list for the Event
            $requestTickets['eventTimezoneName'] = $eventData['location']['timeZoneName'];
            $ticketResponse = $this->ticketHandler->getEventTicketList($requestTickets);
            if ($ticketResponse['status'] && $ticketResponse['response']['total'] > 0) {
                $ticketDetailsData = $ticketResponse['response']['ticketList'];
                $taxDetails = isset($ticketResponse['response']['taxDetails']) ? $ticketResponse['response']['taxDetails'] : array();
                $ticketDetails = commonHelperGetIdArray($ticketDetailsData);
            }
            foreach ($taxDetails as $key => $value) {
                $ticketDetails[$key]['taxes'] = $taxDetails[$key];
            }
            $ticketIds = array_keys($ticketDetails);
        }
        $eventAddress = array();
        if (isset($eventData['location']['venueName']) && !empty($eventData['location']['venueName'])) {
            $eventAddress[] = $eventData['location']['venueName'];
        }
        if (isset($eventData['location']['address1']) && !empty($eventData['location']['address1'])) {
            $eventAddress[] = $eventData['location']['address1'];
        }
        if (isset($eventData['location']['address2']) && !empty($eventData['location']['address2'])) {
            $eventAddress[] = $eventData['location']['address2'];
        }
        if (isset($eventData['location']['cityName']) && !empty($eventData['location']['cityName'])) {
            $eventAddress[] = " ".$eventData['location']['cityName'];
        }
        if (isset($eventData['location']['stateName']) && !empty($eventData['location']['stateName'])) {
            $eventAddress[] = " ".$eventData['location']['stateName'];
        }
        if (isset($eventData['location']['countryName']) && !empty($eventData['location']['countryName'])) {
            $eventAddress[] = " ".$eventData['location']['countryName'];
        }
        
        $eventData['fullAddress'] = implode(',',array_unique($eventAddress));
        $eventData['saleButtonTitleList'] = saleButtonTitle();
        $eventData['timeZoneName'] = $eventData['location']['timeZoneName'];
        $seoRequestArray = array();
        $seoRequestArray['eventId'] = $request['eventId'];
        $seoRequestArray['title'] = isset($eventData['title']) ? $eventData['title'] : '';
        $data['pageTitle']= isset($eventData['eventDetails']['seotitle'])  ? $eventData['eventDetails']['seotitle'] : '';
        if(isset($eventData['location']['cityName']) && !empty($eventData['location']['cityName'])  && ($eventData['eventDetails']['seotitle'] == $eventData['title'])) {
            $data['pageTitle'] =  $eventData['title']. ' | ' . $eventData['location']['cityName'] . ' | MeraEvents.com';
        }
        
        $noIndex = false;
        //is private event or not published
        if ($eventData['private'] == 1 || $eventData['status'] != 1) {
            $noIndex = TRUE;
        }
        $data['seoDetails']['noIndex'] = $noIndex;

        if (!empty($referralCode) && count($ticketIds) > 0) {
            $inputViral['ticketIdArr'] = $ticketIds;
            $viralTickets = $this->ticketHandler->getViralTickets($inputViral);
        }
        $viralResponse = array();
        if (isset($viralTickets) && $viralTickets['status']) {
            if ($viralTickets['response']['total'] > 0) {
                $viralResponse = $viralTickets['response']['viralData'];
            }
        } elseif (isset($viralTickets)) {
            $data['errors'][] = $isValidReffCode['response']['messages'][0];
        }
        foreach ($viralResponse as $viralTicketData) {
            $ticketId = $viralTicketData['ticketid'];
            $ticketDetails[$ticketId]['viralData'] = $viralTicketData;
        }
        $eventData['normalDiscountExists'] = FALSE;
        if ($eventData['registrationType'] != 3) {
            $this->discountHandler = new Discount_handler();
            $discountInput['eventId'] = $eventId;
            $discountInput['discountType'] = 'normal';
            $discountInput['pageType'] = 'eventdetail';
		    $eventDiscountList = $this->discountHandler->getDiscountData($discountInput);
            if ($eventDiscountList['status'] && $eventDiscountList['response']['total'] > 0) {
                
                $eventDiscountArr = $eventDiscountList['response']['discountList'][0];
                if ($eventDiscountList['response']['total'] > 0 && $eventDiscountArr['totalused'] < $eventDiscountArr['usagelimit']) {
                    $eventData['normalDiscountExists'] = TRUE;
                }
            }
        }
        $tncType = $eventData['eventDetails']['tnctype'];
        $organizerTnc = $eventData['eventDetails']['organizertnc'];
        $meraeventsTnc = $eventData['eventDetails']['meraeventstnc'];
        if ($tncType == 'organizer') {
            if ($organizerTnc != '') {
                $data['tncDetails'] = $organizerTnc;
            }
        } else if ($tncType == 'meraevents') {
            if ($meraeventsTnc != '') {
                $data['tncDetails'] = $meraeventsTnc;
            }
        }
        $data['aCode']=$aCode;
        $data['eventData'] = $eventData;
        $data['ticketDetails'] = $ticketDetails;
        $data['referralCode'] = $referralCode;
        $data['promoterCode'] = $promoterCode;
        $data['moduleName'] = 'eventModule';
        $data['pageName'] = 'Event List';
        $data['ticketWidget'] = $ticketWidget;
        $data['pageType'] = $pageType;

        if (isset($getVar['wcode']) && $getVar['wcode'] != '') {
            $data['wCode'] = $getVar['wcode'];
        }
		
		
        $data['jsArray'] = array(
            $this->config->item('js_public_path') . 'fixto',
            $this->config->item('js_public_path') . 'lightbox',
            $this->config->item('js_public_path') . 'jquery.validate',
            $this->config->item('js_public_path') . 'common',
           // $this->config->item('js_public_path') . 'onscrollScript',
            $this->config->item('js_public_path') . 'event',
            $this->config->item('js_public_path') . 'inviteFriends');
        $data['cssArray'] = array(
            $this->config->item('css_public_path') . 'lightbox',
            $this->config->item('css_public_path') . 'common');

        $data['content'] = 'event_view';
        if ($isError) {
            $data['content'] = 'error_view';
            $data['message'] = str_replace('REDIRECT_URL', site_url(), UNPUBLISHED_EVENT_ERROR);
        }
        $template = 'templates/user_template';

        if ($ticketWidget == 'Yes') {
            $data['content'] = 'includes/elements/event_tickets';
            $template = 'templates/ticket_widget_template';
        }
		
        $this->load->view($template, $data);
    }

}

?>
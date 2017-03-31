<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/**
 * Payment page controller
 *
 * @package		CodeIgniter
 * @author		Qison  Dev Team
 * @copyright	Copyright (c) 2015, MeraEvents.
 * @Version		Version 1.0
 * @Since       Class available since Release Version 1.0 
 * @Created     11-06-2015
 * @Last Modified On  18-02-2016
 * @Last Modified By  Shashi
 */
//require_once(APPPATH . 'handlers/common_handler.php');
require_once(APPPATH . 'handlers/microsite_handler.php');

class Microsite extends CI_Controller {

    //var $commonHandler;
    var $micrositeHandler;

    public function __construct() {
        parent::__construct();
        //$this->commonHandler = new Common_handler();
        $this->micrositeHandler = new Microsite_handler();
    }

    /*
     * Function to display the booking page
     *
     * @access	public
     * @return	Display the Booking form with the custom fields and payment gateways
     */

    public function index($name, $param = '') {
		
        $name = trim($name);
        $microsite_path = site_url('microsites/' . $name);
		define('_HTTP_MICROSITE_CF_ROOT', $this->config->item('images_cloud_path').'microsites/'. $name);
		
		//if (in_array($name,array('dandiya','newyear','holi'))) {
         if (in_array($name,array('holi'))) {
            define('_HTTP_CF_ROOT', $this->config->item('images_cloud_path'));
            define('_HTTP_SITE_ROOT', site_url());
			
			
			$this->MeraEventsDynamicMicroSites($name,$param);
		}
		
		
        /*if ($name == 'dandiya') {
            //define('_HTTP_CF_ROOT', $this->config->item('images_cloud_path'));
            //define('_HTTP_SITE_ROOT', site_url());
            include FCPATH . 'microsites/' . $name . '/templates/index_tpl.php';
            return true;
        }
         //echo FCPATH . 'microsites\\' . $name . '\\' . $param . '.php';exit;
        if (!empty($param)) {
            //echo FCPATH . 'microsites\\' . $name . '\\' . $param . '.php';exit;
            include FCPATH . 'microsites/' . $name . '/' . $param ;
            return true;
        }*/
		
		else
		{
        include FCPATH . 'microsites/' . $name . '/index.php';
        return true;
		}
    }
	
	
	/*for holi, newyear, dandiya etc ..*/
	public function MeraEventsDynamicMicroSites($micrositename,$city)
	{
		//$this->output->enable_profiler(TRUE);
		
		require_once(APPPATH . 'handlers/banner_handler.php');
		$bannerHandler = new Banner_handler();
		
		require_once(APPPATH . 'handlers/specialdiscount_handler.php');
		$specialDiscountHandler = new SpecialDiscount_handler();
		
		require_once(APPPATH . 'handlers/solr_handler.php');
		$solrHandler = new Solr_handler();
		
		
		require_once(APPPATH . 'handlers/event_handler.php');
		$eventHandler = new Event_handler();
		
		require_once(APPPATH . 'handlers/seodata_handler.php');
		$seoHandler = new Seodata_handler();
		
		
		$bannerInputArray = $data = array();
		
		
		$listedCities = array('hyderabad'=>47,"bengaluru"=>37,"mumbai"=>14,"pune"=>77,"chennai"=>39,"ahmedabad"=>40,"kolkata"=>42,"jaipur"=>41);
		$listedStates = array("delhi"=>53,"goa"=>11);
		$listedRegions = array("delhi"=>38,"goa"=>1231);
		
		
		
		
		$inputArray = array();
		
		
		$inputArray['page'] = 1;
		$inputArray['start'] = 0;
		$inputArray['limit'] = 150;
		
		//India
		$inputArray['countryId'] = $bannerInputArray['countryId'] = 14;
		$inputArray['categoryId'] = $bannerInputArray['categoryId'] = 1;
		
		$data['currentCity']=(empty($city))?'INDIA':strtoupper($city);
		
		if(array_key_exists($city,$listedCities)){
			$inputArray['cityId'] = $listedCities[$city];
		}
		elseif(array_key_exists($city,$listedStates)){
			$inputArray['stateId'] = $data['stateId'] = $listedStates[$city];
		}
		
		if($micrositename == 'holi')
		{
			$inputArray['subcategoryId'] = 164;
			$bannerInputArray['type'] = 4; // 3-newyear, 4-Holi
			
			$data['pageTitle'] = 'Holi Events in India 2016 | Holi festival tickets 2016';
		$data['pageDescription']="Book and Buy Online tickets for upcoming Holi festival tickets and Holi events in India 2016. Let's experience the special Holi festival with colours, rain dance, DJ, and unlimited liquor.";
        $data['pageKeywords'] = "Holi events in India 2016, Holi festivals in India 2016, Holi parties in India 2016, Holi festival tickets, Holi event tickets, Holi party tickets, Tickets for Holi festivals, Tickets for Holi events, Tickets for Holi parties, Holi tickets 2016, Buy tickets for Holi festival, Holi rain dances, Holy colours party";
			
			$uri_city = "holi";
                        if(!empty($city)){
                            $uri_city = "holi/".$city;
                        }
		
			
		}
		
		
		//print_r($inputArray); exit;
		
		
		/*banners*/
        $bannerInputArray['limit'] = 10;
        if($inputArray['cityId'] > 0)
            $bannerInputArray['cityId'] = $data['cityId'] = $inputArray['cityId'];
			
		if(array_key_exists($city,$listedRegions))
			$bannerInputArray['cityId'] = $listedRegions[$city];
			
			
        if($inputArray['categoryId'] > 0)
            $bannerInputArray['categoryId'] = $inputArray['categoryId'];

        $bannerList = $bannerHandler->getBannerList($bannerInputArray);
		//print_r($bannerList); exit;
        if ($bannerList['status'] == true && $bannerList['response']['total']>0) {
            $data['bannerList'] = $bannerList['response']['bannerList'];
        }
		
		
		/*discounts*/
		$discountInputArray['limit'] = 100;
		$discountInputArray['micrositename'] = $micrositename;
		$discountInputArray['status'] = 1;
		
		//$discountInputArray['cityId'] = 0;
		if($inputArray['cityId'] > 0)
            $discountInputArray['cityId'] = $inputArray['cityId'];
		if(array_key_exists($city,$listedRegions))
			$discountInputArray['cityId'] = $listedRegions[$city];
			
		
		$discountList = $specialDiscountHandler->getSpecialDiscounts($discountInputArray);
		//print_r($discountList); exit;
        if ($discountList['status'] == true && $discountList['response']['total']>0) {
			$dbDiscounts = array();
			$sno = 0;
			foreach($discountList['response']['discountList'] as $discounts)
			{
				$solrInputArray['selectFields'] = array("id", "url");
				$solrInputArray['id'] = $discounts['eventid'];
				$solrInputArray['limit'] = 1;
				$solrEventData = $solrHandler->getSitemapSolrEvents($solrInputArray);
				$solrEventData = json_decode($solrEventData,true);
				//print_r($solrEventData); exit;
				$currentEventURL = site_url()."event/".$solrEventData['response']['events'][0]['url'];
				
				$dbDiscounts[$sno]['discountlable'] = $discounts['title'];
				$dbDiscounts[$sno]['eventurl'] = $currentEventURL;
				$dbDiscounts[$sno]['promocode'] = $discounts['promocode'];
				$sno++;
			}
			
            $data['discountList'] = $dbDiscounts;
        }
		
		
		
		$eventListResponse = $eventHandler->getMEDynamicMicrositesEventList($inputArray);
		//print_r($eventListResponse); exit;
		
        if ($eventListResponse['status'] == TRUE && $eventListResponse['response']['total']>0) {
            $data['eventsList'] = $eventListResponse["response"];
            $page = $eventListResponse["response"]["page"];
            $limit = $eventListResponse["response"]["limit"];        
        }
        
        $data['page']=$page;
        $data['limit']=$limit;
        unset($inputArray['categoryId']);
        unset($inputArray['type']);
        unset($inputArray['limit']);
		
		
		
		
		
        $seoArray['url']=$uri_city;
        $seoKeys=$seoHandler->getSeoData($seoArray);       
        $data['seoStaus']=false;
        if(count($seoKeys['response']['seoData']) > 0){
            $sData=$seoKeys['response']['seoData'][0];
            $data['seoStaus']=true;
            $data['pageTitle'] =$sData['seotitle'];
            $data['pageDescription']=$sData['seodescription'];
            $data['pageKeywords'] =$sData['seokeywords']; 
            $data['canonicalurl'] =$sData['canonicalurl'];
        }
		
		//print_r($data); exit;
		
		$this->load->view('microsite/'.$micrositename, $data);
		
		
		//echo "after view fun"; exit;
	}

    public function vh1supersonic2015() {
		
		require_once (APPPATH . 'handlers/eventsignupticketdetail_handler.php');
		$eventsignupticketdetailHandler = new Eventsignup_Ticketdetail_handler();
		
		require_once(APPPATH . 'handlers/ticket_handler.php');
		$ticketHandler = new Ticket_handler();
		
		require_once(APPPATH . 'handlers/eventsignup_handler.php');
		$eventsignupHandler = new Eventsignup_handler();
		
		
		require_once(APPPATH . 'handlers/event_handler.php');
		$eventHandler = new Event_handler();
		
        //  $majorerror = 0;
        $data = $tokenDetails = $postVars = array();
        $data['tickets20arr'] = array("65894", "65895");
        $data['tickets100arr'] = array("65892", "65893");
        $data['ticketsmaparr'] = array("65894" => "65892", "65895" => "65893");
        $eventid = 79256;
        $data['esid'] = $this->input->get('esid');
        $data['code'] = $this->input->get('code');
        $postVars = $this->input->post();

        $inputArray['eventId'] = $eventid;
        $ticketdetails = $ticketHandler->getTickets($inputArray);
        $data['ticketdetails'] = $ticketdetails['response']['viralTicketData'];
        $inputs['eventsignupids'] = array($data['esid']);
        $inputs['ticketids'] = $data['tickets20arr'];
        $inputs['transactiontype'] = 'All';
        $eventSignupDetails = $eventsignupHandler->getSuccessfullTransactionsByEventId('', $data['esid']);
        if ($eventSignupDetails['status']) {
            $validTrancations = $eventsignupticketdetailHandler->getListByEventsignupIds($inputs);
            if ($validTrancations['status'] == TRUE && $validTrancations['response']['total'] > 0) {

                foreach ($validTrancations['response']['eventSignupTicketDetailList'] as $key => $value) {
                    $data['validTrancations'][$key] = $value;
                    $data['validTrancations'][$key]['ticketname'] = $ticketdetails['response']['viralTicketData'][$value['ticketid']]['name'];
                    $data['validTrancations'][$key]['signupdate'] = $eventSignupDetails['response']['eventsignupData'][0]['signupdate'];

                    // amount calculation
                    $calculationInput['eventId'] = $eventSignupDetails['response']['eventsignupData'][0]['eventid'];
                    foreach ($data['ticketdetails'] as $tdkey => $tdvalue) {
                        if ($tdvalue['id'] == $data['ticketsmaparr'][$data['validTrancations'][0]['ticketid']]) {
                            $ticketDetails[$tdvalue['id']] = $value['ticketquantity'];
                        } else {
                            $ticketDetails[$tdvalue['id']] = 0;
                        }
                    }
                    $calculationInput['ticketArray'] = $ticketDetails;
                    $calculationData = $eventHandler->getEventTicketCalculation($calculationInput);
                    $calculationData['response']['calculationDetails']['alreadypaidamount']['amount'] = $data['validTrancations'][0]['totalamount'];
                    $calculationData['response']['calculationDetails']['alreadypaidamount']['conveniencefee'] = $eventSignupDetails['response']['eventsignupData'][0]['totalamount'] - $data['validTrancations'][0]['totalamount'];
                    $calculationData['response']['calculationDetails']['totalPurchaseAmount'] = $calculationData['response']['calculationDetails']['totalPurchaseAmount'] - $eventSignupDetails['response']['eventsignupData'][0]['totalamount'];
                    $data['calculationDetails'] = $calculationData['response']['calculationDetails'];

                    $data['validTrancations'][$key]['remainingamount'] = $calculationData['response']['calculationDetails']['totalTicketAmount'] - $calculationData['response']['calculationDetails']['alreadypaidamount']['amount'];
                }
            } else {
                $data['errorstatus'] = TRUE;
                $data['completedStatus'] = TRUE;
                $data['errorMessage'] = 'You have completed your 100% transaction.';
                //  $majorerror = 1;
                $this->load->view('microsite/vh1supersonic2015', $data);
            }
        } else {
            $data['errorstatus'] = TRUE;
            $data['errorMessage'] = 'Invalid Url';
            //   $majorerror = 1;
            $this->load->view('microsite/vh1supersonic2015', $data);
        }
        $data['eventSignupDetails'] = $eventSignupDetails = $eventSignupDetails['response']['eventsignupData'];

        /* Code to get the event related gateways starts here */
        $eventGateways = array();
        $gateWayInput['eventId'] = $eventid;
        $gateWayInput['paymentGatewayId'] = $eventSignupDetails[0]['paymentgatewayid'];
        $gateWayData = $eventHandler->getEventPaymentGateways($gateWayInput);
        if ($gateWayData['status'] && count($gateWayData['response']['gatewayList']) > 0) {
            $eventGateways = $gateWayData['response']['gatewayList'];
        }
        foreach ($eventGateways as $key => $gateway) {
            $gatewayName = strtolower($gateway['gatewayName']);
            $data[$gatewayName . 'Gateway'] = 1;
            $data[$gatewayName . 'Key'] = $eventSignupDetails[0]['paymentgatewayid'];
        }
        $data['gateWayName'] = $gatewayName;
        $data['redirectUrl'] = site_url() . 'micrositePaymentResponse';
        /* Code to get the event related gateways ends here */

        $data['oldsignupid'] = $data['esid'];
        $data['makepayment'] = 0;
        if (isset($postVars['paynowForm']) && $postVars['paynowForm'] != '') {
            $paymentResult = $this->vh1supersonicPaynow($data);
            if ($paymentResult['status'] == TRUE) {
                $data['paymentdata'] = $paymentResult['response']['paymentdata'];
                $data['orderLogId'] = $paymentResult['response']['paymentdata']['orderId'];
                $data['primaryAddress'] = $paymentResult['response']['paymentdata']['primaryAddress'];
                $data['eventData']['title'] = $paymentResult['response']['paymentdata']['EventTitle'];
                $data['makepayment'] = 1;
            }
        }

        if ((!isset($data['code']) && $validTrancations['response']['total'] > 0) || isset($data['code'])) {
            $tokenDetails = $this->micrositeHandler->codeVerification($data['code']);

            if ($tokenDetails['status'] == TRUE) {
                if ($tokenDetails['response']['total'] == 0) {
                    
                } else {
                    $data['errorstatus'] = FALSE;
                    $data['errorMessage'] = '';
                }
            } else {
                $data['errorstatus'] = TRUE;
                $data['errorMessage'] = 'Sorry, It seems invalid request, Please try again.';
            }
        }
        $this->load->view('microsite/vh1supersonic2015', $data);
    }

    public function vh1supersonic2015Links() {
        $tickets = array("65894", "65895");
        $eventId = 79256;
        $result = $this->micrositeHandler->generateMailLinks($eventId, $tickets);
        print_r($result);
    }

    private function vh1supersonicPaynow($data) {
        $response = $this->micrositeHandler->paynow($data);
        return $response;
    }

    public function micrositePaymentResponse() {
        $orderid = $this->input->get('orderid');
        if (!isset($orderid) && $orderid == '') {
            echo "error";
            exit;
        }
        $response = $this->micrositeHandler->paymentResponse($orderid);
        if ($response['status']) {
            redirect(site_url('vh1supersonic2015?esid=' . $response['response']['eventsignup']));
        } else {
            $this->customsession->setData('isa_error', 'Your event has been published successfully');
            redirect(site_url('vh1supersonic2015?esid=' . $response['response']['eventsignup']));
        }
        return $response;
    }

}

?>
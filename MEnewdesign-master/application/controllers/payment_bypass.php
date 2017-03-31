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
 * @Last Modified On  03-08-2015
 * @Last Modified By  Gautam
 */
require_once(APPPATH . 'handlers/event_handler.php');
require_once(APPPATH . 'handlers/user_handler.php');
require_once(APPPATH . 'handlers/ticket_handler.php');
require_once(APPPATH . 'handlers/tickettax_handler.php');
require_once(APPPATH . 'handlers/discount_handler.php');
require_once(APPPATH . 'handlers/common_handler.php');
require_once(APPPATH . 'handlers/configure_handler.php');
require_once(APPPATH . 'handlers/eventsignup_handler.php');
require_once(APPPATH . 'handlers/paymentgateway_handler.php');
require_once(APPPATH . 'handlers/booking_handler.php');
require_once(APPPATH . 'handlers/paytm_handler.php');
require_once (APPPATH . 'handlers/orderlog_handler.php');
require_once (APPPATH . 'handlers/thirdpartypayment_handler.php');

class Payment extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->defaultCountryId = $this->defaultCityId = $this->defaultCategoryId = 0;
        $this->defaultCustomFilterId = 1;
    }

    /*
     * Function to display the booking page
     *
     * @access	public
     * @return	Display the Booking form with the custom fields and payment gateways
     */

    public function index() {
        //$this->output->enable_profiler(TRUE);
        $getVar = $this->input->get();
        $orderId = $getVar['orderid'];

        $orderLogDataResponse = $this->soldTicketValidation($orderId);
        $redirectUrl = site_url();
        if (($orderLogDataResponse['status'] && $orderLogDataResponse['response']['total'] == 0) || !$orderLogDataResponse['status']) {
            redirect($redirectUrl);
        }
        $orderLogSessionData = unserialize($orderLogDataResponse['response']['orderLogData']['data']);
        //$ticketCount = count($orderLogSessionData['ticketarray']);
        $selectedTicketData = $orderLogSessionData['ticketarray'];
        $eventId = ($orderLogSessionData['eventid']) ? $orderLogSessionData['eventid'] : '';
        if ($eventId == '') {
            redirect($redirectUrl);
        }
        $commonHandler=new Common_handler();
        //$data = array();
        $cookieData = $commonHandler->headerValues();
        if (count($cookieData) > 0) {
            $this->defaultCountryId = isset($cookieData['defaultCountryId']) ? $cookieData['defaultCountryId'] : $this->defaultCountryId;
        }
        $data = $cookieData;
        $isExisted = FALSE;
        $orderLogData = $orderLogDataResponse['response']['orderLogData'];
        if ($orderLogData['eventsignup'] > 0 && is_array($orderLogSessionData['paymentResponse']) &&
                ($orderLogSessionData['paymentResponse']['TransactionID'] > 0 || $orderLogSessionData['paymentResponse']['mode'] != '')) {
            $isExisted = TRUE;
        }

        if ($orderLogSessionData['widgetredirecturl'] != '') {
            $data['redirectUrl'] = $orderLogSessionData['widgetredirecturl'];
        }
        if ($orderLogSessionData['referralcode'] != '') {
            $data['referralcode'] = $orderLogSessionData['referralcode'];
        }
        if ($orderLogSessionData['promotercode'] != '') {
            $data['promotercode'] = $orderLogSessionData['promotercode'];
        }


        $footerValues = $commonHandler->footerValues();
        $data['categoryList'] = $footerValues['categoryList'];
        $data['defaultCountryId'] = $this->defaultCountryId;
        $data['calculationDetails'] = $orderLogSessionData['calculationDetails'];
        $data['addonArray'] = isset($orderLogSessionData['addonArray']) ? $orderLogSessionData['addonArray'] : array();
        /* Getting the Event Details starts here */
        $request['eventId'] = $eventId;
        $eventHandler = new Event_handler();
        $eventDataArr = $eventHandler->getEventLocationDetails($request);
        //print_r($eventDataArr);exit;
       $data['eventData'] = $ticketDetails = array();
        if ($eventDataArr['status'] && $eventDataArr['response']['total'] > 0) {
            $eventData = $eventDataArr['response']['details'];
            $eventAddress = '';

            if (isset($eventData['location']['venueName']) && !empty($eventData['location']['venueName'])) {
                $eventAddress .= ',' . $eventData['location']['venueName'];
            }
            if (isset($eventData['location']['address1']) && !empty($eventData['location']['address1'])) {
                $eventAddress .= ', ' . $eventData['location']['address1'];
            }
            if (isset($eventData['location']['address2']) && !empty($eventData['location']['address2'])) {
                $eventAddress .= ', ' . $eventData['location']['address2'];
            }
            if (isset($eventData['location']['cityName']) && !empty($eventData['location']['cityName'])) {
                $eventAddress .= ', ' . $eventData['location']['cityName'];
            }
            if (isset($eventData['location']['stateName']) && !empty($eventData['location']['stateName'])) {
                $eventAddress .= ', ' . $eventData['location']['stateName'];
            }
            if (isset($eventData['location']['countryName']) && !empty($eventData['location']['countryName'])) {
                $eventAddress .= ', ' . $eventData['location']['countryName'];
            }
            $eventAddress = ltrim($eventAddress, ',');
            $eventData['fullAddress'] = $eventAddress;
            // For edit Order Url for Preview  
            if ($orderLogSessionData['pageType'] == 'preview') {
                $eventData['eventUrl'] = commonHelperGetPageUrl('event-preview', '', '?view=preview&eventId=' . $eventId);
            }
            $data['eventData'] = $eventData;

            $data['pageTitle'] = isset($eventData['title']) ? $eventData['title'] . ' | ' : '';
            $data['pageTitle'].='Book tickets online for music concerts, live shows and professional events. Be informed about upcoming events in your city';
            // Getting Ticketing options of the event
            $ticketOptionInput['eventId'] = $eventId;
            $ticketOptionInput['eventDetailReq'] = false;
            $collectMultipleAttendeeInfo = 0;
            $geoLocalityDisplay = 1;
            $ticketOptionArray = $eventHandler->getTicketOptions($ticketOptionInput);
            if ($ticketOptionArray['status'] && $ticketOptionArray['response']['total'] > 0) {
                $collectMultipleAttendeeInfo = $ticketOptionArray['response']['ticketingOptions'][0]['collectmultipleattendeeinfo'];
                $geoLocalityDisplay = $ticketOptionArray['response']['ticketingOptions'][0]['geolocalitydisplay'];
            }
        } else {
            redirect('home');
        }
        $data['collectMultipleAttendeeInfo'] = $collectMultipleAttendeeInfo;
        $data['geoLocalityDisplay'] = $geoLocalityDisplay;
        /* Getting the Event Details ends here */
        if (!$isExisted) {

            /* Code to get the event related gateways starts here */
            $eventGateways = array();
            $gateWayInput['eventId'] = $eventId;
            $gateWayInput['gatewayStatus'] = true;
            $gateWayData = $eventHandler->getEventPaymentGateways($gateWayInput);
            if ($gateWayData['status'] && count($gateWayData['response']['gatewayList']) > 0) {
                $eventGateways = $gateWayData['response']['gatewayList'];
            }
            $data['eventGateways'] = $eventGateways;
            /* Code to get the event related gateways ends here */
            $customFieldInput['eventId'] = $eventId;
            $customFieldInput['collectMultipleAttendeeInfo'] = $collectMultipleAttendeeInfo;
            $customFieldInput['disableSessionLockTickets'] = true;
            $configureHandler=new Configure_handler();
            $eventCustomFieldsArr = $configureHandler->getCustomFields($customFieldInput);

            $eventCustomFields = $eventLevelCustomFields = $ticketLevelCustomFields = array();
            if ($eventCustomFieldsArr['status'] && $eventCustomFieldsArr['response']['total'] > 0) {
                $tempEventCustomFieldsArray = $eventCustomFieldsArr['response']['customFields'];
            }

            $customFieldIdsArray = array_column($tempEventCustomFieldsArray, 'id');
            $customFieldValuesInput['customFieldIdArray'] = $customFieldIdsArray;
            $tempEventCustomFieldsArr = $configureHandler->getCustomFieldValues($customFieldValuesInput);
            $eventCustomFieldsArr = $tempEventCustomFieldsArr['response']['fieldValuesInArray'];
            foreach ($eventCustomFieldsArr as $eventCustomField) {
                $eventCustomFieldValueArr[$eventCustomField['customfieldid']][] = $eventCustomField;
            }
            /* Getting Ticketwise details starts here */
            $data['ticketData'] = $selectedTicketData;
            foreach ($selectedTicketData as $ticketId => $ticketQty) {
                $calculateTicketArr[$ticketId]['selectedQty'] = $ticketQty;

                /* Getting Custom fields for the event and ticketwise starts here */
                foreach ($tempEventCustomFieldsArray as $customFieldArr) {

                    $customFieldArr['customFieldValues'][$customFieldArr['id']] = isset($eventCustomFieldValueArr[$customFieldArr['id']])?$eventCustomFieldValueArr[$customFieldArr['id']]:array();
                    if ($customFieldArr['fieldlevel'] == 'event') {
                        $eventCustomFields[$ticketId][] = $customFieldArr;
                    } elseif ($customFieldArr['fieldlevel'] == 'ticket' && $customFieldArr['ticketid'] == $ticketId) {
                        $eventCustomFields[$ticketId][] = $customFieldArr;
                    }
                }
                /* Getting Custom fields for the event and ticketwise ends here */
            }
            $data['customFieldsArray'] = $eventCustomFields;
            $data['calculateTicketArr'] = $calculateTicketArr;
            /* Getting Ticketwise details ends here */

            /* Getting user data starts here */
            $userDataArray = array();
            $userId = $this->customsession->getUserId();
            if ($userId > 0) {
                $userDataInput['ownerId'] = $userId;
                $isOrganizer = $this->customsession->getData('isOrganizer');
                if($isOrganizer == 1) {
                    $userDataInput['organizerDataReq'] = true;
                }
                $userDataInput['profileImageReq'] = false;
                $userHandler = new User_handler();
                $userDataResponse = $userHandler->getUserData($userDataInput);
                if ($userDataResponse['status'] && $userDataResponse['response']['total']>0) {
                    $userData = $userDataResponse['response']['userData'];
                    $organizerData=isset($userDataResponse['response']['organizerData'])?$userDataResponse['response']['organizerData']:array();
                    $userDataArray['FullName'] = $userData['name'];
                    $userDataArray['EmailId'] = $userData['email'];
                    $userDataArray['MobileNo'] = ($userData['mobile'] != '') ? $userData['mobile'] : $userData['phone'];
                    $userDataArray['Address'] = $userData['address'];
                    $userDataArray['Country'] = $userData['country'];
                    $userDataArray['State'] = ($userData['state'] != '') ? $userData['state'] : $userDataArray['Country'];
                    $userDataArray['City'] = ($userData['city'] != '') ? $userData['city'] : $userDataArray['State'];

                    $localityArr = array();
                    if ($userDataArray['City'] != '') {
                        $localityArr[] = $userDataArray['City'];
                    }
                    if ($userDataArray['State'] != '') {
                        $localityArr[] = $userDataArray['State'];
                    }
                    if ($userDataArray['Country'] != '') {
                        $localityArr[] = $userDataArray['Country'];
                    }
                    $userDataArray['Locality'] = implode(',',array_unique($localityArr));
                    $userDataArray['State'] = $userDataArray['State'];
                    $userDataArray['City'] = $userDataArray['City'];
                    $userDataArray['PinCode'] = $userData['pincode'];
                    $userDataArray['CompanyName'] = $userData['company'];
                    if(count($organizerData)>0){
                        $userDataArray['Designation'] = isset($organizerData['designation'])?$organizerData['designation']:'';
                    }
                }
            }
            $data['userData'] = $userDataArray;
            /* Getting user data endds here */
            $eventSignupId = 0;
            $data['eventSignupId'] = $eventSignupId;
            $data['orderLogId'] = $orderId;
        }
        $data['moduleName'] = 'eventModule';
        $data['content'] = 'ticketregistration_view';
        $data['pageName'] = 'Payment';
        $data['isExisted'] = $isExisted;
        $data['jsArray'] = array(
            $this->config->item('js_public_path') . 'fixto',
            $this->config->item('js_public_path') . 'jquery.validate',
            $this->config->item('js_public_path') . 'delegate');
        $data['cssArray'] = array(
            $this->config->item('css_public_path') . 'delegate');
        $this->load->view('templates/user_template', $data);
    }

    /*
     * Function to prepare the ebs form data to redirect to its payment page
     *
     * @access	public
     * @param
     *      	TxnAmount - Total amount that need to pay the user
     *      	oid - Order Id from the `orderlogs` table
     *      	uid - User Id of the booking user
     *      	EventSignupId - EventSignUp Id
     *      	EventId - Id of the Event
     *      	EventTitle - Title of the Event
     * @return	redirects to the ebs payment page
     */

    public function ebsPrepare() {

        $data = array();
        $ebsSecretKey = $this->config->item('ebs_secret_key');
        $data['account_id'] = $accountId = $this->config->item('account_id');
        $data['mode'] = $mode = $this->config->item('mode');
        $postVar = $this->input->post();

        /* Getting the payment gateway details from database starts here */
        $paymentGatewayKey = $postVar['paymentGatewayKey'];
        if ($paymentGatewayKey > 0) {
            $gatewayArr = $this->getPaymentgatewayKeys($paymentGatewayKey);
            if (count($gatewayArr) > 0) {
                $ebsSecretKey = $gatewayArr['hashkey'];
                $data['account_id'] = $accountId = $gatewayArr['merchantid'];
            }
        }
        /* Getting the payment gateway details from database ends here */

        // Getting order Log data starts here
        $orderId = $postVar['orderId'];
        $orderLogInput['orderId'] = $orderId;
        $orderlogHandler = new Orderlog_handler();
        $orderLogData = $orderlogHandler->getOrderlog($orderLogInput);
        //print_r($orderLogData); echo $this->ci->db->last_query();exit;
        if ($orderLogData['status'] && $orderLogData['response']['total'] > 0) {
            
        } else {
            $redirectUrl = site_url();
            redirect($redirectUrl);
        }
        $oldOrderLogData = $orderLogData['response']['orderLogData'];
        $orderLogCalculationData = unserialize($orderLogData['response']['orderLogData']['data']);
        $eventSignupId = $oldOrderLogData['eventsignup'];
        $txtTxnAmount = $data['txtTxnAmount'] = $orderLogCalculationData['calculationDetails']['totalPurchaseAmount'];
        $EventId = $orderLogCalculationData['eventid'];
        // Getting order Log data ends here

        $addressStr = $postVar['primaryAddress'];
        $addressArr = explode('@@', $addressStr);

        $data['name'] = $addressArr[0];
        $data['email'] = $addressArr[1];
        $data['phone'] = $addressArr[2];
        $data['city'] = ($addressArr[5] != '') ? $addressArr[5] : $addressArr[3];
        $data['state'] = ($addressArr[4] != '') ? $addressArr[4] : $addressArr[3];
        $tempAddress = '';
        if ($data['city'] != '') {
            $tempAddress .= $data['city'] . ',';
        }
        if ($data['state'] != '') {
            $tempAddress .= $data['state'];
        }
        $data['address'] = ($addressArr[3]) ? $addressArr[3] : $tempAddress;
        $data['pincode'] = ($addressArr[6]) ? $addressArr[6] : '500081';

        $this->soldTicketValidation($orderId,$orderLogData);
        $data['eventTitle'] = $postVar['eventTitle'];
        $txtCustomerID = $data['txtCustomerID'] = $eventSignupId;

        $data['returnUrl'] = commonHelperGetPageUrl('payment_ebsProcessingPage') . "?orderId=$orderId&eventSignup=$eventSignupId&paymentGatewayKey=$paymentGatewayKey&DR={DR}";
        $string = $ebsSecretKey . "|" . $accountId . "|" . $txtTxnAmount . "|" . $txtCustomerID . "|" . html_entity_decode($data['returnUrl']) . "|" . $mode;
        $data['secureHash'] = md5($string);
        $data['pageName'] = 'Ebs';

        /* Need to remove code after the load testing starts here */
            $response['eventSignupId'] = $eventSignupId;
            $response['orderId'] = $orderId;
            $response['Amount'] = $txtTxnAmount;
            $this->bookingHandler = new Booking_handler();
            $this->bookingHandler->bypassPayment($response);
            redirect(site_url().'confirmation?orderid='.$orderId);exit;
        /* Need to remove code after the load testing ends here */
        $this->load->view('payment/ebs_prepare', $data);
    }

    /* Intermediate page for EBS to check the order,signup and checksum values */

    public function ebsProcessingPage() {

        $getVar = $this->input->get();
        $orderId = $getVar['orderId'];
        $redirectUrl = commonHelperGetPageUrl('payment') . '?orderid=' . $orderId;
        $bookingHandler = new Booking_handler();
        $apiResponse = $bookingHandler->ebsProcessingApi($getVar);
        if (!$apiResponse['status']) {
            $errorMessage = $apiResponse['response']['messages'][0];
            $this->customsession->setData('booking_message', $errorMessage);
            redirect($redirectUrl);
        }
        $successUrl = commonHelperGetPageUrl('confirmation') . '?orderid=' . $orderId;
        header("Location: " . $successUrl);
        exit;
    }

    /*
     * Function to prepare the mobikwik form data to redirect to its payment page
     *
     * @access	public
     * @param
     *      	mobileno - Number of the booking user
     *      	email - Email of the booking user
     *      	TxnAmount - Total amount that need to pay the user
     *      	oid - Order Id from the `orderlogs` table
     *      	merchantname - Name of the booking user
     * @return	redirects to the mobikwik payment page
     */

    public function mobikwikPrepare() {

        $data = array();
        $postVar = $this->input->post();

        // Getting order Log data starts here
        $orderId = $postVar['orderId'];
        $orderLogInput['orderId'] = $orderId;
        $orderlogHandler = new Orderlog_handler();
        $orderLogData = $orderlogHandler->getOrderlog($orderLogInput);
        if ($orderLogData['status'] && $orderLogData['response']['total'] > 0) {
            
        } else {
            $redirectUrl = site_url();
            redirect($redirectUrl);
        }
        $oldOrderLogData = $orderLogData['response']['orderLogData'];
        $orderLogCalculationData = unserialize($orderLogData['response']['orderLogData']['data']);
        $eventSignupId = $oldOrderLogData['eventsignup'];
        $txtTxnAmount = $data['txtTxnAmount'] = $orderLogCalculationData['calculationDetails']['totalPurchaseAmount'];
        $EventId = $orderLogCalculationData['eventid'];
        // Getting order Log data ends here

        $addressStr = $postVar['primaryAddress'];
        $addressArr = explode('@@', $addressStr);
        $name = $addressArr[0];
        $email = $addressArr[1];
        $mobile = $addressArr[2];

        $this->soldTicketValidation($orderId,$orderLogData);

        $eventSignup = $eventSignupId;
        $txnAmount = $txtTxnAmount;

        $paymentGatewayKey = $postVar['paymentGatewayKey'];
        $data['redirectUrl'] = commonHelperGetPageUrl('payment_mobikwikProcessingPage') . "?eventSignup=" . $eventSignup . "&orderId=" . $orderId . "&paymentGatewayKey=" . $paymentGatewayKey;

        $mobiwikSecretKey = $this->config->item('mobiwikSecretKey');
        $mobiwikMerchantId = $this->config->item('mobiwikMerchantId');

        /* Getting the payment gateway details from database starts here */
        $paymentGatewayKey = $postVar['paymentGatewayKey'];
        if ($paymentGatewayKey > 0) {
            $gatewayArr = $this->getPaymentgatewayKeys($paymentGatewayKey);
            if (count($gatewayArr) > 0) {
                $mobiwikSecretKey = $gatewayArr['hashkey'];
                $mobiwikMerchantId = $gatewayArr['merchantid'];
            }
        }
        /* Getting the payment gateway details from database ends here */

        $this->load->library('mobikwik/checksum.php');
        $all = "'" . $mobile . "''" . $email . "''" . $txnAmount . "''" .
                $orderId . "''" . $data['redirectUrl'] . "''" . $mobiwikMerchantId . "'";

        $checksum = $this->checksum->calculateChecksum($mobiwikSecretKey, $all);
        $actionUrl = $this->checksum->getActionUrl();
        $data['actionUrl'] = $actionUrl;
        $data['checksum'] = $checksum;
        $data['merchantName'] = $name;
        $data['orderId'] = $orderId;
        $data['mobileNo'] = $mobile;
        $data['amount'] = $txnAmount;
        $data['email'] = $email;
        $data['pageName'] = 'Mobikwik';
        $data['mobikwikMerchantId'] = $mobiwikMerchantId;
        $this->load->view('payment/mobikwik_prepare', $data);
    }

    /* Intermediate page for mobikwik to check the order,signup and checksum values */

    public function mobikwikProcessingPage() {
        $getVar = $this->input->get();
        $postVar = $this->input->post();

        $orderId = $getVar['orderId'];
        $redirectUrl = commonHelperGetPageUrl('payment', '', '?orderid=' . $orderId);

        $postVar['paymentGatewayKey'] = $getVar['paymentGatewayKey'];
        $bookingHandler = new Booking_handler();
        $apiResponse = $bookingHandler->mobikwikProcessingApi($postVar);
        //echo '<pre>';
        // print_r($apiResponse);exit;
        if (!$apiResponse['status']) {
            $errorMessage = $apiResponse['response']['messages'][0];
            $this->customsession->setData('booking_message', $errorMessage);

            if (isset($apiResponse['response']['transactionCancel']) && $apiResponse['response']['transactionCancel']) {
                $redirectUrl = $this->getPreviousEventUrl($orderId);
            }
            redirect($redirectUrl);
        }

        $successUrl = commonHelperGetPageUrl('confirmation') . '?orderid=' . $orderId;
        header("Location: " . $successUrl);
        exit;
    }

    public function getPreviousEventUrl($orderId) {

        $eventUrl = site_url();
        $orderLogInput['orderId'] = $orderId;
        $orderlogHandler = new Orderlog_handler();
        $orderLogData = $orderlogHandler->getOrderlog($orderLogInput);
        $oldOrderLogData = $orderLogData['response']['orderLogData'];
        $orderLogCalculationData = unserialize($orderLogData['response']['orderLogData']['data']);

        $request['eventId'] = $orderLogCalculationData['eventid'];
        $eventHandler = new Event_handler();
        $eventDataArr = $eventHandler->getEventDetails($request);
        $eventData = $eventDataArr['response']['details'];

        if ($orderLogCalculationData['pageType'] == 'preview') {
            $eventUrl = site_url() . 'previewevent?view=preview&eventId=' . $request['eventId'];
        } else {
            $eventUrl = $eventData['eventUrl'];
        }

        if ($orderLogCalculationData['referralcode'] != '') {
            $reffCode = $orderLogCalculationData['referralcode'];
            if (strpos($eventUrl, '?') == true) {
                $eventUrl = $eventUrl . "&reffCode=" . $reffCode;
            } else {
                $eventUrl = $eventUrl . "?reffCode=" . $reffCode;
            }
        }
        if ($orderLogCalculationData['promotercode'] != '') {
            $ucode = $orderLogCalculationData['promotercode'];
            if (strpos($eventUrl, '?') == true) {
                $eventUrl = $eventUrl . "&ucode=" . $ucode;
            } else {
                $eventUrl = $eventUrl . "?ucode=" . $ucode;
            }
        }
        return $eventUrl;
    }

    /*
     * Function to prepare the paytm form data to redirect to its payment page
     *
     * @access	public
     * @param
     *      	MOBILE_NO - Number of the booking user
     *      	EMAIL - Email of the booking user
     *      	TXN_AMOUNT - Total amount that need to pay the user
     *      	oid - Order Id from the `orderlogs` table
     *      	CUST_ID - User Id of the booking user
     *      	ORDER_ID - EventSignUp Id
     * @return	redirects to the paytm payment page
     */

    public function paytmPrepare() {

        $data = array();
        $postVar = $this->input->post();
        /* Getting the payment gateway details from database starts here */
        $paymentGatewayKey = $postVar['paymentGatewayKey'];
        if ($paymentGatewayKey > 0) {
            $gatewayArr = $this->getPaymentgatewayKeys($paymentGatewayKey);
            if (count($gatewayArr) > 0) {
                $paytmSecretKey = $gatewayArr['hashkey'];
                $paytmMerchantId = $gatewayArr['merchantid'];

                $extraParams = unserialize($gatewayArr['extraparams']);
                define('PAYTM_MERCHANT_KEY', $paytmSecretKey);
                define('PAYTM_MERCHANT_MID', $paytmMerchantId);

                define('PAYTM_MERCHANT_WEBSITE', $extraParams['PAYTM_MERCHANT_WEBSITE']);
                define('INDUSTRY_TYPE_ID', $extraParams['INDUSTRY_TYPE_ID']);
                define('CHANNEL_ID', $extraParams['CHANNEL_ID']);
            }
        }
        /* Getting the payment gateway details from database ends here */
        require_once(APPPATH . 'libraries/paytm/paytm_functions.php');

        // Getting order Log data starts here
        $orderId = $postVar['orderId'];
        $orderLogInput['orderId'] = $orderId;
        $orderlogHandler = new Orderlog_handler();
        $orderLogData = $orderlogHandler->getOrderlog($orderLogInput);
        if ($orderLogData['status'] && $orderLogData['response']['total'] > 0) {
            
        } else {
            $redirectUrl = site_url();
            redirect($redirectUrl);
        }
        $oldOrderLogData = $orderLogData['response']['orderLogData'];
        $orderLogCalculationData = unserialize($orderLogData['response']['orderLogData']['data']);
        $eventSignupId = $oldOrderLogData['eventsignup'];
        $txtTxnAmount = $data['txtTxnAmount'] = $orderLogCalculationData['calculationDetails']['totalPurchaseAmount'];
        $EventId = $orderLogCalculationData['eventid'];
        // Getting order Log data ends here

        $addressStr = $postVar['primaryAddress'];
        $addressArr = explode('@@', $addressStr);

        $name = $addressArr[0];
        $email = $addressArr[1];
        $mobile = $addressArr[2];

        $this->soldTicketValidation($orderId,$orderLogData);

        $CUST_ID = trim($oldOrderLogData['userid']);
        $TXN_AMOUNT = number_format($txtTxnAmount, 2);
        $MOBILE_NO = trim($mobile);
        $EMAIL = trim($email);
        $eventSignup = $eventSignupId;
        $CALLBACK_URL = commonHelperGetPageUrl('payment_paytmProcessingPage') . "?orderId=" . $orderId . "&eventSignup=" . $eventSignup . "&email=" . $EMAIL . "&mobile=" . $MOBILE_NO . "&paymentGatewayKey=" . $paymentGatewayKey;

        $checkSum = "";
        $paramList = array();
        // Create an array having all required parameters for creating checksum.
        $paramList["MID"] = PAYTM_MERCHANT_MID;
        $paramList["ORDER_ID"] = $orderId;
        $paramList["CUST_ID"] = $CUST_ID;
        $paramList["INDUSTRY_TYPE_ID"] = INDUSTRY_TYPE_ID;
        $paramList["CHANNEL_ID"] = CHANNEL_ID;
        $paramList["TXN_AMOUNT"] = $TXN_AMOUNT;
        $paramList["MOBILE_NO"] = $MOBILE_NO;
        $paramList["EMAIL"] = $EMAIL;
        $paramList["CALLBACK_URL"] = $CALLBACK_URL;
        $paramList["WEBSITE"] = PAYTM_MERCHANT_WEBSITE;
        //Here checksum string will be return
        $checkSum = getChecksumFromArray($paramList, PAYTM_MERCHANT_KEY);
        $data['checkSum'] = $checkSum;
        $data['paramList'] = $paramList;
        $data['pageName'] = 'Paytm';

        $this->load->view('payment/paytm_prepare', $data);
    }

    /* Intermediate page for paytm to check the order,signup and checksum values */

    public function paytmProcessingPage() {

        $getVar = $this->input->get();
        $postVar = $this->input->post();

        $orderId = $getVar['orderId'];
        $redirectUrl = commonHelperGetPageUrl('payment', '', '?orderid=' . $orderId);

        $postVar['paymentGatewayKey'] = $getVar['paymentGatewayKey'];
        $postVar['orderId'] = $orderId;
        $postVar['eventSignup'] = $getVar['eventSignup'];
        $postVar['mobile'] = $getVar['mobile'];
        $postVar['email'] = $getVar['email'];
        $bookingHandler = new Booking_handler();
        $apiResponse = $bookingHandler->paytmProcessingApi($postVar);
        if (!$apiResponse['status']) {
            $errorMessage = $apiResponse['response']['messages'][0];
            $this->customsession->setData('booking_message', $errorMessage);
            redirect($redirectUrl);
        }

        $successUrl = commonHelperGetPageUrl('confirmation') . '?orderid=' . $orderId;
        header("Location: " . $successUrl);
        exit;
    }

    /*
     * Function to prepare the paypal form data to redirect to its payment page
     *
     * @access	public
     * @param
     *      	txnAmount - Total amount that need to pay the user
     *      	oid - Order Id from the `orderlogs` table
     *      	currencyCode - Currency Code of the tickets
     *      	eventSignupId - EventSignUp Id
     *      	eventTitle - Title of the Event
     * @return	redirects to the paypal payment page
     */

    public function paypalPrepare() {
        $postVar = $this->input->post();
        require_once(APPPATH . 'libraries/paypal/config.php');

        /* Getting the payment gateway details from database starts here */
        $paymentGatewayKey = $postVar['paymentGatewayKey'];
        $mode = $this->config->item('mode');
        if ($paymentGatewayKey > 0) {
            $gatewayArr = $this->getPaymentgatewayKeys($paymentGatewayKey);
            if (count($gatewayArr) > 0) {
                $extraParams = unserialize($gatewayArr['extraparams']);

                $PayPalApiUsername = $extraParams['PayPalApiUsername'];
                $PayPalApiPassword = $extraParams['PayPalApiPassword'];
                $PayPalApiSignature = $extraParams['PayPalApiSignature'];
            }
        }
        /* Getting the payment gateway details from database ends here */

        // Getting order Log data starts here
        $orderId = $postVar['orderId'];
        $orderLogInput['orderId'] = $orderId;
        $orderlogHandler = new Orderlog_handler();
        $orderLogData = $orderlogHandler->getOrderlog($orderLogInput);
        if (($orderLogData['status'] && $orderLogData['response']['total'] == 0) || !$orderLogData['status']) {
            $redirectUrl = site_url();
            redirect($redirectUrl);
        }
        $oldOrderLogData = $orderLogData['response']['orderLogData'];
        $orderLogCalculationData = unserialize($orderLogData['response']['orderLogData']['data']);
        $eventSignupId = $oldOrderLogData['eventsignup'];
        $txtTxnAmount = $data['txtTxnAmount'] = $orderLogCalculationData['calculationDetails']['totalPurchaseAmount'];
        $currencyCode = $orderLogCalculationData['calculationDetails']['currencyCode'];
        $EventId = $orderLogCalculationData['eventid'];
        // Getting order Log data ends here

        $ItemDesc = $ItemName = $postVar["eventTitle"]; //Item Name
        $ItemNumber = $eventSignupId; //Item Number
        $ItemQty = 1; //$postVar["itemQty"]; // Item Quantity
        $orderId = $orderId;

        $this->soldTicketValidation($orderId,$orderLogData);

        $eventSignup = $eventSignupId;
        $PayPalReturnURL = commonHelperGetPageUrl('payment_paypalProcessingPage') . "?eventSignup=" . $eventSignup . "&orderId=" . $orderId . "&paymentGatewayKey=" . $paymentGatewayKey;

        /* Converting the amount to USD other than USD payments since paypal not supports some of currency codes */
        if ($currencyCode != 'USD') {
//            $get = file_get_contents("https://www.google.com/finance/converter?a=" . $txtTxnAmount . "&from=" . $currencyCode . "&to=USD");
            $get = url_get_contents("https://www.google.com/finance/converter?a=" . $txtTxnAmount . "&from=" . $currencyCode . "&to=USD");
            $get = explode("<span class=bld>", $get);
            $get = explode("</span>", $get[1]);
            $converted_amountArr = explode(" ", $get[0]);
            $txtTxnAmount = round($converted_amountArr[0], 2);
        } else {
            $txtTxnAmount = $txtTxnAmount;
        }

        $ItemTotalPrice = $ItemPrice = $GrandTotal = $txtTxnAmount;
        if (!is_numeric($txtTxnAmount)) {
            $errorMessage = "Something is wrong with the transaction amount. Please try again.";
            $this->customsession->setData('booking_message', $errorMessage);
            redirect(commonHelperGetPageUrl('payment', '', '?orderid=' . $orderId));
        }

        $TotalTaxAmount = 0;
        //Parameters for SetExpressCheckout, which will be sent to PayPal
        $padata = '&METHOD=SetExpressCheckout' .
                '&RETURNURL=' . urlencode($PayPalReturnURL) .
                '&CANCELURL=' . urlencode($PayPalReturnURL) .
                '&PAYMENTREQUEST_0_PAYMENTACTION=' . urlencode("SALE") .
                '&L_PAYMENTREQUEST_0_NAME0=' . urlencode($ItemName) .
                '&L_PAYMENTREQUEST_0_NUMBER0=' . urlencode($ItemNumber) .
                '&L_PAYMENTREQUEST_0_DESC0=' . urlencode($ItemDesc) .
                '&L_PAYMENTREQUEST_0_AMT0=' . urlencode($ItemPrice) .
                '&L_PAYMENTREQUEST_0_QTY0=' . urlencode($ItemQty) .
                '&NOSHIPPING=1' . //set 1 to hide buyer's shipping address, in-case products that does not require shipping

                '&PAYMENTREQUEST_0_ITEMAMT=' . urlencode($ItemTotalPrice) .
                '&PAYMENTREQUEST_0_TAXAMT=' . urlencode($TotalTaxAmount) .
                '&PAYMENTREQUEST_0_AMT=' . urlencode($GrandTotal) .
                '&PAYMENTREQUEST_0_CURRENCYCODE=' . urlencode($PayPalCurrencyCode) .
                '&LOCALECODE=GB' . //PayPal pages to match the language on your website.
                '&CARTBORDERCOLOR=FFFFFF' . //border color of cart
                '&ALLOWNOTE=1';

        $this->load->library('paypal/paypal.php');
        $httpParsedResponseAr = $this->paypal->PPHttpPost('SetExpressCheckout', $padata, $PayPalApiUsername, $PayPalApiPassword, $PayPalApiSignature, $PayPalMode);
        //Respond according to message we receive from Paypal
        if ("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) {

            $paypalmode = '';
            if ($PayPalMode == 'sandbox') {
                $paypalmode = '.sandbox';
            }
            //Redirect user to PayPal store with Token received.
            $paypalurl = 'https://www' . $paypalmode . '.paypal.com/webscr?cmd=_express-checkout&useraction=commit&token=' . $httpParsedResponseAr["TOKEN"] . '';
            header('Location: ' . $paypalurl);
        } else {
            //Show error message
            $this->customsession->setData('booking_message', urldecode($httpParsedResponseAr["L_LONGMESSAGE0"]));
            redirect(commonHelperGetPageUrl('payment', '', '?orderid=' . $orderId));
        }
    }

    /* Intermediate page for PAYPAL to update the orderlog data */

    public function paypalProcessingPage() {

        $getVar = $this->input->get();
        $postVar = $this->input->post();

        $orderId = $getVar['orderId'];
        $redirectUrl = commonHelperGetPageUrl('payment', '', '?orderid=' . $orderId);
        $bookingHandler = new Booking_handler();
        $apiResponse = $bookingHandler->paypalProcessingApi($getVar, $postVar);
        if (!$apiResponse['status']) {
            $errorMessage = $apiResponse['response']['messages'][0];
            $this->customsession->setData('booking_message', $errorMessage);
            redirect($redirectUrl);
        }

        $successUrl = commonHelperGetPageUrl('confirmation') . '?orderid=' . $orderId;
        header("Location: " . $successUrl);
        exit;
    }

    /*
     * Function to validage the sold ticket count with the selected ticket quantity
     *
     * @access	public
     * @param
     *      	orderId - Order Id
     * @return	returns TRUE if the tickets are available or redirects to order page with message
     */

    function soldTicketValidation($orderId, $orderLogDataArr=array()) {

        $orderLogInput['orderId'] = $orderId;
        $redirectUrl = site_url();  // Need to replace it after finializing the error response page

        if(is_array($orderLogDataArr) && count($orderLogDataArr) > 0) {
            $orderLogData = $orderLogDataArr;
        } else {
            $orderlogHandler = new Orderlog_handler();
            $orderLogData = $orderlogHandler->getOrderlog($orderLogInput);
            if (($orderLogData['status'] && $orderLogData['response']['total'] == 0) || !$orderLogData['status']) {
                redirect($redirectUrl);
            }
        }
        
        $orderLogSessionData = $orderLogData['response']['orderLogData']['data'];
        $orderLogSessionDataArr = unserialize($orderLogSessionData);
        $ticketArray = $orderLogSessionDataArr['ticketarray'];

        $ticketIds = array_keys($ticketArray);
        $ticketDataInput['eventId'] = $orderLogSessionDataArr['eventid'];
        $ticketDataInput['ticketIds'] = $ticketIds;
        $ticketDataInput['taxRequired'] = false;
        $ticketHandler = new Ticket_Handler();
        $ticketsData = $ticketHandler->getTicketsbyIds($ticketDataInput);
        $ticketDataArr = $ticketsData['response']['ticketdetails'];

        foreach ($ticketDataArr as $ticket) {
            $ticketSoldQty = $ticket['totalsoldtickets'];
            $availableTktQty = $ticket['quantity'];

            $ticketNewSoldQty = $ticketSoldQty + $ticketArray[$ticket['id']];

            // If the selected quantity with already sold tickets exceeded total quantity
            if ($ticketNewSoldQty > $availableTktQty) {
                $errorMessage = $ticket['name'] . ERROR_TICKET_EXCEEDED;
                $this->customsession->setData('booking_message', $errorMessage);
                redirect($redirectUrl);
            }
        }
        return $orderLogData;
    }

    /*
     * Function to validage the orderlog and eventsignid after the payment and before confirmation
     *
     * @access	public
     * @param
     *      	eventSignupId - EventSignUp Id
     *      	orderId - Order Id
     * @return	returns TRUE or FALSE based on the validation and update the `orderLog` table
     */

    /*public function orderLogValidation($getVar) {
        $orderId = $getVar['orderId'];
        $return['status'] = TRUE;

        $orderLogInput['orderId'] = $orderId;
        $orderLogData = array();
        $orderlogHandler = new Orderlog_handler();
        $orderLogData = $orderlogHandler->getOrderlog($orderLogInput);
        if ($orderLogData['status'] && $orderLogData['response']['total'] > 0) {
            
        } else {
            $return['errorMessage'] = SOMETHING_WRONG;
            $return['status'] = FALSE;
            return $return;
        }
        $return['orderLogData'] = $orderLogData['response']['orderLogData'];

        $eventSignupId = $orderLogData['response']['orderLogData']['eventsignup'];
        $eventSignupInput['eventsignupId'] = $eventSignupId;
        $eventsignupHandler = new Eventsignup_handler();
        $signupData = $eventsignupHandler->getEventsignupDetails($eventSignupInput);
        if ($signupData['status'] && $signupData['response']['total'] > 0) {
            
        } else {
            $return['errorMessage'] = SOMETHING_WRONG;
            $return['status'] = FALSE;
            return $return;
        }
        $return['eventSignupData'] = $signupData['response']['eventSignupList'][0];
        return $return;
    }*/

    /*
     * Function to get the payment gateway key values
     *
     * @access	public
     * @param
     *      	$paymentGatewayKey - integer
     * @return	returns an array with the gateway credentials
     */

    public function getPaymentgatewayKeys($paymentGatewayKey) {
        $gatewayInput['gatewayId'] = $paymentGatewayKey;
        $paymentGatewayHandler = new Paymentgateway_handler();
        $gatewayData = $paymentGatewayHandler->getPaymentgatewayList($gatewayInput);
        if ($gatewayData['status']) {
            $gatewayArr = $gatewayData['response']['paymentgatewayList'][0];
            if ($gatewayArr['hashkey'] != '' && $gatewayArr['merchantid'] != '') {
                return $gatewayArr;
            } elseif ($gatewayArr['name'] == 'paypal') {
                return $gatewayArr;
            }
        }
        return array();
    }

    public function thirdPartyEbsPrepare() {
        $postVar = $this->input->post();
        $thirdpartypaymentHandler = new Thirdpartypayment_Handler();
        $thirdpartypaymentHandler->ebsPrepare($postVar);
    }

    public function thirdPartyEbsResponseSave() {
        $inputVar = $this->input->get();
        $thirdpartypaymentHandler = new Thirdpartypayment_Handler();
        $response = $thirdpartypaymentHandler->ebsResponseSave($inputVar);
        print_r($response);
    }

}

?>

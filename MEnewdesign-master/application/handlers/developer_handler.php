<?php

/**
 * Ticket related business logic will be defined in this class
 * Getting Banners Related data
 * @package		CodeIgniter
 * @author		Qison  Dev Team
 * @param		CountryId - required
 *                      cityId,categoryId,type (optional)
 * @addTicket		name,type,description,eventId,price,quantity,
 *                     minOrderQuantity,maxOrderQuantity,startTime,endTime,order,currencyId
 *                    soldOut,endDate,startDate,displayStatus,label[0],value[0]
 *                    @lable and value should be arrays
 * @copyright	Copyright (c) 2015, MeraEvents.
 * @Version		Version 1.0
 * @Since       Class available since Release Version 1.0
 * @Created     16-06-2015
 * @Last Modified 16-06-2015
 */
require_once(APPPATH . 'handlers/event_handler.php');
require_once(APPPATH . 'handlers/ticket_handler.php');
require_once(APPPATH . 'handlers/tickettax_handler.php');
require_once(APPPATH . 'handlers/country_handler.php');
require_once(APPPATH . 'handlers/configure_handler.php');
require_once(APPPATH . 'handlers/booking_handler.php');
require_once(APPPATH . 'handlers/orderlog_handler.php');
require_once(APPPATH . 'handlers/printpass_handler.php');
require_once(APPPATH . 'handlers/user_handler.php');
require_once (APPPATH . 'handlers/orderlog_handler.php');

class Developer_handler extends Handler {

    var $ci;
    var $eventHandler;
   

    public function __construct() {
        parent::__construct();
        $this->ci = parent::$CI;
        $this->eventHandler=new Event_handler();
        $this->setAccessTokenInfo();
    }
    
    public function getEventDetails($inputArray){
        $resultArray=array();
        $eventDetails = $this->eventHandler->getEventDetails($inputArray);
        $resultArray['response']=$eventDetails['response'];
        $resultArray['statusCode']=$eventDetails['statusCode'];
        $this->ticketHandler = new Ticket_handler();
        $ticketList = $this->ticketHandler->getEventTicketList($inputArray);
        
        if($ticketList['response']['total'] > 0 ){
            $resultArray['response']['ticketDetails']=$ticketList['response']['ticketList'];
        }
        return $resultArray;
    }
    
    public function getEventList($inputArray){
        $resultArray=array();

        if(isset($inputArray['date'])){
            // custom date
            $inputArray['dateValue']=$inputArray['date'];
            $inputArray['day']=7;//to enable solr level custom date
        }
        
        //To get the default featured country
        if(isset($inputArray['countryId'])){
            $inputArray['countryId']=$inputArray['countryId'];
        }else{
            $countryHandler=new Country_handler();
            $countryArray['major']=1;
            $countryList=$countryHandler->getCountryList($countryArray);
            $inputArray['countryId']=$countryList['response']['countryList'][0]['id'];
             
        }
        $eventList = $this->eventHandler->getEventList($inputArray);
        $eventList['response']['eventList'] = $this->appendFlagstoEventList($eventList['response']['eventList']);
        $resultArray ['response']= $eventList['response'];
        $resultArray ['statusCode']=$eventList['statusCode'];
        return $resultArray;
        
    }
    
    public function eventSearch($inputArray) {
        if(isset($inputArray['date'])){
            $inputArray['dateValue']=$inputArray['date']; // custom date
            $inputArray['day']=7;//to enable solr level custom date
        }else{
            $inputArray['day']=6; //To Bring the all time events
        }
        
        //To get the default featured country
        if(isset($inputArray['countryId'])){
            $inputArray['dateValue']=$inputArray['countryId'];
        }else{
            $countryHandler=new Country_handler();
            $countryArray['major']=1;
            $countryList=$countryHandler->getCountryList($countryArray);
            $inputArray['countryId']=$countryList['response']['countryList'][0]['id'];
        }

        $searchHandler = new Search_handler();
        $eventResult = $searchHandler->searchEvents($inputArray);
        $resultArray = array('response' => $eventResult['response']);
        $resultArray ['statusCode'] = 200;
        if ($eventResult['statusCode'] != '') {
            $resultArray ['statusCode'] = $eventResult['statusCode'];
        }
        return $resultArray;
    }
    
    public function getTicketCaluculation($inputArray){
        $ticketResultArray = $this->eventHandler->getEventTicketCalculation($inputArray);
        $resultArray ['response']= $ticketResultArray['response'];
        $resultArray ['statusCode']=$ticketResultArray['statusCode'];
        return $resultArray;
    }
    
    public function bookNow($inputArray){
        $this->setAccessTokenInfo();
        $ticketResultArray = $this->eventHandler->bookNow($inputArray);
        $resultArray ['response']= $ticketResultArray['response'];
        $resultArray ['statusCode']=$ticketResultArray['statusCode'];
        return $resultArray;
    }
    
    public function getCustomFieldForms($inputArray) {
        $configureHandler = new Configure_handler();
        $customFieldsList = $configureHandler->getCustomFieldForms($inputArray);
        $resultArray ['response'] = $customFieldsList['response'];
        $resultArray ['statusCode'] = $customFieldsList['statusCode'];
        return $resultArray;
    }
    
    public function getCustomFieldValues($inputArray){
        $configureHandler = new Configure_handler();
        $customFieldsList = $configureHandler->getCustomFieldValues($inputArray);
        $resultArray ['response'] = $customFieldsList['response'];
        $resultArray ['statusCode'] = $customFieldsList['statusCode'];
        return $resultArray;
        
    }
    public function saveAttendeeData($inputArray){
        $orderlogHandler= new Orderlog_handler();
        $orderDetails=$orderlogHandler->getOrderlog($inputArray);
        
        if($orderDetails['response']['total'] > 0){
            $orderData = unserialize($orderDetails['response']['orderLogData']['data']);
            $inputArray['eventId']=$orderData['eventid'];
            //order id contains eventsignup id its invalid order id
            if ($orderDetails['response']['orderLogData']['eventsignup'] > 0) {
                $resultArray['status'] = FALSE;
                $resultArray['response']['messages'][] = ERROR_NO_ORDERLOG_FOUND;
                $resultArray ['statusCode'] = STATUS_BAD_REQUEST;
                return $resultArray;
            }

            $inputArray['eventId']=$orderData['eventid'];
            $inputArray['ticketArr']=$orderData['ticketarray'];
            
            $bookingHandler = new Booking_handler();
            $bookingResponse = $bookingHandler->saveBookingData($inputArray);
        }else{
            //Invalid order id
            $resultArray ['response'] = $orderDetails['response'];
            $resultArray ['statusCode'] = STATUS_BAD_REQUEST;
            return $resultArray;
        }
        
        $eventPaymentGateway = new EventpaymentGateway_handler();
        $inputArray['gatewayStatus'] = true;
        $eventGatewaysResponse = $eventPaymentGateway->getPaymentgatewayByEventId($inputArray);
        $eventGatewaysList = $eventGatewaysResponse['response']['eventPaymentGatewayList'];
        foreach ($eventGatewaysList as $eventGateway) {
            $eventGatewayIdsArray[] = $eventGateway['paymentgatewayid'];
        }
        
        $paymentGateway = new Paymentgateway_handler();
        $inputPaymentGateway['gatewayIds'] = $eventGatewayIdsArray;
        $webApiStatus = 'TRUE';
        $gatewayDetails = $paymentGateway->getPaymentgatewayList($inputPaymentGateway, $webApiStatus);

        $resultArray ['response'] = $bookingResponse['response'];
        $resultArray ['response']['totalPurchaseAmount'] = $bookingResponse['totalPurchaseAmount'];
        $resultArray ['response']['eventSignupId'] = $bookingResponse['eventSignupId'];
        $resultArray ['response']['gatewayList'] = $gatewayDetails['response']['paymentgatewayList'];
        $resultArray ['statusCode'] = $bookingResponse['statusCode'];
        return $resultArray;
        
    }
    
    /*
     * order id check
     * return url check after completion of the transaction
     * gateway id check
     * 
     */
    public function paynow($inputArray){
        $accessTokenInfo=$this->setAccessTokenInfo();
        
        $orderlogHandler= new Orderlog_handler();
        $orderDetails=$orderlogHandler->getOrderlog($inputArray);
        //Invalid order id
        if($orderDetails['response']['total'] == 0){
            $resultArray ['response'] = $orderDetails['response'];
            $resultArray ['statusCode'] = STATUS_BAD_REQUEST;
            return $resultArray; 
        }
        
        $orderData = unserialize($orderDetails['response']['orderLogData']['data']);
        
        $inputArray['eventId']=$orderData['eventid'];
        $data['primaryAddress']=$orderData['addressStr'];
        //order id does not contains eventsignup id so invalid order id
        if ($orderDetails['response']['orderLogData']['eventsignup'] == 0) {
            $resultArray['status'] = FALSE;
            $resultArray['response']['messages'][] = ERROR_NO_ORDERLOG_FOUND;
            $resultArray ['statusCode'] = STATUS_BAD_REQUEST;
            return $resultArray;
        }
        
        $eventPaymentGateway = new EventpaymentGateway_handler();
        $inputArray['gatewayStatus'] = true;
        $eventGatewaysResponse = $eventPaymentGateway->getPaymentgatewayByEventId($inputArray);
        $eventGatewaysList = $eventGatewaysResponse['response']['eventPaymentGatewayList'];
        $validGatewayIdStatus=FALSE;
        foreach ($eventGatewaysList as $eventGateway){
            if($eventGateway['paymentgatewayid'] == $inputArray['paymentGatewayId']){
                $validGatewayIdStatus=TRUE;
            
                break;
            }
        }
        if(!$validGatewayIdStatus){
            $resultArray['status'] = FALSE;
            $resultArray['response']['messages'][] = "Invalid Gateway Id";
            $resultArray ['statusCode'] = STATUS_BAD_REQUEST;
            return $resultArray;
        }
        $data['orderId']=$inputArray['orderId'];
        $data['paymentGatewayKey']=$inputArray['paymentGatewayId'];
        
        //get the event title
        $eventData=$this->eventHandler->getSimpleEventDetails($inputArray);
        $data['eventTitle']=$eventData['response']['details']['title'];
        $data['accessToken']=$accessTokenInfo['access_token'];
        $gatewayCall = $this->ci->load->view('api/payNow', $data,true);
        print_r($gatewayCall);exit;
    }
    
    //To set the access token related user information
    public function setAccessTokenInfo(){
        $this->ci->load->library('resource');     
        $response = $this->ci->resource->getAccessTokenDetails();
        if(count($response)> 0){
            $this->ci->customsession->setAccessToken($response['user_id']);
        }
        return $response;
    }
    
    public function printPass($inputArray) {

        if(isset($inputArray['isAccessToken']) && !$inputArray['isAccessToken']) {
            $response['user_id'] = getUserId();
        } else {
        $response = $this->ci->resource->getAccessTokenDetails();
        }


        $inputArray['userId'] = $response['user_id'];
        $userHandler = new User_handler();
        $userDetails = $userHandler->getEventsignupUserdata($inputArray);

        $eventsignup['eventsignupId'] = isset($inputArray['eventsignupId']) ? $inputArray['eventsignupId'] : '';
        $eventsignup['userEmail'] = $userDetails['response']['userData']['email'];
      
        $printpassHandler = new Printpass_handler();
        $eventsignpudata = $printpassHandler->getUserEventsignup($eventsignup);
        if ($eventsignpudata['status'] && count($eventsignpudata['response']['eventSignupDetailData']) > 0) {
            
            $resultArray = $eventsignpudata['response']['eventSignupDetailData'];
            return $resultArray;
        }

        $resultArray['message'] = ERROR_INVALID_DATA;
        return $resultArray;
    }
    
    public function printPass_common($inputArray) {
        
        $this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('eventsignupId', 'eventsignupId', 'required_strict|is_natural_no_zero');
        if ($this->ci->form_validation->run() == FALSE) {
            $errorMsg = $this->ci->form_validation->get_errors();
            $output = parent::createResponse(FALSE, $errorMsg['message'], STATUS_BAD_REQUEST);
            return $output;
        }
        $inputArray['isAccessToken'] = false;
        $resultArray = $this->printPass($inputArray);
        return $resultArray;
    }
    
    public function createTicket($inputData){
        
        $ticketHandler=new Ticket_handler();
        
        $ticketArray['eventId']=$inputData['eventId'];
        $ticketArray['name']=$inputData['ticketName'];
        $ticketArray['description']=$inputData['ticketDescription'];
        $ticketArray['price']=$inputData['price'];
        $ticketArray['currencyId']=1;
        if($ticketArray['price']> 0){
            $currencyHandler=new Currency_handler();
            $currencyArray['currencyCode']=$inputData['currency'];
            $currencyResponse=$currencyHandler->getCurrencyDetailByCode($currencyArray);
//            print_r($currencyResponse);exit;
            $ticketArray['currencyId']=$currencyResponse['response']['currencyList']['detail']['currencyId'];
        }
              
        
        $ticketArray['quantity']=$inputData['totalTicketCount'];
        $ticketArray['soldOut']=((isset($inputData['isSoldOut']) && $inputData['isSoldOut']=='false') ||(!isset($inputData['isSoldOut'])))?0:1;
        
        $ticketArray['startDate']= urldecode($inputData['startDate']) ;
        $ticketArray['startTime']= allTimeFormats(urldecode($inputData['startTime']), 12);
        $ticketArray['endDate'] = urldecode($inputData['endDate']) ;
        $ticketArray['endTime'] = allTimeFormats(urldecode($inputData['endTime']), 12);
        $ticketArray['minOrderQuantity']=$inputData['minQuantity'];
        $ticketArray['maxOrderQuantity']=$inputData['maxQuantity'];
        $ticketArray['order']=1;
        
        
        $timezoneHandler =new Timezone_handler();
        $timeZoneData['timezoneId'] = 1;
        $timeZoneData['status'] = 1;
        $timeZoneDetails = $timezoneHandler->details($timeZoneData);
        $timeZoneName = "";
        if ($timeZoneDetails['status']) {
            $timeZoneName = $timeZoneDetails['response']['detail'][1]['name'];
        } else {
            return $timeZoneDetails;
        }
        $ticketArray['timeZoneName']=$timeZoneName;
        

        if($inputData['price'] > 0 && (!isset($inputData['donationType']) ==false || $inputData['donationType'] == false )){
            $ticketArray['type']=2;
        }else if($inputData['donationType'] == true){
            $ticketArray['type']=3;
        }else if($inputData['price'] == 0){
            $ticketArray['type']=1;
        }
        $ticketArray['type']=getTicketType($ticketArray['type']);
        
        
        //validation
        $ticketValidations=$this->eventTicketValidations($ticketArray);
        if($ticketValidations['status'] == FALSE){
            return $ticketValidations;
        }
        $response= $ticketHandler->add($ticketArray);
        
        return $response;
    }
    
    
    public function publishOrUnpublishEvent($inputArray){
        $this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('eventId', 'Event Id', 'is_natural_no_zero|required_strict');
        $this->ci->form_validation->set_rules('publish', 'Publish', 'required_strict');
        
        if ($this->ci->form_validation->run() === FALSE) {
            $output['status'] = FALSE;
            $output['response']['messages'] = $this->ci->form_validation->get_errors();
            $output['statusCode'] = STATUS_BAD_REQUEST;
            $output['response']['status'] = FALSE;
            return $output;
        }
        $eventHandler=new Event_handler();
        $eventData=$eventHandler->getEventDetails($inputArray);
//        print_r($eventData);exit;
        if($inputArray['publish'] == 1){
            $timezoneHandler =new Timezone_handler();
            $timeZoneData['timezoneId'] = 1;
            $timeZoneData['status'] = 1;
            $timeZoneDetails = $timezoneHandler->details($timeZoneData);
            $timeZoneName = "";
            if ($timeZoneDetails['status']) {
                $timeZoneName = $timeZoneDetails['response']['detail'][1]['name'];
            } else {
                return $timeZoneDetails;
            }
            $startDate=$eventData['response']['details']['startDate'];
            $eventStartDate = convertTime($startDate, $timeZoneName);
            if (strtotime(date("Y-m-d H:i:s")) > strtotime($eventStartDate)) {
                $output['status'] = FALSE;
                $output["response"]["messages"][] = ERROR_EVENT_START_DATE_GREATER_THAN_NOW;
                $output['statusCode'] = STATUS_BAD_REQUEST;
                return $output;
            }
        }

        if($eventData['response']['total']==1){
            $solrArray['status'] =$inputArray['publish'];
            $solrArray['id'] = $inputArray['eventId'];
            
            $solrArray['registrationType']=$this->eventRegistrationType($inputArray);
            
            
            $solrHandler = new Solr_handler();
            $addEventInSolr = $solrHandler->solrUpdateEvent($solrArray);
            $eventHandler->updateEventStatusRegistration($solrArray);
            $output['status'] = TRUE;
            $output['response']['messages'][] = SUCCESS_UPDATED;
            $output['response']['statusUpdated'] = 'Success';
            $output['statusCode'] = STATUS_OK;
            return $output;
            
        }
        
        return $eventData;
        
    }
    
    public function eventRegistrationType($inputArray){
        $eventType=3;//No registration event
        $ticketHandler=new Ticket_handler();
        $inputArray['allTickets']=0;
        $ticketList=$ticketHandler->getEventTicketList($inputArray);
        $alltickets=$ticketList['response']['ticketList'];
        if(count($alltickets) == 0){
            return $eventType;
        }
        foreach($alltickets as $key => $ticket){
            if($ticket['type']!='free'){
                $eventType=2; //paid event
                break;
            }else{
                $eventType=1;//free event
            }
            
        }
            
       return $eventType;  
        
    }
    
    public function eventTicketValidations($data){
        $output['status'] = TRUE;
        if (empty(trim($data['eventId'])) || $data['eventId'] < 1) {
            $output['status'] = FALSE;
            $output['response']['ticketmessages']['eventId'] = ERROR_INVALID_EVENTID;
            $output['statusCode'] = STATUS_BAD_REQUEST;
            
        }
        if (empty(trim($data['name']))) {
            $output['status'] = FALSE;
            $output['response']['ticketmessages']['ticketName'] = ERROR_EMPTY_TICKET_NAME;
            $output['statusCode'] = STATUS_BAD_REQUEST;
            
        } elseif (strlen($data['name']) < 2) {
            $output['status'] = FALSE;
            $output['response']['ticketmessages']['ticketName'] = ERROR_TICKET_NAME_MIN_LENGTH;
            $output['statusCode'] = STATUS_BAD_REQUEST;
             
        } elseif (strlen($data['name']) > 75) {
            $output['status'] = FALSE;
            $output['response']['ticketmessages']['ticketName'] = ERROR_TICKET_NAME_MAX_LENGTH;
            $output['statusCode'] = STATUS_BAD_REQUEST;
             
        } elseif (!preg_match('/^[0-9a-zA-Z \$\%\#\&\_\-\*\@\+\,\(\)]+$/', $data['name'])) {
            $output['status'] = FALSE;
            $output['response']['ticketmessages']['ticketName'] = ERROR_TICKET_NAME_PATTERN;
            $output['statusCode'] = STATUS_BAD_REQUEST;
             
        }

        if (strlen($data['description']) > 300) {
            $output['status'] = FALSE;
            $output['response']['ticketmessages']['ticketDescription'] = ERROR_TICKET_DESCRIPTION_MAX_LENGTH;
            $output['statusCode'] = STATUS_BAD_REQUEST;
             
        }
        
        //price validation
        if (($data['type'] == '2' || $data['type'] == '4')) {
            if ($data['price'] == '') {
                $output['status'] = FALSE;
                $output['response']['ticketmessages']['price'] = ERROR_TICKET_PRICE_EMPTY;
                $output['statusCode'] = STATUS_BAD_REQUEST;
            } elseif (!preg_match('/^[0-9]+$/', $data['price']) || (int) $data['price'] <= 0) {
                $output['status'] = FALSE;
                $output['response']['ticketmessages']['price'] = ERROR_TICKET_PRICE_NON_NUMERIC;
                $output['statusCode'] = STATUS_BAD_REQUEST;
            }
        }
        
        if($data['type']!='3'){
                if(!preg_match('/^[0-9]+$/',$data['quantity']) || (int)$data['quantity']<=0){
                    $output['status'] = FALSE;
                    $output['response']['ticketmessages']['quantity'] = ERROR_TICKET_QUANTITY_NON_NUMERIC;
                    $output['statusCode'] = STATUS_BAD_REQUEST;
                    
                }
                if(!preg_match('/^[0-9]+$/',$data['minOrderQuantity']) || (int)$data['minOrderQuantity']<=0){
                    $output['status'] = FALSE;
                    $output['response']['ticketmessages']['minOrderQuantity'] = ERROR_TICKET_MIN_QTY_NON_NUMERIC;
                    $output['statusCode'] = STATUS_BAD_REQUEST;
                    
                }elseif(!isset($output['response']['ticketmessages']['quantity']) && $data['minOrderQuantity']>$data['quantity']){
                    $output['status'] = FALSE;
                    $output['response']['ticketmessages']['minOrderQuantity'] = ERROR_TICKET_MIN_QTY_MORE_THAN_QTY;
                    $output['statusCode'] = STATUS_BAD_REQUEST;
                    
                }
                if(!preg_match('/^[0-9]+$/',$data['maxOrderQuantity']) || (int)$data['maxOrderQuantity']<=0){
                    $output['status'] = FALSE;
                    $output['response']['ticketmessages']['maxOrderQuantity'] = ERROR_TICKET_MAX_QTY_NON_NUMERIC;
                    $output['statusCode'] = STATUS_BAD_REQUEST;
                    
                }elseif(!isset($output['response']['ticketmessages']['quantity']) && $data['maxOrderQuantity']>$data['quantity']){
                    $output['status'] = FALSE;
                    $output['response']['ticketmessages']['maxOrderQuantity'] = ERROR_TICKET_MAX_QTY_MORE_THAN_QTY;
                    $output['statusCode'] = STATUS_BAD_REQUEST;
                    
                }elseif(!isset($output['response']['ticketmessages']['minOrderQuantity']) && $data['maxOrderQuantity']<$data['minOrderQuantity']){
                    $output['status'] = FALSE;
                    $output['response']['ticketmessages']['maxOrderQuantity'] = ERROR_TICKET_MAX_QTY_LESS_THAN_MIN_QTY;
                    $output['statusCode'] = STATUS_BAD_REQUEST;
                    
                }
            }
            if(!isset($data['startDate'])){
                $output['status'] = FALSE;
                $output['response']['ticketmessages']['startDate'] = ERROR_TICKET_START_DATE_REQUIRED;
                $output['statusCode'] = STATUS_BAD_REQUEST;
                
            }elseif(!preg_match("/^(0[1-9]|1[0-2])\/(0[1-9]|[1-2][0-9]|3[0-1])\/[0-9]{4}+$/", $data['startDate'])){
                $output['status'] = FALSE;
                $output['response']['ticketmessages']['startDate'] = ERROR_DATE_VALUE_FORMAT;
                $output['statusCode'] = STATUS_BAD_REQUEST;
                
            }
            if(!isset($data['startTime'])){
                $output['status'] = FALSE;
                $output['response']['ticketmessages']['startTime'] = ERROR_TICKET_START_TIME_REQUIRED;
                $output['statusCode'] = STATUS_BAD_REQUEST;
                
            } 
            if(!isset($data['endDate'])){
                $output['status'] = FALSE;
                $output['response']['ticketmessages']['endDate'] = ERROR_TICKET_END_DATE_REQUIRED;
                $output['statusCode'] = STATUS_BAD_REQUEST;
                
            }elseif(!preg_match("/^(0[1-9]|1[0-2])\/(0[1-9]|[1-2][0-9]|3[0-1])\/[0-9]{4}+$/", $data['endDate'])){
                $output['status'] = FALSE;
                $output['response']['ticketmessages']['endDate'] = ERROR_DATE_VALUE_FORMAT;
                $output['statusCode'] = STATUS_BAD_REQUEST;
                
            }
            if(!isset($data['endTime'])){
                $output['status'] = FALSE;
                $output['response']['ticketmessages']['endTime'] = ERROR_TICKET_END_TIME_REQUIRED;
                $output['statusCode'] = STATUS_BAD_REQUEST;
                
            }
            
            
            return $output;
        
    }
    
    public function appendFlagstoEventList($eventList){
        
        $eventIdArr = array();
        if(count($eventList)){
            foreach($eventList as $key =>$event){
                $eventIdArr[] = $event['id'];
            }
        }
        
        $seatingHandler = new Seating_handler();
        $seatingEventsIdTempArr = $seatingHandler->getSeatingEvents();
        foreach ($seatingEventsIdTempArr as $eventId) {
            $seatingEventsIdArr[] = $eventId['eventid'];
        }
        
        $eventSettings = $this->getTicketOptionsByArr(array('eventIds' => $eventIdArr));
        
        $configureHandler = new Configure_handler();
        $customFieldInput['excludeCommonFields'] = true;
        $customFieldInput['eventIds'] = $eventIdArr;
        $customFieldResult = $configureHandler->getCustomFieldsOfEvents($customFieldInput);
        if($customFieldResult['status'] && $customFieldResult['response']['total'] > 0) {
            $customFields = $customFieldResult['response']['customFields'];
            foreach($customFields as $customField) {
                $customFieldArr[$customField['eventid']][] = $customField;
            }
        }
        
        $customFieldInput['eventId'] = $eventId;
        $customFieldInput['collectMultipleAttendeeInfo'] = $collectMultipleAttendeeInfo;
        $customFieldInput['disableSessionLockTickets'] = true;
        $configureHandler=new Configure_handler();
        $eventCustomFieldsArr = $configureHandler->getCustomFields($customFieldInput);
        
        if(count($eventList)){
            foreach($eventList as $key =>$event){
                
                $seatingEnabled = 0;
                if (in_array($recordValue["id"], $seatingEventsIdArr)) {
                    $seatingEnabled = 1;
                }
                $eventList[$key]['isSeatingLayout'] = $seatingEnabled;
                
                
                $eventList[$key]['isMultipleAttendee'] = 0;
                if(isset($eventSettings[$event['id']]) && $eventSettings[$event['id']]['collectmultipleattendeeinfo']) {
                    $eventList[$key]['isMultipleAttendee'] = $eventSettings[$event['id']]['collectmultipleattendeeinfo'];
                }
                
                $eventList[$key]['isCustomFields'] = 0;
                if(isset($customFieldArr[$event['id']]) && count($customFieldArr[$event['id']]) > 0) {
                    $eventList[$key]['isCustomFields'] = 1;
                }
            }
            
        }
        return $eventList;
        
    }
    
    public function getTicketOptionsByArr($inputArray) {
        
        $this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('eventIds', 'eventIds', 'required_strict|is_array');
        if ($this->ci->form_validation->run() === FALSE) {
            $errorMessages = $this->ci->form_validation->get_errors();
            $output['status'] = FALSE;
            $output['response']['messages'] = $errorMessages['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $eventDetailReq = isset($inputArray['eventDetailReq']) ? $inputArray['eventDetailReq'] : true;
        $this->ci->load->model('Event_setting_model');
        if ($eventDetailReq) {
            $this->ci->load->model('Eventdetail_model');
        }
        $this->ci->Event_setting_model->resetVariable();
        $selectEventSettingData = array();
        $selectEventSettingData['collectmultipleattendeeinfo'] = $this->ci->Event_setting_model->collectmultipleattendeeinfo;
        $selectEventSettingData['customemail'] = $this->ci->Event_setting_model->customemail;
        $selectEventSettingData['displayamountonticket'] = $this->ci->Event_setting_model->displayamountonticket;
        $selectEventSettingData['nonormalwhenbulk'] = $this->ci->Event_setting_model->nonormalwhenbulk;
        $selectEventSettingData['geolocalitydisplay'] = $this->ci->Event_setting_model->geolocalitydisplay;
        $selectEventSettingData['calculationmode'] = $this->ci->Event_setting_model->calculationmode;
        $selectEventSettingData['eventid'] = $this->ci->Event_setting_model->eventid;

        $this->ci->Event_setting_model->setSelect($selectEventSettingData);
        $whereDetails['eventid'] = $inputArray['eventIds'];
        $this->ci->Event_setting_model->setWhereIns($whereDetails);
        $eventSettings = $this->ci->Event_setting_model->get();
        
        $eventDetails = array();
        if ($eventDetailReq) {
            $selectEventDetailData['limitsingletickettype'] = $this->ci->Eventdetail_model->eventdetail_limitsingletickettype;
            $selectEventDetailData['discountaftertax'] = $this->ci->Eventdetail_model->eventdetail_discountaftertax;
            $this->ci->Eventdetail_model->setSelect($selectEventDetailData);
            $whereDetails['eventid'] = $inputArray['eventId'];
            $this->ci->Eventdetail_model->setWhere($whereDetails);
            $eventDetails = $this->ci->Eventdetail_model->get();
        }
        $ticketingOptions = array_merge($eventSettings, $eventDetails);
        $data = commonHelperGetIdArray($ticketingOptions,'eventid');
        return $data;
    }
    
    public function offlineBookingApi($inputArray){
        $accessTokenInfo=$this->setAccessTokenInfo();
        $orderlogHandler= new Orderlog_handler();
        $orderDetails=$orderlogHandler->getOrderlog($inputArray);
        //Invalid order id
        if($orderDetails['response']['total'] == 0){
            $resultArray ['response'] = $orderDetails['response'];
            $resultArray ['statusCode'] = STATUS_BAD_REQUEST;
            return $resultArray; 
        }
        
        $orderData = unserialize($orderDetails['response']['orderLogData']['data']);
        
        $inputArray['eventId']=$orderData['eventid'];
        $data['primaryAddress']=$orderData['addressStr'];
        $eventSignupId=$orderDetails['response']['orderLogData']['eventsignup'];
        //order id does not contains eventsignup id so invalid order id
        if ($eventSignupId == 0) {
            $resultArray['status'] = FALSE;
            $resultArray['response']['messages'][] = ERROR_NO_ORDERLOG_FOUND;
            $resultArray ['statusCode'] = STATUS_BAD_REQUEST;
            return $resultArray;
        }
        
        //checking the eventsignup id status
        $eventSingupHandler=new Eventsignup_handler();
        $eventSignupArray['eventsignupId']=$eventSignupId;
        $signupDetails=$eventSingupHandler->getEventSignupData($eventSignupArray);
        if(count($signupDetails['response']['eventSignupData']) > 0){
            $resultArray['status'] = FALSE;
            $resultArray['response']['messages'][] = ERROR_NO_ORDERLOG_FOUND;
            $resultArray ['statusCode'] = STATUS_BAD_REQUEST;
            return $resultArray;
        }
         
      
        $ebsReturnArray['MerchantRefNo'] = $eventSignupId;
        $ebsReturnArray['TransactionID'] = $inputArray['transactionID'];
        $ebsReturnArray['ResponseCode'] = 200;
        $ebsReturnArray['PaymentStatus'] = 'Api Transaction Successful';
        $ebsReturnArray['thirdPartyGateway'] = TRUE;
//        $ebsReturnArray['Amount'] = $orderData ['totalPurchaseAmount'];
        $ebsReturnArray['mode'] = 'other';
        $ebsReturnArray['isSmsEnable'] =  ($inputArray['isSmsEnable']==TRUE)?TRUE:FALSE;
        $ebsReturnArray['isEmailEnable'] = ($inputArray['isEmailEnable']==TRUE)?TRUE:FALSE;
        $orderData['paymentResponse'] = $ebsReturnArray;
         
        $updatedSessData = serialize($orderData);
        $orderLogUpdateInput['condition']['orderId'] = $inputArray['orderId'];
        $orderLogUpdateInput['condition']['eventSignupId'] = $eventSignupId;
        $orderLogUpdateInput['update']['data'] = $updatedSessData;
        $orderlogHandler = new Orderlog_handler();
        $orderLogUpdateResponse = $orderlogHandler->orderLogUpdate($orderLogUpdateInput);
        $data=$orderlogHandler->getOrderlog($inputArray);
        if (!$orderLogUpdateResponse['status']) {
            $response['status'] = FALSE;
            $response['statusCode'] = STATUS_BAD_REQUEST;
            $response['response']['messages'][] = ERROR_ORDERLOG_UPDATED;
            return $response;
        }
        
       $bookingHandler=new Booking_handler();
       $bookingHandler->updateTicketSoldCount($orderData, $inputArray['orderId']);
      
        $response['status'] = TRUE;
        $response['statusCode'] = STATUS_OK;
        $response['response']['messages'][] = SUCCESS_BOOKING;
        return $response;
        
    }
    


} 

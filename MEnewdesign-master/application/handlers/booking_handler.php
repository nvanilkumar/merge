<?php

/**
 * offlinebooking  related business logic will be defined in this class
 *
 * @package		CodeIgniter
 * @author		Qison  Dev Team
 * @copyright	Copyright (c) 2015, MeraEvents.
 * @Version		Version 1.0
 * @Since       Class available since Release Version 1.0 
 * @Created     25-08-2015
 * @Last Modified 25-08-2015
 */
require_once (APPPATH . 'handlers/handler.php');
require_once (APPPATH . 'handlers/event_handler.php');
require_once (APPPATH . 'handlers/user_handler.php');
require_once(APPPATH . 'handlers/eventsignup_handler.php');
require_once(APPPATH . 'handlers/ticket_handler.php');
require_once(APPPATH . 'handlers/eventsignupticketdetail_handler.php');
require_once(APPPATH . 'handlers/attendee_handler.php');
require_once(APPPATH . 'handlers/configure_handler.php');
require_once(APPPATH . 'handlers/attendeedetail_handler.php');
require_once(APPPATH . 'handlers/email_handler.php');
require_once(APPPATH . 'handlers/confirmation_handler.php');
require_once(APPPATH . 'handlers/file_handler.php');
require_once (APPPATH . 'handlers/currency_handler.php');
require_once(APPPATH . 'handlers/eventsignuptax_handler.php');
require_once (APPPATH . 'handlers/discount_handler.php');
require_once(APPPATH . 'handlers/sentmessage_handler.php');
require_once(APPPATH . 'handlers/paymentgateway_handler.php');
require_once(APPPATH . 'handlers/sessionlock_handler.php');
require_once(APPPATH . 'handlers/promoter_handler.php');
require_once(APPPATH . 'handlers/paymentsource_handler.php');
require_once(APPPATH . 'handlers/ticketdiscount_handler.php');
require_once (APPPATH . 'handlers/orderlog_handler.php');
require_once (APPPATH . 'handlers/userpoint_handler.php');
require_once (APPPATH . 'handlers/viralticketsale_handler.php');
require_once (APPPATH . 'handlers/seating_handler.php');

class Booking_handler extends Handler {

    var $ci;
    var $eventsignupHandler;
    var $emailHandler;
    var $sentmessageHandler;
    var $paymentsourceHandler;
    var $orderlogHandler;

    public function __construct() {
        parent::__construct();
        $this->ci = parent::$CI;
    }

    function offlineBooking($inputArray) {    
    
        $this->promoterHandler = new Promoter_handler();
        $this->ticketHandler = new Ticket_handler();
        $this->eventHandler = new Event_handler();
        $this->discountHandler = new Discount_handler();
        $this->currencyHandler = new Currency_handler();
        $this->eventsignupHandler = new Eventsignup_handler();
        $this->eventsignupticketdetailHandler = new Eventsignup_Ticketdetail_handler();
        $this->eventsignupTaxHandler = new Eventsignuptax_handler();
        $this->attendeeHandler = new Attendee_handler();
        $this->configureHandler = new Configure_handler();
        $this->attendeeDetailHandler = new Attendeedetail_handler();
        $this->emailHandler = new Email_handler();
        $this->confirmationHandler = new Confirmation_handler();
        $this->sentmessageHandler = new Sentmessage_handler();
    
        /* offline booking related validations */
        $this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('eventId', 'Event Id', 'required_strict|is_natural_no_zero');
        $this->ci->form_validation->set_rules('ticketId', 'Ticket Id', 'required_strict|is_natural_no_zero');
        $this->ci->form_validation->set_rules('quantity', 'Quantity', 'required_strict|is_natural_no_zero');
        $this->ci->form_validation->set_rules('name', 'Name', 'required_strict');
        $this->ci->form_validation->set_rules('mobile', 'Mobile', 'required_strict|numeric|min_length[10]|max_length[10]');
        $this->ci->form_validation->set_rules('email', 'Email', 'required_strict|valid_email');
        if ($this->ci->form_validation->run() == FALSE) {
            $errorMsg = $this->ci->form_validation->get_errors();
            $output = parent::createResponse(FALSE, $errorMsg['message'], STATUS_BAD_REQUEST);
            return $output;
        }
        // check total ticket sold limit
        $pArray['userId'] = getUserId();
        $pArray['promoterId'] = $inputArray['promoterId'];
        $promoters = $this->promoterHandler->getPromoterEvents($pArray, $inputArray['eventId']);
        if ($promoters['status']) {
            if (isset($promoters['response']['promoters']) && !empty($promoters['response']['promoters'])) {
                $totalSoldTickets = isset($promoters['response']['promoters'][0]['quantity']) ? $promoters['response']['promoters'][0]['quantity'] : 0;
                $inputArray['promoterId'] = $promoters['response']['promoters'][0]['promoterId'];
                $gfpArray['eventId'] = $promoters['response']['promoters'][0]['eventId'];
                $gfpArray['id'] = $promoters['response']['promoters'][0]['promoterId'];
                $gfpArray['ticketType'] = 'paidfree';
                $gfpArray['soldout'] = 0;
                $offlinePromoter = $this->promoterHandler->getOfflinePromoterData($gfpArray);
                if ($offlinePromoter['status'] == true && $offlinePromoter['response']['total'] > 0) {
                    if (isset($offlinePromoter['response']['offlinePromoter'][$promoters['response']['promoters'][0]['promoterId']]) && !empty($offlinePromoter['response']['offlinePromoter'][$promoters['response']['promoters'][0]['promoterId']])) {

                        $ticketsLimit = isset($offlinePromoter['response']['offlinePromoter'][$promoters['response']['promoters'][0]['promoterId']]['ticketslimit']) ? $offlinePromoter['response']['offlinePromoter'][$promoters['response']['promoters'][0]['promoterId']]['ticketslimit'] : '';
                        if ($ticketsLimit != '' || $ticketsLimit != null) {
                            $remainingTicketsCount = $ticketsLimit - $totalSoldTickets;
                            $totalSoldTickets = $totalSoldTickets + $inputArray['quantity'];
                            if ($totalSoldTickets > $ticketsLimit) {
                                $errorRemainingTickets = '';
                                if ($remainingTicketsCount > 0) {
                                    $errorRemainingTickets = ', ' . str_replace('XXXX', $remainingTicketsCount, ERROR_TICKET_REMAINING_LIMIT);
                                }
                                $output['status'] = FALSE;
                                $output["response"]["messages"][] = ERROR_TICKET_LIMIT . $errorRemainingTickets;
                                $output['statusCode'] = STATUS_INVALID_INPUTS;
                                return $output;
                            }
                        }
                    }
                }
            }
        }

        $tickets = $this->ticketHandler->getTicketName($inputArray);
        if ($tickets['response']['total'] == 0) {
            $output['status'] = TRUE;
            $output['response']['messages'][] = ERROR_NO_DATA;
            $output['response']['total'] = 0;
            $output['statusCode'] = STATUS_OK;
            return $output;
        }
        $offline = array();
        $ticketArray[$inputArray['ticketId']] = $inputArray['quantity'];
        $inputArray['ticketArray'] = $ticketArray;
        $ticketResultArray = $this->eventHandler->getEventTicketCalculation($inputArray);
        if ($ticketResultArray['status'] == FALSE) {
            $output['status'] = FALSE;
            $output["response"]["messages"][] = ERROR_NO_DATA;
            $output['statusCode'] = STATUS_NO_DATA;
            return $output;
        }
         $inputArray['discount'] = $ticketResultArray['response']['calculationDetails']['discountCode'];
          if (isset($inputArray['discountCode']) && $ticketResultArray['response']['calculationDetails']['totalCodeDiscount'] > 0) {
           $offline['discountcode'] = $ticketResultArray['response']['calculationDetails']['discountCode'];
              $discountId = $this->discountHandler->getDisountId($inputArray);
            if ($discountId['status'] == TRUE && $discountId['response']['total'] == 0) {
                $output['status'] = FALSE;
                $output["response"]["messages"][] = ERROR_INVALID_DISCOUNT_CODE;
                $output['statusCode'] = STATUS_INVALID_INPUTS;
                return $output;
            }
        }
        $offline['discountcodeid'] = $discountId['response']['discountCode']['0']['id'];
        $offline['totalused'] = $discountId['response']['discountCode']['0']['totalused'];
        $offline['usagelimit'] = $discountId['response']['discountCode']['0']['usagelimit'];
        $inputCurrency['currencyCode'] = $ticketResultArray['response']['calculationDetails']['currencyCode'];
        if ($inputCurrency['currencyCode'] == '') {
            $currency = array();
            $currency['currencyname'] = 'free';
            $currencyData = $this->currencyHandler->getCurrencyDetailByCode($currency);
        } else {
            $currencyData = $this->currencyHandler->getCurrencyDetailByCode($inputCurrency);
        }
        if ($currencyData['status'] == false && $currencyData['response']['total'] == 0) {
            $output['status'] = TRUE;
            $output['response']['messages'][] = ERROR_NO_CURRENCIES;
            $output['response']['total'] = 0;
            $output['statusCode'] = STATUS_OK;
            return $output;
        }
        $transactionToCurrencyId = $currencyData['response']['currencyList']['detail']['currencyId'];
        $offline['amount'] = $ticketResultArray['response']['calculationDetails']['totalPurchaseAmount'];
        if (isset($ticketResultArray['response']['calculationDetails']['extraCharge']['totalAmount'])) {
            $offline['amount'] = $offline['amount'] - $ticketResultArray['response']['calculationDetails']['extraCharge']['totalAmount'];
        }

        $offline['price'] = $ticketResultArray['response']['calculationDetails']['ticketsData'][$inputArray['ticketId']]['ticketPrice'];
        $offline['quantity'] = $ticketResultArray['response']['calculationDetails']['ticketsData'][$inputArray['ticketId']]['selectedQuantity'];
        $offline['discountValue'] = $ticketResultArray['response']['calculationDetails']['totalCodeDiscount'];
        //$offline['discountcode'] = $inputArray['discountCode'];
        $offline['totalTicketAmount'] = $ticketResultArray['response']['calculationDetails']['totalTicketAmount'];
        $offline['totalBulkDiscount'] = $ticketResultArray['response']['calculationDetails']['totalBulkDiscount'];
        $offline['totaltaxamount'] = $ticketResultArray['response']['calculationDetails']['totalTaxAmount'];
        $offline['bulkDiscountId'] =$ticketResultArray['response']['calculationDetails']['ticketsData'][$inputArray['ticketId']]['bulkDiscountId'];
        $offline['totalTicketAmount'] = $ticketResultArray['response']['calculationDetails']['ticketsData'][$inputArray['ticketId']]['totalAmount'];
        $offline['totalTicket']= (($offline['totalTicketAmount'] + $offline['totaltaxamount']) - ($offline['discountValue'] + $offline['totalBulkDiscount']));
        $ticketType = $tickets['response']['ticketName']['0']['displaystatus'];
        $eventId = $inputArray['eventId'];        
        $offline['eventId'] = $inputArray['eventId'];
        $eventTitle = $this->eventHandler->getEventName($eventId);
        $offline['eventTitle'] = $eventTitle['response']['eventName'];
        $offline['ticketId'] = $inputArray['ticketId'];
        $userId = $this->ci->customsession->getUserId();
        $offline['userId'] = $userId;
        $offline['name'] = $inputArray['name'];
        $offline['mobile'] = $inputArray['mobile'];
        $offline['email'] = $inputArray['email'];
        $offline['PaymentTransId'] = "Offline";
        $offline['transactionstatus'] = "success";
        $offline['paymentstatus'] = "verified";
        $offline['paymentGateway'] = "EBS";
        $offline['paymentmodeId'] = 4;
        $offline['PaymentStatus'] = "Successful Transaction";
        $offline['transactiontickettype'] = 'paid';
        $offline['fromcurrencyid'] = $transactionToCurrencyId;
        $eventSignupInsert['userid'] = $offline['userId'];
        $eventSignupInsert['eventid'] = $offline['eventId'];
        $eventSignupInsert['quantity'] = $offline['quantity'];
        $eventSignupInsert['totalamount'] = $offline['amount'];
        $eventSignupInsert['attendeeid'] = '';
        $eventSignupInsert['transactionstatus'] = $offline['transactionstatus'];
        $eventSignupInsert['paymentstatus'] = $offline['paymentstatus'];
        $eventSignupInsert['paymentGateway'] = $offline['paymentGateway'];
        $eventSignupInsert['transactiontickettype'] = $offline['transactiontickettype'];
        $eventSignupInsert['transactionticketids'] = $offline['ticketId'];
        $eventSignupInsert['paymenttransactionid'] = $offline['PaymentTransId'];
        $eventSignupInsert['fromcurrencyid'] = $offline['fromcurrencyid'];
        $eventSignupInsert['paymentmodeid'] = $offline['paymentmodeId'];
        $eventSignupInsert['promotercode'] = OFFLINE_ . $userId;
        $eventSignupInsert['discountamount'] = $offline['discountValue'] + $offline['totalBulkDiscount'];
        $eventSignupInsert['discountcodeid'] = $offline['discountcodeid'];
        $eventSignupInsert['discount'] = $offline['discountcode'];
        $eventSignupId = '';
        $eventSignupId = $custome = $attendeeInsert = $attendeeId = $custome = $finalData = $ticketUpdateData = array();
        if ($ticketType == 0) {//No display tickets
            $uniqueId = md5(time() . $inputArray['eventId']);
            //multiple evenrsignupids inserted type as 0
            for ($i = 1; $i <= $offline['quantity']; $i++) {
                $eventSignupInsert['quantity'] = 1;
                // $offline['discountamount'] = round($offline['discountValue'] + $offline['totalBulkDiscount'] / $offline['quantity']);
                $offline['discountamount'] = round(($offline['discountValue'] + $offline['totalBulkDiscount']) / $offline['quantity']);
                $eventSignupInsert['discountamount'] = $offline['discountamount'];
                //$eventSignupInsert['totalamount'] = ($offline['price'] - $offline['discountamount']);
                $eventSignupInsert['totalamount'] = round($offline['amount'] / $offline['quantity']);
                $eventSignupInsert['extrafield'] = $uniqueId;
                $eventSignupInsert['extrainfo'] = 'transactionGrouping';
                $eventSignupReturn = $this->eventsignupHandler->add($eventSignupInsert);

                if ($eventSignupReturn['status']) {
                    $eventSignupId[] = $eventSignupReturn['response']['eventSignUpId'];
                    $signUpId = $eventSignupId['0'];
                }            
            }
            $startSignupId = current($eventSignupId);
            $endSignupId = end($eventSignupId);
            $tax = $ticketResultArray['response']['calculationDetails']['ticketsData'][$inputArray['ticketId']]['taxes'];           
              foreach ($eventSignupId as $val) {
                $offline['totalamount'] = round(($offline['totalTicket'] / $offline['quantity']),2);
                // add data into eventsignupticketdetail table
                $offline['discountamount'] = $offline['discountValue'] / $offline['quantity'];
                $offline['bulkDiscountAmount'] = $offline['totalBulkDiscount'] / $offline['quantity'];
                $eventSignupTicketInsert['eventsignupid'] = $val;
                $eventSignupTicketInsert['ticketid'] = $offline['ticketId'];
                $eventSignupTicketInsert['ticketquantity'] = 1;
                $eventSignupTicketInsert['amount'] = $offline['price'];
                $eventSignupTicketInsert['totalamount'] = ($offline['totalamount']);
                $eventSignupTicketInsert['discountcode'] = $offline['discountcode'];
                $eventSignupTicketInsert['discountcodeid'] = $offline['discountcodeid'];
                $eventSignupTicketInsert['discountamount'] = $offline['discountamount'];
                $eventSignupTicketInsert['bulkdiscountamount'] = $offline['bulkDiscountAmount'];
                $eventSignupTicketInsert['totaltaxamount'] = round($offline['totaltaxamount'] / $offline['quantity']);
                $this->eventsignupticketdetailHandler->add($eventSignupTicketInsert);
                // add data into attendeed table
                $attendeeInsert['eventSignupId'] = $val;
                $attendeeInsert['ticketId'] = $offline['ticketId'];
                $attendeeInsert['primary'] = 1;
                $addAttendeeReturn = $this->attendeeHandler->add($attendeeInsert);
                $attendeeId[] = $addAttendeeReturn['response']['attendeeId'];
                $barCOde = substr($offline['eventId'], 1, 4) . $val;
                $k = 1;
                foreach ($attendeeId as $attendy) {
                    $updateEventSignUp['attendeeid'] = $attendy;
                    $updateEventSignUp['barcodenumber'] = $barCOde;
                    $updateEventSignUp['eventSignUpId'] = $val;
                    $k++;
                }
                $updateEventSIgnUpRecords = $this->eventsignupHandler->updateEventSignUp($updateEventSignUp);
                if(isset($tax)){
                    foreach ($tax as $taxId => $ticketTaxes) {
                        // Code to insert the tax data into "eventsignuptax" table starts here
                        $eventSignupTaxInput['eventSignupId'] = $val;
                        $eventSignupTaxInput['ticketId'] = $inputArray['ticketId'];
                        $eventSignupTaxInput['ticketMappingId'] = $ticketTaxes['taxmappingid'];
                $eventSignupTaxInput['taxAmount'] = $ticketTaxes['taxAmount'] /  $offline['quantity'];
                $this->eventsignupTaxHandler->add($eventSignupTaxInput);
                        //Code to insert the tax data into "eventsignuptax" table ends here
                    }
                }
                                    
                /* TrueSemantic API call */
                $this->sendDataToTrueSemantic($val, true);
                /* TrueSemantic API call ends here */            }
        } else {
            //single evenrsignupids inserted type as 1
            $eventSignupInsert['totalamount'] = round($offline['amount']);
            $eventSignupReturn = $this->eventsignupHandler->add($eventSignupInsert);
            if ($eventSignupReturn['status']) {
                $eventSignupId = $eventSignupReturn['response']['eventSignUpId'];
            }
            /// insert eventsignupticket detail table
            $eventSignupTicketInsert['eventsignupid'] = $eventSignupId;
            $eventSignupTicketInsert['ticketid'] = $offline['ticketId'];
            $eventSignupTicketInsert['ticketquantity'] = $offline['quantity'];
            $eventSignupTicketInsert['amount'] = $offline['totalTicketAmount'];
            $eventSignupTicketInsert['totalamount'] = $offline['totalTicket'];
            $eventSignupTicketInsert['discountcode'] = $offline['discountcode'];
            $eventSignupTicketInsert['discountcodeid'] = $offline['discountcodeid'];
            $eventSignupTicketInsert['discountamount'] = $offline['discountValue'];
            $eventSignupTicketInsert['bulkdiscountamount'] = $offline['totalBulkDiscount'];
            $eventSignupTicketInsert['totaltaxamount'] = $offline['totaltaxamount'];  
            $eventsignup = $this->eventsignupticketdetailHandler->add($eventSignupTicketInsert);
            $attendeeInsert['eventSignupId'] = $eventSignupId;
            $attendeeInsert['ticketId'] = $offline['ticketId'];
            $attendeeInsert['primary'] = 1;
            for ($i = 1; $i <= $offline['quantity']; $i++) {
                $addAttendeeReturn = $this->attendeeHandler->add($attendeeInsert);
                $attendeeId[] = $addAttendeeReturn['response']['attendeeId'];
            }
            $barCOde = substr($offline['eventId'], 1, 4) . $eventSignupId;
            $updateEventSignUp['attendeeid'] = $attendeeId['0'];
            $updateEventSignUp['barcodenumber'] = $barCOde;
            $updateEventSignUp['eventSignUpId'] = $eventSignupId;
            $updateEventSIgnUpRecords = $this->eventsignupHandler->updateEventSignUp($updateEventSignUp);
            if ($updateEventSIgnUpRecords['status'] == FALSE) {
                $output['status'] = FALSE;
                $output["response"]["messages"][] = ERROR_EVENTSIGNUP_UPDATE;
                $output['statusCode'] = STATUS_SERVER_ERROR;
                return $output;
            }
            $tax = $ticketResultArray['response']['calculationDetails']['ticketsData'][$inputArray['ticketId']]['taxes'];
            if(isset($tax)){
                foreach ($tax as $taxId => $ticketTaxes) {
                    // Code to insert the tax data into "eventsignuptax" table starts here
                    $eventSignupTaxInput['eventSignupId'] = $eventSignupId;
                    $eventSignupTaxInput['ticketId'] = $inputArray['ticketId'];
                    $eventSignupTaxInput['ticketMappingId'] = $ticketTaxes['taxmappingid'];
                    $eventSignupTaxInput['taxAmount'] = $ticketTaxes['taxAmount'];
                    $this->eventsignupTaxHandler->add($eventSignupTaxInput);
                    //Code to insert the tax data into "eventsignuptax" table ends here
                }
            }
            /* TrueSemantic API call */
            $this->sendDataToTrueSemantic($eventSignupId, true);
            /* TrueSemantic API call ends here */
        }
        // Update normal dicount usage
        if ($offline['discountcodeid'] > 0) {
            $discount = array();
            $discount['eventId'] = $inputArray['eventId'];
            $discount['discountId'] = $offline['discountcodeid'];
            $discount['totalused'] = $offline['totalused'] + $offline['quantity'];
            if($discount['totalused'] > $offline['usagelimit']){
            $discount['totalused'] = $offline['usagelimit'];
            }
            $discount['type'] = 'normal';
            $updateDiscount = $this->discountHandler->updateDiscountUsage($discount);
            if ($updateDiscount == FALSE) {
                return $updateDiscount;
            }
        }
        
        // Update bulk dicount usage         
        if ($offline['bulkDiscountId'] > 0) {
           $bulkdicount= array();
           $bulkdicount['eventId']=$inputArray['eventId'];
           $bulkdicount['discountId']=$offline['bulkDiscountId'];
           $bulkdicount['discountType']='bulk';
           $bulkdicount['bulkDiscount'] = TRUE;
           $bulkDiscountId = $this->discountHandler->getDisountId($bulkdicount);
            if ($bulkDiscountId['status'] == TRUE && $bulkDiscountId['response']['total'] == 0) {
                $output['status'] = FALSE;
                $output["response"]["messages"][] = ERROR_INVALID_DISCOUNT_CODE;
                $output['statusCode'] = STATUS_INVALID_INPUTS;
                return $output;
            }
            $bulkDiscountUsed= $bulkDiscountId['response']['discountCode']['0']['totalused'];
            $discountBulk = array();
            $discountBulk['eventId'] = $inputArray['eventId'];
            $discountBulk['discountId'] = $offline['bulkDiscountId'];
            $discountBulk['totalused'] =  $bulkDiscountUsed + $offline['quantity'];
            $discountBulk['type'] = 'bulk';
            $updateBulkDiscount = $this->discountHandler->updateDiscountUsage($discountBulk);
            if ($updateBulkDiscount == FALSE) {
                return $updateBulkDiscount;
            }
        }
        $customFieldInput['eventId'] = $offline['eventId'];
        $eventCustomFieldsArr = $this->configureHandler->getCustomFields($customFieldInput);
        if ($eventCustomFieldsArr['status'] == 1 && count($eventCustomFieldsArr['response']['customFields'] > 0)) {
            $eventCustomFields = $eventCustomFieldsArr['response']['customFields'];
        }
        $loop = 0;
        foreach ($attendeeId as $value) {
            foreach ($eventCustomFields as $eventCustomField) {
                if ($eventCustomField['commonfieldid'] > 0) {
                    $custome[$loop]['customFieldId'] = $eventCustomField['id'];
                    $custome[$loop]['commonFieldId'] = $eventCustomField['commonfieldid'];
                    $custome[$loop]['attendeeId'] = $value;
                    if ($eventCustomField['fieldname'] == 'Full Name') {
                        $custome[$loop]['value'] = $offline['name'];
                    } elseif ($eventCustomField['fieldname'] == 'Email Id') {
                        $custome[$loop]['value'] = $offline['email'];
                    } elseif ($eventCustomField['fieldname'] == 'Mobile No') {
                        $custome[$loop]['value'] = $offline['mobile'];
                    } else {
                        $custome[$loop]['value'] = '';
                    }
                }
                $loop++;
            }
            $loop++;
        }
        $finalData = $custome;
        $addAttendeeDetails = $this->attendeeDetailHandler->addMultiple($finalData);
        if ($addAttendeeDetails['status'] == FALSE) {
            $output['status'] = FALSE;
            $output["response"]["messages"][] = ERROR_ADD_ATTENDEE_DETAIL;
            $output['statusCode'] = STATUS_SERVER_ERROR;
            return $output;
        }
        // custom feilds insert  code endded
        // update sold tickets
        $ticketsData = $this->ticketHandler->getTicketName($inputArray);
        $ticketUpdateData['condition']['ticketId'] = $ticketsData['response']['ticketName']['0']['id'];
        //$ticketUpdateData['update']['totalSoldTickets'] = $ticketsData['response']['ticketName']['0']['totalsoldtickets'] + $offline['quantity'];
        $ticketUpdateData['update']['totalSoldTickets'] = '`totalSoldTickets` + ' . $offline['quantity'];
        $ticketUpdateResponse = $this->ticketHandler->ticketIndividualUpdate($ticketUpdateData);
        /* seending sms */
  /*       if (getenv('HTTP_HOST') !== 'menew.com') {
            $smsData = array();
            if ($ticketType == 0) {
                $smsData['eventsignupid'] = $signUpId;
            }
            if ($ticketType == 1) {
                $smsData['eventsignupid'] = $eventSignupId;
            }
            $smsData['mobile'] = $inputArray['mobile'];
            $smsData['eventtitle'] = $offline['eventTitle'];
            $sms = $this->emailHandler->sendSuccessEventsignupsmstoDelegate($smsData);
            if ($sms['status'] == FALSE) {
                return $sms;
            }
        } */
        // sending email and pdfs to orgnizer and deligate
        $inputArray['eventsignupId'] = $eventSignupId;
        $inputArray['deltype'] = 'offlinedelegate';
        $inputArray['orgtype'] = 'offlineOrgnizer';
        if ($ticketType == 1) {
            // sending single email and pdfs to orgnizer and deligate
            $inputArray['eventsignupId'] = $eventSignupId;
            $inputArray['deltype'] = 'offlinedelegate';
            $inputArray['orgtype'] = 'offlineOrgnizer';
            $inputArray['type'] = 'offline';
            $eventSignupdata = $this->confirmationHandler->eventSignupDetailData($inputArray);
            }

        if ($ticketType == 0) {
            // sending single email and multiple pdfs to orgnizer and deligate
            foreach ($eventSignupId as $value) {
                $eventsignupArray['eventsignupId'] = $value;
                $eventsignupDetails[] = $this->eventsignupHandler->getEventsignupDetaildata($eventsignupArray);
            }
            $data = $eventsignupDetails['0']['response']['eventSignupDetailData'];
            $eventsignup['eventsignupid'] = $data['eventsignupDetails']['id'];
            // Checking Whether Email is already sent Or not
            $messages = $this->sentmessageHandler->getEventsignupSentMessages($eventsignup);
            if ($messages['status']) {
                $sentmessages = $messages['response']['sentmessages'];
            } else {
                $sentmessages = array();
            }
            $messageIds = array();
            foreach ($sentmessages as $key => $v) {
                $messageIds[] = $v['type'];
            }
            if (!in_array("offlineNodisplay", $messageIds)) {
                $data['delegateName'] = $inputArray['name'];
                $data['delegateEmail'] = $inputArray['email'];
                $data['delegateMobile'] = $inputArray['mobile'];
                $data['delegateTicketQty'] = $offline['quantity'];
                $data['delegateTicketType'] = $tickets['response']['ticketName']['0']['name'];
                $data['delegateTicketPrice'] = $offline['price'];
                $data['delegateTicketTotal'] = round($offline['amount']);
                $data['uniqueId'] = $uniqueId;
                $data['eventId'] = $inputArray['eventId'];
                $data['startSignupId'] = $startSignupId;
                $data['endSignupId'] = $endSignupId;
                $data['emailType']='OfflinreNoDisplay';
                $eventSignupdata = $this->emailHandler->sendOfflineNoDisplaybooking($data);
            }
        }
        if ($eventSignupdata['status'] == TRUE || $eventSignupdata == TRUE) {
		
		
            $output['status'] = TRUE;
            $output["response"]["messages"][] = SUCCESS_OFFLINE_BOOKING;
            $output['statusCode'] = STATUS_OK;
        } else {
            $output['status'] = FALSE;
            $output["response"]["messages"][] = ERROR_INTERNAL_DB_ERROR;
            $output['statusCode'] = STATUS_SERVER_ERROR;
        }
        return $output;
    }

    /*
     * Function to save the booking data
     *
     * @access	public
     * @param
     *      	All the POST & FILE data that came from custom fields,tickts
     *      	ticketArr - array will be in `array(ticketId => ticketCount)` formet
     * @return	gives the response regards the saving signup data
     */

    public function saveBookingData($inputArray) {

        $this->eventHandler = new Event_handler();
        $this->orderlogHandler = new Orderlog_handler();
        $this->configureHandler = new Configure_handler();
        $this->userHandler = new User_handler();
        $this->ticketHandler = new Ticket_handler();
        $this->currencyHandler = new Currency_handler();
        $this->discountHandler = new Discount_handler();
        $this->eventsignupHandler = new Eventsignup_handler();
        $this->eventsignupTaxHandler = new Eventsignuptax_handler();
        $this->eventsignupticketdetailHandler = new Eventsignup_Ticketdetail_handler();
        $this->attendeeHandler = new Attendee_handler();
        $this->fileHandler = new File_handler();
        $this->attendeeDetailHandler = new Attendeedetail_handler();
    
        $eventId = $request['eventId'] = $inputArray['eventId'];

        $this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('eventId', 'event id', 'required_strict|is_natural_no_zero');
        $this->ci->form_validation->set_rules('orderId', 'order id', 'required_strict');

        if (!$inputArray['isMobile'] && $orderLogSessionData['calculationDetails']['totalPurchaseAmount'] > 0) {
            $this->ci->form_validation->set_rules('paymentGateway', 'payment gateway', 'required_strict');
        }
        $this->ci->form_validation->set_rules('ticketArr', 'ticket Array', 'required_strict|is_array');
        if ($this->ci->form_validation->run() == FALSE) {
            $errorMsg = $this->ci->form_validation->get_errors();
            $output = parent::createResponse(FALSE, $errorMsg['message'], STATUS_BAD_REQUEST);
            return $output;
        }

        /* check whether the event is published or not */
        $request['id'] = $eventId;
        $requestExists['eventId'] = $eventId;
        $eventDataArr = $this->eventHandler->getSimpleEventDetails($requestExists);
        if ($eventDataArr['status']) {
            $eventDetails = $eventDataArr['response']['details'];
            
            if ($eventDetails['status'] == 0) {
                $response['status'] = FALSE;
                $response['statusCode'] = STATUS_BAD_REQUEST;
                $response['response']['messages'][] = ERROR_BOOK_UNPUBLISHED_EVENT;
                return $response;
            }
        }


        $orderId = $inputArray['orderId'];
        $orderLogInput['orderId'] = $orderId;
        $orderLogData = $this->orderlogHandler->getOrderlog($orderLogInput);
        if ($orderLogData['status'] && $orderLogData['response']['total'] == 0) {
            $response['status'] = FALSE;
            $response['statusCode'] = STATUS_NO_DATA;
            $response['response']['messages'][] = ERROR_NO_ORDERLOG_FOUND;
            return $response;
        } elseif ($orderLogData['eventsignup'] > 0 && isset($orderLogData['paymentResponse']) && is_array($orderLogData['paymentResponse']) &&
                ($orderLogData['paymentResponse']['TransactionID'] > 0 || $orderLogData['paymentResponse']['mode'] != '')) {
            $response['status'] = FALSE;
            $response['statusCode'] = STATUS_NO_DATA;
            $response['response']['messages'][] = ERROR_ORDERID_USED;
            return $response;
        }
        $orderLogSessionData = unserialize($orderLogData['response']['orderLogData']['data']);
        $paymentGatewaySelected = $inputArray['paymentGateway'];
        $paymentGatewayIdSelected = $inputArray['paymentGatewayId'];
        $addonArray = $orderLogSessionData['addonArray'];
        $purchaseTotal = $orderLogSessionData['calculationDetails']['totalPurchaseAmount'];
        $addressStr = $inputArray['FullName1'] . '@@' . $inputArray['EmailId1'] . '@@' . $inputArray['MobileNo1'] . '@@' . (isset($inputArray['Address1']) ? $inputArray['Address1'] : '') . '@@' . (isset($inputArray['State1']) ? $inputArray['State1'] : '') . '@@' . (isset($inputArray['City1']) ? $inputArray['City1'] : '') . '@@' . (isset($inputArray['PinCode1']) ? $inputArray['PinCode1'] : '');
        //update gateway selected and address string for selectseat for free update is done below
        
        /* Getting payment gateway data starts here */
        $gatewayData = array();
        $gateWayInput['eventId'] = $orderLogSessionData['eventid'];
        $gateWayInput['paymentGatewayId'] = $paymentGatewayIdSelected;
        $gateWayData = $this->eventHandler->getEventPaymentGateways($gateWayInput);
        if ($gateWayData['status'] && count($gateWayData['response']['gatewayList']) > 0) {
            $gatewayData = $gateWayData['response']['gatewayList'][0];
        } else {
            $response['status'] = FALSE;
            $response['statusCode'] = STATUS_BAD_REQUEST;
            $response['response']['messages'][] = ERROR_EVENT_PAYMENTGATEWAYS;
            return $response;
        }
        $paymentGatewaySelected = strtolower($gatewayData['gatewayName']);
        /* Getting payment gateway data ends here */

        $ticketArr = $inputArray['ticketArr'];
        $totalSelQty = 0;
        foreach ($ticketArr as $ticketId => $ticketQty) {
            if ($ticketQty > 0) {
                $ticketIds[] = $ticketId;
                $totalSelQty += $ticketQty;
            }
        }
        $totalAttendeeCount = $totalSelQty;
        if ($totalAttendeeCount == 0) {
            $response['status'] = FALSE;
            $response['statusCode'] = STATUS_BAD_REQUEST;
            $response['response']['messages'][] = ERROR_SELECT_TICKET_QTY;
            return $response;
        }

        /* Getting the event settings starts here */
        $ticketOptionInput['eventId'] = $eventId;
        $collectMultipleAttendeeInfo = 0;
        $ticketOptionArray = $this->eventHandler->getTicketOptions($ticketOptionInput);
        if ($ticketOptionArray['status'] && $ticketOptionArray['response']['total'] > 0) {
            $collectMultipleAttendeeInfo = $ticketOptionArray['response']['ticketingOptions'][0]['collectmultipleattendeeinfo'];
        }
        /* Getting the event settings ends here */

        /* Getting custom fields of the event starts here */
        $tempEventCustomFields = array();
        $customFieldInput['eventId'] = $eventId;
        $customFieldInput['collectMultipleAttendeeInfo'] = $collectMultipleAttendeeInfo;
        $customFieldInput['ticketDetailInput'] = $ticketIds;
        
        $customFieldInput['eventTimezoneId'] = $eventDetails['timeZoneId'];
        $customFieldInput['disableSessionLockTickets'] = true;
        
        $eventCustomFieldsArr = $this->configureHandler->getCustomFields($customFieldInput);
        if ($eventCustomFieldsArr['status'] && count($eventCustomFieldsArr['response']['customFields'] > 0)) {
            $tempEventCustomFields = $eventCustomFieldsArr['response']['customFields'];
        } else {
            $response['status'] = FALSE;
            $response['statusCode'] = STATUS_NO_DATA;
            $response['response']['messages'][] = ERROR_NO_CUSTOMFIELD_DATA;
            return $response;
        }
        foreach ($tempEventCustomFields as $customFields) {
            if ($customFields['fieldlevel'] == 'event' || ($customFields['ticketid'] != '' && in_array($customFields['ticketid'], $ticketIds))) {
                $eventCustomFields[] = $customFields;
            }
        }
        /* Getting custom fields of the event ends here */

        $formArray = $validationMessages = array();
        $arrayCustomFields = array('checkbox', 'file');
        $linearCustomFields = array('textbox', 'textarea', 'dropdown', 'date', 'radio');
        $validated = true;
        if ($collectMultipleAttendeeInfo == 0) {
            $totalAttendeeCount = 1;
        }
        /* validation the custom fields starts here */
        for ($i = 1; $i <= $totalAttendeeCount; $i++) {
            $formNum = $i;
            if ($collectMultipleAttendeeInfo == 0) {
                $formNum = 1;
            }
            foreach ($eventCustomFields as $eventCustomField) {
                $formCustomFieldName = str_replace(" ", "", preg_replace("/[^A-Za-z0-9\s\s+]/", "", $eventCustomField['fieldname'])); //str_replace(' ', '', $eventCustomField['fieldname']);
                $customFieldId = $eventCustomField['id'];
                $customFieldType = $eventCustomField['fieldtype'];
                $commonFieldIdArr[$customFieldId] = $eventCustomField['commonfieldid'];
                $customfieldTicketId = $eventCustomField['ticketid'];

                if ($eventCustomField['fieldmandatory'] == 1 && isset($inputArray['formTicket' . $formNum]) && ((($customfieldTicketId == '' || $customfieldTicketId == 0) && !in_array($inputArray['formTicket' . $formNum], $addonArray)) || ($customfieldTicketId == $inputArray['formTicket' . $formNum]))) {
                    //echo $formCustomFieldName . $formNum;
                    $this->ci->form_validation->reset_form_rules();
                    $this->ci->form_validation->pass_array($inputArray);
                    $this->ci->form_validation->set_rules($formCustomFieldName . $formNum, $formCustomFieldName, 'required_strict');

                    if (in_array($customFieldType, $linearCustomFields)) {
                        if ($customFieldType == 'radio' || $customFieldType == 'dropdown') {
                            $customFieldValuesInput['customFieldId'] = $customFieldId;
                            $eventCustomFieldsArr = $this->configureHandler->getCustomFieldValues($customFieldValuesInput);
                            if ($eventCustomFieldsArr['status'] && count($eventCustomFieldsArr['response']['fieldValuesInArray']) > 0) {
                                if ($this->ci->form_validation->run() == FALSE) {
                                    $response = $this->ci->form_validation->get_errors();
                                    $validationMessages[$i][] = $response['message'][0];
                                    $validated = false;
                                } else {
                                    $formArray['attendeeData' . $i][$customFieldId][$customFieldType] = $inputArray[$formCustomFieldName . $formNum];
                                }
                            }
                        } else {
                            if ($this->ci->form_validation->run() == FALSE) {
                                $response = $this->ci->form_validation->get_errors();
                                $validationMessages[$i][] = $response['message'][0];
                                $validated = false;
                            } else {
                                $formArray['attendeeData' . $i][$customFieldId][$customFieldType] = $inputArray[$formCustomFieldName . $formNum];
                            }
                        }
                    } elseif (in_array($customFieldType, $arrayCustomFields)) {

                        if (is_array($inputArray[$formCustomFieldName . $formNum]) && count($inputArray[$formCustomFieldName . $formNum]) > 0) {

                            $formArray['attendeeData' . $i][$customFieldId][$customFieldType] = $inputArray[$formCustomFieldName . $formNum];
                            if ($customFieldType == 'file') {
                                $formArray['attendeeData' . $i][$customFieldId][$customFieldType]['formFieldName'] = $formCustomFieldName . $formNum;
                            }
                        } else {
                            if ($customFieldType == 'checkbox') {
                                $customFieldValuesInput['customFieldId'] = $customFieldId;
                                $eventCustomFieldsArr = $this->configureHandler->getCustomFieldValues($customFieldValuesInput);
                                if ($eventCustomFieldsArr['status'] && count($eventCustomFieldsArr['response']['fieldValuesInArray']) > 0) {
                                    if ($this->ci->form_validation->run() == FALSE) {
                                        $response = $this->ci->form_validation->get_errors();
                                        $validationMessages[$i][] = $response['message'][0];
                                        $validated = false;
                                    } else {
                                        $formArray['attendeeData' . $i][$customFieldId][$customFieldType] = $inputArray[$formCustomFieldName . $formNum];
                                    }
                                }
                            } else {
                                if ($this->ci->form_validation->run() == FALSE) {
                                    $response = $this->ci->form_validation->get_errors();
                                    $validationMessages[$i][] = $response['message'][0];
                                    $validated = false;
                                } else {
                                    $formArray['attendeeData' . $i][$customFieldId][$customFieldType] = $inputArray[$formCustomFieldName . $formNum];
                                }
                            }
                        }
                    }
                } else {

                    if (in_array($customFieldType, $linearCustomFields)) {

                        if ($inputArray[$formCustomFieldName . $formNum] != '') {
                            if ($customFieldType == 'radio' || $customFieldType == 'dropdown') {
                                $customFieldValuesInput['customFieldId'] = $customFieldId;
                                $eventCustomFieldsArr = $this->configureHandler->getCustomFieldValues($customFieldValuesInput);
                                if ($eventCustomFieldsArr['status'] && count($eventCustomFieldsArr['response']['fieldValuesInArray']) > 0) {
                                    $formArray['attendeeData' . $i][$customFieldId][$customFieldType] = $inputArray[$formCustomFieldName . $formNum];
                                }
                            } else {
                                $formArray['attendeeData' . $i][$customFieldId][$customFieldType] = $inputArray[$formCustomFieldName . $formNum];
                            }
                        }
                    } elseif (in_array($customFieldType, $arrayCustomFields)) {

                        if (is_array($inputArray[$formCustomFieldName . $formNum]) && count($inputArray[$formCustomFieldName . $formNum]) > 0) {

                            $formArray['attendeeData' . $i][$customFieldId][$customFieldType] = $inputArray[$formCustomFieldName . $formNum];
                            if ($customFieldType == 'file') {
                                $formArray['attendeeData' . $i][$customFieldId][$customFieldType]['formFieldName'] = $formCustomFieldName . $formNum;
                            }
                        }
                    }
                }
            }
        }
        //print_r($formArray);exit;
        if (!$validated && count($eventCustomFields) > 0) {
            foreach ($validationMessages as $attendeeCountId => $fieldArr) {
                if ($collectMultipleAttendeeInfo == 0) {
                    $attendeeCountId = '';
                }
                foreach ($fieldArr as $fieldName) {
                    $messagesArr['messages'][] = $fieldName . " for attendee " . $attendeeCountId;
                }
            }
            $response = array();
            $response['status'] = FALSE;
            $response['statusCode'] = STATUS_BAD_REQUEST;
            $response['response']['messages'][] = $messagesArr['messages'][0];
            return $response;
        }
        /* validation the custom fields ends here */

        // If the user does'nt choose the "paypal" for the "USD" payments
        $currencyCode = $orderLogSessionData['calculationDetails']['currencyCode'];
        if (!$inputArray['isMobile']) {
            if ($currencyCode == 'USD' && $paymentGatewaySelected != 'paypal') {
                $response['status'] = FALSE;
                $response['statusCode'] = STATUS_BAD_REQUEST;
                $response['response']['messages'][] = ERROR_SELECT_PAYPAL;
                return $response;
            }
        }

        /* Check for the session or signup the primary user */
        $userId = $this->ci->customsession->getUserId();
        $signup=0;
        if ($userId == '') {
            $userInput['email'] = $inputArray['EmailId1'];
            $userData = $this->userHandler->getUserData($userInput);
            if ($userData['status'] && $userData['response']['total'] > 0) {
                $primaryUserDataArray = $userDataArray = $userData['response']['userData'];

                $loginInputArray['email'] = $userDataArray['email'];
                $loginInputArray['password'] = $userDataArray['password'];
                $loginInputArray['type'] = 'me';

                $loginInputArray['guestLogin'] = true;
                $loginReturn = $this->userHandler->login($loginInputArray);
                if (!$loginReturn['status']) {
                    $primaryUserDataArray = array();
                    $response['status'] = FALSE;
                    $response['statusCode'] = $loginReturn['statusCode'];
                    $response['response']['messages'] = $loginReturn['response']['messages'];
                    return $response;
                }
            } else {
                $userInput = array();
                $userInput['name'] = $inputArray['FullName1'];
                $userInput['username'] = $userInput['email'] = $inputArray['EmailId1'];
                $userInput['phonenumber'] = $inputArray['MobileNo1'];
                $userInput['address'] = $inputArray['Address1'];
                $userInput['pincode'] = $inputArray['PinCode1'];
                $userInput['company'] = $inputArray['CompanyName1'];
                $userInput['password'] = commonHelpergenerateRandomString(10);

                $userInput['bookingSignup'] = 'Yes';
                $userData = $this->userHandler->signup($userInput);
                if (!$userData) {
                    $response['status'] = FALSE;
                    $response['statusCode'] = $userData['statusCode'];
                    $response['response']['messages'][] = $userData['response']['messages'][0];
                    return $response;
                }
                  $signup=1;
                $primaryUserDataArray = $userDataArray = $userData['response']['userData'];
            }
        } else {
            $userInput['ownerId'] = $userId;
            $userData = $this->userHandler->getUserData($userInput);
            if ($userData['status'] && $userData['response']['total'] > 0) {
                $primaryUserDataArray = $userDataArray = $userData['response']['userData'];
            }

            if ($userDataArray['email'] != $inputArray['EmailId1']) {
                $userInput = array();
                $userInput['name'] = $inputArray['FullName1'];
                $userInput['username'] = $userInput['email'] = $inputArray['EmailId1'];
                $userInput['phonenumber'] = $inputArray['MobileNo1'];
                $userInput['address'] = $inputArray['Address1'];
                $userInput['pincode'] = $inputArray['PinCode1'];
                $userInput['company'] = $inputArray['CompanyName1'];
                $userInput['password'] = commonHelpergenerateRandomString(10);

                $userInput2['email'] = $inputArray['EmailId1'];
                $userData = $this->userHandler->getUserData($userInput2);
                if ($userData['status'] && $userData['response']['total'] > 0) {
                    
                } else {
                    $userData = $this->userHandler->signup($userInput);
                }
                $primaryUserDataArray = $userData['response']['userData'];
            }
        }
        if (is_array($primaryUserDataArray) && count($primaryUserDataArray) > 0) {
            
        } else {
            $response['status'] = FALSE;
            $response['statusCode'] = STATUS_BAD_REQUEST;
            $response['response']['messages'][] = ERROR_NO_USER;
            return $response;
        }
        /* Check for the session or signup the primary user ends here */

        $calculationDetails = $orderLogSessionData['calculationDetails'];
        $transactionTicketTypeString = '';
        $transactionTicketIdString = '';
        $transactionCurrencyId = 3;
        
        $ticketIdsArr = array_keys($ticketArr);
        $eventTicketListInput['ticketIds'] = $ticketIdsArr;
        $eventTicketListInput['eventId'] = $eventId;

        $eventTicketListInput['eventTimezoneId'] = $eventDetails['timeZoneId'];;
        $eventTicketListInput['disableSessionLockTickets'] = true;
        $tempCalculateTicket = $this->ticketHandler->getEventTicketList($eventTicketListInput);
        $tempCalculateTicketResponse = $tempCalculateTicket['response']['ticketList'];
        foreach($tempCalculateTicketResponse as $tempData) {
            $ticketDataArray[$tempData['id']] = $tempData;
        }
        $orderLogSessionData['convertedAmount'] = 0;
        
        foreach ($ticketArr as $tktId => $tktQty) {
            if ($tktQty > 0) {
                if (strpos($transactionTicketTypeString, $ticketDataArray[$tktId]['type']) === FALSE) {
                    $transactionTicketTypeString .= $ticketDataArray[$tktId]['type'] . ',';
                }
                $transactionTicketIdString .= $tktId . ',';
            }
            if (!empty($ticketDataArray[$tktId]['currencyCode'])) {
                $transactionCurrencyId = $ticketDataArray[$tktId]['currencyId'];
            }
            $transactionToCurrencyId = $transactionCurrencyId;

            if (!$inputArray['isMobile']) {
                if (($ticketDataArray[$tktId]['currencyCode'] == 'INR' || $ticketDataArray[$tktId]['currencyCode'] == '') && $paymentGatewaySelected == 'paypal' && $orderLogSessionData['convertedAmount'] == 0) {
                    $inputCurrency['currencyCode'] = 'USD';
                    $currencyData = $this->currencyHandler->getCurrencyDetailByCode($inputCurrency);
                    $transactionToCurrencyId = $currencyData['response']['currencyList']['detail']['currencyId'];

                    $totalPurchaseAmount = $calculationDetails['totalPurchaseAmount'];
                    $fromCurrencyCode = "INR";
                    $get = url_get_contents("https://www.google.com/finance/converter?a=" . $totalPurchaseAmount . "&from=" . $fromCurrencyCode . "&to=USD");
                    $get = explode("<span class=bld>", $get);
                    $get = explode("</span>", $get[1]);
                    $converted_amountArr = explode(" ", $get[0]);
                    $convertedAmount = round($converted_amountArr[0], 2);
                    $eventSignupInsert['convertedamount'] = round(($convertedAmount / $totalSelQty), 2);
                    $orderLogSessionData['convertedAmount'] = $convertedAmount;
                }
            }
        }
        /* Insert into `eventsignup` table starts here */
        $eventSignupInsert['userid'] = $userDataArray['id'];
        $eventSignupInsert['eventid'] = $eventId;
        $eventSignupInsert['quantity'] = $totalSelQty;
        $eventSignupInsert['totalamount'] = $calculationDetails['totalPurchaseAmount'];
        $eventSignupInsert['attendeeid'] = 0;
        $eventSignupInsert['transactionstatus'] = 'pending';
        $eventSignupInsert['paymentstatus'] = 'NotVerified';
        
        
        // Save extra Charge Id and Extra Charge Amount
        $extraChargeTotal = 0;
        $extraChargeIdArr = array();
        foreach($calculationDetails['extraCharge'] as $extraChargeArray) {
        
            if(isset($extraChargeArray['id']) && isset($extraChargeArray['totalAmount']) && $extraChargeArray['id'] > 0 && $extraChargeArray['totalAmount']) {
                $extraChargeIdArr[] = $extraChargeArray['id'];
                $extraChargeTotal += $extraChargeArray['totalAmount'];
        }
        }
        if($extraChargeTotal > 0) {
            $eventSignupInsert['eventextrachargeamount'] = $extraChargeTotal;
            $eventSignupInsert['eventextrachargeid'] = implode(',',$extraChargeIdArr);
        }
        
        $eventSignupInsert['paymentGatewayId'] = 0;
        if (!$inputArray['isMobile'] && $calculationDetails['totalPurchaseAmount'] == 0) {
            $eventSignupInsert['paymentGatewayId'] = 1;
        } elseif (!$inputArray['isMobile']) {
            $eventSignupInsert['paymentGatewayId'] = $paymentGatewayIdSelected;
        }

        if (empty($orderLogSessionData['referralcode']) && !empty($orderLogSessionData['promotercode'])) {
            $this->promoterHandler = new Promoter_handler();

            $promoterInput['eventId'] = $eventId;
            $promoterInput['promoterCode'] = $orderLogSessionData['promotercode'];
            $promoterStatus = $this->promoterHandler->checkPromoterCode($promoterInput);

            if ($promoterStatus['status'] && $promoterStatus['response']['total'] > 0) {
                $eventSignupInsert['promotercode'] = $orderLogSessionData['promotercode'];
            }
        }
        if (empty($orderLogSessionData['promotercode']) && empty($orderLogSessionData['referralcode']) && !empty($orderLogSessionData['acode'])) {
            $this->promoterHandler = new Promoter_handler();
            $promoterInput['promoterCode'] = $orderLogSessionData['acode'];
            $promoterInput['type'] = 'global';
            $promoterStatus = $this->promoterHandler->checkPromoterCode($promoterInput);
            if ($promoterStatus['status'] && $promoterStatus['response']['total'] > 0) {
                $eventSignupInsert['promotercode'] = $orderLogSessionData['acode'];
                $eventSignupInsert['bookingtype'] = 'global';
                $orderLogSessionData['addGlobalPoints']=true;
                $orderLogSessionData['globalPromoterId']=$promoterStatus['response']['promoterList'][0]['userid'];
            }
        }
        $eventSignupInsert['transactiontickettype'] = rtrim($transactionTicketTypeString, ',');
        $eventSignupInsert['transactionticketids'] = rtrim($transactionTicketIdString, ',');
        $eventSignupInsert['fromcurrencyid'] = $transactionCurrencyId;
        $eventSignupInsert['tocurrencyid'] = $transactionToCurrencyId;
        $eventSignupInsert['paymentmodeid'] = (isset($inputArray['paymentModeId']) && $inputArray['paymentModeId'] != '') ? $inputArray['paymentModeId'] : 1;
        $eventSignupInsert['discountamount'] = $calculationDetails['totalCodeDiscount'] + $calculationDetails['totalBulkDiscount'];
        $eventSignupInsert['referraldiscountamount'] = $calculationDetails['totalReferralDiscount'];

        $eventSignupInsert['discount'] = $orderLogSessionData['discountcode'];
        $discountCode = ($orderLogSessionData['discountcode'] != '') ? $orderLogSessionData['discountcode'] : '';
        $discountCodeId = '';
        if ($discountCode != '' && strlen($discountCode) > 0) {

            $this->TicketDiscountHandler = new Ticketdiscount_handler();

            $discountInput['eventId'] = $eventId;
            $discountInput['discountCode'] = $discountCode;
            $discountData = $this->discountHandler->getDiscountData($discountInput);
            if ($discountData['status']) {
                $discountDataArr = $discountData['response']['discountList'][0];
                $discountCodeId = $eventSignupInsert['discountcodeid'] = $discountDataArr['id'];
                $usageLimit = $discountDataArr['usagelimit'];
                $alreadyUsed = $discountDataArr['totalused'];

                $ticketDiscountInput[0] = $discountCodeId;
                $discountTicketResponse = $this->TicketDiscountHandler->getTicketDiscountData($ticketDiscountInput);
                $discountTicketArray = $discountTicketResponse['response']['ticketDiscountList'];

                $selectedTktQty = 0;
                if (count($discountTicketArray) > 0) {
                    foreach ($discountTicketArray as $discountTicket) {
                        $selectedTktQty += $ticketArr[$discountTicket['ticketid']];
                    }
                }
            }
        }
        $eventSignupId = 0;
        $eventSignupReturn = $this->eventsignupHandler->add($eventSignupInsert);
        if ($eventSignupReturn['status']) {
            $eventSignupId = $eventSignupReturn['response']['eventSignUpId'];
            
            $uniqueNum = substr($eventId, 1, 4) . $eventSignupId;
            $updateArray['barcodenumber'] = $uniqueNum;
            $updateArray['eventSignUpId'] = $eventSignupId;
            $this->eventsignupHandler->updateEventSignUp($updateArray);
        } else {
            $response['status'] = FALSE;
            $response['statusCode'] = $eventSignupReturn['statusCode'];
            $response['response']['messages'][] = $eventSignupReturn['response']['messages'][0];
            return $response;
        }
        /* Insert into `eventsignup` table ends here */


        /* Insert into `userpoint` table starts here */
        if ($orderLogSessionData['referralcode'] != '') {

            $eventSignupInput['referralCode'] = $orderLogSessionData['referralcode'];
            $referralCodeSignupDetails = $this->eventsignupHandler->getEventsignupDetails($eventSignupInput);
            $referrerUserId = 0;
            if ($referralCodeSignupDetails['status']) {
                $referrerUserId = $referralCodeSignupDetails['response']['eventSignupList'][0]['userid'];
            }

            $userPointInsert['points'] = $orderLogSessionData['calculationDetails']['totalReferralDiscount'];
            $userPointInsert['userId'] = $referrerUserId;
            $userPointInsert['eventSignupId'] = $eventSignupId;
            $userPointHandler = new Userpoint_handler();
            $userPointReturn = $userPointHandler->addUserPoint($userPointInsert); // Need to send mail ****
            $userPointId = $userPointReturn['response']['userPointId'];
        }
        /* Insert into `userpoint` table ends here */

        /* Insert into `eventsignupticketdetails` table starts here */
        $eventSignupTicketInsert['eventsignupid'] = $eventSignupId;
        foreach ($ticketArr as $tktId => $tktQty) {

            $orderlogTicketData = $calculationDetails['ticketsData'][$tktId];

            $eventSignupTicketInsert['ticketid'] = $tktId;
            $eventSignupTicketInsert['ticketquantity'] = $tktQty;
            $ticketData = $ticketDataArray[$tktId];
            $eventSignupTicketInsert['amount'] = $orderlogTicketData['totalAmount'];

            $totalTaxAmount = 0;
            if (is_array($orderlogTicketData['taxes']) && count($orderlogTicketData['taxes']) > 0) {
                
                $eventSignupTaxInputArr = array();
                foreach ($orderlogTicketData['taxes'] as $taxId => $ticketTaxes) {
                    $totalTaxAmount += $ticketTaxes['taxAmount'];

                    // Code to insert the tax data into "eventsignuptax" table starts here
                    $eventSignupTaxInput['eventSignupId'] = $eventSignupId;
                    $eventSignupTaxInput['ticketId'] = $tktId;
                    $eventSignupTaxInput['ticketMappingId'] = $ticketTaxes['taxmappingid'];
                    $eventSignupTaxInput['taxAmount'] = $ticketTaxes['taxAmount'];
                    $eventSignupTaxInputArr[] = $eventSignupTaxInput;
                    //$this->eventsignupTaxHandler->add($eventSignupTaxInput);
                }
                if(count($eventSignupTaxInputArr) > 0) {
                    $this->eventsignupTaxHandler->addArray($eventSignupTaxInputArr);
                }
                    // Code to insert the tax data into "eventsignuptax" table ends here
                }

            $ticketWiseDiscount = 0;
            if ($orderlogTicketData['normalDiscount'] > 0) {
                $ticketWiseDiscount += $orderlogTicketData['normalDiscount'];
            }
            if ($orderlogTicketData['referralDiscount'] > 0) {
                $ticketWiseDiscount += $orderlogTicketData['referralDiscount'];
            }
            if ($orderlogTicketData['bulkDiscount'] > 0) {
                $ticketWiseDiscount += $orderlogTicketData['bulkDiscount'];
            }

            $ticketAmount = ($orderlogTicketData['totalAmount']) + $totalTaxAmount - $ticketWiseDiscount;
            $eventSignupTicketInsert['totalamount'] = ($ticketAmount > 0) ? $ticketAmount : 0;
            $eventSignupTicketInsert['totaltaxamount'] = $totalTaxAmount;
            $eventSignupTicketInsert['discountcode'] = $discountCode;
            $eventSignupTicketInsert['discountcodeid'] = $discountCodeId;
            $eventSignupTicketInsert['discountamount'] = $orderlogTicketData['normalDiscount'];
            $eventSignupTicketInsert['bulkdiscountamount'] = $orderlogTicketData['bulkDiscount'];
            $eventSignupTicketInsert['referraldiscountamount'] = $orderlogTicketData['referralDiscount'];

            $eventSignUpTicketDetailResponse = $this->eventsignupticketdetailHandler->add($eventSignupTicketInsert);
            $eventSignUpTicketDetailId = $eventSignUpTicketDetailResponse['response']['eventSignUpTicketDetailId'];

            /* Insert into `viralticketsale` table starts here */
            if ($orderLogSessionData['referralcode'] != '' && $userPointId > 0) {
                $viralTicketSaleInsert['referrerUserId'] = $referrerUserId;
                $viralTicketSaleInsert['eventSignupTicketDetailId'] = $eventSignUpTicketDetailId;
                $viralTicketSaleInsert['viralTicketSettingId'] = $orderlogTicketData['referralDiscountId'];
                $viralTicketSaleInsert['referrerUserPointId'] = $userPointId;
                $viralTicketSaleInsert['referralCode'] = $orderLogSessionData['referralcode'];

                $viralTicketSaleHandler = new Viralticketsale_handler();
                $viralTicketSaleHandler->addViralTicketSale($viralTicketSaleInsert);
            }
            /* Insert into `viralticketsale` table ends here */

            if ($tktQty > 0) {
                $ticketIds[] = $tktId;
            }
        }
        /* Insert into `eventsignupticketdetails` table ends here */

        unset($inputArray['eventSignupId']);
        unset($inputArray['eventId']);

        $response['status'] = TRUE;
        $response['statusCode'] = STATUS_OK;
        $response['response']['messages'][] = SUCCESS_ATTENDEE_DETAIL_ADDED;
        $k = 1;
        $addonData = $normalTickets = array();
        foreach ($ticketArr as $ticketId => $ticketCount) {
            if (in_array($ticketId, $addonArray)) {
                $addonData[$ticketId] = $ticketCount;
            } else {
                $normalTickets[$ticketId] = $ticketCount;
            }
        }
        $ticketArr = ($normalTickets + $addonData);
        //print_r($ticketArr);exit;
        $attendeeOrder=1;
        $attendeeDetailInsertArr = array();
        foreach ($ticketArr as $ticketId => $ticketCount) {

            for ($cnt = 1; $cnt <= $ticketCount; $cnt++) {
               
                $attendeeInsert['ticketId'] = $ticketId;
                // Insert into `attendee` table

                $attendeeInsert['eventSignupId'] = $eventSignupId;
                $attendeeInsert['primary'] = 0;
                $attendeeInsert['order'] = $attendeeOrder;
                if ($attendeeOrder==1) {
                    $attendeeInsert['primary'] = 1;
                }
                $addAttendeeReturn = $this->attendeeHandler->add($attendeeInsert);
                if ($addAttendeeReturn['status']) {
                    $attendeeId = $addAttendeeReturn['response']['attendeeId'];
                    if ($attendeeOrder==1) {
                        $eventSignupUpdate['eventSignUpId'] = $eventSignupId;
                        $eventSignupUpdate['attendeeid'] = $attendeeId;
                        $eventSignupReturn = $this->eventsignupHandler->updateEventSignUp($eventSignupUpdate);
                    }
                    $attendeeDetail = $formArray['attendeeData' . $k];
                    if ($collectMultipleAttendeeInfo == 1) {
                        $k++;
                    }
                    if (in_array($ticketId, $addonArray)) {
                        foreach ($formArray['attendeeData1'] as $customFieldId => $customFieldArr) {
                            if ($commonFieldIdArr[$customFieldId] > 0) {
                                $attendeeDetail[$customFieldId] = $customFieldArr;
                            }
                        }
                    }
                    // Code to insert attendee details starts here
                    foreach ($attendeeDetail as $customFieldId => $customFieldArr) {
                        $attendeeDetailInsert['customFieldId'] = $customFieldId;
                        foreach ($customFieldArr as $customFieldType => $customFieldValue) {
                            $attendeeDetailInsert['value'] = '';
                            if (in_array($customFieldType, $linearCustomFields)) {

                                $attendeeDetailInsert['value'] = $customFieldValue;
                            } elseif ($customFieldType == 'checkbox') {

                                $checkboxValueString = '';
                                foreach ($customFieldValue as $customFieldVal) {
                                    $checkboxValueString .= $customFieldVal . ',';
                                }
                                $attendeeDetailInsert['value'] = rtrim($checkboxValueString, ',');
                            } elseif ($customFieldType == 'file') {
                                $fileData = array();
                                $fileData['fieldName'] = 'customField'; //$customFieldValue['formFieldName'];
                                $fileData['delegateFileName'] = $customFieldValue['formFieldName'];

                                $customFieldPath = $this->ci->config->item('customfield_path') . $eventId;
                                $fileData['upload_path'] = $this->ci->config->item('file_upload_temp_path') . $customFieldPath;
                                $fileData['allowed_types'] = IMAGE_EXTENTIONS;
                                $fileData['file_name'] = $customFieldValue['name'];
                                $fileData['dbFilePath'] = $customFieldPath . "/";
                                $fileData['dbFileType'] = FILE_TYPE_CUSTOMFIELD;
                                $fileData['folderId'] = $eventId;
                                $fileReturn = $this->fileHandler->doUpload($fileData);
                                if ($fileReturn['status']) {
                                    $attendeeDetailInsert['value'] = $fileReturn['response']['fileId'];
                                }
                            }
                            $attendeeDetailInsert['attendeeId'] = $attendeeId;
                            $attendeeDetailInsert['commonFieldId'] = $commonFieldIdArr[$customFieldId];
                            $attendeeDetailInsertArr[] = $attendeeDetailInsert;
                            }
                        }
                    // Code to insert attendee details ends here
                } else {
                    $response['status'] = FALSE;
                    $response['statusCode'] = STATUS_BAD_REQUEST;
                    $response['response']['messages'] = $addAttendeeReturn['response']['messages'];
                }
                 $attendeeOrder++;
            }
        }
        // Insert into `attendeedetail` table
        if(count($attendeeDetailInsertArr) > 0) {
            
            $addAttendeeDetailReturn = $this->attendeeDetailHandler->addArray($attendeeDetailInsertArr);
            if (!$addAttendeeDetailReturn['status']) {
                $response['status'] = FALSE;
                $response['statusCode'] = STATUS_BAD_REQUEST;
                $response['response']['messages'] = $addAttendeeDetailReturn['response']['messages'];
            }
        }
        
        
        
        $seatingHandler = new Seating_handler();
        $inputLayout['eventid'] = $eventId;
        $response['seatingLayout'] = false;
        $layoutResponse = $seatingHandler->checkLayout($inputLayout);
        if ($layoutResponse['status'] && $layoutResponse['response']['total'] > 0) {
            $response['seatingLayout'] = $layoutResponse['response']['seatingEnabled'];
        } else {
            return $layoutResponse;
        }
        $response['totalPurchaseAmount'] = $orderLogSessionData['calculationDetails']['totalPurchaseAmount'];
        $orderLogSessionData['paymentGatewaySelected'] = $paymentGatewaySelected;
        $orderLogSessionData['addressStr'] = $addressStr;
        $orderLogUpdateInput['condition']['orderId'] = $orderId;
        $orderLogUpdateInput['update']['userId'] = $this->ci->customsession->getUserId();
        $orderLogUpdateInput['update']['eventSignupId'] = $eventSignupId;
        if ($response['totalPurchaseAmount'] == 0) {
            $orderLogSessionData['paymentGatewaySelected'] = '';
            $orderLogSessionData['paymentResponse'] = array(
                'mode' => 'free',
                'MerchantRefNo' => $eventSignupId,
                'PaymentStatus' => SUCCESS_BOOKING,
                'Amount' => $response['totalPurchaseAmount'],
                'ResponseCode' => 0
            );
        }
        $orderLogSessionData['seatingLayout'] = $response['seatingLayout'];
        $orderLogSerialData = serialize($orderLogSessionData);
        $orderLogUpdateInput['update']['data'] = $orderLogSerialData;
        $orderLogUpdateResponse = $this->orderlogHandler->orderLogUpdate($orderLogUpdateInput);
        if ($response['totalPurchaseAmount'] == 0) {
            $this->updateTicketSoldCount($orderLogSessionData, $orderId);
        }
        $response['orderId'] = $orderId;
         $primaryUserDataArray['signup']=$signup;
        $response['userDetails'] = $primaryUserDataArray;
        $response['eventSignupId'] = $eventSignupId;
        $response['addressStr'] = $addressStr;
        if (isset($orderLogUpdateResponse) && !$orderLogUpdateResponse['status']) {
            $return['errorMessage'] = ERROR_ORDERLOG_UPDATED;
            $return['status'] = FALSE;
        }
        return $response;
    }

    /*
     * Function to update the total sold ticket count, discounts quota
     *
     * @access	public
     * @param
     *      	$oldOrderLogData - array of the old orderlog data
     *      	$orderId - id of the order
     * @return	updates the respective counts
     */

    function updateTicketSoldCount($oldOrderLogData, $orderId) {
        $this->ticketHandler = new Ticket_handler();
        $this->discountHandler = new Discount_handler();
        $this->confirmationHandler = new Confirmation_handler();
        $this->TicketDiscountHandler = new Ticketdiscount_handler();
        
        /* updating the soldout count for the tickets starts here */
        $ticketArray = $oldOrderLogData['ticketarray'];
        $ticketIds = array_keys($ticketArray);
        $normarDiscountId = 0;
        $ticketDataInput['eventId'] = $oldOrderLogData['eventid'];
        $ticketDataInput['ticketIds'] = $ticketIds;
        $ticketsData = $this->ticketHandler->getTicketsbyIds($ticketDataInput);
        $ticketDataArr = $ticketsData['response']['ticketdetails'];

        $ticketOrderlogData = $oldOrderLogData['calculationDetails']['ticketsData'];
        
        $isSoldCountUpdates = $oldOrderLogData['soldCountUpdated'];
        if($isSoldCountUpdates) {
            return true;
        } else {
            foreach ($ticketOrderlogData as $ticketId => $ticketData) {
                if (isset($ticketData['normalDiscountId']) && $ticketData['normalDiscountId'] > 0) {
                    $discountIdArr[] = $ticketData['normalDiscountId'];
                    $normalDiscountIds[] = $ticketData['normalDiscountId'];
                }
                if (isset($ticketData['bulkDiscountId']) && $ticketData['bulkDiscountId'] > 0) {
                    $discountIdArr[] = $ticketData['bulkDiscountId'];
                    $bulkDiscountIds[] = $ticketData['bulkDiscountId'];
                }
            }
            $discountTicketIds = array();
            if (count($discountIdArr) > 0) {
                $ticketDiscountInput = $discountIdArr;
                $discountTicketResponse = $this->TicketDiscountHandler->getTicketDiscountData($ticketDiscountInput);
                $discountTicketArray = $discountTicketResponse['response']['ticketDiscountList'];
                foreach ($discountTicketArray as $discountData) {
                    $discountTicketIds[] = $discountData['ticketid'];
                }
            }
    
            $discountArray = array();
            $eventId = $oldOrderLogData['eventid'];
            if (count($discountIdArr) > 0) {
                $discountInput['eventId'] = $oldOrderLogData['eventid'];
                $discountInput['discountId'] = $discountIdArr;
                $discountData = $this->discountHandler->getDiscountData($discountInput);
                $discountArray = commonHelperGetIdArray($discountData['response']['discountList']);
            }
    
            foreach ($discountTicketArray as $discountArr) {
                $discountTickets[$discountArr['discountid']][] = $discountArr['ticketid'];
            }
    
            $totalNormalDiscountsUsed = $totalBulkDiscountsUsed = 0;
            $normalDiscountIdArr = array_unique($normalDiscountIds);
            $bulkDiscountIdArr = array_unique($bulkDiscountIds);
    
            if (count($discountTickets) > 0) { 
                foreach ($discountTickets as $discountId => $discountTktArr) {
    
                    $updateDiscount = array();
                    $totalNormalDiscountsUsed = $totalBulkDiscountsUsed = 0;
    
                    if (in_array($discountId, $normalDiscountIdArr)) {
                        foreach ($discountTktArr as $ticketId) {
                            $totalNormalDiscountsUsed += $ticketArray[$ticketId];
                        }
                        if ($totalNormalDiscountsUsed > 0) {
                            $updateDiscount['eventId'] = $eventId;
                            $updateDiscount['discountId'] = $discountId;
                            $updateDiscount['totalused'] = $discountArray[$discountId]['totalused'] + $totalNormalDiscountsUsed;
                            if ($updateDiscount['totalused'] > $discountArray[$discountId]['usagelimit']) {
                                $updateDiscount['totalused'] = $discountArray[$discountId]['usagelimit'];
                            }
                            $updateDiscount['type'] = 'normal';
                            $discountResponse = $this->discountHandler->updateDiscountUsage($updateDiscount);
                        }
                    }
    
                    if (in_array($discountId, $bulkDiscountIdArr)) {
                        foreach ($discountTktArr as $ticketId) {
                            $totalBulkDiscountsUsed += $ticketArray[$ticketId];
                        }
                        if ($totalBulkDiscountsUsed > 0) {
                            $updateDiscount = array();
                            $updateDiscount['eventId'] = $eventId;
                            $updateDiscount['discountId'] = $discountId;
                            $updateDiscount['totalused'] = $discountArray[$discountId]['totalused'] + $totalBulkDiscountsUsed;
                            $updateDiscount['type'] = 'bulk';
                            $discountResponse = $this->discountHandler->updateDiscountUsage($updateDiscount);
                        }
                    }
                }
            }
    
            foreach ($ticketDataArr as $ticket) {
    
                $updateDiscount['eventId'] = $oldOrderLogData['eventid'];
                $ticketUpdateData['condition']['ticketId'] = $ticket['id'];
                //$ticketUpdateData['update']['totalSoldTickets'] = $ticket['totalsoldtickets'] + $oldOrderLogData['ticketarray'][$ticket['id']];
                $ticketUpdateData['update']['totalSoldTickets'] = '`totalSoldTickets` + ' . $oldOrderLogData['ticketarray'][$ticket['id']];
                $ticketUpdateResponse = $this->ticketHandler->ticketIndividualUpdate($ticketUpdateData);
            }
            /* updating the soldout count for the tickets ends here */
    
            /* Unlocking the tickets starts here */
            $this->sessionlockHandler = new Sessionlock_handler();
            $sessionLockInput['orderId'] = $orderId;
            $this->sessionlockHandler->deleteTicketLock($sessionLockInput);
            /* Unlocking the tickets ends here */
    
    
            /* Update the soldcount flag for the order starts here */
                $oldOrderLogData['soldCountUpdated'] = true;
                $updatedSessData = serialize($oldOrderLogData);
                $orderLogUpdateInput['condition']['orderId'] = $orderId;
                $orderLogUpdateInput['condition']['eventSignupId'] = $eventSignupId;
                $orderLogUpdateInput['update']['data'] = $updatedSessData;
                $this->orderlogHandler = new Orderlog_handler();
                $orderLogUpdateResponse = $this->orderlogHandler->orderLogUpdate($orderLogUpdateInput);
                if (!$orderLogUpdateResponse['status']) {
                    $response['status'] = FALSE;
                    $response['statusCode'] = STATUS_BAD_REQUEST;
                    $response['response']['messages'][] = ERROR_ORDERLOG_UPDATED;
                    return $response;
                }
            /* Update the soldcount flag for the order ends here */
            
            /* Updating the loggedin user "attendee" status starts here*/
//            $userId = $this->ci->customsession->getData('userId');
            $userId = $this->ci->customsession->getUserId();
                $attendeecheck = $this->ci->customsession->getData('isAttendee');
                if ($userId != '' && $attendeecheck == 0) {
                    $userdata['isAttendee'] = 1;
                    $userdata['id'] = $userId;
                    $this->userHandler = new User_handler();
                    
                    $userststatus = $this->userHandler->updateattendeeStatus($userdata);
                    if ($userststatus) {
                        $this->ci->customsession->setData('isAttendee', 1);
                    }
                }
            /* Updating the loggedin user "attendee" status ends here*/
            
            /* updating the eventsignup data and sending mails and messages starts here */
            $userId = $this->ci->customsession->getUserId();
            $eventsignupArray['orderId'] = $orderId;
            $eventsignupArray['userId'] = $userId;
            $eventsignupArray['updateData'] = 1;
            if(isset($oldOrderLogData['paymentResponse']['isSmsEnable'])){
            $eventsignupArray['isSmsEnable'] = $oldOrderLogData['paymentResponse']['isSmsEnable'];
         } 
         if(isset($oldOrderLogData['paymentResponse']['isEmailEnable'])){
             $eventsignupArray['isEmailEnable'] = $oldOrderLogData['paymentResponse']['isEmailEnable'];
         } 
            $this->confirmationHandler->eventSignupDetailData($eventsignupArray);
            /* updating the eventsignup data and sending mails and messages ends here */   
        }



        /* TrueSemantic API call */
        $this->sendDataToTrueSemantic($orderId);
        /* TrueSemantic API call ends here */
        
	}
    public function updateEventPromoCodes($inputArray) {
        require_once (APPPATH . 'handlers/eventpromocodes_handler.php');
        $promocodesHandler=new Eventpromocodes_handler();
        //$input['eventsignupid']=$inputArray['eventsignupid'];
        $updateResponse=$promocodesHandler->update($inputArray);
        //print_r($updateResponse);exit;
    }
    // This function is send data to true semantic 
    function sendDataToTrueSemantic($orderId, $isEvntSignupId=false) {
        if ($this->ci->config->item('tsFeedbackEnable')) {
            /*error_reporting(-1);
            ini_set('display_errors',1);*/
            $orderlogHandler = new Orderlog_handler();
            $eventsignupHandler = new Eventsignup_handler();
            $paymentGatewayHandler = new Paymentgateway_handler();
            $eventHandler = new Event_handler();
            $userHandler = new User_handler();

            if (!$isEvntSignupId) {
                $orderLogInput['orderId'] = $orderId;
                $orderLogData = $orderlogHandler->getOrderlog($orderLogInput);
                $eventSignupId = $orderLogData['response']['orderLogData']['eventsignup'];
            }
            else {
                $eventSignupId =$orderId;
            }

            $eventSignupInput['eventsignupId'] = $eventSignupId;
            $signupData = $eventsignupHandler->getEventsignupDetails($eventSignupInput);
            if ($signupData['status'] && $signupData['response']['total'] > 0) {

            } else {
                $return['errorMessage'] = SOMETHING_WRONG;
                $return['status'] = FALSE;
                return $return;
            }
            $eventSignupData = $signupData['response']['eventSignupList'][0]; 
            /*echo "<pre>";
            print_r($eventSignupData);*/
            $discount = ($eventSignupData['discount']!='X')?$eventSignupData['discount']:"";
            if ($eventSignupData['totalamount']!=0) {
                switch ($eventSignupData['paymentmodeid']) {
                    case "1":
                        $paymentmode = "Card Transaction";
                        break;
                    case "2":
                        $paymentmode = "Cash On Delivery";
                        break;
                    case "3":
                        $paymentmode = "Cheque Transaction";
                        break;
                    case "4":
                        $paymentmode = "Offline Transaction";
                        break;
                    case "5":
                        $paymentmode = "Spot Transaction";
                        break;
                    default:
                        $paymentmode = "Card Transaction";
                        break;
                }
            } 
            else {
                $paymentmode = "Free Registration";
            }

            $paymentGateWayInput['paymentgatewayid'] = $eventSignupData['paymentgatewayid'];
            $paymentDetails = $paymentGatewayHandler->getPaymentGatewayDetailsById($paymentGateWayInput);
            $paymentGateWay = "Offline";
            if ($paymentDetails['status']) {
                $paymentGateWay = ucfirst($paymentDetails['response']['paymentGateways'][0]['name']);
            }
            $eventRequest['eventId'] = $eventSignupData['eventid'];
            $eventDetails = $eventHandler->getEventDetailsTrueSemantic($eventRequest);
            if ($eventDetails['status'] && $eventDetails['response']['total'] != 0){
                $eventData = $eventDetails['response']['details'];
                $eventtype = "Free Event";
                $eveMode = ($eventData['eventMode']=="1")?'Webinar':'Event';
                if ($eventData['registrationType'] == 3) {
                    $eventtype = "InfoOnly ".$eveMode;
                }
                else if ($eventData['registrationType'] == 2) {
                    $eventtype = "Paid ".$eveMode;
                }
                else {
                    $eventtype = "Free ".$eveMode;
                }
            }

            $userInfoObject = $userHandler->getUserSpecificInfo(array('userIds' => $eventSignupData['userid']));
            if ($userInfoObject['status'] && $userInfoObject['response']['total'] != 0){
               $userInfo  =  $userInfoObject['response']['userData'];
            }
            //print_r($userInfo);
            $organiserInfo = $userHandler->getUserSpecificInfo(array('userIds' => $eventData['ownerId']));
            //print_r($organiserInfo);
            $url="http://www.truesemantic.com/ts-api";

            $data=array('ts_api_key'=>'3e93579f21979ec265ad63371b048c12',
                        'ts_survey_id'=>'na8',
                        'ts_survey_source'=>'email_embed',
                        'ts_invite_key'=>$eventSignupId,
                        'fcategory'=>"Booking Success",
                        'trxid'=>$eventSignupId,
                        'bdate'=>$eventSignupData['signupdate'],
                        'revenue'=>$eventSignupData['totalamount'],
                        'pmethod'=>$paymentmode,
                        'pgateway'=>$paymentGateWay,
                        'qty'=>$eventSignupData['quantity'],
                        'ccode'=>$discount,
                        'email'=>$userInfo['email'],
                        'mobile'=>$userInfo['mobile'],
                        'fname'=>$userInfo['name'],
                        'city'=>$userInfo['cityName'],
                        'state'=>$userInfo['stateName'],
                        'country'=>$userInfo['countryName'],
                        'eveid'=>$eventSignupData['eventid'],
                        'evecategory'=>$eventData['categoryName'],
                        'evescategory'=>$eventData['subCategoryName'],
                        'evename'=>$eventData['title'],
                        'eveorganizer'=>$organiserInfo['response']['userData']['name'],
                        'evecity'=>$eventData['location']['cityName'],
                        'evedate'=>date("d M Y, h:i A",strtotime($eventData['startDate'])),
                        'evevenue'=>str_replace("\r\n",",",$eventData['location']['venueName']),
                        'evetype'=>$eventtype,
                        'ucode'=>$eventSignupData['promotercode'],
                    );

            /*print_r($data);
            echo "</pre>";*/
            //exit;
            $ch=curl_init();
            curl_setopt($ch,CURLOPT_URL,$url);
            curl_setopt($ch,CURLOPT_POST,true);
            curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
            curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
            curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
            $res=curl_exec($ch);
            //var_dump($res);
            //$resObj=simplexml_load_string($res);
            //return $resObj;
            curl_close($ch);
            //exit;
        }
    }
    
        
        
    /*
     * Function to save the gateway data of the booking from mobile
     *
     * @access	public
     * @param
     *      	eventSignupId - integer
     *      	orderId - string
     *      	paymentGatewayId - integer
     * @return	gives the response status based on saving the gateway adding
     */

    function saveGatewayData($inputArray) {

        $this->orderlogHandler = new Orderlog_handler();
        $this->eventHandler = new Event_handler();
        $this->eventsignupHandler = new Eventsignup_handler();

        $this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('eventSignupId', 'event signup id', 'required_strict|is_natural_no_zero');
        $this->ci->form_validation->set_rules('orderId', 'order id', 'required_strict');
        $this->ci->form_validation->set_rules('paymentGatewayId', 'payment gateway id', 'required_strict|is_natural_no_zero');

        if ($this->ci->form_validation->run() == FALSE) {
            $errorMsg = $this->ci->form_validation->get_errors();
            $output = parent::createResponse(FALSE, $errorMsg['message'][0], STATUS_BAD_REQUEST);
            return $output;
        }

        $orderId = $inputArray['orderId'];
        $eventSignupId = $inputArray['eventSignupId'];
        $paymentGatewayId = $inputArray['paymentGatewayId'];

        /* Getting order log data starts here */
        $orderLogInput['orderId'] = $orderId;
        $orderLogData = $this->orderlogHandler->getOrderlog($orderLogInput);
        if ($orderLogData['status'] && $orderLogData['response']['total'] == 0) {
            $response['status'] = FALSE;
            $response['statusCode'] = STATUS_NO_DATA;
            $response['response']['messages'][] = ERROR_NO_ORDERLOG_FOUND;
            return $response;
        }
        $orderLogSessionData = unserialize($orderLogData['response']['orderLogData']['data']);
        $currencyCode = $orderLogSessionData['calculationDetails']['currencyCode'];
        /* Getting order log data ends here */

        /* Getting payment gateway data starts here */
        $gatewayData = array();
        $gateWayInput['eventId'] = $orderLogSessionData['eventid'];
        $gateWayInput['paymentGatewayId'] = $paymentGatewayId;
        $gateWayData = $this->eventHandler->getEventPaymentGateways($gateWayInput);
        if ($gateWayData['status'] && count($gateWayData['response']['gatewayList']) > 0) {
            $gatewayData = $gateWayData['response']['gatewayList'][0];
        }
        $paymentGatewaySelected = strtolower($gatewayData['gatewayName']);
        /* Getting payment gateway data ends here */


        // If the user does'nt choose the "paypal" for the "USD" payments
        if ($currencyCode == 'USD' && $paymentGatewaySelected != 'paypal') {
            $response['status'] = FALSE;
            $response['statusCode'] = STATUS_BAD_REQUEST;
            $response['response']['messages'][] = ERROR_SELECT_PAYPAL;
            return $response;
        }

        $transactionToCurrencyId = '';
        if ($currencyCode == 'INR' && $paymentGatewaySelected == 'paypal') {
            $inputCurrency['currencyCode'] = 'USD';
            $this->currencyHandler = new Currency_handler();
            $currencyData = $this->currencyHandler->getCurrencyDetailByCode($inputCurrency);
            $transactionToCurrencyId = $currencyData['response']['currencyList']['detail']['currencyId'];
        }

        $eventSignupUpdate['eventSignUpId'] = $eventSignupId;
        $eventSignupUpdate['paymentGatewayId'] = $paymentGatewayId;
        if ($transactionToCurrencyId > 0) {
            $eventSignupUpdate['toCurrencyId'] = $transactionToCurrencyId;
        }
        $eventSignupReturn = $this->eventsignupHandler->updateEventSignUp($eventSignupUpdate);

        $response = array();
        $response['status'] = FALSE;
        $response['statusCode'] = STATUS_BAD_REQUEST;
        $response['response']['messages'][] = ERROR_PAYMENT_GATEWAY;
        $response['response']['paymentGatewayId'] = $paymentGatewayId;

        if ($eventSignupReturn['status']) {
            $response['status'] = TRUE;
            $response['statusCode'] = STATUS_CREATED;
            $response['response']['messages'] = SUCCESS_GATEWAY_ADDED;
        }
        return $response;
    }

    public function ebsProcessingApi($getVar) {

        $this->orderlogHandler = new Orderlog_handler();
        $this->eventsignupHandler = new Eventsignup_handler();
    
        $loginCheck = $this->loginCheck();
        if (!$loginCheck['status']) {
            return $loginCheck;
        }

        $this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($getVar);
        $this->ci->form_validation->set_rules('orderId', 'order id', 'required_strict');
        $this->ci->form_validation->set_rules('paymentGatewayKey', 'payment gateway key', 'required_strict|is_natural_no_zero');

        if ($this->ci->form_validation->run() == FALSE) {
            $errorMsg = $this->ci->form_validation->get_errors();
            $output = parent::createResponse(FALSE, $errorMsg['message'][0], STATUS_BAD_REQUEST);
            return $output;
        }
        $validOrder = $this->orderLogValidation($getVar);
        $orderId = $getVar['orderId'];
        if (!$validOrder['status']) {
            $response['status'] = FALSE;
            $response['statusCode'] = STATUS_BAD_REQUEST;
            $response['response']['messages'][] = $validOrder['errorMessage'];
            return $response;
        }
        $oldOrderLogData = unserialize($validOrder['orderLogData']['data']);

        $ebsSecretKey = $this->ci->config->item('ebs_secret_key');
        /* Getting the payment gateway details from database starts here */
        $paymentGatewayKey = $getVar['paymentGatewayKey'];
        if ($paymentGatewayKey > 0) {
            $gatewayArr = $this->getPaymentgatewayKeys($paymentGatewayKey);
            if (count($gatewayArr) > 0) {
                $ebsSecretKey = $gatewayArr['hashkey'];
                $data['account_id'] = $accountId = $gatewayArr['merchantid'];
            }
        }
        /* Getting the payment gateway details from database ends here */

        $eventSignupData = $validOrder['eventSignupData'];
        $eventSignupId = $eventSignupData['id'];
        /* Getting params from the DR */
            require_once(APPPATH . 'libraries/Rc43.php');

            $DR = preg_replace("/\s/", "+", $getVar['DR']);
            $secret_key = $ebsSecretKey;
            $rc4 = new Crypt_RC4($secret_key);
            $QueryString = base64_decode($DR);
            $rc4->decrypt($QueryString);
            $QueryString = explode('&', $QueryString);
    
            $response = $getSeats = array();
            foreach ($QueryString as $param) {
                $param = explode('=', $param);
                $response[$param[0]] = urldecode($param[1]);
            }   
        /* If the total amount not equals to ordered amount */
        if ((float) $oldOrderLogData['calculationDetails']['totalPurchaseAmount'] != $response['Amount']) {
            $response['status'] = FALSE;
            $response['statusCode'] = STATUS_SERVER_ERROR;
            $response['response']['messages'][] = SOMETHING_WRONG;
            return $response;
        }
        
        $paymentStatus = $response['ResponseCode'];
        if(strcmp($paymentStatus,0) == 0) {
        
        } else {
            $response['status'] = FALSE;
            $response['statusCode'] = STATUS_BAD_REQUEST;
            $response['response']['messages'][] = $response['ResponseMessage'];
            return $response;
        }
        /* If the transaction is un successful from ebs side */
        if ($response['ResponseMessage'] != "Transaction Successful") {
            $response['status'] = FALSE;
            $response['statusCode'] = STATUS_BAD_REQUEST;
            $response['response']['messages'][] = $response['ResponseMessage'];
            return $response;
        }
        $ebsReturnArray['MerchantRefNo'] = $eventSignupId;
        $ebsReturnArray['TransactionID'] = $response['PaymentID'];
        $ebsReturnArray['ResponseCode'] = $response['ResponseCode'];
        $ebsReturnArray['PaymentStatus'] = $response['ResponseMessage'];
        $ebsReturnArray['Amount'] = $response['Amount'];
        $ebsReturnArray['mode'] = 'ebs';
        $oldOrderLogData['paymentResponse'] = $ebsReturnArray;
        $totalSaleQuantity=$oldOrderLogData['calculationDetails']['totalTicketQuantity'];
        $eventId = $oldOrderLogData['eventid'];
        if (isset($oldOrderLogData['seatingLayout']) && $oldOrderLogData['seatingLayout']) {
            $inputES['eventsignupid'] = $eventSignupId;
            $inputES['eventid'] = $eventId;
            $seatingHandler = new Seating_handler();
            $getSeats = $seatingHandler->updateAsBooked($inputES);
        }
        if (isset($getSeats['status']) && $getSeats['status'] && $getSeats['response']['total'] > 0) {
            // foreach ($getSeats['response']['seatsData'] as $value) {
            $oldOrderLogData['seatsData'] = $getSeats['response']['seatsData'];
            // }
        } elseif (isset($getSeats['status']) && !$getSeats['status']) {
            return $getSeats;
        }

        $updatedSessData = serialize($oldOrderLogData);
        $orderLogUpdateInput['condition']['orderId'] = $orderId;
        $orderLogUpdateInput['condition']['eventSignupId'] = $eventSignupId;
        $orderLogUpdateInput['update']['data'] = $updatedSessData;
        $orderLogUpdateResponse = $this->orderlogHandler->orderLogUpdate($orderLogUpdateInput);
        if (!$orderLogUpdateResponse['status']) {
            $response['status'] = FALSE;
            $response['statusCode'] = STATUS_BAD_REQUEST;
            $response['response']['messages'][] = ERROR_ORDERLOG_UPDATED;
            return $response;
        }

        /* Inserting the gateway response starts here */
        $gatewayInsert['eventSignupId'] = $eventSignupId;
        $gatewayInsert['paymentId'] = $response['PaymentID'];
        $gatewayInsert['transactionId'] = $response['TransactionID'];
        $gatewayInsert['statusCode'] = $response['ResponseCode'];
        $gatewayInsert['statusMessage'] = $response['ResponseMessage'];
        $this->eventsignupHandler->saveGatewayPaymentResponse($gatewayInsert);
        /* Inserting the gateway response ends here */
        //print_r(array_keys((array)(json_decode(EVENT_PROMOCODES))));exit;
        if(in_array($eventId, array_keys((array)(json_decode(EVENT_PROMOCODES))))){
            $inputEPromo['eventid']=$eventId;
            $inputEPromo['quantity']=$totalSaleQuantity;
            $inputEPromo['eventsignupid']=$eventSignupId;
            $this->updateEventPromoCodes($inputEPromo);
        }
        $this->updateTicketSoldCount($oldOrderLogData, $orderId);
        $response['status'] = TRUE;
        $response['statusCode'] = STATUS_OK;
        $response['response']['messages'][] = SUCCESS_BOOKING;
        return $response;
    }

    public function mobikwikProcessingApi($postVar) {

        $this->orderlogHandler = new Orderlog_handler();
        $this->eventsignupHandler = new Eventsignup_handler();
    
        $loginCheck = $this->loginCheck();
        if (!$loginCheck['status']) {
            return $loginCheck;
        }

        $this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($postVar);
        $this->ci->form_validation->set_rules('orderid', 'order id', 'required_strict');
        $this->ci->form_validation->set_rules('paymentGatewayKey', 'payment gateway key', 'required_strict|is_natural_no_zero');
        $this->ci->form_validation->set_rules('statuscode', 'status code', 'required_strict');
        $this->ci->form_validation->set_rules('amount', 'amount', 'required_strict');
        $this->ci->form_validation->set_rules('statusmessage', 'status message', 'required_strict');

        if ($this->ci->form_validation->run() == FALSE) {
            $errorMsg = $this->ci->form_validation->get_errors();
            $output = parent::createResponse(FALSE, $errorMsg['message'][0], STATUS_BAD_REQUEST);
            return $output;
        }
        $mobiwikSecretKey = $this->ci->config->item('mobiwikSecretKey');
        $mobiwikMerchantId = $this->ci->config->item('mobiwikMerchantId');
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
        $isChecksumValid = $isMobile = false;
        if (isset($postVar['isMobile'])) {
            $isMobile = $postVar['isMobile'];
        }
        // Added on 16-02-2016
        $orderId = $postVar['orderId'];
        $redirectUrl = commonHelperGetPageUrl('payment', '', '?orderid=' . $orderId);

        $postVarValid['orderId'] = $orderId;
        $validOrder = $this->orderLogValidation($postVarValid);

        if (!$validOrder['status']) {
            $response['status'] = FALSE;
            $response['statusCode'] = STATUS_BAD_REQUEST;
            $response['response']['messages'][] = $validOrder['errorMessage'];
            return $response;
        }
        $oldOrderLogData = unserialize($validOrder['orderLogData']['data']);
        $eventSignupData = $validOrder['eventSignupData'];
        $eventSignupId = $eventSignupData['id'];
        $userId = $eventSignupData['userid'];

        $responseStatusCode = $postVar['statuscode'];
        //$responseOrderId = $orderId;
        $responseOrderId = $eventSignupId;
        $eventId = $validOrder['orderLogData']['eventid'];
        $responseAmount = $postVar['amount'];
        $orderLogAmount = $oldOrderLogData['calculationDetails']['totalPurchaseAmount'];

        if ($orderLogAmount != $responseAmount) {
            $response['status'] = FALSE;
            $response['statusCode'] = STATUS_SERVER_ERROR;
            $response['response']['messages'][] = ERROR_SOMETHING_WENT_WRONG;
        }
        $responseMessage = $postVar['statusmessage'];

        $this->ci->load->library('mobikwik/checksum.php');
        if (!$isMobile) {
            $responseCheckSum = $postVar['checksum'];
            $allParamValue = "'" . $responseStatusCode . "''" . $responseOrderId . "''" . $responseAmount . "''" . $responseMessage . "''" . $mobiwikMerchantId . "'";

            if ($responseCheckSum != '') {
                $isChecksumValid = $this->ci->checksum->verifyChecksum($responseCheckSum, $allParamValue, $mobiwikSecretKey);
            }
        } else {
            $isChecksumValid = true;
        }
        
        $mobikwikReturnArray['MerchantRefNo'] = $eventSignupId;
        $mobikwikReturnArray['ResponseCode'] = $responseStatusCode;
        $mobikwikReturnArray['PaymentStatus'] = $responseMessage;
        $mobikwikReturnArray['mode'] = 'mobikwik';
        if ($isChecksumValid) {
            if ($responseStatusCode == 0) {
                if ($responseOrderId != $eventSignupId) {
                    //echo 'Fraud Detected';
                    $mobikwikReturnArray['TransactionID'] = "";
                    $mobikwikReturnArray['Amount'] = $amount;
                    
                    $response['status'] = FALSE;
                    $response['statusCode'] = STATUS_BAD_REQUEST;
                    $response['response']['messages'][] = ERROR_UNSUCCESSFUL_TRANSACTION;
                    return $response;
                } else {
                    $return = array();
                    // this makes a check status call
                    $return = $this->ci->checksum->verifyTransaction($mobiwikMerchantId, $responseOrderId, $responseAmount, $mobiwikSecretKey);
                    if ($return['flag'] == true) {

                        $mobikwikReturnArray['TransactionID'] = (string) $return['refid'];
                        $mobikwikReturnArray['ResponseCode'] = (string) $return['statuscode'];
                        $mobikwikReturnArray['PaymentStatus'] = (string) $return['statusmessage'];
                        $mobikwikReturnArray['Amount'] = (string) $return['amount'];

                        /* Inserting the gateway response starts here */
                        $gatewayInsert['eventSignupId'] = $eventSignupId;
                        $gatewayInsert['paymentId'] = 0;
                        $gatewayInsert['transactionId'] = $mobikwikReturnArray['TransactionID'];
                        $gatewayInsert['statusCode'] = $mobikwikReturnArray['ResponseCode'];
                        $gatewayInsert['statusMessage'] = $mobikwikReturnArray['PaymentStatus'];
                        $this->eventsignupHandler->saveGatewayPaymentResponse($gatewayInsert);
                        /* Inserting the gateway response ends here */
                    } else {
                        $response['status'] = FALSE;
                        $response['statusCode'] = STATUS_BAD_REQUEST;
                        $response['response']['messages'][] = ERROR_UNSUCCESSFUL_TRANSACTION;
                        if ($return['statusmessage'] != '') {
                            $response['response']['messages'][] = (string) $return['statusmessage'];
                        }
                        return $response;
                    }
                }
            } else {
                $response['status'] = FALSE;
                $response['statusCode'] = STATUS_BAD_REQUEST;
                if ($mobikwikReturnArray['ResponseCode'] == 40 || $mobikwikReturnArray['ResponseCode'] == 80) {
                    $response['response']['transactionCancel'] = true;
                }
                $response['response']['messages'][] = $responseMessage;
                return $response;
            }
        } else {
            $response['status'] = FALSE;
            $response['statusCode'] = STATUS_BAD_REQUEST;
            $response['response']['messages'][] = ERROR_UNSUCCESSFUL_TRANSACTION;
            return $response;
        }
        $oldOrderLogData['paymentResponse'] = $mobikwikReturnArray;
        if (isset($oldOrderLogData['seatingLayout']) && $oldOrderLogData['seatingLayout']) {
            $inputES['eventsignupid'] = $eventSignupId;
            $inputES['eventid'] = $eventId;
            $seatingHandler = new Seating_handler();
            $getSeats = $seatingHandler->updateAsBooked($inputES);
        }
        if (isset($getSeats['status']) && $getSeats['status'] && $getSeats['response']['total'] > 0) {
            // foreach ($getSeats['response']['seatsData'] as $value) {
            $oldOrderLogData['seatsData'] = $getSeats['response']['seatsData'];
            // }
        } elseif (isset($getSeats['status']) && !$getSeats['status']) {
            return $getSeats;
        }
        $updatedSessData = serialize($oldOrderLogData);

        $orderLogUpdateInput['condition']['orderId'] = $orderId;
        $orderLogUpdateInput['condition']['eventSignupId'] = $eventSignupId;

        $orderLogUpdateInput['update']['data'] = $updatedSessData;
        $orderLogUpdateResponse = $this->orderlogHandler->orderLogUpdate($orderLogUpdateInput);
        if (!$orderLogUpdateResponse['status']) {
            $response['status'] = FALSE;
            $response['statusCode'] = STATUS_BAD_REQUEST;
            $response['response']['messages'][] = ERROR_ORDERLOG_UPDATED;
            return $response;
        }
        $totalSaleQuantity=$oldOrderLogData['calculationDetails']['totalTicketQuantity'];
        if(in_array($eventId, array_keys((array)(json_decode(EVENT_PROMOCODES))))){
            $inputEPromo['eventid']=$eventId;
            $inputEPromo['quantity']=$totalSaleQuantity;
            $inputEPromo['eventsignupid']=$eventSignupId;
            $this->updateEventPromoCodes($inputEPromo);
        }
        $return = $this->updateTicketSoldCount($oldOrderLogData, $orderId);
        $response['status'] = TRUE;
        $response['statusCode'] = STATUS_OK;
        $response['response']['messages'][] = SUCCESS_BOOKING;
        return $response;
    }

    public function paypalProcessingApi($getVar, $postVar) {

        $this->orderlogHandler = new Orderlog_handler();
        $this->eventsignupHandler = new Eventsignup_handler();
        $this->eventHandler = new Event_handler();
        
        require_once(APPPATH . 'libraries/paypal/config.php');
        /* Getting the payment gateway details from database starts here */
        $paymentGatewayKey = $getVar['paymentGatewayKey'];
        $mode = $this->ci->config->item('mode');
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

        $orderId = $getVar['orderId'];
        $eventSignupId = $getVar['eventSignup'];
        $redirectUrl = commonHelperGetPageUrl('payment', '', '?orderid=' . $orderId);

        $validOrder = $this->orderLogValidation($getVar);
        if (!$validOrder['status']) {
            $response['status'] = FALSE;
            $response['statusCode'] = STATUS_BAD_REQUEST;
            $response['response']['messages'][] = $validOrder['errorMessage'];
            return $response;
        }
        $orderLogData = $validOrder['orderLogData'];
        $oldOrderLogData = unserialize($orderLogData['data']);
        $totalPurchaseAmount = $oldOrderLogData['calculationDetails']['totalPurchaseAmount'];
        $eventId = $oldOrderLogData['eventid'];
        
        if($oldOrderLogData['calculationDetails']['currencyCode'] != 'USD') {
            /*$get = url_get_contents("https://www.google.com/finance/converter?a=" . $totalPurchaseAmount . "&from=" . $oldOrderLogData['calculationDetails']['currencyCode'] . "&to=USD");
            $get = explode("<span class=bld>", $get);
            $get = explode("</span>", $get[1]);
            $converted_amountArr = explode(" ", $get[0]);
            $totalPurchaseAmount = round($converted_amountArr[0], 2);*/
            $totalPurchaseAmount = $oldOrderLogData['convertedAmount'];
        } else {
            $totalPurchaseAmount = $totalPurchaseAmount;
        }
        
        
        if (!is_numeric($totalPurchaseAmount)) {
            $errorMessage = "Something is wrong with the transaction amount. Please try again.";
            $this->customsession->setData('booking_message', $errorMessage);
            redirect(commonHelperGetPageUrl('payment', '', '?orderid=' . $orderId));
        }

        if (isset($getVar["token"]) && isset($getVar["PayerID"])) {

            $payerId = $getVar["PayerID"];
            $eventSignupData = $validOrder['eventSignupData'];
            $request['eventId'] = $eventId;
            $eventDataArr = $this->eventHandler->getEventDetails($request);
            $eventDataArr = $eventDataArr['response']['details'];
            $eventTitle = $eventDataArr['title'];

            $ItemName = $eventTitle; //Item Name
            $ItemPrice = $totalPurchaseAmount; //Item Price
            $ItemNumber = $eventSignupId; //Item Number
            $ItemDesc = $eventTitle; //Item Number
            $ItemQty = 1; // Item Quantity
            $ItemTotalPrice = $totalPurchaseAmount; //(Item Price x Quantity = Total) Get total amount of product; 
            $TotalTaxAmount = 0;  //Sum of tax for all items in this order. 
            $GrandTotal = $totalPurchaseAmount;
            $PayPalCurrencyCode = 'USD';
            $token = $getVar['token'];
            $padata = '&TOKEN=' . urlencode($token) .
                    '&PAYERID=' . urlencode($payerId) .
                    '&PAYMENTREQUEST_0_PAYMENTACTION=' . urlencode("SALE") .
                    '&L_PAYMENTREQUEST_0_NAME0=' . urlencode($ItemName) .
                    '&L_PAYMENTREQUEST_0_NUMBER0=' . urlencode($ItemNumber) .
                    '&L_PAYMENTREQUEST_0_DESC0=' . urlencode($ItemDesc) .
                    '&L_PAYMENTREQUEST_0_AMT0=' . urlencode($ItemPrice) .
                    '&L_PAYMENTREQUEST_0_QTY0=' . urlencode($ItemQty) .
                    '&PAYMENTREQUEST_0_ITEMAMT=' . urlencode($ItemTotalPrice) .
                    '&PAYMENTREQUEST_0_TAXAMT=' . urlencode($TotalTaxAmount) .
                    '&PAYMENTREQUEST_0_AMT=' . urlencode($GrandTotal) .
                    '&PAYMENTREQUEST_0_CURRENCYCODE=' . urlencode($PayPalCurrencyCode);
            $this->ci->load->library('paypal/paypal.php');
            $httpParsedResponseAr = $this->ci->paypal->PPHttpPost('DoExpressCheckoutPayment', $padata, $PayPalApiUsername, $PayPalApiPassword, $PayPalApiSignature, $PayPalMode);

            if ("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) {
                $paypalTransactionId = urldecode($httpParsedResponseAr["PAYMENTINFO_0_TRANSACTIONID"]);

                $padata = '&TOKEN=' . urlencode($token);
                $httpParsedResponseAr = $this->ci->paypal->PPHttpPost('GetExpressCheckoutDetails', $padata, $PayPalApiUsername, $PayPalApiPassword, $PayPalApiSignature, $PayPalMode);

                if ("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) {
                    $paypalReturnArray['MerchantRefNo'] = $eventSignupId;
                    $paypalReturnArray['TransactionID'] = $paypalTransactionId;
                    $paypalReturnArray['ResponseCode'] = 0; //means success transaction
                    $paypalReturnArray['PaymentStatus'] = SUCCESSFUL_TRANSACTION;
                    $paypalReturnArray['Amount'] = $ItemTotalPrice;
                    if (isset($oldOrderLogData['seatingLayout']) && $oldOrderLogData['seatingLayout']) {
                        $inputES['eventsignupid'] = $eventSignupId;
                        $inputES['eventid'] = $oldOrderLogData['eventid'];
                        $seatingHandler = new Seating_handler();
                        $getSeats = $seatingHandler->updateAsBooked($inputES);
                    }
                    if (isset($getSeats['status']) && $getSeats['status'] && $getSeats['response']['total'] > 0) {
                        // foreach ($getSeats['response']['seatsData'] as $value) {
                        $oldOrderLogData['seatsData'] = $getSeats['response']['seatsData'];
                        // }
                    } elseif (isset($getSeats['status']) && !$getSeats['status']) {
                        return $getSeats;
                    }
                    /* Inserting the gateway response starts here */
                    $gatewayInsert['eventSignupId'] = $eventSignupId;
                    $gatewayInsert['paymentId'] = 0;
                    $gatewayInsert['transactionId'] = $paypalTransactionId;
                    $gatewayInsert['statusCode'] = 0;
                    $gatewayInsert['statusMessage'] = SUCCESSFUL_TRANSACTION;
                    $this->eventsignupHandler->saveGatewayPaymentResponse($gatewayInsert);
                    /* Inserting the gateway response ends here */
                } else {
                    $response['status'] = FALSE;
                    $response['statusCode'] = STATUS_BAD_REQUEST;
                    $response['response']['messages'][] = urldecode($httpParsedResponseAr["L_LONGMESSAGE0"]);
                    return $response;
                }
            } else {
                $response['status'] = FALSE;
                $response['statusCode'] = STATUS_BAD_REQUEST;
                $response['response']['messages'][] = urldecode($httpParsedResponseAr["L_LONGMESSAGE0"]);
                return $response;
            }
            $paypalReturnArray['mode'] = 'paypal';
            $oldOrderLogData['paymentResponse'] = $paypalReturnArray;
            $updatedSessData = serialize($oldOrderLogData);

            $orderLogUpdateInput['condition']['orderId'] = $orderId;
            $orderLogUpdateInput['condition']['eventSignupId'] = $eventSignupId;

            $orderLogUpdateInput['update']['data'] = $updatedSessData;
            $orderLogUpdateResponse = $this->orderlogHandler->orderLogUpdate($orderLogUpdateInput);
            if (!$orderLogUpdateResponse['status']) {
                $response['status'] = FALSE;
                $response['statusCode'] = STATUS_BAD_REQUEST;
                $response['response']['messages'][] = ERROR_ORDERLOG_UPDATED;
                return $response;
            }
            $totalSaleQuantity=$oldOrderLogData['calculationDetails']['totalTicketQuantity'];
            if(in_array($eventId, array_keys((array)(json_decode(EVENT_PROMOCODES))))){
                $inputEPromo['eventid']=$eventId;
                $inputEPromo['quantity']=$totalSaleQuantity;
                $inputEPromo['eventsignupid']=$eventSignupId;
                $this->updateEventPromoCodes($inputEPromo);
            }
            $this->updateTicketSoldCount($oldOrderLogData, $orderId);
        } elseif (isset($getVar["token"])) {

            $paypalReturnArray['MerchantRefNo'] = $eventSignupId;
            $paypalReturnArray['TransactionID'] = '';
            $paypalReturnArray['ResponseCode'] = 1;
            $paypalReturnArray['PaymentStatus'] = 'Paypal cancel transaction';
            $paypalReturnArray['Amount'] = 0;
            $oldOrderLogData['paypal'] = $paypalReturnArray;
            if (isset($oldOrderLogData['seatingLayout']) && $oldOrderLogData['seatingLayout']) {
                $inputES['eventsignupid'] = $eventSignupId;
                $inputES['eventid'] = $eventId;
                $seatingHandler = new Seating_handler();
                $getSeats = $seatingHandler->updateAsBooked($inputES);
            }
            if (isset($getSeats['status']) && $getSeats['status'] && $getSeats['response']['total'] > 0) {
                // foreach ($getSeats['response']['seatsData'] as $value) {
                $oldOrderLogData['seatsData'] = $getSeats['response']['seatsData'];
                // }
            } elseif (isset($getSeats['status']) && !$getSeats['status']) {
                return $getSeats;
            }
            $updatedSessData = serialize($oldOrderLogData);

            $orderLogUpdateInput['condition']['orderId'] = $orderId;
            $orderLogUpdateInput['condition']['eventSignupId'] = $eventSignupId;

            $orderLogUpdateInput['update']['data'] = $updatedSessData;
            $orderLogUpdateResponse = $this->orderlogHandler->orderLogUpdate($orderLogUpdateInput);

            if (!$orderLogUpdateResponse['status']) {
                $response['status'] = FALSE;
                $response['statusCode'] = STATUS_BAD_REQUEST;
                $response['response']['messages'][] = ERROR_ORDERLOG_UPDATED;
                return $response;
            }
            $response['status'] = FALSE;
            $response['statusCode'] = STATUS_BAD_REQUEST;
            $response['response']['messages'][] = ERROR_UNSUCCESSFUL_TRANSACTION;
            return $response;
        }
        $response['status'] = TRUE;
        $response['statusCode'] = STATUS_OK;
        $response['response']['messages'][] = SUCCESS_BOOKING;
        return $response;
    }

    public function paytmProcessingApi($postVar) {
        
        $this->orderlogHandler = new Orderlog_handler();
        $this->eventsignupHandler = new Eventsignup_handler();
        $this->userHandler = new User_handler();
        
        $this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($postVar);
        $this->ci->form_validation->set_rules('orderId', 'order id', 'required_strict');
        $this->ci->form_validation->set_rules('paymentGatewayKey', 'payment gateway key', 'required_strict|is_natural_no_zero');
        $this->ci->form_validation->set_rules('eventSignup', 'event signup id', 'required_strict');
        $this->ci->form_validation->set_rules('email', 'email', 'required_strict|email');
        $this->ci->form_validation->set_rules('mobile', 'mobile', 'required_strict');

        if ($this->ci->form_validation->run() == FALSE) {
            $response = $this->ci->form_validation->get_errors('message');
            $output['status'] = FALSE;
            $output['response']['messages'] = $response['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }

        require_once(APPPATH . 'libraries/paytm/config_paytm.php');
        /* Getting the payment gateway details from database starts here */
        $paymentGatewayKey = $postVar['paymentGatewayKey'];
        //$mode = $this->ci->config->item('mode');

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

        $orderId = $postVar['orderId'];
        $eventSignupId = $postVar['eventSignup'];
        $redirectUrl = commonHelperGetPageUrl('payment', '', '?orderid=' . $orderId);

        $validOrder = $this->orderLogValidation($postVar);
        if (!$validOrder['status']) {
            $response['status'] = FALSE;
            $response['statusCode'] = STATUS_BAD_REQUEST;
            $response['response']['messages'][] = $validOrder['errorMessage'];
            return $response;
        }
        $eventSignupData = $validOrder['eventSignupData'];
        $userId = $eventSignupData['userid'];
        $userDataInput['ownerId'] = $userId;
        $userDataArray = $this->userHandler->getUserData($userDataInput);
        if ($userDataArray['status']) {
            $userData = $userDataArray['response']['userData'];
        }

        $oldOrderLogData = unserialize($validOrder['orderLogData']['data']);

        $eventId = $oldOrderLogData['eventid'];
        $eventGatewayData = array();
        
        require_once(APPPATH . 'handlers/eventpaymentgateway_handler.php');
        $eventPaymentGateway = new EventpaymentGateway_handler();
        $eventGateways = array();
        $gateWayInput['eventId'] = $eventId;
        $gateWayInput['gatewayStatus'] = true;
        $gateWayInput['paymentGatewayId'] = $paymentGatewayKey;
        $gateWayData = $eventPaymentGateway->getPaymentgatewayByEventId($gateWayInput);
        if ($gateWayData['status'] && count($gateWayData['response']['eventPaymentGatewayList']) > 0) {
            $eventGatewayData = $gateWayData['response']['eventPaymentGatewayList'];
        }

        /*$paramListToVerify['MID'] = $postVar['MID'];
        //$paramListToVerify['ORDERID'] = $postVar['ORDERID'];
        $paramListToVerify['ORDERID'] = $eventSignupId;
        $paramListToVerify['TXNAMOUNT'] = $postVar['TXNAMOUNT'];
        $paramListToVerify['CURRENCY'] = $postVar['CURRENCY'];
        $paramListToVerify['TXNID'] = $postVar['TXNID'];
        $paramListToVerify['BANKTXNID'] = $postVar['BANKTXNID'];
        $paramListToVerify['STATUS'] = $postVar['STATUS'];
        $paramListToVerify['RESPCODE'] = $postVar['RESPCODE'];
        $paramListToVerify['RESPMSG'] = $postVar['RESPMSG'];
        $paramListToVerify['TXNDATE'] = $postVar['TXNDATE'];
        $paramListToVerify['GATEWAYNAME'] = $postVar['GATEWAYNAME'];
        $paramListToVerify['BANKNAME'] = $postVar['BANKNAME'];
        $paramListToVerify['PAYMENTMODE'] = $postVar['PAYMENTMODE'];
        $paramListToVerify['CHECKSUMHASH'] = $postVar['CHECKSUMHASH'];

        if(isset($eventGatewayData[0]['extraparams']) && $eventGatewayData[0]['extraparams'] != '') {
            $serializedExtraParam = unserialize($eventGatewayData[0]['extraparams']);
            foreach($serializedExtraParam as $key => $value) {
                $paramListToVerify[$key] = $value;
            }
        }*/
        $paramListToVerify = $postVar['paytmPostParams'];

        $isValidChecksum = verifychecksum_e($paramListToVerify, PAYTM_MERCHANT_KEY, $paramListToVerify['CHECKSUMHASH']);
        /* params that need to update in orderlogs */
        $paytmReturnArray['mode'] = 'paytm';

        $orderLogUpdateInput['condition']['orderId'] = $orderId;
        $orderLogUpdateInput['condition']['eventSignupId'] = $eventSignupId;
        if (!$isValidChecksum) {
            $paytmReturnArray['MerchantRefNo'] = $postVar['ORDERID'];
            $paytmReturnArray['TransactionID'] = '';
            $paytmReturnArray['ResponseCode'] = $postVar['RESPCODE'];
            $paytmReturnArray['PaymentStatus'] = (array_key_exists($postVar['RESPCODE'], $paytmErrCodes)) ? $paytmErrCodes[$postVar['RESPCODE']] : $postVar['RESPMSG'];
            $paytmReturnArray['Amount'] = $postVar['TXNAMOUNT'];

            $oldOrderLogData['paymentResponse'] = $paytmReturnArray;
            $updatedSessData = serialize($oldOrderLogData);

            $orderLogUpdateInput['update']['data'] = $updatedSessData;
            $orderLogUpdateResponse = $this->orderlogHandler->orderLogUpdate($orderLogUpdateInput);
            if (!$orderLogUpdateResponse['status']) {
                $response['status'] = FALSE;
                $response['statusCode'] = STATUS_BAD_REQUEST;
                $response['response']['messages'][] = ERROR_ORDERLOG_UPDATED;
                return $response;
            }
            $response['status'] = FALSE;
            $response['statusCode'] = STATUS_BAD_REQUEST;
            $response['response']['messages'][] = ERROR_UNSUCCESSFUL_TRANSACTION;
            return $response;
        } else {
            $paytmReturnArray['MerchantRefNo'] = $postVar['ORDERID'];
            $paytmReturnArray['PaymentStatus'] = (array_key_exists($postVar['RESPCODE'], $paytmErrCodes)) ? $paytmErrCodes[$postVar['RESPCODE']] : $postVar['RESPMSG'];
            $paytmReturnArray['Amount'] = $postVar['TXNAMOUNT'];

            if ($postVar["STATUS"] == "TXN_SUCCESS") {
                $paytmReturnArray['TransactionID'] = $postVar['TXNID'];
                $paytmReturnArray['ResponseCode'] = 0;
            } else {
                $paytmReturnArray['TransactionID'] = '';
                $paytmReturnArray['ResponseCode'] = $postVar['RESPCODE'];
                
                $response['status'] = FALSE;
                $response['statusCode'] = STATUS_BAD_REQUEST;
                $response['response']['messages'][] = ERROR_UNSUCCESSFUL_TRANSACTION;
                return $response;
            }

            $oldOrderLogData['paymentResponse'] = $paytmReturnArray;
            if (isset($oldOrderLogData['seatingLayout']) && $oldOrderLogData['seatingLayout']) {
                $inputES['eventsignupid'] = $eventSignupId;
                $inputES['eventid'] = $oldOrderLogData['eventid'];
                $seatingHandler = new Seating_handler();
                $getSeats = $seatingHandler->updateAsBooked($inputES);
            }
            if (isset($getSeats['status']) && $getSeats['status'] && $getSeats['response']['total'] > 0) {
                // foreach ($getSeats['response']['seatsData'] as $value) {
                $oldOrderLogData['seatsData'] = $getSeats['response']['seatsData'];
                // }
            } elseif (isset($getSeats['status']) && !$getSeats['status']) {
                return $getSeats;
            }
            $updatedSessData = serialize($oldOrderLogData);

            $orderLogUpdateInput['update']['data'] = $updatedSessData;
            $orderLogUpdateResponse = $this->orderlogHandler->orderLogUpdate($orderLogUpdateInput);
            if (!$orderLogUpdateResponse['status']) {
                $response['status'] = FALSE;
                $response['statusCode'] = STATUS_BAD_REQUEST;
                $response['response']['messages'][] = ERROR_ORDERLOG_UPDATED;
                return $response;
            }

            /* Inserting the gateway response starts here */
            $gatewayInsert['eventSignupId'] = $eventSignupId;
            $gatewayInsert['paymentId'] = 0;
            $gatewayInsert['transactionId'] = $paytmReturnArray['TransactionID'];
            $gatewayInsert['statusCode'] = $paytmReturnArray['ResponseCode'];
            $gatewayInsert['statusMessage'] = $paytmReturnArray['PaymentStatus'];
            $this->eventsignupHandler->saveGatewayPaymentResponse($gatewayInsert);
            /* Inserting the gateway response ends here */
            $totalSaleQuantity=$oldOrderLogData['calculationDetails']['totalTicketQuantity'];
            if(in_array($eventId, array_keys((array)(json_decode(EVENT_PROMOCODES))))){
                $inputEPromo['eventid']=$eventId;
                $inputEPromo['quantity']=$totalSaleQuantity;
                $inputEPromo['eventsignupid']=$eventSignupId;
                $this->updateEventPromoCodes($inputEPromo);
            }
            $this->updateTicketSoldCount($oldOrderLogData, $orderId);
            $response['status'] = TRUE;
            $response['statusCode'] = STATUS_OK;
            $response['response']['messages'][] = SUCCESS_BOOKING;
            return $response;
        }
    }

    /*
     * Function to get the payment gateway key values
     *
     * @access	public
     * @param
     *      	$paymentGatewayKey - integer
     * @return	returns an array with the gateway credentials
     */

    public function getPaymentgatewayKeys($paymentGatewayKey) {
        
        $this->paymentGatewayHandler = new Paymentgateway_handler();
        $gatewayInput['gatewayId'] = $paymentGatewayKey;
        $gatewayData = $this->paymentGatewayHandler->getPaymentgatewayList($gatewayInput);
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

    /*
     * Function to validage the orderlog and eventsignid after the payment and before confirmation
     *
     * @access	public
     * @param
     *      	eventSignupId - EventSignUp Id
     *      	orderId - Order Id
     * @return	returns TRUE or FALSE based on the validation and update the `orderLog` table
     */

    public function orderLogValidation($getVar) {
        
        $this->orderlogHandler = new Orderlog_handler();
        $this->eventsignupHandler = new Eventsignup_handler();
        
        $orderId = $getVar['orderId'];
        $return['status'] = TRUE;

        $orderLogInput['orderId'] = $orderId;
        $orderLogData = array();
        $orderLogData = $this->orderlogHandler->getOrderlog($orderLogInput);
        if ($orderLogData['status'] && $orderLogData['response']['total'] > 0) {
            
        } else {
            $return['errorMessage'] = SOMETHING_WRONG;
            $return['status'] = FALSE;
            return $return;
        }
        $return['orderLogData'] = $orderLogData['response']['orderLogData'];

        $eventSignupId = $orderLogData['response']['orderLogData']['eventsignup'];
        $eventSignupInput['eventsignupId'] = $eventSignupId;
        $signupData = $this->eventsignupHandler->getEventsignupDetails($eventSignupInput);
        if ($signupData['status'] && $signupData['response']['total'] > 0) {
            
        } else {
            $return['errorMessage'] = SOMETHING_WRONG;
            $return['status'] = FALSE;
            return $return;
        }
        $return['eventSignupData'] = $signupData['response']['eventSignupList'][0];
        return $return;
    }

    public function createMobikwikChecksum($inputArray) {

        $this->orderlogHandler = new Orderlog_handler();
        
        $loginCheck = $this->loginCheck();
        if (!$loginCheck['status']) {
            return $loginCheck;
        }

        $this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('orderId', 'order id', 'required_strict');
        //$this->ci->form_validation->set_rules('paymentGatewayKey', 'payment gateway id', 'required_strict|is_natural_no_zero');

        if ($this->ci->form_validation->run() == FALSE) {
            $errorMsg = $this->ci->form_validation->get_errors();
            $output = parent::createResponse(FALSE, $errorMsg['message'][0], STATUS_BAD_REQUEST);
            return $output;
        }

        $mobile = $this->ci->customsession->getData('userMobile');
        $email = $this->ci->customsession->getData('userEmail');
        $orderId = $inputArray['orderId'];
        $paymentGatewayKey = $inputArray['paymentGatewayKey'];

        $orderLogInput['orderId'] = $inputArray['orderId'];
        $orderLogData = $this->orderlogHandler->getOrderlog($orderLogInput);
        $orderLogSessionData = unserialize($orderLogData['response']['orderLogData']['data']);
        $eventSignupId = $orderLogData['response']['orderLogData']['eventsignup'];
        $txnAmount = $orderLogSessionData['calculationDetails']['totalPurchaseAmount'];

        $orderLogInput['orderId'] = $inputArray['orderId'];
        $orderLogDetails = $this->orderLogValidation($orderLogInput);
        if ($orderLogDetails['status'] && $orderLogDetails['eventSignupData']['paymentgatewayid'] > 0) {
            $paymentGatewayKey = $orderLogDetails['eventSignupData']['paymentgatewayid'];
        }

        if ($eventSignupId == 0) {
            $response['status'] = FALSE;
            $response['statusCode'] = STATUS_INVALID_INPUTS;
            $response['response']['messages'][] = ERROR_NOT_EVENTSIGNUPID;
            return $response;
        }

        if (isset($orderLogSessionData['paymentResponse']) && count($orderLogSessionData['paymentResponse']) > 0) {
            $response['status'] = FALSE;
            $response['statusCode'] = STATUS_NO_DATA;
            $response['response']['messages'][] = ERROR_NO_ORDERLOG_FOUND;
            return $response;
        }


        $data['redirectUrl'] = commonHelperGetPageUrl("payment_mobikwikProcessingPage", "", "?eventSignup=" . $eventSignupId . "&orderId=" . $orderId . "&paymentGatewayKey=" . $paymentGatewayKey);
        $mobiwikSecretKey = $this->ci->config->item('mobiwikSecretKey');
        $mobiwikMerchantId = $this->ci->config->item('mobiwikMerchantId');

        /* Getting the payment gateway details from database starts here */
        if ($paymentGatewayKey > 0) {
            $gatewayArr = $this->getPaymentgatewayKeys($paymentGatewayKey);
            if (count($gatewayArr) > 0) {
                $mobiwikSecretKey = $gatewayArr['hashkey'];
                $mobiwikMerchantId = $gatewayArr['merchantid'];
            }
        }
        /* Getting the payment gateway details from database ends here */
        $this->ci->load->library('mobikwik/checksum.php');

        $all = "'" . $mobile . "''" . $email . "''" . $txnAmount . "''" .
                $orderId . "''" . $data['redirectUrl'] . "''" . $mobiwikMerchantId . "'";
        $checksum = $this->ci->checksum->calculateChecksum($mobiwikSecretKey, $all);
        //return $checksum;
        $output['status'] = TRUE;
        $output['statusCode'] = STATUS_OK;
        $output['response']['checkSum'] = $checksum;
        $output['response']['messages'] = array();
        return $output;
    }

    public function validateMobikwikChecksum($postVar) {

        $loginCheck = $this->loginCheck();
        if (!$loginCheck['status']) {
            return $loginCheck;
        }

        $this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($postVar);
        $this->ci->form_validation->set_rules('orderId', 'order id', 'required_strict');
        //$this->ci->form_validation->set_rules('paymentGatewayKey', 'payment gateway key', 'required_strict|is_natural_no_zero');

        if ($this->ci->form_validation->run() == FALSE) {
            $errorMsg = $this->ci->form_validation->get_errors();
            $output = parent::createResponse(FALSE, $errorMsg['message'][0], STATUS_BAD_REQUEST);
            return $output;
        }

        $mobiwikSecretKey = $this->ci->config->item('mobiwikSecretKey');
        $mobiwikMerchantId = $this->ci->config->item('mobiwikMerchantId');
        /* Getting the payment gateway details from database starts here */

        $paymentGatewayKey = $postVar['paymentGatewayKey'];
        $orderLogInput['orderId'] = $inputArray['orderId'];
        $orderLogDetails = $this->orderLogValidation($orderLogInput);
        if ($orderLogDetails['status'] && $orderLogDetails['eventSignupData']['paymentgatewayid'] > 0) {
            $paymentGatewayKey = $orderLogDetails['eventSignupData']['paymentgatewayid'];
        }

        if ($paymentGatewayKey > 0) {
            $gatewayArr = $this->getPaymentgatewayKeys($paymentGatewayKey);
            if (count($gatewayArr) > 0) {
                $mobiwikSecretKey = $gatewayArr['hashkey'];
                $mobiwikMerchantId = $gatewayArr['merchantid'];
            }
        }
        /* Getting the payment gateway details from database ends here */
        $isChecksumValid = false;

        $orderId = $postVar['orderId'];
        $validOrder = $this->orderLogValidation($postVar);
        if (!$validOrder['status']) {
            $response['status'] = FALSE;
            $response['statusCode'] = STATUS_BAD_REQUEST;
            $response['response']['messages'][] = $validOrder['errorMessage'];
            return $response;
        }

        $responseStatusCode = $postVar['statuscode'];
        $responseOrderId = $orderId;
        $responseAmount = ($postVar['amount'] != '') ? $postVar['amount'] : 0;
        $responseMessage = $postVar['statusmessage'];
        $responseCheckSum = $postVar['checksum'];

        $this->ci->load->library('mobikwik/checksum.php');
        $allParamValue = "'" . $responseStatusCode . "''" . $responseOrderId . "''" . $responseAmount . "''" . $responseMessage . "''" . $mobiwikMerchantId . "'";
        if ($responseCheckSum != '') {
            $isChecksumValid = $this->ci->checksum->verifyChecksum($responseCheckSum, $allParamValue, $mobiwikSecretKey);
        }
        $status = 1;
        if ($isChecksumValid) {
            $status = 0;
        }
        $response['orderId'] = $responseOrderId;
        $response['amount'] = $responseAmount;
        $response['status'] = $status;
        $response['statusMsg'] = $responseMessage;

        $output['status'] = TRUE;
        $output['statusCode'] = STATUS_OK;
        $output['response']['responseData'] = $response;
        $output['response']['messages'] = array();
        return $output;
    }

    /*
     * Function to check for logged in user
     *
     * @access	public
     * @return	json response with status and message
     */

    public function loginCheck() {

        $loginCheck = $this->ci->customsession->loginCheck();

        $output['status'] = TRUE;
        $output['statusCode'] = STATUS_OK;
        if ($loginCheck != 1 && !$loginCheck['status']) {
            $output['status'] = FALSE;
            $output['response']['messages'] = $loginCheck['response']['messages'];
            $output['statusCode'] = STATUS_INVALID_SESSION;
        }
        return $output;
    }

    public function guestBooking($inputArray) {
        
        $this->orderlogHandler = new Orderlog_handler();
        $this->eventsignupHandler = new Eventsignup_handler();
        $this->userHandler = new User_handler();
        $this->eventHandler = new Event_handler();
        $this->currencyHandler = new Currency_handler();
        $this->eventsignupticketdetailHandler = new Eventsignup_Ticketdetail_handler();
        $this->attendeeHandler = new Attendee_handler();
        $this->eventsignupTaxHandler = new Eventsignuptax_handler();
        $this->configureHandler = new Configure_handler();
        $this->attendeeDetailHandler = new Attendeedetail_handler();
        $this->ticketHandler = new Ticket_handler();
        $this->emailHandler = new Email_handler();
        $this->sentmessageHandler = new Sentmessage_handler();
        
        $this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('eventId', 'Event Id', 'required_strict|is_natural_no_zero');
        $this->ci->form_validation->set_rules('ticketId', 'Ticket Id', 'required_strict|is_natural_no_zero');
        $this->ci->form_validation->set_rules('quantity', 'Quantity', 'required_strict|is_natural_no_zero');
        $this->ci->form_validation->set_rules('name', 'Name', 'required_strict');
        $this->ci->form_validation->set_rules('mobile', 'Mobile', 'required_strict|numeric|min_length[10]|max_length[10]');
        $this->ci->form_validation->set_rules('email', 'Email', 'required_strict|valid_email');
        if ($this->ci->form_validation->run() == FALSE) {
            $errorMsg = $this->ci->form_validation->get_errors();
            $output = parent::createResponse(FALSE, $errorMsg['message'], STATUS_BAD_REQUEST);
            return $output;
        }

        $ticketArray[$inputArray['ticketId']] = $inputArray['quantity'];
        $inputArray['ticketArray'] = $ticketArray;
        $inputArray['guestListbooking'] = TRUE;
        $ticketResultArray = $this->eventHandler->getEventTicketCalculation($inputArray);
        if ($ticketResultArray['status'] == FALSE) {
            $output['status'] = FALSE;
            $output["response"]["messages"][] = ERROR_NO_DATA;
            $output['statusCode'] = STATUS_OK;
            return $output;
        }
        $inputCurrency['currencyCode'] = $ticketResultArray['response']['calculationDetails']['currencyCode'];
        if ($inputCurrency['currencyCode'] == '') {
            $currency = array();
            $currency['currencyname'] = 'free';
            $currencyData = $this->currencyHandler->getCurrencyDetailByCode($currency);
        } else {
            $currencyData = $this->currencyHandler->getCurrencyDetailByCode($inputCurrency);
        }
        if ($currencyData['status'] == false && $currencyData['response']['total'] == 0) {
            $output['status'] = TRUE;
            $output['response']['messages'][] = ERROR_NO_CURRENCIES;
            $output['response']['total'] = 0;
            $output['statusCode'] = STATUS_OK;
            return $output;
        }
        $transactionToCurrencyId = $currencyData['response']['currencyList']['detail']['currencyId'];
        $offline = $eventSignupInsert = $eventSignupId = $custome = $attendeeInsert = $attendeeId = $custome = $finalData = $ticketUpdateData = array();
        $offline['amount'] = $ticketResultArray['response']['calculationDetails']['totalPurchaseAmount'];
        if (isset($ticketResultArray['response']['calculationDetails']['extraCharge']['totalAmount'])) {
            $offline['amount'] = $offline['amount'] - $ticketResultArray['response']['calculationDetails']['extraCharge']['totalAmount'];
        }

        $offline['price'] = $ticketResultArray['response']['calculationDetails']['ticketsData'][$inputArray['ticketId']]['ticketPrice'];
        $offline['quantity'] = $ticketResultArray['response']['calculationDetails']['ticketsData'][$inputArray['ticketId']]['selectedQuantity'];
        $offline['totalTicketAmount'] = $ticketResultArray['response']['calculationDetails']['totalTicketAmount'];
        $offline['eventId'] = $inputArray['eventId'];
        $offline['totalBulkDiscount'] = $ticketResultArray['response']['calculationDetails']['totalBulkDiscount'];
        $offline['totaltaxamount'] = $ticketResultArray['response']['calculationDetails']['totalTaxAmount'];
        $offline['totalTicketAmount'] = $ticketResultArray['response']['calculationDetails']['ticketsData'][$inputArray['ticketId']]['totalAmount'];
        $offline['totalTicket']= (($offline['totalTicketAmount'] + $offline['totaltaxamount']));		
		
        $eventId = $inputArray['eventId'];
        $eventTitle = $this->eventHandler->getEventName($eventId);
        if ($eventTitle['status'] == TRUE && $eventTitle['response']['total'] > 0) {
            $offline['eventTitle'] = $eventTitle['response']['eventName'];
        }
        /*  getting payment soucrce id */
        $this->paymentsourceHandler = new Paymentsource_handler();
        $paymentSourceArray = array();
        $paymentSourceArray['paymentSourceName'] = 'meguestbooking';
        $paymentSource = $this->paymentsourceHandler->getPaymentSourceId($paymentSourceArray);
        if ($paymentSource['status'] == TRUE && $paymentSource['response']['total'] > 0) {
            $offline['paymentsourceId'] = $paymentSource['response']['paymentSource'][0]['id'];
        }
        $offline['ticketId'] = $inputArray['ticketId'];
        $offline['userId'] = $inputArray['userId'];
        $offline['name'] = $inputArray['name'];
        $offline['mobile'] = $inputArray['mobile'];
        $offline['email'] = $inputArray['email'];
        $offline['PaymentTransId'] = "Offline";
        $offline['transactionstatus'] = "success";
        $offline['paymentstatus'] = "verified";
        $offline['paymentGateway'] = "EBS";
        $offline['paymentmodeId'] = 4;
        $offline['PaymentStatus'] = "Successful Transaction";
        $offline['transactiontickettype'] = 'paid';
        $offline['fromcurrencyid'] = $transactionToCurrencyId;
        $eventSignupInsert['userid'] = $offline['userId'];
        $eventSignupInsert['eventid'] = $offline['eventId'];
        $eventSignupInsert['totalamount'] = $offline['amount'];
        $eventSignupInsert['attendeeid'] = '';
        $eventSignupInsert['transactionstatus'] = $offline['transactionstatus'];
        $eventSignupInsert['paymentstatus'] = $offline['paymentstatus'];
        $eventSignupInsert['paymentGateway'] = $offline['paymentGateway'];
        $eventSignupInsert['transactiontickettype'] = $offline['transactiontickettype'];
        $eventSignupInsert['transactionticketids'] = $offline['ticketId'];
        $eventSignupInsert['paymenttransactionid'] = $offline['PaymentTransId'];
        $eventSignupInsert['fromcurrencyid'] = $offline['fromcurrencyid'];
        $eventSignupInsert['paymentmodeid'] = $offline['paymentmodeId'];
        $eventSignupInsert['paymentsourceid'] = $offline['paymentsourceId'];
        $uniqueId = md5(time() . $inputArray['eventId'] . $inputArray['userId']);
        if($offline['totalBulkDiscount'] > 0){
        	$offline['amount'] = $offline['amount'] + $offline['totalBulkDiscount'];
        }
        //multiple evenrsignupids inserted 
        for ($i = 1; $i <= $offline['quantity']; $i++) {
            $eventSignupInsert['quantity'] = 1;
            $eventSignupInsert['totalamount'] = round($offline['amount'] / $offline['quantity']);
            $eventSignupInsert['extrafield'] = $uniqueId;
            $eventSignupInsert['extrainfo'] = 'transactionGrouping';
            $eventSignupReturn = $this->eventsignupHandler->add($eventSignupInsert);
            if ($eventSignupReturn['status'] == FALSE) {
                return $eventSignupReturn;
            }
            if ($eventSignupReturn['status']) {
                $eventSignupId[] = $eventSignupReturn['response']['eventSignUpId'];
                $signUpId = $eventSignupId['0'];
            }
        }
        $startSignupId = current($eventSignupId);
        $endSignupId = end($eventSignupId);
         $tax = $ticketResultArray['response']['calculationDetails']['ticketsData'][$inputArray['ticketId']]['taxes'];     
        foreach ($eventSignupId as $val) {
             $offline['totalamount'] = round(($offline['totalTicket'] / $offline['quantity']),2);
            // $offline['bulkDiscountAmount'] = round(($offline['totalBulkDiscount'] / $offline['quantity']),2);
            /* add data into eventsignupticketdetail table */
            $eventSignupTicketInsert['eventsignupid'] = $val;
            $eventSignupTicketInsert['ticketid'] = $offline['ticketId'];
            $eventSignupTicketInsert['ticketquantity'] = 1;
            $eventSignupTicketInsert['amount'] = $offline['price'];
            $eventSignupTicketInsert['totalamount'] = $offline['totalamount'];
            //$eventSignupTicketInsert['bulkdiscountamount'] = $offline['bulkDiscountAmount'];
            $eventSignupTicketInsert['totaltaxamount'] = $offline['totaltaxamount'] / $offline['quantity'];
            $eventSignupTiketData = $this->eventsignupticketdetailHandler->add($eventSignupTicketInsert);
            if ($eventSignupTiketData['status'] == FALSE) {
                return $eventSignupTiketData;
            }
            /* add data into attendeed table */
            $attendeeInsert['eventSignupId'] = $val;
            $attendeeInsert['ticketId'] = $offline['ticketId'];
            $attendeeInsert['primary'] = 1;
            $addAttendeeReturn = $this->attendeeHandler->add($attendeeInsert);
            $attendeeId[] = $addAttendeeReturn['response']['attendeeId'];
            $barCOde = substr($offline['eventId'], 1, 4) . $val;
            $k = 1;
            foreach ($attendeeId as $attendy) {
                $updateEventSignUp['attendeeid'] = $attendy;
                $updateEventSignUp['barcodenumber'] = $barCOde;
                $updateEventSignUp['eventSignUpId'] = $val;
                $k++;
            }
            $updateEventSIgnUpRecords = $this->eventsignupHandler->updateEventSignUp($updateEventSignUp);
              if(isset($tax)){
            foreach ($tax as $taxId => $ticketTaxes) {
                // Code to insert the tax data into "eventsignuptax" table starts here
                $eventSignupTaxInput['eventSignupId'] = $val;
                $eventSignupTaxInput['ticketId'] = $inputArray['ticketId'];
                $eventSignupTaxInput['ticketMappingId'] = $ticketTaxes['taxmappingid'];
                $eventSignupTaxInput['taxAmount'] = $ticketTaxes['taxAmount'] /  $offline['quantity'];
                $this->eventsignupTaxHandler->add($eventSignupTaxInput);
                //Code to insert the tax data into "eventsignuptax" table ends here
            }
            }
        }
        $customFieldInput['eventId'] = $offline['eventId'];
        $eventCustomFieldsArr = $this->configureHandler->getCustomFields($customFieldInput);
        if ($eventCustomFieldsArr['status'] == 1 && count($eventCustomFieldsArr['response']['customFields'] > 0)) {
            $eventCustomFields = $eventCustomFieldsArr['response']['customFields'];
        }
        $loop = 0;
        foreach ($attendeeId as $value) {
            foreach ($eventCustomFields as $eventCustomField) {
                if ($eventCustomField['commonfieldid'] > 0) {
                    $custome[$loop]['customFieldId'] = $eventCustomField['id'];
                    $custome[$loop]['commonFieldId'] = $eventCustomField['commonfieldid'];
                    $custome[$loop]['attendeeId'] = $value;
                    if ($eventCustomField['fieldname'] == 'Full Name') {
                        $custome[$loop]['value'] = $offline['name'];
                    } elseif ($eventCustomField['fieldname'] == 'Email Id') {
                        $custome[$loop]['value'] = $offline['email'];
                    } elseif ($eventCustomField['fieldname'] == 'Mobile No') {
                        $custome[$loop]['value'] = $offline['mobile'];
                    } else {
                        $custome[$loop]['value'] = '';
                    }
                }
                $loop++;
            }
            $loop++;
        }
        $finalData = $custome;
        $addAttendeeDetails = $this->attendeeDetailHandler->addMultiple($finalData);
        if ($addAttendeeDetails['status'] == FALSE) {
            $output['status'] = FALSE;
            $output["response"]["messages"][] = ERROR_ADD_ATTENDEE_DETAIL;
            $output['statusCode'] = STATUS_SERVER_ERROR;
            return $output;
        }
        /* update sold tickets */
        $ticketsData = $this->ticketHandler->getTicketName($inputArray);
        $ticketUpdateData['condition']['ticketId'] = $ticketsData['response']['ticketName']['0']['id'];
        //$ticketUpdateData['update']['totalSoldTickets'] = $ticketsData['response']['ticketName']['0']['totalsoldtickets'] + $offline['quantity'];
        $ticketUpdateData['update']['totalSoldTickets'] = '`totalSoldTickets` + ' . $offline['quantity'];
        $ticketUpdateResponse = $this->ticketHandler->ticketIndividualUpdate($ticketUpdateData);
        if ($ticketUpdateResponse['status'] == FALSE) {
            return $ticketUpdateResponse;
        }
        /* TrueSemantic API call */
        $this->sendDataToTrueSemantic($signUpId, true);
        /* TrueSemantic API call ends here */
           
        /* seending sms */
//        if (getenv('HTTP_HOST') !== 'menew.com') {
//            $smsData = array();
//            $smsData['eventsignupid'] = $signUpId;
//            $smsData['mobile'] = $inputArray['mobile'];
//            $smsData['eventtitle'] = $offline['eventTitle'];
//            $sms = $this->emailHandler->sendSuccessEventsignupsmstoDelegate($smsData);
//            if ($sms['status'] == FALSE) {
//                return $sms;
//            }
//        }

        /* sending email to deligate */
        $inputArray['deltype'] = 'offlinedelegate';
        $inputArray['orgtype'] = 'offlineOrgnizer';
        foreach ($eventSignupId as $value) {
            $eventsignupArray['eventsignupId'] = $value;
            $eventsignupDetails[] = $this->eventsignupHandler->getEventsignupDetaildata($eventsignupArray);
        }
        $data = $eventsignupDetails['0']['response']['eventSignupDetailData'];
        $eventsignup['eventsignupid'] = $data['eventsignupDetails']['id'];
        /* Checking Whether Email is already sent Or not */
        $messages = $this->sentmessageHandler->getEventsignupSentMessages($eventsignup);
        if ($messages['status']) {
            $sentmessages = $messages['response']['sentmessages'];
        } else {
            $sentmessages = array();
        }
        $messageIds = array();
        foreach ($sentmessages as $key => $v) {
            $messageIds[] = $v['type'];
        }
        $tickets = $this->ticketHandler->getTicketName($inputArray);
        if (!in_array("offlineNodisplay", $messageIds)) {
            $data['delegateName'] = $inputArray['name'];
            $data['delegateEmail'] = $inputArray['email'];
            $data['delegateMobile'] = $inputArray['mobile'];
            $data['delegateTicketQty'] = $offline['quantity'];
            $data['delegateTicketType'] = $tickets['response']['ticketName']['0']['name'];
            $data['delegateTicketPrice'] = $offline['price'];
            $data['delegateTicketTotal'] = round($offline['amount']);
            $data['uniqueId'] = $uniqueId;
            $data['eventId'] = $inputArray['eventId'];
            $data['bookingType'] = 'guestBooking';
            $data['email'] = $inputArray['email'];
            $data['userId'] = $inputArray['userId'];
            $data['startSignupId'] = $startSignupId;
            $data['endSignupId'] = $endSignupId;
            $eventSignupdata = $this->emailHandler->sendOfflineNoDisplaybooking($data);
        }
        if ($eventSignupdata['status'] == TRUE) {
            $output['status'] = TRUE;
            $output["response"]["messages"][] = SUCCESS_OFFLINE_BOOKING;
            $output['statusCode'] = STATUS_OK;
        } else {
            $output['status'] = FALSE;
            $output["response"]["messages"][] = ERROR_INTERNAL_DB_ERROR;
            $output['statusCode'] = STATUS_SERVER_ERROR;
        }
        return $output;
    }

}

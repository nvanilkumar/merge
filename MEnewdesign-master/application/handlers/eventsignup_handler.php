<?php

/**
 * Reports related business logic will be defined in this class
 * @package		CodeIgniter
 * @author		Qison  Dev Team
 * @param		
 * @addTicket		
 * @copyright	Copyright (c) 2015, Meraevents.
 * @Version		Version 1.0
 * @Since       Class available since Release Version 1.0
 * @Created     03-08-2015
 * @Last Modified 03-08-2015
 */
require_once (APPPATH . 'handlers/handler.php');
require_once (APPPATH . 'handlers/eventsignupticketdetail_handler.php');
require_once (APPPATH . 'handlers/ticket_handler.php');
require_once (APPPATH . 'handlers/attendee_handler.php');
require_once (APPPATH . 'handlers/attendeedetail_handler.php');
require_once (APPPATH . 'handlers/comment_handler.php');
require_once (APPPATH . 'handlers/eventsignuptax_handler.php');
require_once (APPPATH . 'handlers/taxmapping_handler.php');
require_once (APPPATH . 'handlers/tax_handler.php');
require_once (APPPATH . 'handlers/commonfield_handler.php');
require_once (APPPATH . 'handlers/currency_handler.php');
require_once (APPPATH . 'handlers/configure_handler.php');
require_once (APPPATH . 'handlers/event_handler.php');
require_once (APPPATH . 'handlers/user_handler.php');
require_once (APPPATH . 'handlers/eventsignupticketdetail_handler.php');
require_once (APPPATH . 'handlers/email_handler.php');
require_once (APPPATH . 'handlers/orderlog_handler.php');
require_once (APPPATH . 'handlers/promoter_handler.php');
require_once (APPPATH . 'handlers/refund_handler.php');
require_once (APPPATH . 'handlers/eventextracharge_handler.php');
require_once (APPPATH . 'handlers/salesperson_handler.php');
require_once (APPPATH . 'handlers/file_handler.php');
require_once (APPPATH . 'handlers/paymentgateway_handler.php');

class Eventsignup_handler extends Handler {

    var $ci;
    var $estdHandler;
    var $ticketHandler;
    var $attendeeHandler;
    var $attendeedetailHandler;
    var $commentHandler;
    var $configureHandler;
    var $eventsignupTaxHandler;
    var $taxMappingHandler;
    var $taxHandler;
    var $commonfieldHandler;
    var $currencyHanlder;
    var $eventHandler;
    var $userHandler;
    var $eventsignupticketdetailHandler;
    var $tickettaxHandler;
    var $emailHandler;
    var $orderlogHandler;
    var $promoterHandler;
    var $eventextrachargeHandler;
    var $salespersonHandler;

    //var $refundHandler;
    public function __construct() {
        parent::__construct();
        $this->ci = parent::$CI;
        $this->ci->load->model('Eventsignup_model');
        $this->estdHandler = new Eventsignup_Ticketdetail_handler();
        $this->ticketHandler = new Ticket_handler();
        $this->attendeeHandler = new Attendee_handler();
        $this->attendeedetailHandler = new Attendeedetail_handler();
        $this->commentHandler = new Comment_handler();
        $this->configureHandler = new Configure_handler();
        $this->eventsignupTaxHandler = new Eventsignuptax_handler();
        $this->taxMappingHandler = new Taxmapping_handler();
        $this->taxHandler = new Tax_handler();
        $this->commonfieldHandler = new Commonfield_handler();
        $this->currencyHanlder = new Currency_handler();
        $this->eventHandler = new Event_handler();
        $this->userHandler = new User_handler();
        $this->eventsignupticketdetailHandler = new Eventsignup_Ticketdetail_handler();
        $this->tickettaxHandler = new Tickettax_handler();
        $this->orderlogHandler = new Orderlog_handler();
        // $this->refundHandler=new Refund_handler();
    }
    public function getEventSignupListById($inputArray) {
        $this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('eventsignupids', 'eventsignupids', 'required_strict|is_array');
        //to get data
        $selectES['id'] = $this->ci->Eventsignup_model->id;
        $selectES['eventid'] = $this->ci->Eventsignup_model->eventid;
        $selectES['userid'] = $this->ci->Eventsignup_model->userid;
        $selectES['fromcurrencyid'] = $this->ci->Eventsignup_model->fromcurrencyid;
        $selectES['promotercode'] = $this->ci->Eventsignup_model->promotercode;
        $selectES['tocurrencyid'] = $this->ci->Eventsignup_model->tocurrencyid;
        $selectES['conversionrate'] = $this->ci->Eventsignup_model->conversionrate;
        $selectES['convertedamount'] = $this->ci->Eventsignup_model->convertedamount;
        $selectES['totalamount'] = $this->ci->Eventsignup_model->totalamount;
        $selectES['userpointid'] = $this->ci->Eventsignup_model->userpointid;
        //$selectES['transactioncount'] = 'COUNT('.$this->ci->Eventsignup_model->id.')';
        $whereInsES['id']=$inputArray['eventsignupids'];
        //$groupBy[]=$this->ci->Eventsignup_model->eventid;
        $this->ci->Eventsignup_model->resetVariable();
        $this->ci->Eventsignup_model->setSelect($selectES);
        $this->ci->Eventsignup_model->setWhereIns($whereInsES);
        //$this->ci->Eventsignup_model->setGroupBy($groupBy);
        $selectESResponse = $this->ci->Eventsignup_model->get();

        //echo $this->ci->db->last_query();exit;
        if (count($selectESResponse) == 0) {
            $output['status'] = TRUE;
            $output['statusCode'] = STATUS_OK;
            $output['response']['total'] = 0;
            $output['response']['messages'][] = ERROR_NO_RECORDS;
            return $output;
        }
        $output['status'] = TRUE;
        $output['response']['eventsignupList'] = $selectESResponse;
        $output['response']['total'] = count($selectESResponse);
        $output['statusCode'] = STATUS_OK;
        $output['response']['messages'][]=array();
        return $output;
    }
    public function getSummaryDisplayInfo($input) {
        //validate input array
        $validationResponse = $this->validateGetTransaction($input);
        if (!$validationResponse['status']) {
            return $validationResponse;
        }
        //echo 'in';exit;
        //print_r($input);
        //fetch data from input array
        $eventId = $input['eventid'];
        $ticketId = isset($input['ticketid']) ? $input['ticketid'] : 0;
        $reportType = $input['reporttype'];
        //check valid report type is given
        $report_types = $this->ci->config->item('report_types');
        if (!in_array($input['reporttype'], $report_types)) {
            $reportType = 'summary';
        }

        $transactionType = $input['transactiontype'];
        $page = $input['page'];
        $start = ($page - 1) * REPORTS_DISPLAY_LIMIT;
        $response = $setOrWhere = $where_in_ES = array();
        $groupBy = $findInSet = $notLike = $whereES = $orfindInSet = array();
        $eventSignupIds = $selectESTDResponse = $ticketIds = $selectTicketResponse = $partialEventsignupIds = $ticketDataIdIndexed = $selectAttendeeResponse = $attendeeIds = $selectAttendeedetailResponse = $commentResponse = array();
        $whereES[$this->ci->Eventsignup_model->eventid] = $eventId;
        $whereES[$this->ci->Eventsignup_model->deleted] = 0;
        $whereES[$this->ci->Eventsignup_model->transactionstatus] = 'success';
        //currencies list to disply beside transaction amounts
        $currencyResponse = $this->currencyHanlder->getCurrencyList();
        if ($currencyResponse['status'] && $currencyResponse['response']['total'] > 0) {
            $indexedCurrencyListById = commonHelperGetIdArray($currencyResponse['response']['currencyList'], 'currencyId');
            $indexedCurrencyListByCode = commonHelperGetIdArray($currencyResponse['response']['currencyList'], 'currencyCode');
        } else {
            return $currencyResponse;
        }
        //'promoter' for affiliate marketing,'meraevents' for all me sales(used in sales effort page)
        if ((isset($input['promotercode']) && $input['promotercode'] == 'promoter') || (!isset($input['promotercode']) && $transactionType == 'affiliate')) {
            $where_not_in_ES[$this->ci->Eventsignup_model->promotercode] = array('organizer');
            //$whereES[$this->ci->Eventsignup_model->promotercode . ' != '] = '';
            //$whereES[$this->ci->Eventsignup_model->promotercode . ' != '] = '0';
        } elseif (isset($input['promotercode']) && $input['promotercode'] == 'organizer') {
            $whereES[$this->ci->Eventsignup_model->promotercode] = 'organizer';
        }
        //when promotercode is passed
        elseif (isset($input['promotercode']) && $input['promotercode'] != 'meraevents') {
            $whereES[$this->ci->Eventsignup_model->promotercode] = $input['promotercode'];
            $where_not_in_ES[$this->ci->Eventsignup_model->promotercode] = array('organizer', '', '0');
        }
        //for me sales
        elseif (isset($input['promotercode'])) {
            $where_in_ES[$this->ci->Eventsignup_model->promotercode] = array('', '0');
            //exclude boxoffice from me sales
            $where_not_in_ES[$this->ci->Eventsignup_model->paymentgatewayid] = array('7', '8');
            $where_not_in_ES[$this->ci->Eventsignup_model->paymentmodeid] = array('4');
            //exclude viral transaction
            $whereES[$this->ci->Eventsignup_model->referraldiscountamount] = '0';
        }
        //pass currency id when currencycode is passed in input
        if (isset($input['currencycode'])) {
            $whereES[$this->ci->Eventsignup_model->fromcurrencyid] = $indexedCurrencyListByCode[$input['currencycode']]['currencyId'];
        }
        //exclude all canceled and refund records
        $where_not_in_ES[$this->ci->Eventsignup_model->paymentstatus] = array('Canceled', 'Refunded');

        //when ticketid is passed
        if ($ticketId > 0) {
            $findInSet[$ticketId] = $this->ci->Eventsignup_model->transactionticketids;
        }
        //  $whereES = array();
        //check trans type and set req conditions
        switch ($transactionType) {
            case 'all':
                break;
            case 'card':
                $whereES[$this->ci->Eventsignup_model->paymentmodeid] = 1;
                $whereES[$this->ci->Eventsignup_model->totalamount . " > "] = 0;
                $where_not_in_ES[$this->ci->Eventsignup_model->paymentgatewayid] = array('', 'A1', 0, 7, 8);
                break;
            case 'cod':
                $whereES[$this->ci->Eventsignup_model->paymentmodeid] = 2;
                break;
            case 'free':
                //for 100% discount
                $setOrWhere[$this->ci->Eventsignup_model->totalamount] = 0;
                //to fetch free tickets
                $orfindInSet['free'] = $this->ci->Eventsignup_model->transactiontickettype;
                $where_not_in_ES[$this->ci->Eventsignup_model->paymentmodeid] = 4;
                break;
            case 'offline':
                $whereES[$this->ci->Eventsignup_model->paymentmodeid] = 4;
                // $where_not_in_ES[$this->ci->Eventsignup_model->totalamount] = '0';
                break;
            case 'incomplete':
                $whereES[$this->ci->Eventsignup_model->transactionstatus] = 'pending';
                $groupBy = array($this->ci->Eventsignup_model->userid);
                break;
            case 'boxoffice':
                //$whereES[$this->ci->Eventsignup_model->paymentmodeid] = 5;
                $where_in_ES[$this->ci->Eventsignup_model->paymentgatewayid] = array(7, 8);
                break;
            case 'viral':
                $whereES[$this->ci->Eventsignup_model->referraldiscountamount . " > "] = 0;
                break;
            case 'affiliate':
                $newData = array('', '0');
                if (isset($where_not_in_ES[$this->ci->Eventsignup_model->promotercode])) {
                    $addedData = $where_not_in_ES[$this->ci->Eventsignup_model->promotercode];
                    $newData = array_merge($newData, $addedData);
                }
                //exclude me sales
                $where_not_in_ES[$this->ci->Eventsignup_model->promotercode] = array_unique($newData);
                //$whereES[$this->ci->Eventsignup_model->promotercode . ' != '] = '0';
                //exclude offline sales
                $notLike[$this->ci->Eventsignup_model->promotercode] = 'OFFLINE_';
                break;
            case 'cancel':
                $whereES[$this->ci->Eventsignup_model->paymentstatus] = 'Canceled';
                $where_not_in_ES = array();
                unset($whereES[$this->ci->Eventsignup_model->transactionstatus]);
                break;
            default:
                break;
        }
        //to get total count
        $selectESCount['totalcount'] = 'COUNT( ' . $this->ci->Eventsignup_model->id . ' )';
        $this->ci->Eventsignup_model->setSelect($selectESCount);
        $this->ci->Eventsignup_model->setWhereNotIn($where_not_in_ES);
        $this->ci->Eventsignup_model->setWhere($whereES);
        $this->ci->Eventsignup_model->setOrWhere($setOrWhere, ' or ', ' = ', $orfindInSet);
        $this->ci->Eventsignup_model->setNotLike($notLike);
        $this->ci->Eventsignup_model->setWhereIns($where_in_ES);
        $this->ci->Eventsignup_model->setFindInSet($findInSet);
        $this->ci->Eventsignup_model->setGroupBy($groupBy);
        $this->ci->Eventsignup_model->setRecords(0, 0);
        $seletESCountResponse = $this->ci->Eventsignup_model->get();
        //echo $this->ci->db->last_query();exit;
        //($transactionType == 'incomplete' && count($seletESCountResponse) == 0) ||
        if ($seletESCountResponse[0]['totalcount'] == 0) {
            $output['status'] = TRUE;
            $output['statusCode'] = STATUS_OK;
            $output['response']['total'] = 0;
            $output['response']['messages'][] = ERROR_NO_RECORDS;
            return $output;
        }
        //get event time zone name
        $eventTimeZoneName = $this->eventHandler->getEventTimeZone($eventId);
//        if($eventTimeZoneResponse['status'] && $eventTimeZoneResponse['response']['total']>0){
//            
//        }
        //to get data
        $selectES['id'] = $this->ci->Eventsignup_model->id;
        $selectES['userid'] = $this->ci->Eventsignup_model->userid;
        $selectES['signupdate'] = $this->ci->Eventsignup_model->signupdate;
        $selectES['transactiontickettype'] = $this->ci->Eventsignup_model->transactiontickettype;
        $selectES['paymentstatus'] = $this->ci->Eventsignup_model->paymentstatus;
        $selectES['barcodenumber'] = $this->ci->Eventsignup_model->barcodenumber;
        $selectES['fromcurrencyid'] = $this->ci->Eventsignup_model->fromcurrencyid;
        $selectES['tocurrencyid'] = $this->ci->Eventsignup_model->tocurrencyid;
        $selectES['conversionrate'] = $this->ci->Eventsignup_model->conversionrate;
        $selectES['convertedamount'] = $this->ci->Eventsignup_model->convertedamount;
        $selectES['quantity'] = $this->ci->Eventsignup_model->quantity;
        $selectES['totalamount'] = $this->ci->Eventsignup_model->totalamount;
        $selectES['discountamount'] = $this->ci->Eventsignup_model->discountamount;
        $selectES['referraldiscountamount'] = $this->ci->Eventsignup_model->referraldiscountamount;
        $selectES['eventextrachargeamount'] = $this->ci->Eventsignup_model->eventextrachargeamount;
        $selectES['paymenttransactionid'] = $this->ci->Eventsignup_model->paymenttransactionid;
        if ($transactionType == 'affiliate') {
            $selectES['promotercode'] = $this->ci->Eventsignup_model->promotercode;
        }
        if ($transactionType == 'incomplete') {
            $selectES['failedcount'] = 'COUNT(' . $this->ci->Eventsignup_model->id . ')';
        }
        $this->ci->Eventsignup_model->resetVariable();
        $this->ci->Eventsignup_model->setSelect($selectES);
        $this->ci->Eventsignup_model->setWhereNotIn($where_not_in_ES);
        if ($transactionType != 'incomplete') {
            $this->ci->Eventsignup_model->setRecords(REPORTS_DISPLAY_LIMIT, $start);
        }
        $this->ci->Eventsignup_model->setWhere($whereES);
        $this->ci->Eventsignup_model->setOrWhere($setOrWhere, ' or ', ' = ', $orfindInSet);
        $this->ci->Eventsignup_model->setNotLike($notLike);
        $this->ci->Eventsignup_model->setWhereIns($where_in_ES);
        $this->ci->Eventsignup_model->setFindInSet($findInSet);
        $this->ci->Eventsignup_model->setGroupBy($groupBy);

        $orderBy = array($this->ci->Eventsignup_model->id . ' desc');
        $this->ci->Eventsignup_model->setOrderBy($orderBy);
//        if ($transactionType == 'incomplete') {
//            $inputData['eventid'] = $eventId;
//            $selectESResponse = $this->ci->Eventsignup_model->getIncompleteTransData($inputData);
//        } else {
//            $selectESResponse = $this->ci->Eventsignup_model->get();
//        }
        $selectESResponse = $this->ci->Eventsignup_model->get();
        //}
        //print_r($selectESResponse);exit;
        //echo $this->ci->db->last_query();exit;
        if (count($selectESResponse) == 0) {
            $output['status'] = TRUE;
            $output['statusCode'] = STATUS_OK;
            $output['response']['total'] = 0;
            $output['response']['messages'][] = ERROR_NO_RECORDS;
            return $output;
        }
        //get event tickets
        $inputTkt['eventId'] = $eventId;
        $inputTkt['status'] = 0;
        $selectTicketResponse = $this->ticketHandler->getTicketName($inputTkt);
        if ($selectTicketResponse['status'] && $selectTicketResponse['response']['total'] > 0) {
            $ticketDataIdIndexed = commonHelperGetIdArray($selectTicketResponse['response']['ticketName']);
        } else {
            return $selectTicketResponse;
        }
        //prepare paid,free tickets array for card transactions
        $paidTickets = $freeTickets = array();
        foreach ($ticketDataIdIndexed as $tickets) {
            if ($tickets['type'] == 'paid' || $tickets['type'] == 'donation' || $tickets['type'] == 'addon') {
                $paidTickets[] = $tickets['id'];
            } elseif ($tickets['type'] == 'free') {
                $freeTickets[] = $tickets['id'];
            }
        }

        //for contact details
        $userIdList = array();
        $indexEventSignupIdArray = array();
        foreach ($selectESResponse as $value) {
            $indexEventSignupIdArray[$value['id']] = $value;
            $eventSignupIds[] = $value['id'];
            $userIdList[] = $value['userid'];
            //maintain partial refund esid's
            if (strcmp(strtolower($value['paymentstatus']), 'partialrefund') == 0) {
                $partialEventsignupIds[] = $value['id'];
            }
        }
        //fetch estd data
        if (count($eventSignupIds) > 0) {
            $inputESTD['eventsignupids'] = $eventSignupIds;
            $inputESTD['transactiontype'] = $transactionType;
            if ($ticketId > 0) {
                $inputESTD['ticketids'] = array($ticketId);
            } elseif ($transactionType == 'card' && count($paidTickets) > 0) {
                $inputESTD['ticketids'] = $paidTickets;
            }
//            elseif ($transactionType == 'free' && count($freeTickets) > 0) {
//                //Passing nullify param for free tickets with no ticket selection
//                if ($ticketId == 0) {
//                    $inputESTD['nullify'] = 1;
//                }
//                $inputESTD['ticketids'] = $freeTickets;
//            }
            $selectESTDResponse = $this->estdHandler->getListByEventsignupIds($inputESTD);
        }

        if ($selectESTDResponse['status'] && $selectESTDResponse['response']['total'] > 0) {
            $estdArray = $selectESTDResponse['response']['eventSignupTicketDetailList'];
        } else {
            $output['status'] = TRUE;
            $output['statusCode'] = STATUS_OK;
            $output['response']['total'] = 0;
            $output['response']['messages'][] = ERROR_NO_RECORDS;
            return $output;
        }
        //when ticketid is passed fetch amounts from estd
        if ($ticketId > 0) {
            foreach ($estdArray as $data) {
                $ticketWiseAmounts[$data['eventsignupid']] = $data;
            }
        }
        //fetch partial refunds and deduct from paid amount
        $selectPartialPayments = array();
        if (count($partialEventsignupIds) > 0) {
            $refundHandler = new Refund_handler();
            $inputRefunds['eventsignupids'] = $partialEventsignupIds;
            $selectPartialPayments = $refundHandler->getRefundList($inputRefunds);
        }
        $indexedRefunds = array();
        if (isset($selectPartialPayments['status']) && $selectPartialPayments['status']) {
            if ($selectPartialPayments['response']['total'] > 0) {
                $indexedRefunds = commonHelperGetIdArray($selectPartialPayments['response']['refundList'], 'eventsignupid');
            }
        } elseif (count($selectPartialPayments) > 0) {
            return $selectPartialPayments;
        }
        //print_r($selectESResponse);
        //prepare response array
        $incompleteUsersArray = array();
        foreach ($selectESResponse as $value) {
            $paid = '';
            $response[$value['id']]['id'] = $value['id'];
            //for incompelte reports maintain esid's of users 
            if ($transactionType == 'incomplete') {
                $incompleteUsersArray[$value['userid']][] = $value['id'];
                $response[$value['id']]['failedcount'] = $value['failedcount'];
                $response[$value['id']]['comment'] = '';
            }
            $response[$value['id']]['discount'] = 0;

            $response[$value['id']]['signupDate'] = allTimeFormats(convertTime($value['signupdate'], $eventTimeZoneName, true), 11);
            $response[$value['id']]['quantity'] = $value['quantity'];
            $response[$value['id']]['ticketDetails'] = array();
//            if (strcmp(strtolower($value['paymentstatus']), 'partialrefund') == 0) {
//                $partialEventsignupIds[] = $value['id'];
//            } else {
            if ($transactionType == 'free' || ($ticketId > 0 && in_array($ticketId, $freeTickets))) {
                $paid = 0;
                //Setting discount for free with paid tickets
                $response[$value['id']]['discount'] = $indexedCurrencyListById[$value['fromcurrencyid']]['currencyCode'] . ' ' . round($value['discountamount'] + $value['referraldiscountamount']);
                if ($ticketId > 0) {
                    $response[$value['id']]['discount'] = $indexedCurrencyListById[$value['fromcurrencyid']]['currencyCode'] . ' ' . round($ticketWiseAmounts[$value['id']]['discountamount'] + $ticketWiseAmounts[$value['id']]['referraldiscountamount'] + $ticketWiseAmounts[$value['id']]['bulkdiscountamount'], 2);
                }
            } else {
                $extraCovertedAmount = 0;
                $refundAmt = isset($indexedRefunds[$value['id']]) ? $indexedRefunds[$value['id']] : 0;
                $response[$value['id']]['discount'] = $indexedCurrencyListById[$value['fromcurrencyid']]['currencyCode'] . ' ' . round($value['discountamount'] + $value['referraldiscountamount']);
                //INR with paypal and converted
                if (($value['convertedamount'] > 0 && $value['conversionrate'] > 1)) {
                    $totalAmount = $value['totalamount'];
                    $purchaseTotal = $value['convertedamount'] * $value['quantity'];
                    if ($ticketId > 0) {
                        $totalAmount = $ticketWiseAmounts[$value['id']]['totalamount'];
                        $purchaseTotal = ($ticketWiseAmounts[$value['id']]['totalamount'] * $value['convertedamount'] * $value['quantity']) / $value['totalamount'];
                        $response[$value['id']]['discount'] = $indexedCurrencyListById[$value['fromcurrencyid']]['currencyCode'] . ' ' . round($ticketWiseAmounts[$value['id']]['discountamount'] + $ticketWiseAmounts[$value['id']]['referraldiscountamount'] + $ticketWiseAmounts[$value['id']]['bulkdiscountamount'], 2);
                    } else {
                        if ($value['eventextrachargeamount'] > 0) {
                            $convertedAmount = $value['convertedamount'] * $value['quantity'];
                            //$totalAmount = $value['totalamount'];
                            $echargeAmount = $value['eventextrachargeamount'];
                            $extraCovertedAmount = ($echargeAmount * $convertedAmount) / $totalAmount;
                        }
                    }
                    $paid = $indexedCurrencyListById[$value['tocurrencyid']]['currencyCode'] . ' ' . round(((($purchaseTotal) - $extraCovertedAmount) * $value['conversionrate'] - $refundAmt), 2);
                }
                //INR with paypal 
                elseif ($value['convertedamount'] > 0) {
                    if ($ticketId > 0) {
                        $totalAmount = $ticketWiseAmounts[$value['id']]['totalamount'];
                        $purchaseTotal = ($ticketWiseAmounts[$value['id']]['totalamount'] * $value['convertedamount'] * $value['quantity']) / $value['totalamount'];
                        $response[$value['id']]['discount'] = $indexedCurrencyListById[$value['fromcurrencyid']]['currencyCode'] . ' ' . round($ticketWiseAmounts[$value['id']]['discountamount'] + $ticketWiseAmounts[$value['id']]['referraldiscountamount'] + $ticketWiseAmounts[$value['id']]['bulkdiscountamount'], 2);
                    } else {
                        $purchaseTotal = $value['convertedamount'] * $value['quantity'];
                        if ($value['eventextrachargeamount'] > 0) {
                            //$convertedAmount = $value['convertedamount'] * $value['quantity'];
                            $totalAmount = $value['totalamount'];
                            $echargeAmount = $value['eventextrachargeamount'];
                            $extraCovertedAmount = ($echargeAmount * $purchaseTotal) / $totalAmount;
                        }
                    }
                    $paid = 'USD' . ' ' . round(($purchaseTotal) - $extraCovertedAmount, 2);
                }
                //Other currencies transaction and converted
                elseif ($value['conversionrate'] > 1) {
                    $purchaseTotal = $value['totalamount'] - $value['eventextrachargeamount'];
                    if ($ticketId > 0) {
                        $purchaseTotal = $ticketWiseAmounts[$value['id']]['totalamount'];
                        $response[$value['id']]['discount'] = $indexedCurrencyListById[$value['fromcurrencyid']]['currencyCode'] . ' ' . round($ticketWiseAmounts[$value['id']]['discountamount'] + $ticketWiseAmounts[$value['id']]['referraldiscountamount'] + $ticketWiseAmounts[$value['id']]['bulkdiscountamount'], 2);
                    }
                    $paid = $indexedCurrencyListById[$value['tocurrencyid']]['currencyCode'] . ' ' . round(($purchaseTotal) * $value['conversionrate'] - $indexedRefunds[$value['id']], 2);
                }
                //All currencies transactions
                else {
                    $purchaseTotal = $value['totalamount'] - round($value['eventextrachargeamount']);
                    if ($ticketId > 0) {
                        $purchaseTotal = $ticketWiseAmounts[$value['id']]['totalamount'];
                        $response[$value['id']]['discount'] = $indexedCurrencyListById[$value['fromcurrencyid']]['currencyCode'] . ' ' . round($ticketWiseAmounts[$value['id']]['discountamount'] + $ticketWiseAmounts[$value['id']]['referraldiscountamount'] + $ticketWiseAmounts[$value['id']]['bulkdiscountamount'], 2);
                    }
                    if ($refundAmt > 0 && $value['fromcurrencyid'] != 1) {
                        $refundAmt = 0;
                    }
                    $paid = $indexedCurrencyListById[$value['fromcurrencyid']]['currencyCode'] . ' ' . round($purchaseTotal - $refundAmt,2);
                }
            }
            $response[$value['id']]['paid'] = $paid;
            // }
        }
        //print_r($response);
        //Fetching user related data
        $this->userHandler = new User_handler();
        $userInputs['userIdList'] = array_unique($userIdList); //To pass as an array to the method
        $userData = $this->userHandler->getUserDetails($userInputs);

        if ($userData['status'] && $userData['response']['total'] > 0) {
            $userDataList = commonHelperGetIdArray($userData['response']['userData']);
        } else {
            return $userData;
        }
        //fetch comments of incomplete,cod
        $commentResponse = array();
        if ($transactionType == 'incomplete' || $transactionType == 'cod') {
            $inputArray['eventsignupids'] = $eventSignupIds;
            $inputArray['commenttype'] = 'incomplete';
            $commentResponse = $this->commentHandler->getCommentByEventsignupIds($inputArray);
        }

        //$IndexedCommentArray = array();
        if (count($commentResponse) > 0 && $commentResponse['status'] && $commentResponse['response']['total'] > 0) {
            //$IndexedCommentArray = commonHelperGetIdArray($commentResponse['response']['commentList'], 'eventsignupid');
            foreach ($commentResponse['response']['commentList'] as $comment) {
                $response[$comment['eventsignupid']]['comment'] = $comment['comment'];
            }
        }
//        elseif (count($commentResponse) > 0) {
//            return $commentResponse;
//        }

        $ticketData = array();
        $lastKey = end(array_keys($estdArray));
        //set tickets data in response array
        // getting attendee realted data start
            $inputAttendees['eventsignupids'] = $eventSignupIds;
            $inputAttendees['primary'] = 1;
            $selectAttendeeResponse = $this->attendeeHandler->getListByEventsignupIds($inputAttendees);
           if ($selectAttendeeResponse['status'] && $selectAttendeeResponse['response']['total'] > 0) {
            foreach ($selectAttendeeResponse['response']['attendeeList'] as $value) {
                $attendeeIds[] = $value['id'];
            }
        } else {
            return $selectAttendeeResponse;
        }
        if (count($attendeeIds) > 0) {
            $inputCustomFieldData['attendeeids'] = $attendeeIds;
            $selectAllCustomFieldDataResponse = $this->attendeedetailHandler->getListByAttendeeIds($inputCustomFieldData);
        }
        if ($selectAllCustomFieldDataResponse['status'] && $selectAllCustomFieldDataResponse['response']['total'] > 0) {
            $inputCustomFields['eventId'] = $eventId;
            $inputCustomFields['allfields'] = 1;
            $inputCustomFields['activeCustomField'] = 1;
            $eventCustomFields = $this->configureHandler->getCustomFields($inputCustomFields);
        } else {
            return $selectAllCustomFieldDataResponse;
        }
        if ($eventCustomFields['status'] && $eventCustomFields['response']['total'] > 0) {
            $indexedCustomFieldNames = commonHelperGetIdArray($eventCustomFields['response']['customFields']);
            foreach ($selectAllCustomFieldDataResponse['response']['attendeedetailList'] as $value) {
                $IndexedCustomFieldDataArray[$value['attendeeid']][$indexedCustomFieldNames[$value['customfieldid']]['fieldname']] = $value['value'];
            }
            $responseCustomFields = array();
            $i = 0;
            $attendeesList = $selectAttendeeResponse['response']['attendeeList'];
            foreach ($attendeesList as $key => $value) {
                if ($attendeesList[$key]['eventsignupid'] != $attendeesList[$key + 1]['eventsignupid'] || ($attendeesList[$key]['eventsignupid'] == $attendeesList[$key + 1]['eventsignupid'] && $attendeesList[$key]['ticketid'] != $attendeesList[$key + 1]['ticketid'])) {
                    $i = 0;
                }
                foreach ($indexedCustomFieldNames as $customField) {
                    $responseCustomFields[$value['eventsignupid']][$i][$customField['fieldname']] = $IndexedCustomFieldDataArray[$value['id']][$customField['fieldname']];
                }
                $i++;
            }
        } else {
            return $eventCustomFields;
        }
        foreach ($estdArray as $key => $value) {
            $esId = $value['eventsignupid'];
            if ($value['amount'] == 0) {
                $ticketData[$value['ticketid']]['amount'] = 0;
            } else {
                $ticketData[$value['ticketid']]['amount'] = $indexedCurrencyListById[$indexEventSignupIdArray[$value['eventsignupid']]['fromcurrencyid']]['currencyCode'] . ' ' . $value['amount'];
            }
            $ticketData[$value['ticketid']]['tickettype'] = $ticketDataIdIndexed[$value['ticketid']]['name'];
            $ticketData[$value['ticketid']]['quantity'] = $value['ticketquantity'];
            if ($transactionType == 'affiliate') {
                $ticketData[$value['ticketid']]['promotercode'] = $indexEventSignupIdArray[$value['eventsignupid']]['promotercode'];
            }
            if (isset($estdArray[$key + 1]) && $esId != $estdArray[$key + 1]['eventsignupid']) {
                $response[$value['eventsignupid']]['ticketDetails'] = $ticketData;
                $ticketData = array();
            } elseif ($key == $lastKey) {
                $response[$value['eventsignupid']]['ticketDetails'] = $ticketData;
            }
         $userIdIndex = $indexEventSignupIdArray[$value['eventsignupid']]['userid'];
         foreach($responseCustomFields as $key => $attendData){
            $response[$key]['contactDetails']['name'] = $attendData[0]['Full Name'];
            $response[$key]['contactDetails']['email'] = $attendData[0]['Email Id'];
            $response[$key]['contactDetails']['phone'] = $attendData[0]['Mobile No'];
            $response[$key]['contactDetails']['userid'] = $userDataList[$userIdIndex]['id'];
         }
//            $userIdIndex = $indexEventSignupIdArray[$value['eventsignupid']]['userid'];
//            $response[$value['eventsignupid']]['contactDetails']['name'] = $userDataList[$userIdIndex]['name'];
//            $response[$value['eventsignupid']]['contactDetails']['email'] = $userDataList[$userIdIndex]['email'];
//            $response[$value['eventsignupid']]['contactDetails']['phone'] = $userDataList[$userIdIndex]['mobile'];
//            $response[$value['eventsignupid']]['contactDetails']['userid'] = $userDataList[$userIdIndex]['id'];
        } 
//        foreach ($indexedRefunds as $key => $value) {
//            if (($indexEventSignupIdArray[$key]['convertedamount'] > 0 && $indexEventSignupIdArray[$key]['conversionrate'] > 1)) {
//                $response[$key]['paid'][$indexedCurrencyListById[$indexEventSignupIdArray[$key]['tocurrencyid']]['currencyCode']] = ($indexEventSignupIdArray[$key]['convertedamount'] * $indexEventSignupIdArray[$key]['quantity'] * $indexEventSignupIdArray[$key]['conversionrate']) - $value['totalrefundamount'];
//            }if ($indexEventSignupIdArray[$key]['conversionrate'] > 1) {
//                $response[$key]['paid'][$indexedCurrencyListById[$value['tocurrencyid']]['currencyCode']] = (($value['totalamount'] - $value['eventextrachargeamount']) * $value['conversionrate']);
//            } else {
//                $response[$key]['paid'][$indexedCurrencyListById[$value['fromcurrencyid']]['currencyCode']] = ($value['totalamount']);
//            }
//        }
        //check success transaction done
        if ($transactionType == 'incomplete') {
            $whereES[$this->ci->Eventsignup_model->transactionstatus] = 'success';
            $select['successcount'] = 'COUNT(' . $this->ci->Eventsignup_model->id . ')';
            $select['userid'] = $this->ci->Eventsignup_model->userid;
            $select['id'] = $this->ci->Eventsignup_model->id;
            $this->ci->Eventsignup_model->setSelect($select);
            $this->ci->Eventsignup_model->setWhere($whereES);
            $this->ci->Eventsignup_model->setFindInSet($findInSet);
            $this->ci->Eventsignup_model->setWhereNotIn($where_in_ES = array());
            $this->ci->Eventsignup_model->setGroupBy($groupBy);
            $bookedSuccessResponse = $this->ci->Eventsignup_model->get();
        }
        if (isset($bookedSuccessResponse) && count($bookedSuccessResponse) > 0) {
            foreach ($bookedSuccessResponse as $bookedRecord) {
                if (isset($incompleteUsersArray[$bookedRecord['userid']])) {
                    foreach ($incompleteUsersArray[$bookedRecord['userid']] as $userids => $eventsignupid) {
                        if (isset($response[$eventsignupid])) {
                            unset($response[$eventsignupid]);
                        }
                    }
                }
            }
            if (count($response) == 0) {
                $output['status'] = TRUE;
                $output['statusCode'] = STATUS_OK;
                $output['response']['total'] = 0;
                $output['response']['messages'][] = ERROR_NO_RECORDS;
                return $output;
            }
        }
        //get file upload custom field data
        $allFileCustData = array();
        $inputFileData['eventid'] = $eventId;
        $inputFileData['eventsignupids'] = $eventSignupIds;
        //$inputFileData['reporttype'] = $reportType;
        $fileUploadsResponse = $this->getFileTypeCustomFieldData($inputFileData);
        //print_r($fileUploadsResponse);
        //exit;
        if ($fileUploadsResponse['status']) {
            if ($fileUploadsResponse['response']['total'] > 0) {
                foreach ($fileUploadsResponse['response']['primaryAttendeedetailData'] as $esId => $attendeeData) {
                    foreach ($attendeeData['path'] as $custId => $fileData) {
                        //print_r($attendeeData);
                        // $response[$esId]['customfields'][$custId] = $this->ci->config->item('images_content_path') . $this->ci->config->item('s3_customField_path') . $eventId . "/" . $fileData['value'];
                        $response[$esId]['customfields'][$custId] = $fileData['value'];
                    }
                }
                $output['response']['downloadAllRequired'] = true;
                //$allFileCustData = $fileUploadsResponse['response']['attendeedetailData'];
            }
        } else {
            return $fileUploadsResponse;
        }
        $output['status'] = TRUE;
        $output['response']['transactionList'] = $response;
        if (count($allFileCustData) > 0) {
            $output['response']['fileUploadCustomFieldData'] = $allFileCustData;
        }
        $output['response']['total'] = count($response);
        $output['response']['totalTransactionCount'] = $seletESCountResponse[0]['totalcount'];
        $output['statusCode'] = STATUS_OK;
        return $output;
    }

    public function getFileTypeCustomFieldData($input) {
        $this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($input);
        $this->ci->form_validation->set_rules('eventid', 'eventid', 'is_natural_no_zero|required_strict');
        $this->ci->form_validation->set_rules('eventsignupids', 'eventsignupids', 'is_array');
//        $this->ci->form_validation->set_rules('reporttype', 'reporttype', 'required_strict');
//        $this->ci->form_validation->set_rules('transactiontype', 'transactiontype', 'required_strict|is_valid_type[transaction]');
//        $this->ci->form_validation->set_rules('page', 'page', 'is_natural_no_zero|required_strict');
        $this->ci->form_validation->set_rules('ticketid', 'ticketid', 'is_natural_no_zero');
        if ($this->ci->form_validation->run() == FALSE) {
            $responseErrors = $this->ci->form_validation->get_errors();
            $output['status'] = FALSE;
            $output['response']['messages'] = $responseErrors['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $eventId = $input['eventid'];
        // Ending for Downloading Files
        // Getting the EventsignupIds for the Event

        $eventSignupIds = isset($input['eventsignupids']) ? $input['eventsignupids'] : array();
        $ticketId = isset($input['ticketid']) ? $input['ticketid'] : 0;
        //$downloadAll = isset($input['downloadall']) ? $input['downloadall'] : false;
//        if ($downloadAll) {
//            $eventSignupIds = $this->customFileEventsignupIds($input);
//        }
        $inputCustomFields['eventId'] = $eventId;
        $inputCustomFields['fieldtypes'] = array('file');
        $response = array('status' => TRUE);
        $customFieldIds = array();
        $fileCustomFieldResponse = $this->configureHandler->getCustomFields($inputCustomFields);
        if ($fileCustomFieldResponse['status']) {
            $response['response']['total'] = $fileCustomFieldResponse['response']['total'];
            if ($fileCustomFieldResponse['response']['total'] > 0) {
                foreach ($fileCustomFieldResponse['response']['customFields'] as $value) {
                    $response['response']['fileCustomFieldData'][$value['id']] = $value['fieldname'];
                    $customFieldIds[] = $value['id'];
                }
            }
        } else {
            return $fileCustomFieldResponse;
        }

        //print_r($fileCustomFieldResponse);
        if ((count($customFieldIds) > 0 && count($eventSignupIds) > 0)) {
            $inputAttendees['eventsignupids'] = $eventSignupIds;
            if ($ticketId > 0) {
                $inputAttendees['ticketids'] = array($ticketId);
            }



            $atttendeeResponse = $this->attendeeHandler->getListByEventsignupIds($inputAttendees);
        }
        $attendeeIds = $indexESAttendeeData = $indexedPrimaryAttendeeData = array();
        if (isset($atttendeeResponse) && $atttendeeResponse['status']) {
            if ($atttendeeResponse['response']['total'] > 0) {
                //$indexESAttendeeData = $indexedPrimaryAttendeeData = array();
                if (count($eventSignupIds) > 0) {
                    foreach ($atttendeeResponse['response']['attendeeList'] as $value) {
                        $attendeeIds[] = $value['id'];
                        $indexESAttendeeData[$value['eventsignupid']][$value['ticketid']][] = $value['id'];
                        if ($value['primary'] == 1) {
                            $indexedPrimaryAttendeeData[$value['eventsignupid']] = $value['id'];
                        }
                    }
                } else {
                    foreach ($atttendeeResponse['response']['attendeeList'] as $value) {
                        $attendeeIds[] = $value['id'];
                    }
                }
            }
        } elseif (isset($atttendeeResponse)) {
            return $atttendeeResponse;
        }
        //print_r($atttendeeResponse);
        if (count($attendeeIds) > 0) {
            $inputDetail['attendeeids'] = $attendeeIds;
            $inputDetail['customfieldids'] = $customFieldIds;
            $attendeeDetailResponse = $this->attendeedetailHandler->getListByAttendeeIds($inputDetail);
        }
        $fileIds = array();
        if (isset($attendeeDetailResponse) && $attendeeDetailResponse['status']) {
            if ($attendeeDetailResponse['response']['total'] > 0) {
                $indexedAttendeeData = array();
                foreach ($attendeeDetailResponse['response']['attendeedetailList'] as $value) {
                    if (!empty($value['value'])) {
                        $indexedAttendeeData[$value['attendeeid']][$value['customfieldid']]['id'] = $value['id'];
                        $indexedAttendeeData[$value['attendeeid']][$value['customfieldid']]['value'] = $value['value'];
                        $fileIds[] = $value['value'];
                    }
                }
            }
        } elseif (isset($attendeeDetailResponse)) {
            return $attendeeDetailResponse;
        }
        //print_r($indexedAttendeeData);
        if (count($fileIds) > 0) {
            $fileHandler = new File_handler();
            $inputFile['fileids'] = $fileIds;
            $fileDataResponse = $fileHandler->getData($inputFile);
        }
        //print_r($fileDataResponse);
        if (isset($fileDataResponse) && $fileDataResponse['status']) {
            if ($fileDataResponse['response']['total'] > 0) {
                $indexedFileData = commonHelperGetIdArray($fileDataResponse['response']['fileData']);
                foreach ($attendeeDetailResponse['response']['attendeedetailList'] as $value) {
                    if (isset($indexedFileData[$value['value']]['path'])) {
                        $indexedAttendeeData[$value['attendeeid']][$value['customfieldid']]['value'] = $this->ci->config->item('images_content_path') . $indexedFileData[$value['value']]['path'];
                    }
                }
            }
        } elseif (isset($fileDataResponse)) {
            return $fileDataResponse;
        }
        //print_r($indexedFileData);
        //print_r($indexedAttendeeData);
        //exit;
        $esAttendeeData = $esPrimaryAttData = array();
        foreach ($indexESAttendeeData as $esid => $ticketIdArray) {
            $fileData = array();
            foreach ($ticketIdArray as $ticketId => $attendeeIdArray) {
                $primaryAttendeeIds = array_values($indexedPrimaryAttendeeData);
                foreach ($attendeeIdArray as $attendeeId) {
                    if (isset($indexedAttendeeData[$attendeeId])) {
                        $fileData[$ticketId][] = $indexedAttendeeData[$attendeeId];
                    }

                    if (isset($indexedAttendeeData[$attendeeId]) && $indexedPrimaryAttendeeData[$esid] && in_array($attendeeId, $primaryAttendeeIds)) {
                        $esPrimaryAttData[$esid]['id'] = $indexedPrimaryAttendeeData[$esid];
                        $esPrimaryAttData[$esid]['path'] = $indexedAttendeeData[$attendeeId];
                    }
                }
            }
            if (count($fileData) > 0) {
                $esAttendeeData[$esid] = $fileData;
            }
        }
        if (isset($esAttendeeData) && count($esAttendeeData) > 0) {
            $response['response']['attendeedetailData'] = $esAttendeeData;
        }
        if (isset($esPrimaryAttData) && count($esPrimaryAttData) > 0) {
            $response['response']['primaryAttendeedetailData'] = $esPrimaryAttData;
        }
//        if (isset($indexedAttendeeData)) {
//            foreach ($indexESAttendeeData as $esid => $attendeeIdArray) {
//                foreach ($attendeeIdArray as $attendeeId){
//                    
//                }
//                $esAttendeeData[$esid]=$fileData;
//            }
//            $response['response']['attendeedetailData'] = $indexedAttendeeData;
//        }
        // File Data for DownloadAll option
//        if ($downloadAll) {
//            //   if (!empty($response['response']['fileCustomFieldData'])) {
//            $attendeecustomFilepaths = array();
//            //  $customfileIds = array_keys($response['response']['fileCustomFieldData']);
//            foreach ($response['response']['attendeedetailData'] as $attendedetailData => $eventSignupdetails) {
//                foreach ($eventSignupdetails as $fileId => $fileValues) {
//                    for ($i = 0; $i < count($customFieldIds); $i++) {
//                        if (strlen($fileValues[$customFieldIds[$i]]['value']) > 0) {
//                            $attendeecustomFilepaths[] = $this->ci->config->item('images_content_path') . $this->ci->config->item('s3_customField_path') . $eventId . "/" . $fileValues[$customFieldIds[$i]]['value'];
//                        }
//                    }
//                }
//            }
//            // 	return json_encode()
//            /*  	$this->ci->load->library('zip');
//              $Filepaths = json_encode($attendeecustomFilepaths);
//              foreach($attendeecustomFilepaths as $key => $value){
//              $data = $this->ci->zip->read_file($value);
//              } */
//            // if($data){
//            $response = array();
//            //	$fileName = $this->ci->config->item('customfieldfiles_path').$eventId."_".time()."_files.zip";
//            $response['response']['filepath'] = $attendeecustomFilepaths;
//            $response['response']['messages'] = '';
//            $response['statusCode'] = STATUS_OK;
//            $response['response']['total'] = count($attendeecustomFilepaths);
//            //  	$this->ci->zip->archive($fileName);
//            return $response;
//            //}
//            //   }
//        }
        return $response;
    }

    public function getDetailDisplayInfo($input) {
        //validate input data
        $validationResponse = $this->validateGetTransaction($input);
        if (!$validationResponse['status']) {
            return $validationResponse;
        }
        //echo 'in';exit;
        $eventId = $input['eventid'];
        $ticketId = isset($input['ticketid']) ? $input['ticketid'] : 0;
        $reportType = $input['reporttype'];
        $report_types = $this->ci->config->item('report_types');
        if (!in_array($input['reporttype'], $report_types)) {
            $reportType = 'summary';
        }

        $transactionType = $input['transactiontype'];
        $page = $input['page'];
        $start = ($page - 1) * REPORTS_DISPLAY_LIMIT;
        $response = $setOrWhere = $where_in_ES = $groupBy = $findInSet = $notLike = $where_not_in_ES = $orfindInSet = array();
        $eventSignupIds = $selectESTDResponse = $ticketIds = $selectTicketResponse = $partialEventsignupIds = $ticketDataIdIndexed = $selectAttendeeResponse = $attendeeIds = $selectAttendeedetailResponse = $commentResponse = array();
        $whereES[$this->ci->Eventsignup_model->eventid] = $eventId;
        $whereES[$this->ci->Eventsignup_model->deleted] = 0;
        $whereES[$this->ci->Eventsignup_model->transactionstatus] = 'success';

        //currencies list to display beside amounts
        $currencyResponse = $this->currencyHanlder->getCurrencyList();
        if ($currencyResponse['status'] && $currencyResponse['response']['total'] > 0) {
            $indexedCurrencyListById = commonHelperGetIdArray($currencyResponse['response']['currencyList'], 'currencyId');
            $indexedCurrencyListByCode = commonHelperGetIdArray($currencyResponse['response']['currencyList'], 'currencyCode');
        } else {
            return $currencyResponse;
        }
//        if (isset($input['promotercode']) && $input['promotercode'] == 'promoter') {
//            $whereES[$this->ci->Eventsignup_model->promotercode . ' != '] = 'organizer';
//            $whereES[$this->ci->Eventsignup_model->promotercode . ' != '] = '';
//            $whereES[$this->ci->Eventsignup_model->promotercode . ' != '] = '0';
//        } elseif (isset($input['promotercode']) && $input['promotercode'] != 'meraevents') {
//            $whereES[$this->ci->Eventsignup_model->promotercode] = $input['promotercode'];
//        } elseif (isset($input['promotercode'])) {
//            $setOrWhere[$this->ci->Eventsignup_model->promotercode] = '';
//            $setOrWhere[$this->ci->Eventsignup_model->promotercode] = '0';
//        }
        //'promoter' for affiliate marketing,
        //'meraevents' for all me sales(used in sales effort page)
        if ((isset($input['promotercode']) && $input['promotercode'] == 'promoter') || (!isset($input['promotercode']) && $transactionType == 'affiliate')) {
            $where_not_in_ES[$this->ci->Eventsignup_model->promotercode] = array('organizer', '', '0');
            //$whereES[$this->ci->Eventsignup_model->promotercode . ' != '] = '';
            //$whereES[$this->ci->Eventsignup_model->promotercode . ' != '] = '0';
        } elseif (isset($input['promotercode']) && $input['promotercode'] == 'organizer') {
            $whereES[$this->ci->Eventsignup_model->promotercode] = 'organizer';
        }
        //when promotercode is sent(offline)
        elseif (isset($input['promotercode']) && $input['promotercode'] != 'meraevents') {
            $whereES[$this->ci->Eventsignup_model->promotercode] = $input['promotercode'];
        }
        //for me sales
        elseif (isset($input['promotercode'])) {
            $where_in_ES[$this->ci->Eventsignup_model->promotercode] = array('', '0');
            $where_not_in_ES[$this->ci->Eventsignup_model->paymentgatewayid] = array('7', '8');
            $where_not_in_ES[$this->ci->Eventsignup_model->paymentmodeid] = array('4');
            //exclude viral
            $whereES[$this->ci->Eventsignup_model->referraldiscountamount] = '0';
        }
        if (isset($input['currencycode'])) {
            $whereES[$this->ci->Eventsignup_model->fromcurrencyid] = $indexedCurrencyListByCode[$input['currencycode']]['currencyId'];
        }
        if ($transactionType == 'refund') {
            $whereES[$this->ci->Eventsignup_model->paymentstatus] = 'Refunded';
        } else {
            $where_not_in_ES[$this->ci->Eventsignup_model->paymentstatus] = array('Canceled', 'Refunded');
        }


        if ($ticketId > 0) {
            $findInSet[$ticketId] = $this->ci->Eventsignup_model->transactionticketids;
        }
        switch ($transactionType) {
            case 'all':
                break;
            case 'card':
                $whereES[$this->ci->Eventsignup_model->paymentmodeid] = 1;
                $whereES[$this->ci->Eventsignup_model->totalamount . " > "] = 0;
                $where_not_in_ES[$this->ci->Eventsignup_model->paymentgatewayid] = array('', 'A1', 0, 7, 8);
                break;
            case 'cod':
                $whereES[$this->ci->Eventsignup_model->paymentmodeid] = 2;
                break;
            case 'free':
                $setOrWhere[$this->ci->Eventsignup_model->totalamount] = 0;
                $orfindInSet['free'] = $this->ci->Eventsignup_model->transactiontickettype;
                $where_not_in_ES[$this->ci->Eventsignup_model->paymentmodeid] = 4;
                break;
            case 'offline':
                $whereES[$this->ci->Eventsignup_model->paymentmodeid] = 4;
                //$where_not_in_ES[$this->ci->Eventsignup_model->totalamount] = '0';
                break;
            case 'incomplete':
                $whereES[$this->ci->Eventsignup_model->transactionstatus] = 'pending';
                $groupBy = array($this->ci->Eventsignup_model->userid);
                break;
            case 'boxoffice':
                $where_in_ES[$this->ci->Eventsignup_model->paymentgatewayid] = array(7, 8);
                break;
            case 'viral':
                $whereES[$this->ci->Eventsignup_model->referraldiscountamount . " > "] = 0;
                break;
            case 'affiliate':
                $newData = array('', '0');
                if (isset($where_not_in_ES[$this->ci->Eventsignup_model->promotercode])) {
                    $addedData = $where_not_in_ES[$this->ci->Eventsignup_model->promotercode];
                    $newData = array_merge($newData, $addedData);
                }
                //exclude me sales
                $where_not_in_ES[$this->ci->Eventsignup_model->promotercode] = array_unique($newData);
                $notLike[$this->ci->Eventsignup_model->promotercode] = 'OFFLINE_';
                break;
            case 'cancel':
                $whereES[$this->ci->Eventsignup_model->paymentstatus] = 'Canceled';
                $where_not_in_ES = array();
                unset($whereES[$this->ci->Eventsignup_model->transactionstatus]);
                break;
            case 'refund':
                break;
            default:
                break;
        }
        //to get total count
        $selectESCount['totalcount'] = 'COUNT( ' . $this->ci->Eventsignup_model->id . ' )';
        $this->ci->Eventsignup_model->setSelect($selectESCount);
        $this->ci->Eventsignup_model->setWhereNotIn($where_not_in_ES);
        $this->ci->Eventsignup_model->setWhere($whereES);
        $this->ci->Eventsignup_model->setOrWhere($setOrWhere, ' or ', ' = ', $orfindInSet);
        $this->ci->Eventsignup_model->setNotLike($notLike);
        $this->ci->Eventsignup_model->setWhereIns($where_in_ES);
        $this->ci->Eventsignup_model->setFindInSet($findInSet);
        $this->ci->Eventsignup_model->setGroupBy($groupBy);
        $this->ci->Eventsignup_model->setRecords(0, 0);
        $seletESCountResponse = $this->ci->Eventsignup_model->get();
        //echo $this->ci->db->last_query();exit;
//        print_r($seletESCountResponse);exit;
        if ($seletESCountResponse[0]['totalcount'] == 0) {
            $output['status'] = TRUE;
            $output['statusCode'] = STATUS_OK;
            $output['response']['total'] = 0;
            $output['response']['messages'][] = ERROR_NO_RECORDS;
            return $output;
        }
        //to get data
        $selectES['id'] = $this->ci->Eventsignup_model->id;
        $selectES['userid'] = $this->ci->Eventsignup_model->userid;
        $selectES['signupdate'] = $this->ci->Eventsignup_model->signupdate;
        $selectES['transactiontickettype'] = $this->ci->Eventsignup_model->transactiontickettype;
        $selectES['paymentstatus'] = $this->ci->Eventsignup_model->paymentstatus;
        $selectES['barcodenumber'] = $this->ci->Eventsignup_model->barcodenumber;
        $selectES['fromcurrencyid'] = $this->ci->Eventsignup_model->fromcurrencyid;
        $selectES['tocurrencyid'] = $this->ci->Eventsignup_model->tocurrencyid;
        $selectES['conversionrate'] = $this->ci->Eventsignup_model->conversionrate;
        $selectES['convertedamount'] = $this->ci->Eventsignup_model->convertedamount;
        $selectES['quantity'] = $this->ci->Eventsignup_model->quantity;
        $selectES['totalamount'] = $this->ci->Eventsignup_model->totalamount;
        $selectES['discountamount'] = $this->ci->Eventsignup_model->discountamount;
        $selectES['eventextrachargeamount'] = $this->ci->Eventsignup_model->eventextrachargeamount;
        $selectES['paymenttransactionid'] = $this->ci->Eventsignup_model->paymenttransactionid;
        if ($transactionType == 'affiliate') {
            $selectES['promotercode'] = $this->ci->Eventsignup_model->promotercode;
        }
        $this->ci->Eventsignup_model->setSelect($selectES);
        $this->ci->Eventsignup_model->setWhereNotIn($where_not_in_ES);
        $this->ci->Eventsignup_model->setRecords(REPORTS_DISPLAY_LIMIT, $start);
        $this->ci->Eventsignup_model->setWhere($whereES);
        $this->ci->Eventsignup_model->setOrWhere($setOrWhere, ' or ', ' = ', $orfindInSet);
        $this->ci->Eventsignup_model->setNotLike($notLike);
        $this->ci->Eventsignup_model->setWhereIns($where_in_ES);
        $this->ci->Eventsignup_model->setFindInSet($findInSet);
        $this->ci->Eventsignup_model->setGroupBy($groupBy);

        $orderBy = array($this->ci->Eventsignup_model->id . ' desc');
        $this->ci->Eventsignup_model->setOrderBy($orderBy);
        $selectESResponse = $this->ci->Eventsignup_model->get();
        //echo $this->ci->db->last_query();exit;
        if (count($selectESResponse) == 0) {
            $output['status'] = TRUE;
            $output['statusCode'] = STATUS_OK;
            $output['response']['total'] = 0;
            $output['response']['messages'][] = ERROR_NO_RECORDS;
            return $output;
        }


        $indexEventSignupIdArray = array();
        foreach ($selectESResponse as $value) {
            $indexEventSignupIdArray[$value['id']] = $value;
            $eventSignupIds[] = $value['id'];
            $userIdList[] = $value['userid'];
            if (strcmp(strtolower($value['paymentstatus']), 'partialrefund') == 0) {
                $partialEventsignupIds[] = $value['id'];
            }
        }
        $this->userHandler = new User_handler();
        $userInputs['userIdList'] = array_unique($userIdList); //To pass as an array to the method
        $userData = $this->userHandler->getUserDetails($userInputs);
        //print_r($userData);exit;
        if ($userData['status'] && $userData['response']['total'] > 0) {
            $userDataList = commonHelperGetIdArray($userData['response']['userData']);
        } else {
            return $userData;
        }
        $selectPartialPayments = array();
        if (count($partialEventsignupIds) > 0) {
            $refundHandler = new Refund_handler();
            $inputRefunds['eventsignupids'] = $partialEventsignupIds;
            $selectPartialPayments = $refundHandler->getRefundList($inputRefunds);
        }
        $indexedRefunds = array();
        if (isset($selectPartialPayments['status']) && $selectPartialPayments['status']) {
            if ($selectPartialPayments['response']['total'] > 0) {
                $indexedRefunds = commonHelperGetIdArray($selectPartialPayments['response']['refundList'], 'eventsignupid');
            }
        } elseif (count($selectPartialPayments) > 0) {
            return $selectPartialPayments;
        }

        $inputTkt['eventId'] = $eventId;
        $inputTkt['status'] = 0;
        $selectTicketResponse = $this->ticketHandler->getTicketName($inputTkt);
        if ($selectTicketResponse['status'] && $selectTicketResponse['response']['total'] > 0) {
            $ticketDataIdIndexed = commonHelperGetIdArray($selectTicketResponse['response']['ticketName']);
        } else {
            return $selectTicketResponse;
        }
        $paidTickets = $freeTickets = array();
        foreach ($ticketDataIdIndexed as $tickets) {
            if ($tickets['type'] == 'paid' || $tickets['type'] == 'donation' || $tickets['type'] == 'addon') {
                $paidTickets[] = $tickets['id'];
            } elseif ($tickets['type'] == 'free') {
                $freeTickets[] = $tickets['id'];
            }
        }
        if (count($eventSignupIds) > 0) {
            $inputESTD['eventsignupids'] = $eventSignupIds;
            $inputESTD['transactiontype'] = $transactionType;
            if ($ticketId > 0) {
                $inputESTD['ticketids'] = array($ticketId);
            } else if ($transactionType == 'card' && count($paidTickets) > 0) {
                $inputESTD['ticketids'] = $paidTickets;
            }
//            elseif ($transactionType == 'free' && count($freeTickets) > 0) {
//                if ($ticketId == 0) {
//                    $inputESTD['nullify'] = 1;
//                }
//                $inputESTD['ticketids'] = $freeTickets;
//            }
            $selectESTDResponse = $this->estdHandler->getListByEventsignupIds($inputESTD);
        }
        $eventTimeZoneName = $this->eventHandler->getEventTimeZone($eventId);
        //print_r($selectESTDResponse);exit;
        //get file upload custom field data
        $allFileCustData = array();
        $inputFileData['eventid'] = $eventId;
        $inputFileData['eventsignupids'] = $eventSignupIds;
        $inputFileData['reporttype'] = $reportType;
        $fileUploadsResponse = $this->getFileTypeCustomFieldData($inputFileData);
        if ($fileUploadsResponse['status']) {
            if ($fileUploadsResponse['response']['total'] > 0) {
                $allFileCustData = $fileUploadsResponse['response']['attendeedetailData'];
                $output['response']['downloadAllRequired'] = true;
            }
        } else {
            return $fileUploadsResponse;
        }
        //print_r($fileUploadsResponse);
        // exit;

        $commentResponse = array();
        if ($transactionType == 'incomplete' || $transactionType == 'cod' || $transactionType == 'cancel') {
            $inputArray['eventsignupids'] = $eventSignupIds;
            $inputArray['commenttype'] = 'incomplete';
            $commentResponseData = $this->commentHandler->getCommentByEventsignupIds($inputArray);
        }
        if (isset($commentResponseData) && $commentResponseData['status'] && $commentResponseData['response']['total'] > 0) {
            foreach ($commentResponseData['response']['commentList'] as $data) {
                $commentResponse[$data['eventsignupid']] = $data['comment'];
            }
        }
        //print_r($commentResponse);
        //exit;
        $inputTktOptions['eventId'] = $eventId;
        $eventOptions = $this->eventHandler->getTicketOptions($inputTktOptions);
        $collectAll = 0;
        //print_r($eventOptions);exit;
        if ($eventOptions['status'] && $eventOptions['response']['total'] > 0) {
            $collectAll = $eventOptions['response']['ticketingOptions'][0]['collectmultipleattendeeinfo'];
        } else {
            return $eventOptions;
        }       
         // getting attendee realted data start
            $inputAttendees['eventsignupids'] = $eventSignupIds;
            //$inputAttendees['primary'] = 0;
            $selectAttendeeResponse = $this->attendeeHandler->getListByEventsignupIds($inputAttendees);
           if ($selectAttendeeResponse['status'] && $selectAttendeeResponse['response']['total'] > 0) {
            foreach ($selectAttendeeResponse['response']['attendeeList'] as $value) {
                $attendeeIds[] = $value['id'];
            }
        } else {
            return $selectAttendeeResponse;
        }
        if (count($attendeeIds) > 0) {
            $inputCustomFieldData['attendeeids'] = $attendeeIds;
            $selectAllCustomFieldDataResponse = $this->attendeedetailHandler->getListByAttendeeIds($inputCustomFieldData);
        }
        if ($selectAllCustomFieldDataResponse['status'] && $selectAllCustomFieldDataResponse['response']['total'] > 0) {
            $inputCustomFields['eventId'] = $eventId;
            $inputCustomFields['allfields'] = 1;
            $inputCustomFields['activeCustomField'] = 1;
            $eventCustomFields = $this->configureHandler->getCustomFields($inputCustomFields);
        } else {
            return $selectAllCustomFieldDataResponse;
        }
        if ($eventCustomFields['status'] && $eventCustomFields['response']['total'] > 0) {
            $indexedCustomFieldNames = commonHelperGetIdArray($eventCustomFields['response']['customFields']);
            foreach ($selectAllCustomFieldDataResponse['response']['attendeedetailList'] as $value) {
                $IndexedCustomFieldDataArray[$value['attendeeid']][$indexedCustomFieldNames[$value['customfieldid']]['fieldname']] = $value['value'];
            }  
            $responseCustomFields = array();
            $i = 0;
            $attendeesList = $selectAttendeeResponse['response']['attendeeList'];
            foreach ($attendeesList as $key => $value) {
               if ($attendeesList[$key]['eventsignupid'] != $attendeesList[$key + 1]['eventsignupid'] || ($attendeesList[$key]['eventsignupid'] == $attendeesList[$key + 1]['eventsignupid'] && $attendeesList[$key]['ticketid'] != $attendeesList[$key + 1]['ticketid'])) {
                   $i = 0;
                }
                foreach ($indexedCustomFieldNames as $customField) {
                    $responseCustomFields[$value['eventsignupid']][$value['ticketid']][$i][$customField['fieldname']] = $IndexedCustomFieldDataArray[$value['id']][$customField['fieldname']];
                }
                $i++;
            }
        } else {
            return $eventCustomFields;
        }
        if ($selectESTDResponse['status'] && $selectESTDResponse['response']['total'] > 0) {
            $sno = 0;
            foreach ($selectESTDResponse['response']['eventSignupTicketDetailList'] as $value) {
                $ticketData = array();
                $cfqty = 0;
                $amount = round($value['amount'] / $value['ticketquantity'], 2);
                if ($amount == 0) {
                    $ticketData[$value['ticketid']]['amount'] = 0;
                } else {
                    $ticketData[$value['ticketid']]['amount'] = $indexedCurrencyListById[$indexEventSignupIdArray[$value['eventsignupid']]['fromcurrencyid']]['currencyCode'] . ' ' . $amount;
                }
                $ticketData[$value['ticketid']]['tickettype'] = $ticketDataIdIndexed[$value['ticketid']]['name'];
                $ticketData[$value['ticketid']]['quantity'] = 1;
                if ($transactionType == 'affiliate') {
                    $ticketData[$value['ticketid']]['promotercode'] = $indexEventSignupIdArray[$value['eventsignupid']]['promotercode'];
                }
                $index = ($collectAll == 1) ? ($cfqty++) : 0;
                $ticketData[$value['ticketid']]['customfields'] = isset($allFileCustData[$value['eventsignupid']][$value['ticketid']][$index]) ? $allFileCustData[$value['eventsignupid']][$value['ticketid']][$index] : array();
                $paidAmount = round($value['totalamount'] / $value['ticketquantity'], 2);
                for ($loop = 0; $loop < $value['ticketquantity']; $loop++) {
                    $response[$sno][$value['eventsignupid']]['id'] = $indexEventSignupIdArray[$value['eventsignupid']]['id'];
                    $response[$sno][$value['eventsignupid']]['signupDate'] = allTimeFormats(convertTime($indexEventSignupIdArray[$value['eventsignupid']]['signupdate'], $eventTimeZoneName, true), 11);

                    $response[$sno][$value['eventsignupid']]['quantity'] = 1;
                    $refundAmt = isset($indexedRefunds[$value['eventsignupid']]) ? ($indexedRefunds[$value['eventsignupid']]['totalrefundamount'] / $value['ticketquantity']) : 0;
                    if (($indexEventSignupIdArray[$value['eventsignupid']]['convertedamount'] > 0 && $indexEventSignupIdArray[$value['eventsignupid']]['conversionrate'] > 1)) {
                        $convertedAmount = $indexEventSignupIdArray[$value['eventsignupid']]['convertedamount'] * $indexEventSignupIdArray[$value['eventsignupid']]['quantity'];
                        $totalAmount = $indexEventSignupIdArray[$value['eventsignupid']]['totalamount'];
                        $paidCovertedAmount = ($paidAmount * $convertedAmount) / $totalAmount;
                        $paid = $indexedCurrencyListById[$indexEventSignupIdArray[$value['eventsignupid']]['tocurrencyid']]['currencyCode'] . ' ' . round(($paidCovertedAmount * $indexEventSignupIdArray[$value['eventsignupid']]['conversionrate']) - $refundAmt, 2);
                    } elseif ($indexEventSignupIdArray[$value['eventsignupid']]['convertedamount'] > 0) {
                        $convertedAmount = round($indexEventSignupIdArray[$value['eventsignupid']]['convertedamount'] * $indexEventSignupIdArray[$value['eventsignupid']]['quantity'], 2);
                        $totalAmount = $indexEventSignupIdArray[$value['eventsignupid']]['totalamount'];
                        $paidCovertedAmount = (($paidAmount * $convertedAmount) / $totalAmount);
                        $paid = 'USD' . ' ' . round($paidCovertedAmount, 2);
                    } elseif ($indexEventSignupIdArray[$value['eventsignupid']]['conversionrate'] > 1) {
                        $paid = $indexedCurrencyListById[$indexEventSignupIdArray[$value['eventsignupid']]['tocurrencyid']]['currencyCode'] . ' ' . round((($value['totalamount'] * $indexEventSignupIdArray[$value['eventsignupid']]['conversionrate']) - $refundAmt) / $value['ticketquantity'], 2);
                    } else {
                        $amt = round($value['totalamount']);
                        if (isset($indexedRefunds[$value['eventsignupid']]) && $indexEventSignupIdArray[$value['eventsignupid']]['fromcurrencyid'] == 1) {
                            $amt = ($value['totalamount'] - $indexedRefunds[$value['eventsignupid']]['totalrefundamount']);
                        }
                        $paid = $indexedCurrencyListById[$indexEventSignupIdArray[$value['eventsignupid']]['fromcurrencyid']]['currencyCode'] . ' ' . round($amt / $value['ticketquantity'],2);
                    }
                    $response[$sno][$value['eventsignupid']]['paid'] = $paid;
                    $response[$sno][$value['eventsignupid']]['discount'] = $indexedCurrencyListById[$ticketDataIdIndexed[$value['ticketid']]['currencyid']]['currencyCode'] . ' ' . round(($value['bulkdiscountamount'] + $value['referraldiscountamount'] + $value['discountamount']) / $value['ticketquantity'], 2);
                    $response[$sno][$value['eventsignupid']]['ticketDetails'] = $ticketData;
                    $response[$sno][$value['eventsignupid']]['comment'] = isset($commentResponse[$value['eventsignupid']]) ? $commentResponse[$value['eventsignupid']] : '';
                    $userIdIndex = $indexEventSignupIdArray[$value['eventsignupid']]['userid'];
                    $response[$sno][$value['eventsignupid']]['contactDetails']['name'] = isset($responseCustomFields[$value['eventsignupid']][$value['ticketid']][$loop]['Full Name']) ? $responseCustomFields[$value['eventsignupid']][$value['ticketid']][$loop]['Full Name'] : $responseCustomFields[$value['eventsignupid']][$value['ticketid']][0]['Full Name'];
                    $response[$sno][$value['eventsignupid']]['contactDetails']['email'] =  isset($responseCustomFields[$value['eventsignupid']][$value['ticketid']][$loop]['Email Id']) ? $responseCustomFields[$value['eventsignupid']][$value['ticketid']][$loop]['Email Id'] : $responseCustomFields[$value['eventsignupid']][$value['ticketid']][0]['Email Id'];
                    $response[$sno][$value['eventsignupid']]['contactDetails']['phone']=  isset($responseCustomFields[$value['eventsignupid']][$value['ticketid']][$loop]['Mobile No']) ? $responseCustomFields[$value['eventsignupid']][$value['ticketid']][$loop]['Mobile No'] : $responseCustomFields[$value['eventsignupid']][$value['ticketid']][0]['Mobile No'];
                    $sno++;
                }
            }
            //$inputAttendees['eventsignupids'] = $eventSignupIds;
            //$inputAttendees['primary'] = 1;
            //$selectAttendeeResponse = $this->attendeeHandler->getListByEventsignupIds($inputAttendees);
        } else {
            $output['status'] = TRUE;
            $output['statusCode'] = STATUS_OK;
            $output['response']['total'] = 0;
            $output['response']['messages'][] = ERROR_NO_RECORDS;
            return $output;
        }
        $output['status'] = TRUE;
        $output['response']['transactionList'] = $response;
        if (count($allFileCustData) > 0) {
            $output['response']['fileUploadCustomFieldData'] = $allFileCustData;
        }
        $output['response']['total'] = count($response);
        $output['response']['totalTransactionCount'] = $seletESCountResponse[0]['totalcount'];
        $output['statusCode'] = STATUS_OK;
        return $output;
    }

    public function getSummaryExportInfo($input) {
        $validationResponse = $this->validateGetTransaction($input);
        if (!$validationResponse['status']) {
            return $validationResponse;
        }
        require_once (APPPATH . 'handlers/seating_handler.php');
        $this->seatingHandler = new Seating_handler();
        $eventId = $input['eventid'];
        $seatingLayout = 0;
        $inputSeatlayout['eventid'] = $eventId;
        $checkSeatlayout = $this->seatingHandler->checkLayout($inputSeatlayout);
        if ($checkSeatlayout['status'] && $checkSeatlayout['response']['seatingEnabled'] == true) {
            $seatingLayout = 1;
        }
        //   $seatingLayout=1;
        $ticketId = isset($input['ticketid']) ? $input['ticketid'] : 0;
        $reportType = $input['reporttype'];
        $report_types = $this->ci->config->item('report_types');
        if (!in_array($input['reporttype'], $report_types)) {
            $reportType = 'summary';
        }

        $transactionType = $input['transactiontype'];
        $page = $input['page'];
		$pageLimit=REPORTS_TRANSACTION_LIMIT;
        if(isset($input['REPORTS_TRANSACTION_LIMIT'])){
            $pageLimit=$input['REPORTS_TRANSACTION_LIMIT'];
        }
        $start = ($page - 1) * $pageLimit;
        $response = $setOrWhere = $where_in_ES = $groupBy = $findInSet = $notLike = $where_not_in_ES = $orfindInSet = array();
        $eventSignupIds = $selectESTDResponse = $ticketIds = $selectTicketResponse = $partialEventsignupIds = $ticketDataIdIndexed = $selectAttendeeResponse = $attendeeIds = $selectAttendeedetailResponse = $commentResponse = array();
        $whereES[$this->ci->Eventsignup_model->eventid] = $eventId;
        $whereES[$this->ci->Eventsignup_model->deleted] = 0;
        $whereES[$this->ci->Eventsignup_model->transactionstatus] = 'success';
        if(isset($input['eventSignupId']) && $input['eventSignupId'] > 0){
           $whereES[$this->ci->Eventsignup_model->id] = $input['eventSignupId']; 
        }
        //currencies list
        $currencyResponse = $this->currencyHanlder->getCurrencyList();
        if ($currencyResponse['status'] && $currencyResponse['response']['total'] > 0) {
            $indexedCurrencyListById = commonHelperGetIdArray($currencyResponse['response']['currencyList'], 'currencyId');
            $indexedCurrencyListByCode = commonHelperGetIdArray($currencyResponse['response']['currencyList'], 'currencyCode');
        } else {
            return $currencyResponse;
        }
//        if (isset($input['promotercode']) && $input['promotercode'] == 'promoter') {
//            $whereES[$this->ci->Eventsignup_model->promotercode . ' != '] = 'organizer';
//            $whereES[$this->ci->Eventsignup_model->promotercode . ' != '] = '';
//        } elseif (isset($input['promotercode']) && $input['promotercode'] != 'meraevents') {
//            $whereES[$this->ci->Eventsignup_model->promotercode] = $input['promotercode'];
//        } elseif (isset($input['promotercode'])) {
//            $setOrWhere[$this->ci->Eventsignup_model->promotercode] = '';
//            $setOrWhere[$this->ci->Eventsignup_model->promotercode] = '0';
//        }
        if ((isset($input['promotercode']) && $input['promotercode'] == 'promoter') || (!isset($input['promotercode']) && $transactionType == 'affiliate')) {
            $where_not_in_ES[$this->ci->Eventsignup_model->promotercode] = array('organizer', '', '0');
            //$whereES[$this->ci->Eventsignup_model->promotercode . ' != '] = '';
            //$whereES[$this->ci->Eventsignup_model->promotercode . ' != '] = '0';
        } elseif (isset($input['promotercode']) && $input['promotercode'] == 'organizer') {
            $whereES[$this->ci->Eventsignup_model->promotercode] = 'organizer';
        } elseif (isset($input['promotercode']) && $input['promotercode'] != 'meraevents') {
            $whereES[$this->ci->Eventsignup_model->promotercode] = $input['promotercode'];
            $where_not_in_ES[$this->ci->Eventsignup_model->promotercode] = array('organizer', '', '0');
        } elseif (isset($input['promotercode'])) {
            $where_in_ES[$this->ci->Eventsignup_model->promotercode] = array('', '0');
            $where_not_in_ES[$this->ci->Eventsignup_model->paymentgatewayid] = array('7', '8');
            $where_not_in_ES[$this->ci->Eventsignup_model->paymentmodeid] = array('4');
            $whereES[$this->ci->Eventsignup_model->referraldiscountamount] = '0';
        }
        if (isset($input['currencycode'])) {
            $whereES[$this->ci->Eventsignup_model->fromcurrencyid] = $indexedCurrencyListByCode[$input['currencycode']]['currencyId'];
        }
        $where_not_in_ES[$this->ci->Eventsignup_model->paymentstatus] = array('Canceled', 'Refunded');

        if ($ticketId > 0) {
            $findInSet[$ticketId] = $this->ci->Eventsignup_model->transactionticketids;
        }
        switch ($transactionType) {
            case 'all':
                break;
            case 'card':
                $whereES[$this->ci->Eventsignup_model->paymentmodeid] = 1;
                $whereES[$this->ci->Eventsignup_model->totalamount . " > "] = 0;
                $where_not_in_ES[$this->ci->Eventsignup_model->paymentgatewayid] = array('', 'A1', 0, 7, 8);
                break;
            case 'cod':
                $whereES[$this->ci->Eventsignup_model->paymentmodeid] = 2;
                break;
            case 'free':
                $setOrWhere[$this->ci->Eventsignup_model->totalamount] = 0;
                $orfindInSet['free'] = $this->ci->Eventsignup_model->transactiontickettype;
                $where_not_in_ES[$this->ci->Eventsignup_model->paymentmodeid] = 4;
                break;
            case 'offline':
                $whereES[$this->ci->Eventsignup_model->paymentmodeid] = 4;
                //$where_not_in_ES[$this->ci->Eventsignup_model->totalamount] = '0';
                break;
            case 'incomplete':
                $whereES[$this->ci->Eventsignup_model->transactionstatus] = 'pending';
                $groupBy = array($this->ci->Eventsignup_model->userid);
                break;
            case 'boxoffice':
                //$whereES[$this->ci->Eventsignup_model->paymentmodeid] = 5;
                $where_in_ES[$this->ci->Eventsignup_model->paymentgatewayid] = array(7, 8);
                break;
            case 'viral':
                $whereES[$this->ci->Eventsignup_model->referraldiscountamount . " > "] = 0;
                break;
            case 'affiliate':
                $newData = array('', '0');
                if (isset($where_not_in_ES[$this->ci->Eventsignup_model->promotercode])) {
                    $addedData = $where_not_in_ES[$this->ci->Eventsignup_model->promotercode];
                    $newData = array_merge($newData, $addedData);
                }
                //exclude me sales
                $where_not_in_ES[$this->ci->Eventsignup_model->promotercode] = array_unique($newData);
                $notLike[$this->ci->Eventsignup_model->promotercode] = 'OFFLINE_';
                break;
            case 'cancel':
                $whereES[$this->ci->Eventsignup_model->paymentstatus] = 'Canceled';
                $where_not_in_ES = array();
                unset($whereES[$this->ci->Eventsignup_model->transactionstatus]);
                break;
            default:
                break;
        }
        if (isset($input['modifiedDate'])) {
            $whereES[$this->ci->Eventsignup_model->mts . " > "] = $input['modifiedDate'];
        }
        //to get total count
        $selectESCount['totalcount'] = 'COUNT( ' . $this->ci->Eventsignup_model->id . ' )';
        $this->ci->Eventsignup_model->setSelect($selectESCount);
        $this->ci->Eventsignup_model->setWhereNotIn($where_not_in_ES);
        $this->ci->Eventsignup_model->setWhere($whereES);
        $this->ci->Eventsignup_model->setOrWhere($setOrWhere, ' or ', ' = ', $orfindInSet);
        $this->ci->Eventsignup_model->setNotLike($notLike);
        $this->ci->Eventsignup_model->setWhereIns($where_in_ES);
        $this->ci->Eventsignup_model->setFindInSet($findInSet);
        $this->ci->Eventsignup_model->setGroupBy($groupBy);
        $this->ci->Eventsignup_model->setRecords(0, 0);
        $seletESCountResponse = $this->ci->Eventsignup_model->get();
        //echo $this->ci->db->last_query();exit;
        //   print_r($seletESCountResponse);exit;
        if (($transactionType == 'incomplete' && count($seletESCountResponse) == 0) || $seletESCountResponse[0]['totalcount'] == 0) {
            $output['status'] = TRUE;
            $output['statusCode'] = STATUS_OK;
            $output['response']['total'] = 0;
            $output['response']['messages'][] = ERROR_NO_RECORDS;
            return $output;
        }
        //to get data
        $selectES['id'] = $this->ci->Eventsignup_model->id;
        $selectES['userid'] = $this->ci->Eventsignup_model->userid;
        $selectES['signupdate'] = $this->ci->Eventsignup_model->signupdate;
        $selectES['transactiontickettype'] = $this->ci->Eventsignup_model->transactiontickettype;
        $selectES['paymentstatus'] = $this->ci->Eventsignup_model->paymentstatus;
        $selectES['barcodenumber'] = $this->ci->Eventsignup_model->barcodenumber;
        $selectES['fromcurrencyid'] = $this->ci->Eventsignup_model->fromcurrencyid;
        $selectES['tocurrencyid'] = $this->ci->Eventsignup_model->tocurrencyid;
        $selectES['conversionrate'] = $this->ci->Eventsignup_model->conversionrate;
        $selectES['convertedamount'] = $this->ci->Eventsignup_model->convertedamount;
        $selectES['quantity'] = $this->ci->Eventsignup_model->quantity;
        $selectES['totalamount'] = $this->ci->Eventsignup_model->totalamount;
        $selectES['discountamount'] = $this->ci->Eventsignup_model->discountamount;
        $selectES['eventextrachargeamount'] = $this->ci->Eventsignup_model->eventextrachargeamount;
        $selectES['paymenttransactionid'] = $this->ci->Eventsignup_model->paymenttransactionid;

        $selectES['referraldiscountamount'] = $this->ci->Eventsignup_model->referraldiscountamount;
        $selectES['paymentmodeid'] = $this->ci->Eventsignup_model->paymentmodeid;
        $selectES['attendeeid'] = $this->ci->Eventsignup_model->attendeeid;
        $selectES['paymentgatewayid'] = $this->ci->Eventsignup_model->paymentgatewayid;
        $selectES['cts'] = $this->ci->Eventsignup_model->cts;
        $selectES['mts'] = $this->ci->Eventsignup_model->mts;
        if ($transactionType == 'affiliate') {
        $selectES['promotercode'] = $this->ci->Eventsignup_model->promotercode;
        }
        if ($transactionType == 'incomplete') {
            $selectES['failedcount'] = 'COUNT(' . $this->ci->Eventsignup_model->id . ')';
        }
        $this->ci->Eventsignup_model->setSelect($selectES);
        $this->ci->Eventsignup_model->setWhereNotIn($where_not_in_ES);
        $this->ci->Eventsignup_model->setRecords($pageLimit, $start);
        $this->ci->Eventsignup_model->setWhere($whereES);
        $this->ci->Eventsignup_model->setOrWhere($setOrWhere, ' or ', ' = ', $orfindInSet);
        $this->ci->Eventsignup_model->setNotLike($notLike);
        $this->ci->Eventsignup_model->setWhereIns($where_in_ES);
        $this->ci->Eventsignup_model->setFindInSet($findInSet);
        $this->ci->Eventsignup_model->setGroupBy($groupBy);
        
         if(!isset($input['orderStatus'])){
            $orderBy = array($this->ci->Eventsignup_model->id . ' desc');
        }else{
            $orderBy = array($this->ci->Eventsignup_model->mts);
        }  
        $this->ci->Eventsignup_model->setOrderBy($orderBy);
        $selectESResponse = $this->ci->Eventsignup_model->get();
        //echo $this->ci->db->last_query();
        if (count($selectESResponse) == 0) {
            $output['status'] = TRUE;
            $output['statusCode'] = STATUS_OK;
            $output['response']['total'] = 0;
            $output['response']['messages'][] = ERROR_NO_RECORDS;
            return $output;
        }

        //get event tickets
        $inputTkt['eventId'] = $eventId;
        $inputTkt['status'] = 0;
        $selectTicketResponse = $this->ticketHandler->getTicketName($inputTkt);
        if ($selectTicketResponse['status'] && $selectTicketResponse['response']['total'] > 0) {
            $ticketDataIdIndexed = commonHelperGetIdArray($selectTicketResponse['response']['ticketName']);
        } else {
            return $selectTicketResponse;
        }
        $paidTickets = $freeTickets = array();
        foreach ($ticketDataIdIndexed as $tickets) {
            if ($tickets['type'] == 'paid' || $tickets['type'] == 'donation' || $tickets['type'] == 'addon') {
                $paidTickets[] = $tickets['id'];
            } elseif ($tickets['type'] == 'free') {
                $freeTickets[] = $tickets['id'];
            }
        }

        $userIdList = $EventsignupSeats = array();
        $indexEventSignupIdArray = array();
        foreach ($selectESResponse as $value) {
            $indexEventSignupIdArray[$value['id']] = $value;
            $eventSignupIds[] = $value['id'];
            $userIdList[] = $value['userid'];
            }
        //Fetching user related data
        $this->userHandler = new User_handler();
        $userInputs['userIdList'] = array_unique($userIdList); //To pass as an array to the method
        $userData = $this->userHandler->getUserDetails($userInputs);

        //fetch estd data
        if (count($eventSignupIds) > 0) {
            $inputESTD['eventsignupids'] = $eventSignupIds;
            $inputESTD['transactiontype'] = $transactionType;
            if ($ticketId > 0) {
                $inputESTD['ticketids'] = array($ticketId);
            } elseif ($transactionType == 'card' && count($paidTickets) > 0) {
                $inputESTD['ticketids'] = $paidTickets;
            } elseif ($transactionType == 'free' && count($freeTickets) > 0) {
                //Passing nullify param for free tickets with no ticket selection
                if ($ticketId == 0) {
                    $inputESTD['nullify'] = 1;
                }
                $inputESTD['ticketids'] = $freeTickets;
            }
            $selectESTDResponse = $this->estdHandler->getListByEventsignupIds($inputESTD);
            // Getting Seat Numbers for the EventSignupIds
            if ($seatingLayout == 1) {
                $seatingArray['eventId'] = $eventId;
                //	$seatingArray['eventsignupId'] = $eventSignupIds;
                $seatNumbers = $this->seatingHandler->getseatNumbersByEventId($seatingArray);
                if ($seatNumbers['status'] && $seatNumbers['response']['total'] > 0) {
                    $EventsignupSeats = $seatNumbers['response']['seats'];
                }
            }
        }
        //    echo "<pre>";print_r($EventsignupSeats);exit;
        if ($selectESTDResponse['status'] && $selectESTDResponse['response']['total'] > 0) {
            $estdArray = $selectESTDResponse['response']['eventSignupTicketDetailList'];
        } else {
            $output['status'] = TRUE;
            $output['statusCode'] = STATUS_OK;
            $output['response']['total'] = 0;
            $output['response']['messages'][] = ERROR_NO_RECORDS;
            return $output;
        }
        if ($ticketId > 0) {
            foreach ($estdArray as $data) {
                $ticketWiseAmounts[$data['eventsignupid']] = $data;
            }
        }
        $selectPartialPayments = array();
        if (count($partialEventsignupIds) > 0) {
            $refundHandler = new Refund_handler();
            $inputRefunds['eventsignupids'] = $partialEventsignupIds;
            $selectPartialPayments = $refundHandler->getRefundList($inputRefunds);
        }
        $indexedRefunds = array();
        if (isset($selectPartialPayments['status']) && $selectPartialPayments['status']) {
            if ($selectPartialPayments['response']['total'] > 0) {
                $indexedRefunds = commonHelperGetIdArray($selectPartialPayments['response']['refundList'], 'eventsignupid');
            }
        } elseif (count($selectPartialPayments) > 0) {
            return $selectPartialPayments;
        }

        $eventTimeZoneName = $this->eventHandler->getEventTimeZone($eventId);
        //$indexEventSignupIdArray = array();
        $incompleteUsersArray = array();
        foreach ($selectESResponse as $value) {
            //$indexEventSignupIdArray[$value['id']] = $value;
            //$eventSignupIds[] = $value['id'];
            $paid = '';
            $response[$value['id']]['id'] = $value['id'];
            if ($transactionType == 'incomplete') {
                $incompleteUsersArray[$value['userid']][] = $value['id'];
                $response[$value['id']]['failedcount'] = $value['failedcount'];
                $response[$value['id']]['comment'] = '';
            }
            $response[$value['id']]['discount'] = 0;
            $response[$value['id']]['signupDate'] = allTimeFormats(convertTime($value['signupdate'], $eventTimeZoneName, true), 11);
            $response[$value['id']]['quantity'] = $value['quantity'];
            //$response[$value['id']]['discount'] = $indexedCurrencyListById[$value['fromcurrencyid']]['currencyCode'] . ' ' . $value['discountamount'];
            $response[$value['id']]['TranStatus'] = 'Captured';
            $response[$value['id']]['barcodenumber'] = $value['barcodenumber'];
            $response[$value['id']]['paymentmodeid'] = $value['paymentmodeid'];
            $response[$value['id']]['paymenttransactionid'] = $value['paymenttransactionid'];
            $response[$value['id']]['referraldiscountamount'] = $value['referraldiscountamount'];
            $response[$value['id']]['paymentgatewayid'] = $value['paymentgatewayid'];
            $response[$value['id']]['discountamount'] = $value['discountamount'];
            $response[$value['id']]['totalamount'] = $value['totalamount'];
            $response[$value['id']]['cts'] = $value['cts'];
            $response[$value['id']]['mts'] = $value['mts'];
            $response[$value['id']]['ticketDetails'] = array();
            if (strcmp(strtolower($value['paymentstatus']), 'partialrefund') == 0) {
                $partialEventsignupIds[] = $value['id'];
            } else {
            if ($transactionType == 'free' || ($ticketId > 0 && in_array($ticketId, $freeTickets))) {
                $paid = 0;
                $paidcurrency = '';
                //Setting discount for free with paid tickets
                ////commented currency codes discounts
                //$response[$value['id']]['discount'] = $indexedCurrencyListById[$value['fromcurrencyid']]['currencyCode'] . ' ' . round($value['discountamount'] + $value['referraldiscountamount']);
                $response[$value['id']]['discount'] = round($value['discountamount'] + $value['referraldiscountamount']);
                if ($ticketId > 0) {
                    //$response[$value['id']]['discount'] = $indexedCurrencyListById[$value['fromcurrencyid']]['currencyCode'] . ' ' . round($ticketWiseAmounts[$value['id']]['discountamount'] + $ticketWiseAmounts[$value['id']]['referraldiscountamount'] + $ticketWiseAmounts[$value['id']]['bulkdiscountamount'], 2);
                    $response[$value['id']]['discount'] = round($ticketWiseAmounts[$value['id']]['discountamount'] + $ticketWiseAmounts[$value['id']]['referraldiscountamount'] + $ticketWiseAmounts[$value['id']]['bulkdiscountamount'], 2);
                }
            } else {
                $extraCovertedAmount = 0;
                    $refundAmt = isset($indexedRefunds[$value['id']]) ? $indexedRefunds[$value['id']] : 0;
                $response[$value['id']]['discount'] = round($value['discountamount'] + $value['referraldiscountamount']);
                $paidcurrency = $indexedCurrencyListById[$value['tocurrencyid']]['currencyCode'];
                if (($value['convertedamount'] > 0 && $value['conversionrate'] > 1)) {
                    //if ($value['eventextrachargeamount'] > 0) {
                    $purchaseTotal = $value['convertedamount'] * $value['quantity'];
                    $totalAmount = $value['totalamount'];
                    if ($ticketId > 0) {
                        $totalAmount = $ticketWiseAmounts[$value['id']]['totalamount'];
                        $purchaseTotal = ($ticketWiseAmounts[$value['id']]['totalamount'] * $value['convertedamount'] * $value['quantity']) / $value['totalamount'];
                        //$response[$value['id']]['discount'] = $indexedCurrencyListById[$value['fromcurrencyid']]['currencyCode'] . ' ' . round($ticketWiseAmounts[$value['id']]['discountamount'] + $ticketWiseAmounts[$value['id']]['referraldiscountamount'] + $ticketWiseAmounts[$value['id']]['bulkdiscountamount'], 2);
                        $response[$value['id']]['discount'] = round($ticketWiseAmounts[$value['id']]['discountamount'] + $ticketWiseAmounts[$value['id']]['referraldiscountamount'] + $ticketWiseAmounts[$value['id']]['bulkdiscountamount'], 2);
                    } else {
                        if ($value['eventextrachargeamount'] > 0) {
                            $convertedAmount = $value['convertedamount'] * $value['quantity'];
                            $echargeAmount = $value['eventextrachargeamount'];
                            $extraCovertedAmount = ($echargeAmount * $convertedAmount) / $totalAmount;
                        }
                    }
                    $paid = (((($purchaseTotal) - $extraCovertedAmount) * $value['conversionrate'] - $refundAmt));
                    // } 
//                        else {
//                            $paid = $indexedCurrencyListById[$value['tocurrencyid']]['currencyCode'] . ' ' . (($value['convertedamount'] * $value['quantity']) * $value['conversionrate']);
//                        }
                } else if ($value['convertedamount'] > 0) {
                    if ($ticketId > 0) {
                        $totalAmount = $ticketWiseAmounts[$value['id']]['totalamount'];
                        $purchaseTotal = round(($ticketWiseAmounts[$value['id']]['totalamount'] * $value['convertedamount'] * $value['quantity']) / $value['totalamount'], 2);
                        //$response[$value['id']]['discount'] = $indexedCurrencyListById[$value['fromcurrencyid']]['currencyCode'] . ' ' . round($ticketWiseAmounts[$value['id']]['discountamount'] + $ticketWiseAmounts[$value['id']]['referraldiscountamount'] + $ticketWiseAmounts[$value['id']]['bulkdiscountamount'], 2);
                        $response[$value['id']]['discount'] = round($ticketWiseAmounts[$value['id']]['discountamount'] + $ticketWiseAmounts[$value['id']]['referraldiscountamount'] + $ticketWiseAmounts[$value['id']]['bulkdiscountamount'], 2);
                    } else {
                        $purchaseTotal = round($value['convertedamount'] * $value['quantity'], 2);
                        if ($value['eventextrachargeamount'] > 0) {
                            //$convertedAmount = $value['convertedamount'] * $value['quantity'];
                            $totalAmount = $value['totalamount'];
                            $echargeAmount = $value['eventextrachargeamount'];
                            $extraCovertedAmount = round(($echargeAmount * $purchaseTotal) / $totalAmount, 2);
                        }
                    }
                    $paid = round(($purchaseTotal) - $extraCovertedAmount, 2);
                    $paidcurrency = 'USD';
//                        } else {
//                            $paid = 'USD' . ' ' . ($value['convertedamount'] * $value['quantity']);
//                        }
                } else if ($value['conversionrate'] > 1) {
                    $purchaseTotal = $value['totalamount'] - $value['eventextrachargeamount'];
                    if ($ticketId > 0) {
                        $purchaseTotal = $ticketWiseAmounts[$value['id']]['totalamount'];
                        //$response[$value['id']]['discount'] = $indexedCurrencyListById[$value['fromcurrencyid']]['currencyCode'] . ' ' . round($ticketWiseAmounts[$value['id']]['discountamount'] + $ticketWiseAmounts[$value['id']]['referraldiscountamount'] + $ticketWiseAmounts[$value['id']]['bulkdiscountamount'], 2);
                        $response[$value['id']]['discount'] = round($ticketWiseAmounts[$value['id']]['discountamount'] + $ticketWiseAmounts[$value['id']]['referraldiscountamount'] + $ticketWiseAmounts[$value['id']]['bulkdiscountamount'], 2);
                    }
                    $paid = round(($purchaseTotal) * $value['conversionrate'] - $indexedRefunds[$value['id']]);
                } else {
                    $purchaseTotal = $value['totalamount'] - round($value['eventextrachargeamount']);
                    if ($ticketId > 0) {
                        $purchaseTotal = $ticketWiseAmounts[$value['id']]['totalamount'];
                        //$response[$value['id']]['discount'] = $indexedCurrencyListById[$value['fromcurrencyid']]['currencyCode'] . ' ' . round($ticketWiseAmounts[$value['id']]['discountamount'] + $ticketWiseAmounts[$value['id']]['referraldiscountamount'] + $ticketWiseAmounts[$value['id']]['bulkdiscountamount'], 2);
                        $response[$value['id']]['discount'] = round($ticketWiseAmounts[$value['id']]['discountamount'] + $ticketWiseAmounts[$value['id']]['referraldiscountamount'] + $ticketWiseAmounts[$value['id']]['bulkdiscountamount'], 2);
                    }
                    if ($refundAmt > 0 && $value['fromcurrencyid'] != 1) {
                        $refundAmt = 0;
                    }
                    $paid = round($purchaseTotal - $refundAmt,2);
                    $paidcurrency = $indexedCurrencyListById[$value['fromcurrencyid']]['currencyCode'];
                }
            }
            $response[$value['id']]['paidamount'] = $paid;
            $response[$value['id']]['paidcurrency'] = $paidcurrency;
            $response[$value['id']]['currencyCode'] = $indexedCurrencyListById[$value['fromcurrencyid']]['currencyCode'];
        }
        }
        if ($userData['status'] && $userData['response']['total'] > 0) {
            $userDataList = commonHelperGetIdArray($userData['response']['userData']);
        } else {
            return $userData;
        }
        $commentResponse = array();
        if ($transactionType == 'incomplete' || $transactionType == 'cod') {
            $inputArray['eventsignupids'] = $eventSignupIds;
            $inputArray['commenttype'] = 'incomplete';
            $commentResponse = $this->commentHandler->getCommentByEventsignupIds($inputArray);
        }
        //print_r($commentResponse);exit;
        //$IndexedCommentArray = array();
        if (count($commentResponse) > 0 && $commentResponse['status'] && $commentResponse['response']['total'] > 0) {
            //$IndexedCommentArray = commonHelperGetIdArray($commentResponse['response']['commentList'], 'eventsignupid');
            foreach ($commentResponse['response']['commentList'] as $comment) {
                $response[$comment['eventsignupid']]['comment'] = $comment['comment'];
            }
        }
//        $inputTkt['eventId'] = $eventId;
//        $inputTkt['status'] = 0;
//        $selectTicketResponse = $this->ticketHandler->getTicketName($inputTkt);
//        if ($selectTicketResponse['status'] && $selectTicketResponse['response']['total'] > 0) {
//            $ticketDataIdIndexed = commonHelperGetIdArray($selectTicketResponse['response']['ticketName']);
//        } else {
//            return $selectTicketResponse;
//        }
//        if ($transactionType == 'card' || $transactionType == 'free') {
//            foreach ($ticketDataIdIndexed as $tickets) {
//                if ($tickets['type'] == 'paid' || $tickets['type'] == 'donation') {
//                    $paidTickets[] = $tickets['id'];
//                } elseif ($tickets['type'] == 'free') {
//                    $freeTickets[] = $tickets['id'];
//                }
//            }
//        }
//        if (count($eventSignupIds) > 0) {
//            $inputESTD['eventsignupids'] = $eventSignupIds;
//            $inputESTD['transactiontype'] = $transactionType;
//            if ($ticketId > 0) {
//                $inputESTD['ticketids'] = array($ticketId);
//            }
//            if ($transactionType == 'card' && count($paidTickets) > 0) {
//                $inputESTD['ticketids'] = $paidTickets;
//            } elseif ($transactionType == 'free' && count($freeTickets) > 0) {
//                $inputESTD['ticketids'] = $freeTickets;
//            }
//            $selectESTDResponse = $this->estdHandler->getListByEventsignupIds($inputESTD);
//        }
//        print_r($selectESTDResponse);exit;
        $ticketData = $estdEsIds = array();
        $lastKey = end(array_keys($estdArray));
        if ($selectESTDResponse['status'] && $selectESTDResponse['response']['total'] > 0) {
            //foreach ($selectESTDResponse['response']['eventSignupTicketDetailList'] as $value) {
            foreach ($estdArray as $key => $value) {
                //$ticketData = array();
                $estdEsIds[$value['eventsignupid']][] = $value['ticketid'];
                $esId = $value['eventsignupid'];
                if ($value['amount'] == 0) {
                    $ticketData[$value['ticketid']]['ticketamount'] = 0;
                    $ticketData[$value['ticketid']]['amountcurrency'] = '';
                } else {
                    $ticketData[$value['ticketid']]['amountcurrency'] = $indexedCurrencyListById[$indexEventSignupIdArray[$value['eventsignupid']]['fromcurrencyid']]['currencyCode'] ;
                    $ticketData[$value['ticketid']]['ticketamount'] = $value['amount'];
                }
                //$ticketData[$value['ticketid']]['amount'] = $indexedCurrencyListById[$ticketDataIdIndexed[$value['ticketid']]['currencyid']]['currencyCode'] . ' ' . $value['amount'];
                $ticketData[$value['ticketid']]['tickettype'] = $ticketDataIdIndexed[$value['ticketid']]['name'];
                $ticketData[$value['ticketid']]['quantity'] = $value['ticketquantity'];
                if ($transactionType == 'affiliate') {
                    $ticketData[$value['ticketid']]['promotercode'] = $indexEventSignupIdArray[$value['eventsignupid']]['promotercode'];
                }
                $ticketData[$value['ticketid']]['seats'] = isset($EventsignupSeats[$value['eventsignupid']][$value['ticketid']]['GridPosition']) ? $EventsignupSeats[$value['eventsignupid']][$value['ticketid']]['GridPosition'] : '';
                if (isset($estdArray[$key + 1]) && $esId != $estdArray[$key + 1]['eventsignupid']) {
                    $response[$value['eventsignupid']]['ticketDetails'] = $ticketData;
                    //$response[$value['eventsignupid']]['ticketDetails'][$value['ticketid']] = $ticketData;
                    $ticketData = array();
                } elseif ($key == $lastKey) {
                    $response[$value['eventsignupid']]['ticketDetails'] = $ticketData;

                    //$response[$value['eventsignupid']]['ticketDetails'][$value['ticketid']] = $ticketData;
                }
                if ($seatingLayout == 1) {
                    $response[$value['eventsignupid']]['seats'] = $EventsignupSeats[$value['eventsignupid']]['GridPosition'];
                }
                $userIdIndex = $indexEventSignupIdArray[$value['eventsignupid']]['userid'];
                $response[$value['eventsignupid']]['contactDetails']['name'] = $userDataList[$userIdIndex]['name'];
                $response[$value['eventsignupid']]['contactDetails']['email'] = $userDataList[$userIdIndex]['email'];
                $response[$value['eventsignupid']]['contactDetails']['phone'] = $userDataList[$userIdIndex]['phone'];
                $response[$value['eventsignupid']]['contactDetails']['userid'] = $userDataList[$userIdIndex]['id'];
                $response[$value['eventsignupid']]['contactDetails']['mobile'] = $userDataList[$userIdIndex]['mobile'];
                $response[$value['eventsignupid']]['contactDetails']['company'] = $userDataList[$userIdIndex]['company'];
                //$response[$value['eventsignupid']]['ticketDetails'][$value['ticketid']] = $ticketData;
            }
            $inputAttendees['eventsignupids'] = $eventSignupIds;
            if ($transactionType == 'card' && count($paidTickets) > 0) {
                $inputAttendees['ticketids'] = $paidTickets;
            }
            if ($ticketId > 0) {
                $inputAttendees['ticketids'] = array($ticketId);
            }
            //$inputAttendees['primary'] = 1;
            $selectAttendeeResponse = $this->attendeeHandler->getListByEventsignupIds($inputAttendees);
        } else {
            $output['status'] = TRUE;
            $output['statusCode'] = STATUS_OK;
            $output['response']['total'] = 0;
            $output['response']['messages'][] = ERROR_NO_RECORDS;
            return $output;
        }
//        $ticketData = array();
//        $lastKey = end(array_keys($estdArray));
//        foreach ($estdArray as $key => $value) {
//            $esId = $value['eventsignupid'];
//            $ticketData[$value['ticketid']]['amount'] = $indexedCurrencyListById[$ticketDataIdIndexed[$value['ticketid']]['currencyid']]['currencyCode'] . ' ' . $value['amount'];
//            $ticketData['tickettype'] = $ticketDataIdIndexed[$value['ticketid']]['name'];
//            $ticketData['quantity'] = $value['ticketquantity'];
//            if ($transactionType == 'affiliate') {
//                $ticketData['promotercode'] = $indexEventSignupIdArray[$value['eventsignupid']]['promotercode'];
//            }
//            if (isset($estdArray[$key + 1]) && $esId != $estdArray[$key + 1]['eventsignupid']) {
//                $response[$value['eventsignupid']]['ticketDetails'][$value['ticketid']] = $ticketData;
//                $ticketData = array();
//            } elseif ($key == $lastKey) {
//                $response[$value['eventsignupid']]['ticketDetails'][$value['ticketid']] = $ticketData;
//            }
//            $userIdIndex = $indexEventSignupIdArray[$value['eventsignupid']]['userid'];
//            $response[$value['eventsignupid']]['contactDetails']['name'] = $userDataList[$userIdIndex]['name'];
//            $response[$value['eventsignupid']]['contactDetails']['email'] = $userDataList[$userIdIndex]['email'];
//            $response[$value['eventsignupid']]['contactDetails']['phone'] = $userDataList[$userIdIndex]['phone'];
//            $response[$value['eventsignupid']]['contactDetails']['userid'] = $userDataList[$userIdIndex]['id'];
//        }
//        print_r($selectESTDResponse);print_r($selectAttendeeResponse);exit;
        if ($selectAttendeeResponse['status'] && $selectAttendeeResponse['response']['total'] > 0) {
            foreach ($selectAttendeeResponse['response']['attendeeList'] as $value) {
                $attendeeIds[] = $value['id'];
            }
        } else {
            return $selectAttendeeResponse;
        }
        if (count($attendeeIds) > 0) {
            $commonFieldList = $this->commonfieldHandler->getCommonfieldList();
        }
        if ($commonFieldList['status'] && $commonFieldList['response']['total'] > 0) {
            foreach ($commonFieldList['response']['commonfieldList'] as $value) {
                if ($value['name'] == 'Full Name' || $value['name'] == 'Email Id' || $value['name'] == 'City' || $value['name'] == 'Mobile No') {
                    $commonReqFields[] = $value['id'];
                }
                $commonCustomFieldsIdIndexed[$value['id']] = $value['name'];
            }
            $inputAttendeedetail['attendeeids'] = $attendeeIds;
            $inputAttendeedetail['commonfieldids'] = $commonReqFields;
            $selectCustomFieldDataResponse = $this->attendeedetailHandler->getListByAttendeeIds($inputAttendeedetail);
        } else {
            return $commonFieldList;
        }
        if ($selectCustomFieldDataResponse['status'] && $selectCustomFieldDataResponse['response']['total']) {
            $IndexedCustomFieldDataArray = array();
            foreach ($selectCustomFieldDataResponse['response']['attendeedetailList'] as $value) {
                $IndexedCustomFieldDataArray[$value['attendeeid']][$commonCustomFieldsIdIndexed[$value['commonfieldid']]] = $value['value'];
            }
            //print_r($IndexedCustomFieldDataArray);exit;
            foreach ($selectAttendeeResponse['response']['attendeeList'] as $value) {
                //paid,free show free and 100% discount in free transaction filter
                if (isset($estdEsIds[$value['eventsignupid']]) && in_array($value['ticketid'], array_values($estdEsIds[$value['eventsignupid']]))) {
                    $response[$value['eventsignupid']]['ticketDetails'][$value['ticketid']]['Name'] = isset($IndexedCustomFieldDataArray[$value['id']]['Full Name']) ? $IndexedCustomFieldDataArray[$value['id']]['Full Name'] : '';
                    $response[$value['eventsignupid']]['ticketDetails'][$value['ticketid']]['City'] = isset($IndexedCustomFieldDataArray[$value['id']]['City']) ? $IndexedCustomFieldDataArray[$value['id']]['City'] : '';
                    $response[$value['eventsignupid']]['ticketDetails'][$value['ticketid']]['Email'] = isset($IndexedCustomFieldDataArray[$value['id']]['Email Id']) ? $IndexedCustomFieldDataArray[$value['id']]['Email Id'] : '';
                    $response[$value['eventsignupid']]['ticketDetails'][$value['ticketid']]['Mobile'] = isset($IndexedCustomFieldDataArray[$value['id']]['Mobile No']) ? $IndexedCustomFieldDataArray[$value['id']]['Mobile No'] : '';
                }
            }
        } else {
            return $selectCustomFieldDataResponse;
        }
//        print_r($response);exit;
//        $selectPartialPayments = array();
//        if (count($partialEventsignupIds) > 0) {
//            $refundHandler = new Refund_handler();
//            $inputRefunds['eventsignupids'] = $partialEventsignupIds;
//            $selectPartialPayments = $refundHandler->getRefundList($inputRefunds);
//        }
//        $indexedRefunds = array();
//        if (isset($selectPartialPayments['status']) && $selectPartialPayments['status']) {
//            if ($selectPartialPayments['response']['total'] > 0) {
//                $indexedRefunds = commonHelperGetIdArray($selectPartialPayments['response']['refundList'], 'eventsignupid');
//            }
//        } elseif (count($selectPartialPayments) > 0) {
//            return $selectPartialPayments;
//        }
        
        foreach ($indexedRefunds as $key => $value) {
            if (($indexEventSignupIdArray[$key]['convertedamount'] > 0 && $indexEventSignupIdArray[$key]['conversionrate'] > 1)) {
                $response[$key]['paid'][$indexedCurrencyListById[$indexEventSignupIdArray[$key]['tocurrencyid']]['currencyCode']] = ($indexEventSignupIdArray[$key]['convertedamount'] * $indexEventSignupIdArray[$key]['quantity'] * $indexEventSignupIdArray[$key]['conversionrate']) - $value['totalrefundamount'];
            }if ($indexEventSignupIdArray[$key]['conversionrate'] > 1) {
                $response[$key]['paid'][$indexedCurrencyListById[$value['tocurrencyid']]['currencyCode']] = (($value['totalamount'] - $value['eventextrachargeamount']) * $value['conversionrate']);
            } else {
                $response[$key]['paid'][$indexedCurrencyListById[$value['fromcurrencyid']]['currencyCode']] = ($value['totalamount']);
            }
        }

        //success transaction done
        if ($transactionType == 'incomplete') {
            $select['successcount'] = 'COUNT(' . $this->ci->Eventsignup_model->id . ')';
            $select['id'] = $this->ci->Eventsignup_model->id;
            $select['userid'] = $this->ci->Eventsignup_model->userid;
            $this->ci->Eventsignup_model->setSelect($select);
            $whereES[$this->ci->Eventsignup_model->transactionstatus] = 'success';
            $this->ci->Eventsignup_model->setWhere($whereES);
            $this->ci->Eventsignup_model->setFindInSet($findInSet);
            $this->ci->Eventsignup_model->setGroupBy($groupBy);
            $bookedSuccessResponse = $this->ci->Eventsignup_model->get();
        }
        if (isset($bookedSuccessResponse) && !empty($bookedSuccessResponse)) {
            foreach ($bookedSuccessResponse as $bookedRecord) {
                if (isset($incompleteUsersArray[$bookedRecord['userid']])) {
                    foreach ($incompleteUsersArray[$bookedRecord['userid']] as $userids => $eventsignupid) {
                        if (isset($response[$eventsignupid])) {
                            unset($response[$eventsignupid]);
                        }
                    }
                }
            }
            if (count($response) == 0) {
                $output['status'] = TRUE;
                $output['statusCode'] = STATUS_OK;
                $output['response']['total'] = 0;
                $output['response']['messages'][] = ERROR_NO_RECORDS;
                return $output;
            }
        }
        //unset free transaction,ticketid--->unmatched records
        $notMatchedEsIds = array_diff($eventSignupIds, array_keys($estdEsIds));
        foreach ($notMatchedEsIds as $key => $value) {
            unset($response[$value]);
        }
        $output['response']['seatingLayout'] = $seatingLayout;
        $output['status'] = TRUE;
        $output['response']['transactionList'] = $response;
        $output['response']['total'] = count($response);
        $output['response']['totalTransactionCount'] = $seletESCountResponse[0]['totalcount'];
        $output['statusCode'] = STATUS_OK;
        return $output;
    }

    public function getDetailExportInfo($input) {
        $validationResponse = $this->validateGetTransaction($input);
        if (!$validationResponse['status']) {
            return $validationResponse;
        }
        //echo 'in';exit;
        $eventId = $input['eventid'];
        require_once (APPPATH . 'handlers/seating_handler.php');
        $this->seatingHandler = new Seating_handler();
        $seatingLayout = 0;
        $inputSeatlayout['eventid'] = $eventId;
        $checkSeatlayout = $this->seatingHandler->checkLayout($inputSeatlayout);
        if ($checkSeatlayout && $checkSeatlayout['response']['seatingEnabled'] == true) {
            $seatingLayout = 1;
        }
        //$seatingLayout=1;
        $ticketId = isset($input['ticketid']) ? $input['ticketid'] : 0;
        $reportType = $input['reporttype'];
        $report_types = $this->ci->config->item('report_types');
        if (!in_array($input['reporttype'], $report_types)) {
            $reportType = 'summary';
        }
        $transactionType = $input['transactiontype'];
        $page = $input['page'];
        $pageLimit=REPORTS_TRANSACTION_LIMIT;
        if(isset($input['REPORTS_TRANSACTION_LIMIT'])){
            
            $pageLimit=$input['REPORTS_TRANSACTION_LIMIT'];
        }
        $start = ($page - 1) * $pageLimit;
        $response = $where_in_ES = array();
        $groupBy = $findInSet = $notLike = $where_not_in_ES = $setOrWhere = $orfindInSet = array();
        $eventSignupIds = $selectESTDResponse = $ticketIds = $selectTicketResponse = $partialEventsignupIds = $ticketDataIdIndexed = $selectAttendeeResponse = $attendeeIds = $selectAttendeedetailResponse = $commentResponse = array();
        $whereES[$this->ci->Eventsignup_model->eventid] = $eventId;
        if(isset($input['eventSignupId']) && $input['eventSignupId'] > 0){
           $whereES[$this->ci->Eventsignup_model->id] = $input['eventSignupId']; 
        }
        
        $whereES[$this->ci->Eventsignup_model->deleted] = 0;
        $whereES[$this->ci->Eventsignup_model->transactionstatus] = 'success';
        //currencies list
        $currencyResponse = $this->currencyHanlder->getCurrencyList();
        if ($currencyResponse['status'] && $currencyResponse['response']['total'] > 0) {
            $indexedCurrencyListById = commonHelperGetIdArray($currencyResponse['response']['currencyList'], 'currencyId');
            $indexedCurrencyListByCode = commonHelperGetIdArray($currencyResponse['response']['currencyList'], 'currencyCode');
        } else {
            return $currencyResponse;
        }
//        if (isset($input['promotercode']) && $input['promotercode'] == 'promoter') {
//            $whereES[$this->ci->Eventsignup_model->promotercode . ' != '] = 'organizer';
//            $whereES[$this->ci->Eventsignup_model->promotercode . ' != '] = '';
//        } elseif (isset($input['promotercode']) && $input['promotercode'] != 'meraevents') {
//            $whereES[$this->ci->Eventsignup_model->promotercode] = $input['promotercode'];
//        } elseif (isset($input['promotercode'])) {
//            $setOrWhere[$this->ci->Eventsignup_model->promotercode] = '';
//            $setOrWhere[$this->ci->Eventsignup_model->promotercode] = '0';
//        }
        if ((isset($input['promotercode']) && $input['promotercode'] == 'promoter') || (!isset($input['promotercode']) && $transactionType == 'affiliate')) {
            $where_not_in_ES[$this->ci->Eventsignup_model->promotercode] = array('organizer', '', '0');
            //$whereES[$this->ci->Eventsignup_model->promotercode . ' != '] = '';
            //$whereES[$this->ci->Eventsignup_model->promotercode . ' != '] = '0';
        } elseif (isset($input['promotercode']) && $input['promotercode'] == 'organizer') {
            $whereES[$this->ci->Eventsignup_model->promotercode] = 'organizer';
        } elseif (isset($input['promotercode']) && $input['promotercode'] != 'meraevents') {
            $whereES[$this->ci->Eventsignup_model->promotercode] = $input['promotercode'];
        } elseif (isset($input['promotercode'])) {
            $where_in_ES[$this->ci->Eventsignup_model->promotercode] = array('', '0');
            $where_not_in_ES[$this->ci->Eventsignup_model->paymentgatewayid] = array('7', '8');
            $where_not_in_ES[$this->ci->Eventsignup_model->paymentmodeid] = array('4');
            $whereES[$this->ci->Eventsignup_model->referraldiscountamount] = '0';
        }
        if (isset($input['currencycode'])) {
            $whereES[$this->ci->Eventsignup_model->fromcurrencyid] = $indexedCurrencyListByCode[$input['currencycode']]['currencyId'];
        }
        if ($transactionType == 'refund') {
            $whereES[$this->ci->Eventsignup_model->paymentstatus] = 'Refunded';
        } else {
            $where_not_in_ES[$this->ci->Eventsignup_model->paymentstatus] = array('Canceled', 'Refunded');
        }


        if ($ticketId > 0) {
            $findInSet[$ticketId] = $this->ci->Eventsignup_model->transactionticketids;
        }
        switch ($transactionType) {
            case 'all':
                break;
            case 'card':
                $whereES[$this->ci->Eventsignup_model->paymentmodeid] = 1;
                $whereES[$this->ci->Eventsignup_model->totalamount . " > "] = 0;
                $where_not_in_ES[$this->ci->Eventsignup_model->paymentgatewayid] = array('', 'A1', 0, 7, 8);
                break;
            case 'cod':
                $whereES[$this->ci->Eventsignup_model->paymentmodeid] = 2;
                break;
            case 'free':
                $setOrWhere[$this->ci->Eventsignup_model->totalamount] = 0;
                $orfindInSet['free'] = $this->ci->Eventsignup_model->transactiontickettype;
                $where_not_in_ES[$this->ci->Eventsignup_model->paymentmodeid] = 4;
                break;
            case 'offline':
                $whereES[$this->ci->Eventsignup_model->paymentmodeid] = 4;
                // $where_not_in_ES[$this->ci->Eventsignup_model->totalamount] = '0';
                break;
            case 'incomplete':
                $whereES[$this->ci->Eventsignup_model->transactionstatus] = 'pending';
                $groupBy = array($this->ci->Eventsignup_model->userid);
                break;
            case 'boxoffice':
                $where_in_ES[$this->ci->Eventsignup_model->paymentgatewayid] = array(7, 8);
                break;
            case 'viral':
                $whereES[$this->ci->Eventsignup_model->referraldiscountamount . " > "] = 0;
                break;
            case 'affiliate':
                $newData = array('', '0');
                if (isset($where_not_in_ES[$this->ci->Eventsignup_model->promotercode])) {
                    $addedData = $where_not_in_ES[$this->ci->Eventsignup_model->promotercode];
                    $newData = array_merge($newData, $addedData);
                }
                //exclude me sales
                $where_not_in_ES[$this->ci->Eventsignup_model->promotercode] = array_unique($newData);
                $notLike[$this->ci->Eventsignup_model->promotercode] = 'OFFLINE_';
                break;
            case 'cancel':
                $whereES[$this->ci->Eventsignup_model->paymentstatus] = 'Canceled';
                $where_not_in_ES = array();
                unset($whereES[$this->ci->Eventsignup_model->transactionstatus]);
                break;
            case 'refund':
                break;
            default:
                break;
        }
        if (isset($input['modifiedDate'])) {
            $whereES[$this->ci->Eventsignup_model->mts . " > "] = $input['modifiedDate'];
        }
        //to get total count
        $selectESCount['totalcount'] = 'COUNT( ' . $this->ci->Eventsignup_model->id . ' )';
        $this->ci->Eventsignup_model->setSelect($selectESCount);
        $this->ci->Eventsignup_model->setWhereNotIn($where_not_in_ES);
        $this->ci->Eventsignup_model->setWhere($whereES);
        $this->ci->Eventsignup_model->setOrWhere($setOrWhere, ' or ', ' = ', $orfindInSet);
        $this->ci->Eventsignup_model->setNotLike($notLike);
        $this->ci->Eventsignup_model->setWhereIns($where_in_ES);
        $this->ci->Eventsignup_model->setFindInSet($findInSet);
        $this->ci->Eventsignup_model->setGroupBy($groupBy);
        $this->ci->Eventsignup_model->setRecords(0, 0);
        $seletESCountResponse = $this->ci->Eventsignup_model->get();
        //echo $this->ci->db->last_query();exit;
        //print_r($seletESCountResponse);exit;
        if (count($seletESCountResponse) == 0) {
            $output['status'] = TRUE;
            $output['statusCode'] = STATUS_OK;
            $output['response']['total'] = 0;
            $output['response']['messages'][] = ERROR_NO_RECORDS;
            return $output;
        }
        //to get data
        $selectES['id'] = $this->ci->Eventsignup_model->id;
        $selectES['userid'] = $this->ci->Eventsignup_model->userid;
        $selectES['signupdate'] = $this->ci->Eventsignup_model->signupdate;
        $selectES['transactiontickettype'] = $this->ci->Eventsignup_model->transactiontickettype;
        $selectES['paymentstatus'] = $this->ci->Eventsignup_model->paymentstatus;
        $selectES['barcodenumber'] = $this->ci->Eventsignup_model->barcodenumber;
        $selectES['fromcurrencyid'] = $this->ci->Eventsignup_model->fromcurrencyid;
        $selectES['tocurrencyid'] = $this->ci->Eventsignup_model->tocurrencyid;
        $selectES['conversionrate'] = $this->ci->Eventsignup_model->conversionrate;
        $selectES['convertedamount'] = $this->ci->Eventsignup_model->convertedamount;
        $selectES['quantity'] = $this->ci->Eventsignup_model->quantity;
        $selectES['totalamount'] = $this->ci->Eventsignup_model->totalamount;
        $selectES['discountamount'] = $this->ci->Eventsignup_model->discountamount;
        $selectES['eventextrachargeamount'] = $this->ci->Eventsignup_model->eventextrachargeamount;
        $selectES['paymenttransactionid'] = $this->ci->Eventsignup_model->paymenttransactionid;
        $selectES['promotercode'] = $this->ci->Eventsignup_model->promotercode;
        $selectES['discountcode'] = $this->ci->Eventsignup_model->discount;
        $selectES['paymentstatus'] = $this->ci->Eventsignup_model->paymentstatus;
        $selectES['referraldiscountamount'] = $this->ci->Eventsignup_model->referraldiscountamount;
        $selectES['paymentmodeid'] = $this->ci->Eventsignup_model->paymentmodeid;
        $selectES['attendeeid'] = $this->ci->Eventsignup_model->attendeeid;
        $selectES['paymentgatewayid'] = $this->ci->Eventsignup_model->paymentgatewayid;
        $selectES['transactionticketids'] = $this->ci->Eventsignup_model->transactionticketids;
        $selectES['cts'] = $this->ci->Eventsignup_model->cts;
        $selectES['mts'] = $this->ci->Eventsignup_model->mts;

        if ($transactionType == 'affiliate') {
            $selectES['promotercode'] = $this->ci->Eventsignup_model->promotercode;
        }
        $this->ci->Eventsignup_model->setSelect($selectES);
        $this->ci->Eventsignup_model->setWhereNotIn($where_not_in_ES);
        $this->ci->Eventsignup_model->setRecords($pageLimit, $start);
        $this->ci->Eventsignup_model->setWhere($whereES);
        $this->ci->Eventsignup_model->setOrWhere($setOrWhere, ' or ', ' = ', $orfindInSet);
        $this->ci->Eventsignup_model->setNotLike($notLike);
        $this->ci->Eventsignup_model->setWhereIns($where_in_ES);
        $this->ci->Eventsignup_model->setFindInSet($findInSet);
        $this->ci->Eventsignup_model->setGroupBy($groupBy);
        if(!isset($input['orderStatus'])){
            $orderBy = array($this->ci->Eventsignup_model->id . ' desc');
        }else{
            $orderBy = array($this->ci->Eventsignup_model->mts);
        }    
        
        $this->ci->Eventsignup_model->setOrderBy($orderBy);
       
        $selectESResponse = $this->ci->Eventsignup_model->get();
        if (count($selectESResponse) == 0) {
            $output['status'] = TRUE;
            $output['statusCode'] = STATUS_OK;
            $output['response']['total'] = 0;
            $output['response']['messages'][] = ERROR_NO_RECORDS;
            return $output;
        }
        $indexEventSignupIdArray = $promoterCodeList = array();
        $eventTimeZoneName = $this->eventHandler->getEventTimeZone($eventId);
        foreach ($selectESResponse as $value) {
            $indexEventSignupIdArray[$value['id']] = $value;
            $eventSignupIds[] = $value['id'];
            $userIdList[] = $value['userid'];
            if (!empty($value['promotercode']) && strtolower($value['promotercode']) != 'organizer') {
                $promoterCodeList[] = $value['promotercode'];
            }
            if (strcmp(strtolower($value['paymentstatus']), 'partialrefund') == 0) {
                $partialEventsignupIds[] = $value['id'];
            }
        }
        $this->userHandler = new User_handler();
        $userInputs['userIdList'] = array_unique($userIdList); //To pass as an array to the method
        $userData = $this->userHandler->getUserDetails($userInputs);
        if ($userData['status'] && $userData['response']['total'] > 0) {
            $userDataList = commonHelperGetIdArray($userData['response']['userData']);
        } else {
            return $userData;
        }
        $promoterCodeList = array_unique($promoterCodeList);
        $selectPartialPayments = array();
        if (count($partialEventsignupIds) > 0) {
            $refundHandler = new Refund_handler();
            $inputRefunds['eventsignupids'] = $partialEventsignupIds;
            $selectPartialPayments = $refundHandler->getRefundList($inputRefunds);
        }
        $indexedRefunds = array();
        if (isset($selectPartialPayments['status']) && $selectPartialPayments['status']) {
            if ($selectPartialPayments['response']['total'] > 0) {
                $indexedRefunds = commonHelperGetIdArray($selectPartialPayments['response']['refundList'], 'eventsignupid');
            }
        } elseif (count($selectPartialPayments) > 0) {
            return $selectPartialPayments;
        }
        $inputTkt['eventId'] = $eventId;
        $inputTkt['status'] = 0;
        $selectTicketResponse = $this->ticketHandler->getTicketName($inputTkt);
        if ($selectTicketResponse['status'] && $selectTicketResponse['response']['total'] > 0) {
            $ticketDataIdIndexed = commonHelperGetIdArray($selectTicketResponse['response']['ticketName']);
        } else {
            return $selectTicketResponse;
        }
        $paidTickets = $freeTickets = array();
        foreach ($ticketDataIdIndexed as $tickets) {
            if ($tickets['type'] == 'paid' || $tickets['type'] == 'donation' || $tickets['type'] == 'addon') {
                $paidTickets[] = $tickets['id'];
            } elseif ($tickets['type'] == 'free') {
                $freeTickets[] = $tickets['id'];
            }
        }
        if (count($eventSignupIds) > 0) {
            $inputESTD['eventsignupids'] = $eventSignupIds;
            $inputESTD['transactiontype'] = $transactionType;
            if ($ticketId > 0) {
                $inputESTD['ticketids'] = array($ticketId);
            } else if ($transactionType == 'card' && count($paidTickets) > 0) {
                $inputESTD['ticketids'] = $paidTickets;
            } elseif ($transactionType == 'free' && count($freeTickets) > 0) {
                if ($ticketId == 0) {
                    $inputESTD['nullify'] = 1;
                }
                $inputESTD['ticketids'] = $freeTickets;
            }
            $selectESTDResponse = $this->estdHandler->getListByEventsignupIds($inputESTD);
            // Getting Seat Numbers for the EventSignupIds
            if ($seatingLayout == 1) {
                $seatingArray['eventId'] = $eventId;
                // $seatingArray['eventsignupId'] = $eventSignupIds;
                $seatNumbers = $this->seatingHandler->getseatNumbersByEventId($seatingArray);
                if ($seatNumbers['status'] && $seatNumbers['response']['total'] > 0) {
                    $EventsignupSeats = $seatNumbers['response']['seats'];
                }
            }
        }
        /* echo $this->ci->db->last_query();
          echo "<pre>";print_r($EventsignupSeats);exit; */
        if ($selectESTDResponse['status'] && $selectESTDResponse['response']['total'] > 0) {
            $inputAttendees['eventsignupids'] = $eventSignupIds;
            if ($ticketId > 0) {
                $inputAttendees['ticketids'] = array($ticketId);
            }
            //$inputAttendees['primary'] = 1;
            $selectAttendeeResponse = $this->attendeeHandler->getListByEventsignupIds($inputAttendees);
        } else {
            $output['status'] = TRUE;
            $output['statusCode'] = STATUS_OK;
            $output['response']['total'] = 0;
            $output['response']['messages'][] = ERROR_NO_RECORDS;
            return $output;
        }
        if ($selectAttendeeResponse['status'] && $selectAttendeeResponse['response']['total'] > 0) {
            foreach ($selectAttendeeResponse['response']['attendeeList'] as $value) {
                $attendeeIds[] = $value['id'];
            }
        } else {
            return $selectAttendeeResponse;
        }
        if (count($attendeeIds) > 0) {
            $inputCustomFieldData['attendeeids'] = $attendeeIds;
            $selectAllCustomFieldDataResponse = $this->attendeedetailHandler->getListByAttendeeIds($inputCustomFieldData);
        }
        if ($selectAllCustomFieldDataResponse['status'] && $selectAllCustomFieldDataResponse['response']['total'] > 0) {
            $inputCustomFields['eventId'] = $eventId;
            $inputCustomFields['allfields'] = 1;
            $inputCustomFields['activeCustomField'] = 1;
            $eventCustomFields = $this->configureHandler->getCustomFields($inputCustomFields);
        } else {
            return $selectAllCustomFieldDataResponse;
        }

        if ($eventCustomFields['status'] && $eventCustomFields['response']['total'] > 0) {
            $indexedCustomFieldNames = commonHelperGetIdArray($eventCustomFields['response']['customFields']);
            foreach ($selectAllCustomFieldDataResponse['response']['attendeedetailList'] as $value) {
                $IndexedCustomFieldDataArray[$value['attendeeid']][$indexedCustomFieldNames[$value['customfieldid']]['fieldname']] = $value['value'];
            }
            //print_r($indexedCustomFieldNames);exit;
            $responseCustomFields = array();
            $i = 0;
            $attendeesList = $selectAttendeeResponse['response']['attendeeList'];
            //print_r($attendeesList);
            foreach ($attendeesList as $key => $value) {
                if ($attendeesList[$key]['eventsignupid'] != $attendeesList[$key + 1]['eventsignupid'] || ($attendeesList[$key]['eventsignupid'] == $attendeesList[$key + 1]['eventsignupid'] && $attendeesList[$key]['ticketid'] != $attendeesList[$key + 1]['ticketid'])) {
                    $i = 0;
                }
                foreach ($indexedCustomFieldNames as $customField) {
                            $responseCustomFields[$value['eventsignupid']][$value['ticketid']][$i][$customField['fieldname']] = $IndexedCustomFieldDataArray[$value['id']][$customField['fieldname']];
                        }
                $i++;
            }
        } else {
            return $eventCustomFields;
        }
        //print_r($responseCustomFields);
        $inputESTaxes['eventsignupids'] = $eventSignupIds;
        $getTaxes = $this->eventsignupTaxHandler->getTaxes($inputESTaxes);
        //print_r($getTaxes);exit;
        $responseTaxes = array();
        if ($getTaxes['status'] && $getTaxes['response']['total'] > 0) {
            foreach ($getTaxes['response']['taxList'] as $value) {
                $taxMappingIds[] = $value['taxmappingid'];
            }
            if (count($taxMappingIds) > 0) {
                $inputTaxMapping['ids'] = $taxMappingIds;
                $taxMappingResponse = $this->taxMappingHandler->getTaxmapping($inputTaxMapping);
            }
            if ($taxMappingResponse['status'] && $taxMappingResponse['response']['taxMappingList']) {
                $indexedTaxMapping = commonHelperGetIdArray($taxMappingResponse['response']['taxMappingList']);
                $taxes = $this->taxHandler->getTaxList();
            } else {
                return $taxMappingResponse;
            }
            if ($taxes['status'] && $taxes['response']['total'] > 0) {
                $indexedTaxes = commonHelperGetIdArray($taxes['response']['taxList']);
            } else {
                return $taxes;
            }
            foreach ($getTaxes['response']['taxList'] as $value) {
                $taxLabelIndex = $indexedTaxMapping[$value['taxmappingid']]['taxid'];
                $responseTaxes[$value['eventsignupid']][$value['ticketid']][$indexedTaxes[$taxLabelIndex]['label']] = $value['taxamount'];
            }
        }
        $commentResponseData = $commentResponse = array();
        if ($transactionType == 'incomplete' || $transactionType == 'cod' || $transactionType == 'cancel') {
            $inputArray['eventsignupids'] = $eventSignupIds;
            $inputArray['commenttype'] = 'incomplete';
            $commentResponseData = $this->commentHandler->getCommentByEventsignupIds($inputArray);
        }
        if (count($commentResponseData) > 0 && $commentResponseData['status'] && $commentResponseData['response']['total'] > 0) {
            foreach ($commentResponseData['response']['commentList'] as $data) {
                $commentResponse[$data['eventsignupid']] = $data['comment'];
            }
        }
        //Getting promoter name
        if (count($promoterCodeList) > 0) {
            $inputArrayPromoter['eventId'] = $eventId;
            $inputArrayPromoter['promoterCodeList'] = $promoterCodeList;
            $this->promoterHandler = new Promoter_handler();
            $promoterOutputResponse = $this->promoterHandler->getPromoterList($inputArrayPromoter);
                $IndexedPromoterResponse = commonHelperGetIdArray($promoterOutputResponse['response']['promoterList'], 'code');
            }
        $sno = 0;

//        function taxDividedByQuantity1(&$value1, $key, $quantity) {
//            $value1 = round($value1 / $quantity, 2);
//        }

        foreach ($selectESTDResponse['response']['eventSignupTicketDetailList'] as $value) {
            $ticketData = array();
            $amount = round($value['amount'] / $value['ticketquantity'], 2);
            if ($amount == 0) {
                $ticketData[$value['ticketid']]['ticketamount'] = 0;
                $ticketData[$value['ticketid']]['amountcurrency'] = '';
            } else {
                $ticketData[$value['ticketid']]['amountcurrency'] = $indexedCurrencyListById[$indexEventSignupIdArray[$value['eventsignupid']]['fromcurrencyid']]['currencyCode'];
                $ticketData[$value['ticketid']]['ticketamount'] = $amount;
            }
            $ticketData[$value['ticketid']]['tickettype'] = $ticketDataIdIndexed[$value['ticketid']]['name'];
            $ticketData[$value['ticketid']]['quantity'] = 1;
            $paidAmount = round($value['totalamount'] / $value['ticketquantity'], 2);
            $EventsignupSeatsData = '';
            if (isset($EventsignupSeats[$value['eventsignupid']][$value['ticketid']]['GridPosition'])) {
                $EventsignupSeatsData = explode(',', $EventsignupSeats[$value['eventsignupid']][$value['ticketid']]['GridPosition']);
            }
            for ($loop = 0; $loop < $value['ticketquantity']; $loop++) {
                $response[$sno][$value['eventsignupid']]['id'] = $indexEventSignupIdArray[$value['eventsignupid']]['id'];
                $response[$sno][$value['eventsignupid']]['signupDate'] = allTimeFormats(convertTime($indexEventSignupIdArray[$value['eventsignupid']]['signupdate'], $eventTimeZoneName, true), 11);
                $response[$sno][$value['eventsignupid']]['promotercode'] = $indexEventSignupIdArray[$value['eventsignupid']]['promotercode'];
                $response[$sno][$value['eventsignupid']]['discountcode'] = $indexEventSignupIdArray[$value['eventsignupid']]['discountcode'];
                $response[$sno][$value['eventsignupid']]['promotername'] = isset($IndexedPromoterResponse[$indexEventSignupIdArray[$value['eventsignupid']]['promotercode']]) ? $IndexedPromoterResponse[$indexEventSignupIdArray[$value['eventsignupid']]['promotercode']]['name'] : '';
                $response[$sno][$value['eventsignupid']]['paymentstatus'] = $indexEventSignupIdArray[$value['eventsignupid']]['paymentstatus'];
                $response[$sno][$value['eventsignupid']]['quantity'] = 1;
                $response[$sno][$value['eventsignupid']]['attendeeid'] = $indexEventSignupIdArray[$value['eventsignupid']]['attendeeid'];
                $response[$sno][$value['eventsignupid']]['paymentmodeid'] = $indexEventSignupIdArray[$value['eventsignupid']]['paymentmodeid'];
                $response[$sno][$value['eventsignupid']]['referraldiscountamount'] = $indexEventSignupIdArray[$value['eventsignupid']]['referraldiscountamount'];
                $response[$sno][$value['eventsignupid']]['barcodenumber'] = $indexEventSignupIdArray[$value['eventsignupid']]['barcodenumber'];
                $response[$sno][$value['eventsignupid']]['paymentgatewayid'] = $indexEventSignupIdArray[$value['eventsignupid']]['paymentgatewayid'];
                $response[$sno][$value['eventsignupid']]['paymenttransactionid'] = $indexEventSignupIdArray[$value['eventsignupid']]['paymenttransactionid'];
                $response[$sno][$value['eventsignupid']]['cts'] = $indexEventSignupIdArray[$value['eventsignupid']]['cts'];
                $response[$sno][$value['eventsignupid']]['mts'] = $indexEventSignupIdArray[$value['eventsignupid']]['mts'];
                $refundAmt = isset($indexedRefunds[$value['eventsignupid']]) ? ($indexedRefunds[$value['eventsignupid']]['totalrefundamount'] / $value['ticketquantity']) : 0;
                $paidCurrency = $indexedCurrencyListById[$indexEventSignupIdArray[$value['eventsignupid']]['tocurrencyid']]['currencyCode'];
                if (($indexEventSignupIdArray[$value['eventsignupid']]['convertedamount'] > 0 && $indexEventSignupIdArray[$value['eventsignupid']]['conversionrate'] > 1)) {
                    $convertedAmount = $indexEventSignupIdArray[$value['eventsignupid']]['convertedamount'] * $indexEventSignupIdArray[$value['eventsignupid']]['quantity'];
                    $totalAmount = $indexEventSignupIdArray[$value['eventsignupid']]['totalamount'];
                    $paidCovertedAmount = ($paidAmount * $convertedAmount) / $totalAmount;
                    $paid = round((($paidCovertedAmount * $indexEventSignupIdArray[$value['eventsignupid']]['conversionrate']) - $refundAmt), 2);
                } elseif ($indexEventSignupIdArray[$value['eventsignupid']]['convertedamount'] > 0) {
                    $convertedAmount = round($indexEventSignupIdArray[$value['eventsignupid']]['convertedamount'] * $indexEventSignupIdArray[$value['eventsignupid']]['quantity'], 2);
                    $totalAmount = $indexEventSignupIdArray[$value['eventsignupid']]['totalamount'];
                    $paidCovertedAmount = round(($paidAmount * $convertedAmount) / $totalAmount, 2);
                    $paid = $paidCovertedAmount;
                    $paidCurrency = 'USD';
                } elseif ($indexEventSignupIdArray[$value['eventsignupid']]['conversionrate'] > 1) {
                    $paid = round((($value['totalamount'] * $indexEventSignupIdArray[$value['eventsignupid']]['conversionrate']) - $refundAmt), 2);
                } else {
                    $amt = round($value['totalamount']);
                    if (isset($indexedRefunds[$value['eventsignupid']]) && $indexEventSignupIdArray[$value['eventsignupid']]['fromcurrencyid'] == 1) {
                        $amt = ($value['totalamount'] - $indexedRefunds[$value['eventsignupid']]['totalrefundamount']);
                    }
                    $paid = round($amt / $value['ticketquantity'],2);
                    $paidCurrency = $indexedCurrencyListById[$indexEventSignupIdArray[$value['eventsignupid']]['fromcurrencyid']]['currencyCode'];
                }
                if ($loop == 0 && count($responseTaxes) > 0 && isset($responseTaxes[$value['eventsignupid']][$value['ticketid']])) {
                    foreach ($responseTaxes[$value['eventsignupid']][$value['ticketid']] as $taxLabel => $taxValue) {
                        $responseTaxes[$value['eventsignupid']][$value['ticketid']][$taxLabel] = round($taxValue / $value['ticketquantity'], 2);
                    }
                }
                $ticketData[$value['ticketid']]['customfields'] = isset($responseCustomFields[$value['eventsignupid']][$value['ticketid']][$loop]) ? $responseCustomFields[$value['eventsignupid']][$value['ticketid']][$loop] : $responseCustomFields[$value['eventsignupid']][$value['ticketid']][0];
                $ticketData[$value['ticketid']]['taxesData'] = isset($responseTaxes[$value['eventsignupid']][$value['ticketid']]) ? $responseTaxes[$value['eventsignupid']][$value['ticketid']] : array();
                // $ticketData[$value['ticketid']]['seats'] = $EventsignupSeats[$loop];
                $response[$sno][$value['eventsignupid']]['paidamount'] = $paid;
                $response[$sno][$value['eventsignupid']]['paidcurrency'] = $paidCurrency;
                $response[$sno][$value['eventsignupid']]['currencyCode'] = $indexedCurrencyListById[$indexEventSignupIdArray[$value['eventsignupid']]['fromcurrencyid']]['currencyCode'];
                //$response[$sno][$value['eventsignupid']]['discount'] = $indexedCurrencyListById[$indexEventSignupIdArray[$value['eventsignupid']]['fromcurrencyid']]['currencyCode'] . ' ' . round($value['discountamount'] / $value['ticketquantity'], 2);
                $response[$sno][$value['eventsignupid']]['discount'] = round($value['discountamount'] / $value['ticketquantity'], 2);
                //$response[$sno][$value['eventsignupid']]['bulkdiscount'] = $indexedCurrencyListById[$indexEventSignupIdArray[$value['eventsignupid']]['fromcurrencyid']]['currencyCode'] . ' ' . round(($value['bulkdiscountamount']) / $value['ticketquantity'], 2);
                $response[$sno][$value['eventsignupid']]['bulkdiscount'] = round(($value['bulkdiscountamount']) / $value['ticketquantity'], 2);
                //$response[$sno][$value['eventsignupid']]['referraldiscount'] = $indexedCurrencyListById[$indexEventSignupIdArray[$value['eventsignupid']]['fromcurrencyid']]['currencyCode'] . ' ' . round($value['referraldiscountamount'] / $value['ticketquantity'], 2);
                $response[$sno][$value['eventsignupid']]['referraldiscount'] = round($value['referraldiscountamount'] / $value['ticketquantity'], 2);
                $response[$sno][$value['eventsignupid']]['ticketDetails'] = $ticketData;
                $response[$sno][$value['eventsignupid']]['comment'] = isset($commentResponse[$value['eventsignupid']]) ? $commentResponse[$value['eventsignupid']] : '';
                $response[$sno][$value['eventsignupid']]['seats'] = $EventsignupSeatsData;
                $sno++;
            }
        }

        $output['status'] = TRUE;
        $output['response']['seatingLayout'] = $seatingLayout;
        $output['response']['transactionList'] = $response;
        $output['response']['total'] = count($response);
        $output['response']['totalTransactionCount'] = $seletESCountResponse[0]['totalcount'];
        $output['statusCode'] = STATUS_OK;
        return $output;
    }

    public function getTransByEventId($input) {
        // $transTypeResponse = array('status' => FALSE);
        $validationResponse = $this->validateGetTransaction($input);
        if (!$validationResponse['status']) {
            return $validationResponse;
        }
        //echo 'in';exit;
        $eventId = $input['eventid'];
        $reportType = $input['reporttype'];
        $report_types = $this->ci->config->item('report_types');
        if (!in_array($input['reporttype'], $report_types)) {
            $reportType = 'summary';
        }

        $transactionType = $input['transactiontype'];
        $page = $input['page'];
        $export = isset($input['export']) ? $input['export'] : 0;
        $start = ($page - 1) * REPORTS_DISPLAY_LIMIT;
        $response = array();
        $eventSignupIds = $selectESTDResponse = $ticketIds = $selectTicketResponse = $partialEventsignupIds = $ticketDataIdIndexed = $selectAttendeeResponse = $attendeeIds = $selectAttendeedetailResponse = $commentResponse = array();
        $whereES[$this->ci->Eventsignup_model->eventid] = $eventId;
        $whereES[$this->ci->Eventsignup_model->deleted] = 0;
        $whereES[$this->ci->Eventsignup_model->transactionstatus] = 'success';
        //currencies list
        $currencyResponse = $this->currencyHanlder->getCurrencyList();
        if ($currencyResponse['status'] && $currencyResponse['response']['total'] > 0) {
            $indexedCurrencyList = commonHelperGetIdArray($currencyResponse['response']['currencyList'], 'currencyId');
            $indexedCurrencyListByCode = commonHelperGetIdArray($currencyResponse['response']['currencyList'], 'currencyCode');
        } else {
            return $currencyResponse;
        }
//        if (isset($input['promotercode']) && $input['promotercode'] == 'promoter') {
//            $whereES[$this->ci->Eventsignup_model->promotercode . ' != '] = 'organizer';
//            $whereES[$this->ci->Eventsignup_model->promotercode . ' != '] = '';
//        } elseif (isset($input['promotercode']) && $input['promotercode'] != 'meraevents') {
//            $whereES[$this->ci->Eventsignup_model->promotercode] = $input['promotercode'];
//        } elseif (isset($input['promotercode'])) {
//            $whereES[$this->ci->Eventsignup_model->promotercode] = '';
//        }
        if ((isset($input['promotercode']) && $input['promotercode'] == 'promoter') || (!isset($input['promotercode']) && $transactionType == 'affiliate')) {
            $where_not_in_ES[$this->ci->Eventsignup_model->promotercode] = array('organizer', '', '0');
            //$whereES[$this->ci->Eventsignup_model->promotercode . ' != '] = '';
            //$whereES[$this->ci->Eventsignup_model->promotercode . ' != '] = '0';
        } elseif (isset($input['promotercode']) && $input['promotercode'] == 'organizer') {
            $whereES[$this->ci->Eventsignup_model->promotercode] = 'organizer';
        } elseif (isset($input['promotercode']) && $input['promotercode'] != 'meraevents') {
            $whereES[$this->ci->Eventsignup_model->promotercode] = $input['promotercode'];
        } elseif (isset($input['promotercode'])) {
            $where_in_ES[$this->ci->Eventsignup_model->promotercode] = array('', '0');
            $where_not_in_ES[$this->ci->Eventsignup_model->paymentgatewayid] = array('7', '8');
        }
        if (isset($input['currencycode'])) {
            $whereES[$this->ci->Eventsignup_model->fromcurrencyid] = $indexedCurrencyListByCode[$input['currencycode']]['currencyId'];
        }
        $where_not_in_ES[$this->ci->Eventsignup_model->paymentstatus] = array('Canceled', 'Refunded');
        $groupBy = $findInSet = $where_in_ES = $notLike = array();
        switch ($transactionType) {
            case 'all':
                //$where_not_in_ES[$this->ci->Eventsignup_model->paymenttransactionid] = array('A1', '');

                break;
            case 'card':
                $whereES[$this->ci->Eventsignup_model->paymentmodeid] = 1;
                //$where_not_in_ES[$this->ci->Eventsignup_model->paymenttransactionid] = array('A1', 'Offline', 'SpotCash', 'SpotCard', '');
                break;
            case 'cod':
                $whereES[$this->ci->Eventsignup_model->paymentmodeid] = 2;
                //  $whereES[$this->ci->Eventsignup_model->paymentmodestatus] = 'Verified';
                break;
            case 'free':
                //es.Fees = 0 and (es.PromotionCode='FreeTicket' or es.DAmount>0)) or 
                // (estd.TicketAmt=0 and (es.PaymentTransId!='A1' or es.PaymentGateway='CashonDelivery')
                //$whereES[$this->ci->Eventsignup_model->totalamount] = 0;
                //$setOrWhere[$this->ci->Eventsignup_model->totalamount]=0;
                // $setOrWhere[$this->ci->Eventsignup_model->transactionTicketType]='';
                $findInSet['free'] = $this->ci->Eventsignup_model->transactiontickettype;
                $where_not_in_ES[$this->ci->Eventsignup_model->paymentmodeid] = 4;
                break;
            case 'offline':
                $whereES[$this->ci->Eventsignup_model->paymentmodeid] = 4;
                //$whereES[$this->ci->Eventsignup_model->paymenttransactionid] = 'Offline';
                //$whereES[$this->ci->Eventsignup_model->paymentmodestatus] = 'Verified';
                break;
            case 'incomplete':
                $whereES[$this->ci->Eventsignup_model->transactionstatus] = 'pending';
                $groupBy = array($this->ci->Eventsignup_model->userid);
                //$whereES['$this->ci->Eventsignup_model->paymenttransactionid']='A1';
                //$where_not_in_ES[$this->ci->Eventsignup_model->paymentmodestatus] =array('Verified');
                break;
            case 'boxoffice':
                //$whereES[$this->ci->Eventsignup_model->paymentmodeid] = 5;
                $where_in_ES[$this->ci->Eventsignup_model->paymentgatewayid] = array(7, 8);
                break;
            case 'viral':
                // $setOrWhere[$this->ci->Eventsignup_model->referraldiscountamount] = 0;
                // $this->ci->Eventsignup_model->setOrWhere($setOrWhere, ' or ', '>');
                $whereES[$this->ci->Eventsignup_model->referraldiscountamount . " > "] = 0;
                break;
            case 'affiliate':
                $newData = array('', '0');
                if (isset($where_not_in_ES[$this->ci->Eventsignup_model->promotercode])) {
                    $addedData = $where_not_in_ES[$this->ci->Eventsignup_model->promotercode];
                    $newData = array_merge($newData, $addedData);
                }
                //exclude me sales
                $where_not_in_ES[$this->ci->Eventsignup_model->promotercode] = array_unique($newData);
                $notLike[$this->ci->Eventsignup_model->promotercode] = 'OFFLINE_';
                break;
            case 'cancel':
                $whereES[$this->ci->Eventsignup_model->paymentstatus] = 'Canceled';
                $where_not_in_ES = array();
                unset($whereES[$this->ci->Eventsignup_model->transactionstatus]);
                break;
            default:
                break;
        }
        //to get total count
        $selectESCount['totalcount'] = 'COUNT( ' . $this->ci->Eventsignup_model->id . ' )';
        $this->ci->Eventsignup_model->setSelect($selectESCount);
        $this->ci->Eventsignup_model->setWhereNotIn($where_not_in_ES);
        $this->ci->Eventsignup_model->setWhere($whereES);
        $this->ci->Eventsignup_model->setNotLike($notLike);
        $this->ci->Eventsignup_model->setWhereIns($where_in_ES);
        $this->ci->Eventsignup_model->setFindInSet($findInSet);
        $this->ci->Eventsignup_model->setGroupBy($groupBy);
        $this->ci->Eventsignup_model->setRecords(0, 0);
        $seletESCountResponse = $this->ci->Eventsignup_model->get();
        //echo $this->ci->db->last_query();exit;
        //print_r($seletESCountResponse);exit;
        if (count($seletESCountResponse) == 0) {
            $output['status'] = TRUE;
            $output['statusCode'] = STATUS_OK;
            $output['response']['total'] = 0;
            $output['response']['messages'][] = ERROR_NO_RECORDS;
            return $output;
        }
        //to get data
        $selectES['id'] = $this->ci->Eventsignup_model->id;
        $selectES['signupdate'] = $this->ci->Eventsignup_model->signupdate;
        $selectES['transactiontickettype'] = $this->ci->Eventsignup_model->transactiontickettype;
        $selectES['paymentstatus'] = $this->ci->Eventsignup_model->paymentstatus;
        $selectES['barcodenumber'] = $this->ci->Eventsignup_model->barcodenumber;
        $selectES['fromcurrencyid'] = $this->ci->Eventsignup_model->fromcurrencyid;
        $selectES['tocurrencyid'] = $this->ci->Eventsignup_model->tocurrencyid;
        $selectES['conversionrate'] = $this->ci->Eventsignup_model->conversionrate;
        $selectES['totalamount'] = $this->ci->Eventsignup_model->totalamount;
        $selectES['discountamount'] = $this->ci->Eventsignup_model->discountamount;
        $selectES['eventextrachargeamount'] = $this->ci->Eventsignup_model->eventextrachargeamount;
        $selectES['paymenttransactionid'] = $this->ci->Eventsignup_model->paymenttransactionid;
        if ($transactionType == 'affiliate') {
            $selectES['promotercode'] = $this->ci->Eventsignup_model->promotercode;
        }
        $this->ci->Eventsignup_model->setSelect($selectES);
        $this->ci->Eventsignup_model->setWhereNotIn($where_not_in_ES);
        $this->ci->Eventsignup_model->setRecords(REPORTS_DISPLAY_LIMIT, $start);
        $this->ci->Eventsignup_model->setWhere($whereES);
        $this->ci->Eventsignup_model->setNotLike($notLike);
        $this->ci->Eventsignup_model->setWhereIns($where_in_ES);
        $this->ci->Eventsignup_model->setFindInSet($findInSet);
        $this->ci->Eventsignup_model->setGroupBy($groupBy);

        $orderBy = array($this->ci->Eventsignup_model->id . ' desc');
        $this->ci->Eventsignup_model->setOrderBy($orderBy);
        $seletESResponse = $this->ci->Eventsignup_model->get();
        //echo $this->ci->db->last_query();exit;
        $indexedEventsingupResponse = array();
        if (count($seletESResponse) > 0) {
            $indexedEventsingupResponse = commonHelperGetIdArray($seletESResponse);
            foreach ($seletESResponse as $value) {
                //   $response[$value['id']]['id'] = $value['id'];
                //  $response[$value['id']]['registrationDate'] = $value['signupdate'];
                $eventSignupIds[] = $value['id'];
                if (strcmp(strtolower($value['paymentstatus']), 'partialrefund') == 0) {
                    $partialEventsignupIds[] = $value['id'];
                }
            }
        }
        $inputTkt['eventId'] = $eventId;
        $inputTkt['status'] = 0;
        $selectTicketResponse = $this->ticketHandler->getTicketName($inputTkt);
        if ($selectTicketResponse['status'] && $selectTicketResponse['response']['total'] > 0) {
            $ticketDataIdIndexed = commonHelperGetIdArray($selectTicketResponse['response']['ticketName']);
        } else {
            return $selectTicketResponse;
        }
        foreach ($ticketDataIdIndexed as $tickets) {
            if ($tickets['type'] == 'paid' || $tickets['type'] == 'donation' || $tickets['type'] == 'addon') {
                $paidTickets[] = $tickets['id'];
            } elseif ($tickets['type'] == 'free') {
                $freeTickets[] = $tickets['id'];
            }
        }
        if (count($eventSignupIds) > 0) {
            $inputESTD['eventsignupids'] = $eventSignupIds;
            $inputESTD['transactiontype'] = $transactionType;
            if ($transactionType == 'card' && count($paidTickets) > 0) {
                $inputESTD['ticketids'] = $paidTickets;
            } elseif ($transactionType == 'free' && count($freeTickets) > 0) {
                $inputESTD['ticketids'] = $freeTickets;
            }
            $selectESTDResponse = $this->estdHandler->getListByEventsignupIds($inputESTD);
            $inputAttendees['eventsignupids'] = $eventSignupIds;
            //$inputAttendees['primary'] = 1;
            $selectAttendeeResponse = $this->attendeeHandler->getListByEventsignupIds($inputAttendees);
        }
        $selectPartialPayments = array();
        if (count($partialEventsignupIds) > 0) {
            $refundHandler = new Refund_handler();
            $inputRefunds['eventsignupids'] = $partialEventsignupIds;
            $selectPartialPayments = $refundHandler->getRefundList($inputRefunds);
        }
        if (isset($selectPartialPayments['status']) && $selectPartialPayments['status']) {
            if ($selectPartialPayments['response']['total'] > 0) {
                $indexedRefunds = commonHelperGetIdArray($selectPartialPayments['response']['refundList'], 'eventsignupid');
            }
        } elseif (count($selectPartialPayments) > 0) {
            return $selectPartialPayments;
        }
//        if ($selectESTDResponse['status'] && $selectESTDResponse['response']['total'] > 0) {
//            foreach ($selectESTDResponse['response']['eventSignupTicketDetailList'] as $value) {
//                $ticketIds[] = $value['ticketid'];
//            }
//        }
//        $primaryAttendeeId = 0;
        if ($selectAttendeeResponse['status'] && $selectAttendeeResponse['response']['total'] > 0) {
            foreach ($selectAttendeeResponse['response']['attendeeList'] as $value) {
                $attendeeIds[] = $value['id'];
            }
        } else {
            return $selectAttendeeResponse;
        }
        //print_r($attendeeIds);exit;
        if (count($attendeeIds) > 0) {
            $commonFieldList = $this->commonfieldHandler->getCommonfieldList();
        }
        if ($commonFieldList['status'] && $commonFieldList['response']['total'] > 0) {
            foreach ($commonFieldList['response']['commonfieldList'] as $value) {
                if ($value['name'] == 'Full Name' || $value['name'] == 'Email Id' || $value['name'] == 'Mobile No') {
                    $commonReqFields[] = $value['id'];
                }
            }
            $inputAttendeedetail['attendeeids'] = $attendeeIds;
            $inputAttendeedetail['commonfieldids'] = $commonReqFields;
            $selectAttendeedetailResponse = $this->attendeedetailHandler->getContactDetailsByAttendeeIds($inputAttendeedetail);
        } else {
            return $commonFieldList;
        }
        //print_r($selectAttendeedetailResponse);
        $contactDetails = array();
        if ($selectAttendeedetailResponse['status'] && $selectAttendeedetailResponse['response']['total'] > 0) {
            $IndexedAttendeedetailArray = commonHelperGetIdArray($selectAttendeedetailResponse['response']['contactDetailsList'], 'attendeeid');
            // print_r($IndexedAttendeedetailArray);
            foreach ($selectAttendeeResponse['response']['attendeeList'] as $value) {
                if (!isset($contactDetails[$value['eventsignupid']][$value['ticketid']])) {
                    $contactDetails[$value['eventsignupid']][$value['ticketid']] = $IndexedAttendeedetailArray[$value['id']]['contactdetails'];
                }
            }
        } else {
            return $selectAttendeedetailResponse;
        }
//        if (count($ticketIds) > 0) {
//            $selectTicketResponse = $this->ticketHandler->getEventTicketList($eventId);
//        }
        $taxesData = array();
        if ($export == 1 && $reportType == 'summary') {
            $inputCustomFieldData['attendeeids'] = $attendeeIds;
            foreach ($commonFieldList['response']['commonfieldList'] as $value) {
                if ($value['name'] == 'Full Name' || $value['name'] == 'Email Id' || $value['name'] == 'City' || $value['name'] == 'Mobile No') {
                    $commonReqFields[] = $value['id'];
                }
                $commonCustomFieldsIdIndexed[$value['id']] = $value['name'];
            }
            $inputCustomFieldData['commonfieldids'] = $commonReqFields;
            $selectCustomFieldDataResponse = $this->attendeedetailHandler->getListByAttendeeIds($inputCustomFieldData);
        } elseif ($export == 1 && $reportType == 'detail') {
            $inputCustomFieldData['attendeeids'] = $attendeeIds;
            $selectAllCustomFieldDataResponse = $this->attendeedetailHandler->getListByAttendeeIds($inputCustomFieldData);
        }
        $customFieldData = array();
        if (isset($selectCustomFieldDataResponse) && $selectCustomFieldDataResponse['status'] && $selectCustomFieldDataResponse['response']['total']) {
            // $IndexedCustomFieldDataArray = commonHelperGetIdArray($selectCustomFieldDataResponse['response']['attendeedetailList'], 'attendeeid');
            // print_r($commonCustomFieldsIdIndexed);
            $IndexedCustomFieldDataArray = array();
            foreach ($selectCustomFieldDataResponse['response']['attendeedetailList'] as $value) {
                $IndexedCustomFieldDataArray[$value['attendeeid']][$commonCustomFieldsIdIndexed[$value['commonfieldid']]] = $value['value'];
            }
            // print_r($IndexedCustomFieldDataArray);
            foreach ($selectAttendeeResponse['response']['attendeeList'] as $value) {
                $customFieldData[$value['eventsignupid']]['Name'] = $IndexedCustomFieldDataArray[$value['id']]['Full Name'];
                $customFieldData[$value['eventsignupid']]['City'] = $IndexedCustomFieldDataArray[$value['id']]['City'];
                $customFieldData[$value['eventsignupid']]['Email'] = $IndexedCustomFieldDataArray[$value['id']]['Email Id'];
                $customFieldData[$value['eventsignupid']]['Mobile'] = $IndexedCustomFieldDataArray[$value['id']]['Mobile No'];
            }
//            print_r($customFieldData);
//            exit;
        } elseif (isset($selectCustomFieldDataResponse)) {
            return $selectCustomFieldDataResponse;
        }
        if (isset($selectAllCustomFieldDataResponse) && $selectAllCustomFieldDataResponse['status'] && $selectAllCustomFieldDataResponse['response']['total']) {
            $inputCustomFields['eventId'] = $eventId;
            $eventCustomFields = $this->configureHandler->getCustomFields($inputCustomFields);
        } elseif (isset($selectAllCustomFieldDataResponse)) {
            return $selectAllCustomFieldDataResponse;
        }
        if (isset($eventCustomFields) && $eventCustomFields['status'] && $eventCustomFields['response']['total'] > 0) {
            $indexedCustomFieldNames = commonHelperGetIdArray($eventCustomFields['response']['customFields']);
            foreach ($selectAllCustomFieldDataResponse['response']['attendeedetailList'] as $value) {
                $IndexedCustomFieldDataArray[$value['attendeeid']][$indexedCustomFieldNames[$value['customfieldid']]['fieldname']] = $value['value'];
            }
//print_r($IndexedCustomFieldDataArray);
            foreach ($selectAttendeeResponse['response']['attendeeList'] as $value) {
                foreach ($indexedCustomFieldNames as $customField) {
                    $customFieldData[$value['eventsignupid']][$value['ticketid']][$customField['fieldname']] = $IndexedCustomFieldDataArray[$value['id']][$customField['fieldname']];
                }
            }
            // print_r($customFieldData);exit;
        } elseif (isset($eventCustomFields)) {
            return $eventCustomFields;
        }
        $inputESTaxes['eventsignupids'] = $eventSignupIds;
        $getTaxes = $this->eventsignupTaxHandler->getTaxes($inputESTaxes);
        if ($getTaxes['status'] && $getTaxes['response']['total'] > 0) {
//            foreach ($getTaxes['response']['taxList'] as $value) {
//                $taxMappingIds[] = $value['taxmappingid'];
//            }
//            if (count($taxMappingIds) > 0) {
//                $inputTaxMapping['ids'] = $taxMappingIds;
//                $taxMappingResponse = $this->taxMappingHandler->getTaxmapping($inputTaxMapping);
//            }
//            if ($taxMappingResponse['status'] && $taxMappingResponse['response']['taxMappingList']) {
//                $indexedTaxMapping = commonHelperGetIdArray($taxMappingResponse['response']['taxMappingList']);
//                $taxes = $this->taxHandler->getTaxList();
//            } else {
//                return $taxMappingResponse;
//            }
//            if ($taxes['status'] && $taxes['response']['total'] > 0) {
//                $indexedTaxes = commonHelperGetIdArray($taxes['response']['taxList']);
//            } else {
//                return $taxes;
//            }
            foreach ($getTaxes['response']['taxList'] as $value) {
                //$taxLabelIndex = $indexedTaxMapping[$value['taxmappingid']]['taxid'];
                //$taxesData[$value['eventsignupid']][$value['ticketid']][$indexedTaxes[$taxLabelIndex]['label']] = $value['taxamount'];
                if (!isset($taxesData[$value['eventsignupid']][$value['ticketid']]['totaltax'])) {
                    $taxesData[$value['eventsignupid']][$value['ticketid']]['totaltax'] = 0;
                }
                $taxesData[$value['eventsignupid']][$value['ticketid']]['totaltax'] += $value['taxamount'];
            }
        }
        //print_r($taxesData);exit;
        //  $i=0;
        if ($transactionType == 'incomplete' || $transactionType == 'cod') {
            $inputArray['eventsignupids'] = $eventSignupIds;
            $inputArray['commenttype'] = 'incomplete';
            $commentResponse = $this->commentHandler->getCommentByEventsignupIds($inputArray);
        }
        $IndexedCommentArray = array();
        if (count($commentResponse) > 0 && $commentResponse['status'] && $commentResponse['response']['total'] > 0) {
            $IndexedCommentArray = commonHelperGetIdArray($commentResponse['response']['commentList'], 'eventsignupid');
        }
        // print_r($selectESTDResponse);exit;
        $ticketDetails = array();
        foreach ($selectESTDResponse['response']['eventSignupTicketDetailList'] as $value) {
            if ($reportType == 'summary') {
                $ticketData['name'] = $ticketDataIdIndexed[$value['ticketid']]['name'];
                $ticketData['price'] = $value['amount'];
                $ticketData['quantity'] = $value['ticketquantity'];
                $ticketData['discount'] = round($value['bulkdiscountamount'] + $value['discountamount'] + $value['referraldiscountamount'], 2);
                $ticketData['currencyCode'] = $indexedCurrencyList[$ticketDataIdIndexed[$value['ticketid']]['currencyid']]['currencyCode'];
                if ($transactionType == 'incomplete' || $transactionType == 'cod') {
                    $ticketData['failedcount'] = $value['failedcount'];
                    $ticketData['comment'] = count($IndexedCommentArray) > 0 ? $IndexedCommentArray[$value['eventsignupid']]['comment'] : '';
                }
                if ($transactionType == 'affiliate') {
                    $ticketData['promotercode'] = $indexedEventsingupResponse[$value['eventsignupid']]['promotercode'];
                }
                $ticketData['taxesData']['totaltax'] = 0;
                if (count($taxesData) > 0 && isset($taxesData[$value['eventsignupid']][$value['ticketid']])) {
                    $ticketData['taxesData'] = $taxesData[$value['eventsignupid']][$value['ticketid']];
                }
                $ticketData['contactdetails'] = count($contactDetails) > 0 ? $contactDetails[$value['eventsignupid']][$value['ticketid']] : '';
                $ticketDetails[$value['eventsignupid']][$value['ticketid']] = $ticketData;
            } else {
                $refundAmountDetail = round((isset($indexedRefunds[$value['eventsignupid']]['totalrefundamount']) ? $indexedRefunds[$value['eventsignupid']]['totalrefundamount'] : 0) / $value['ticketquantity'], 2);
                for ($loop = 1; $loop <= $value['ticketquantity']; $loop++) {
                    $ticketData['name'] = $ticketDataIdIndexed[$value['ticketid']]['name'];
                    $ticketData['price'] = round($value['amount'] / $value['ticketquantity'], 2);
                    $ticketData['quantity'] = 1;
                    $ticketData['normaldiscount'] = round($value['discountamount'] / $value['ticketquantity'], 2);
                    $ticketData['bulkdiscount'] = round($value['bulkdiscountamount'] / $value['ticketquantity'], 2);
                    $ticketData['referraldiscount'] = round($value['referraldiscountamount'] / $value['ticketquantity'], 2);
                    $ticketData['discount'] = round(($value['bulkdiscountamount'] + $value['discountamount'] + $value['referraldiscountamount']) / $value['ticketquantity'], 2);
                    $ticketData['contactdetails'] = count($contactDetails) > 0 ? $contactDetails[$value['eventsignupid']][$value['ticketid']] : '';
                    $ticketData['currencyCode'] = $indexedCurrencyList[$ticketDataIdIndexed[$value['ticketid']]['currencyid']]['currencyCode'];
                    //  $ticketData['contactDetails'] = $IndexedAttendeedetailArray[$value['id']]['contactdetails'];
//                    if ($transactionType == 'incomplete') {
//                        $ticketData['failedcount'] = $value['failedcount'];
//                        $ticketData['comment'] = count($IndexedCommentArray) > 0 ? $IndexedCommentArray[$value['eventsignupid']]['comment'] : '';
//                    }
                    if (count($customFieldData) > 0) {
                        $ticketData['customFieldsData'] = $customFieldData[$value['eventsignupid']][$value['ticketid']];
                    }
                    $ticketData['taxesData'] = array('totaltax' => 0);
                    if (count($taxesData) > 0 && isset($taxesData[$value['eventsignupid']][$value['ticketid']])) {

                        function taxDividedByQuantity(&$value1, $key, $quantity) {
                            $value1 = round($value1 / $quantity, 2);
                        }

                        array_walk($taxesData[$value['eventsignupid']][$value['ticketid']], 'taxDividedByQuantity', $value['ticketquantity']);

                        $ticketData['taxesData'] = $taxesData[$value['eventsignupid']][$value['ticketid']];
                    }
                    $ticketDetails[$value['eventsignupid']][$value['ticketid']] = $ticketData;
                    $response[$value['eventsignupid']][] = array('id' => $value['eventsignupid'],
                        'barcodenumber' => $indexedEventsingupResponse[$value['eventsignupid']]['barcodenumber'],
                        'registrationDate' => $indexedEventsingupResponse[$value['eventsignupid']]['signupdate'],
                        'ticketDetails' => array($value['ticketid'] => $ticketData),
                        'fromCurrencyCode' => $indexedCurrencyList[$indexedEventsingupResponse[$value['eventsignupid']]['fromcurrencyid']]['currencyCode'],
                        'toCurrencyCode' => $indexedCurrencyList[$indexedEventsingupResponse[$value['eventsignupid']]['tocurrencyid']]['currencyCode'],
                        'refundAmount' => $refundAmountDetail,
                        'totalDiscount' => $indexedEventsingupResponse[$value['eventsignupid']]['discountamount'],
                        'conversionRate' => round($indexedEventsingupResponse[$value['eventsignupid']]['conversionrate'], 2),
                        'transactionTotal' => $indexedEventsingupResponse[$value['eventsignupid']]['totalamount'] - $indexedEventsingupResponse[$value['eventsignupid']]['eventextrachargeamount']
                    );
                }
            }
            // $response[$value['eventsignupid']]['ticketDetails'][$value['ticketid']] = $ticketData;
            // $response[$value['eventsignupid']]['paymentStatus'] = 'PAID';
        }
        if ($reportType == 'summary') {
            foreach ($seletESResponse as $value) {
                $customFieldValues = array();
                if ($export == 1) {
                    $customFieldValues = array('Name' => $customFieldData[$value['id']]['Name'],
                        'Email' => $customFieldData[$value['id']]['Email'],
                        'City' => $customFieldData[$value['id']]['City'],
                        'Mobile' => $customFieldData[$value['id']]['Mobile']);
                }
                $transactionData = array('id' => $value['id'],
                    'barcodenumber' => $value['barcodenumber'],
                    'registrationDate' => $value['signupdate'],
                    'ticketDetails' => ($ticketDetails[$value['id']]),
                    //'currencyCode' => $indexedCurrencyList[$value['fromcurrencyid']]['currencyCode'],
                    'refundAmount' => isset($indexedRefunds[$value['id']]['totalrefundamount']) ? $indexedRefunds[$value['id']]['totalrefundamount'] : 0,
                    'fromCurrencyCode' => $indexedCurrencyList[$value['fromcurrencyid']]['currencyCode'],
                    'toCurrencyCode' => $indexedCurrencyList[$value['tocurrencyid']]['currencyCode'],
                    'totalDiscount' => $indexedEventsingupResponse[$value['id']]['discountamount'],
                    'conversionRate' => round($indexedEventsingupResponse[$value['id']]['conversionrate'], 2),
                    'transactionTotal' => round(($indexedEventsingupResponse[$value['id']]['totalamount'] - $indexedEventsingupResponse[$value['id']]['eventextrachargeamount']) * $indexedEventsingupResponse[$value['id']]['conversionrate'], 2)
                );
                $response[$value['id']][] = array_merge($transactionData, $customFieldValues);
            }
        }
//         print_r($response);
        //print_r('');exit;
        $output['status'] = TRUE;
        // $output['total'] = $seletESCountResponse[0]['totalcount'];
        $output['response']['transactionList'] = $response;
        $output['response']['total'] = count($response);
        $output['response']['totalTransactionCount'] = $seletESCountResponse[0]['totalcount'];
        $output['statusCode'] = STATUS_OK;
        return $output;
    }

    //
    public function validateGetTransaction($input) {
        $this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($input);
        $this->ci->form_validation->set_rules('eventid', 'eventid', 'is_natural_no_zero|required_strict');
        $this->ci->form_validation->set_rules('ticketid', 'ticketid', 'is_natural_no_zero');
        $this->ci->form_validation->set_rules('reporttype', 'reporttype', 'required_strict');
        $this->ci->form_validation->set_rules('transactiontype', 'transactiontype', 'required_strict|is_valid_type[transaction]');
        $this->ci->form_validation->set_rules('page', 'page', 'is_natural_no_zero|required_strict');
        //$this->ci->form_validation->set_rules('filtertype', 'filtertype', 'is_natural_no_zero|required_strict');
        if ($this->ci->form_validation->run() == FALSE) {
            $response = $this->ci->form_validation->get_errors();
            $output['status'] = FALSE;
            $output['response']['messages'] = $response['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $output['status'] = TRUE;
        $output['response']['messages'] = array();
        $output['statusCode'] = STATUS_OK;
        return $output;
    }

    // Get Event Sign Up Details

    public function getEventsignupDetails($request) {
        $output = array();
        if (!isset($request['eventsignupId']) && !isset($request['referralCode'])) {
            $output['status'] = FALSE;
            $output['response']['messages'][] = ERROR_INVALID_INPUT;
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($request);
        if (isset($request['eventsignupId'])) {
            $this->ci->form_validation->set_rules('eventsignupId', 'eventsignup Id', 'is_natural_no_zero|required_strict');
        }
        if (isset($request['referralCode'])) {
            $this->ci->form_validation->set_rules('referralCode', 'referral code', 'required_strict');
        }

        if (!empty($request) && $this->ci->form_validation->run() == FALSE) {
            $validationStatus = $this->ci->form_validation->get_errors();
            $output['status'] = FALSE;
            $output['response']['messages'] = $validationStatus['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        } else {
            $this->ci->Eventsignup_model->resetVariable();
            $selectInput['id'] = $this->ci->Eventsignup_model->id;
            $selectInput['userid'] = $this->ci->Eventsignup_model->userid;
            $selectInput['eventid'] = $this->ci->Eventsignup_model->eventid;
            $selectInput['quantity'] = $this->ci->Eventsignup_model->quantity;
            $selectInput['barcodenumber'] = $this->ci->Eventsignup_model->barcodenumber;
            $selectInput['transactionticketids'] = $this->ci->Eventsignup_model->transactionticketids;
            $selectInput['fromcurrencyid'] = $this->ci->Eventsignup_model->fromcurrencyid;
            $selectInput['tocurrencyid'] = $this->ci->Eventsignup_model->tocurrencyid;
            $selectInput['bookingtype'] = $this->ci->Eventsignup_model->bookingtype;
            $selectInput['discount'] = $this->ci->Eventsignup_model->discount;
            $selectInput['discountcodeid'] = $this->ci->Eventsignup_model->discountcodeid;
            $selectInput['paymentmodeid'] = $this->ci->Eventsignup_model->paymentmodeid;
            $selectInput['paymentgatewayid'] = $this->ci->Eventsignup_model->paymentgatewayid;
            $selectInput['paymenttransactionid'] = $this->ci->Eventsignup_model->paymenttransactionid;
            $selectInput['referralcode'] = $this->ci->Eventsignup_model->referralcode;
            $selectInput['promotercode'] = $this->ci->Eventsignup_model->promotercode;
            $selectInput['totalamount'] = $this->ci->Eventsignup_model->totalamount;
            $selectInput['convertedamount'] = $this->ci->Eventsignup_model->convertedamount;
            $selectInput['eventextrachargeid'] = $this->ci->Eventsignup_model->eventextrachargeid;
            $selectInput['eventextrachargeamount'] = $this->ci->Eventsignup_model->eventextrachargeamount;
            $selectInput['transactiontickettype'] = $this->ci->Eventsignup_model->transactiontickettype;
            $selectInput['signupdate'] = $this->ci->Eventsignup_model->signupdate;
            $this->ci->Eventsignup_model->setSelect($selectInput);
            //fetching Event Signup Details
            if (isset($request['eventsignupId']) && $request['eventsignupId'] > 0) {
                $where[$this->ci->Eventsignup_model->id] = $request['eventsignupId'];
            }
            if (isset($request['referralCode']) && $request['referralCode'] != '') {
                $where[$this->ci->Eventsignup_model->referralcode] = $request['referralCode'];
                $where[$this->ci->Eventsignup_model->deleted] = 0;
            }
            $this->ci->Eventsignup_model->setWhere($where);
            $eventsSignupDetails = $this->ci->Eventsignup_model->get();
            if (count($eventsSignupDetails) > 0) {
                foreach ($eventsSignupDetails as $esdkey => $esdval) {
                    $timezoneDetails = $this->eventHandler->getEventTimeZoneName(array('eventId' => $esdval['eventid']));
                    $eventsSignupDetails[$esdkey]['signupdate'] = date('Y-m-d', strtotime($esdval['signupdate']));
                    if ($timezoneDetails['status'] && $timezoneDetails['total'] > 0) {
                        $eventsSignupDetails[$esdkey]['signupdate'] = date('d M,Y h:i A', strtotime(convertTime($esdval['signupdate'], $timezoneDetails['response']['details']['location']['timeZoneName'])));
                    }

                    $eventSignupIds[] = $esdval['id'];
                }
                $inputESTaxes['eventsignupids'] = $eventSignupIds;
                $output['status'] = TRUE;
                $output['response']['eventSignupList'] = $eventsSignupDetails;
                $output['response']['total'] = count($eventsSignupDetails);
                $output['statusCode'] = STATUS_OK;
                return $output;
            } else {
                $output['status'] = TRUE;
                $output['response']['messages'][] = ERROR_NO_DATA;
                $output['response']['total'] = 0;
                $output['statusCode'] = STATUS_OK;
                return $output;
            }
        }
    }

    public function getEventSignupPromoterData($inputArray) {

        $this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('eventId', 'eventid', 'is_natural_no_zero|required_strict');
        if (!empty($inputArray) && $this->ci->form_validation->run() == FALSE) {
            $errorMsg = $this->ci->form_validation->get_errors();
            $output = parent::createResponse(FALSE, $errorMsg['message'], STATUS_BAD_REQUEST);
            return $output;
        }
        $this->ci->Eventsignup_model->resetVariable();
        $selectInput['eventsignupid'] = $this->ci->Eventsignup_model->id;
        $selectInput['eventid'] = $this->ci->Eventsignup_model->eventid;
        $selectInput['totalamount'] = $this->ci->Eventsignup_model->totalamount;
        $selectInput['promotercode'] = $this->ci->Eventsignup_model->promotercode;
        $selectInput['quantity'] = $this->ci->Eventsignup_model->quantity;
        $selectInput['fromcurrencyid'] = $this->ci->Eventsignup_model->fromcurrencyid;
        $selectInput['sumtotalamount'] = 'sum(' . $this->ci->Eventsignup_model->totalamount . '-' . $this->ci->Eventsignup_model->eventextrachargeamount . ')';
        $selectInput['sumquantity'] = 'sum( ' . $this->ci->Eventsignup_model->quantity . ' )';
        $this->ci->Eventsignup_model->setSelect($selectInput);

        $where[$this->ci->Eventsignup_model->eventid] = $inputArray['eventId'];
        $whereInArray[$this->ci->Eventsignup_model->promotercode] = $inputArray['codeList']; //Passing promoter code list to the input array.
        $where[$this->ci->Eventsignup_model->deleted] = 0;
        $where[$this->ci->Eventsignup_model->transactionstatus] = 'success';

        $where_not_in[$this->ci->Eventsignup_model->paymentstatus] = array('Canceled', 'Refunded');

        $groupBy = array($this->ci->Eventsignup_model->promotercode, $this->ci->Eventsignup_model->eventid);

        $this->ci->Eventsignup_model->setGroupBy($groupBy);
        $this->ci->Eventsignup_model->setWhereIns($whereInArray);
        $this->ci->Eventsignup_model->setWhereNotIn($where_not_in);
        $this->ci->Eventsignup_model->setWhere($where);
        $eventSignupDetails = $this->ci->Eventsignup_model->get();
        if (count($eventSignupDetails) > 0) {
            $output = parent::createResponse(TRUE, array(), STATUS_OK, count($eventSignupDetails), 'eventSignupDetails', $eventSignupDetails);
            return $output;
        } elseif (count($eventSignupDetails) == 0) {
            $output = parent::createResponse(TRUE, ERROR_NO_PROMOTER_TRANSACTION, STATUS_OK, 0, 'eventSignupDetails', array());
            return $output;
        } else {
            $output = parent::createResponse(FALSE, ERROR_SOMETHING_WENT_WRONG, STATUS_SERVER_ERROR);
            return $output;
        }
    }

    public function getEventSignupId($inputArray, $transactiontype = 1, $paymentstatus = 1) {

        $this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('eventId', 'eventid', 'is_natural_no_zero|required_strict');
        if (!empty($inputArray) && $this->ci->form_validation->run() == FALSE) {
            $errorMsg = $this->ci->form_validation->get_errors();
            $output = parent::createResponse(FALSE, $errorMsg['message'], STATUS_BAD_REQUEST);
            return $output;
        }
        $this->ci->Eventsignup_model->resetVariable();
        $selectInput['id'] = $this->ci->Eventsignup_model->id;
        $selectInput['userid'] = $this->ci->Eventsignup_model->userid;
        $selectInput['quantity'] = $this->ci->Eventsignup_model->quantity;
        $selectInput['totalamount'] = $this->ci->Eventsignup_model->totalamount;
        $selectInput['signupdate'] = $this->ci->Eventsignup_model->signupdate;
        $selectInput['eventextrachargeamount'] = $this->ci->Eventsignup_model->eventextrachargeamount;
        $selectInput['eventid'] = $this->ci->Eventsignup_model->eventid;
        $selectInput['code'] = $this->ci->Eventsignup_model->promotercode;
        $this->ci->Eventsignup_model->setSelect($selectInput);
        $where[$this->ci->Eventsignup_model->eventid] = $inputArray['eventId'];
        $where[$this->ci->Eventsignup_model->deleted] = 0;
        // checking  condition for getting signupids to generate multiple pass
        if ($inputArray['extrafield']) {
            $where[$this->ci->Eventsignup_model->extrafield] = $inputArray['extrafield'];
        }
        if ($transactiontype == 1) {
            $where[$this->ci->Eventsignup_model->transactionstatus] = 'success';
        } else {
            $where[$this->ci->Eventsignup_model->transactionstatus] = 'pending';
        }
        if ($paymentstatus == 1) {
            $where_not_in[$this->ci->Eventsignup_model->paymentstatus] = array('Canceled', 'Refunded');
        } elseif ($paymentstatus === 'Refunded') {
            $where[$this->ci->Eventsignup_model->paymentstatus] = 'Refunded';
        }
        if (isset($where_not_in)) {
            $this->ci->Eventsignup_model->setWhereNotIn($where_not_in);
        }
        // checking condtion for getting offline promoter  data
        if ($inputArray['paymentType'] == 'Offline') {
            $where[$this->ci->Eventsignup_model->paymentmodeid] = 4;
            $where[$this->ci->Eventsignup_model->paymenttransactionid] = 'Offline';
        }
        $this->ci->Eventsignup_model->setWhere($where);
        $eventSignupIds = $this->ci->Eventsignup_model->get();
        if (count($eventSignupIds) > 0) {
            $output['status'] = TRUE;
            $output['response']['eventsignupids'][] = $eventSignupIds;
            $output['response']['total'] = count($eventSignupIds);
            $output['statusCode'] = STATUS_OK;
            return $output;
        } elseif (count($eventSignupIds) == 0) {
            $output['status'] = TRUE;
            $output['response']['messages'][] = ERROR_NO_RECORDS;
            $output['response']['total'] = 0;
            $output['statusCode'] = STATUS_OK;
            return $output;
        }
        $output['status'] = FALSE;
        $output['response']['messages'][] = ERROR_INTERNAL_DB_ERROR;
        $output['statusCode'] = STATUS_SERVER_ERROR;
        return $output;
    }

    public function geteventSignupTickets($inputArray) {
        $this->ci->load->model('Eventsignup_Ticketdetail_model');
        $select['ticketid'] = $this->ci->Eventsignup_Ticketdetail_model->ticketid;
        $select['amount'] = 'sum( ' . $this->ci->Eventsignup_Ticketdetail_model->totalamount . ' )';
        $select['eventsignupid'] = $this->ci->Eventsignup_Ticketdetail_model->eventsignupid;
        $select['ticketquantity'] = 'sum( ' . $this->ci->Eventsignup_Ticketdetail_model->ticketquantity . ' )';
        $this->ci->Eventsignup_Ticketdetail_model->resetVariable();
        $this->ci->Eventsignup_Ticketdetail_model->setSelect($select);
        $groupBy = array($this->ci->Eventsignup_Ticketdetail_model->eventsignupid);


        $this->ci->Eventsignup_Ticketdetail_model->setGroupBy($groupBy);
        $where[$this->ci->Eventsignup_Ticketdetail_model->deleted] = 0;
        $whereInArray[$this->ci->Eventsignup_Ticketdetail_model->eventsignupid] = $inputArray['signupIds'];
        $whereInArray[$this->ci->Eventsignup_Ticketdetail_model->ticketid] = $inputArray['tikcets'];
        $this->ci->Eventsignup_Ticketdetail_model->setWhere($where);
        $this->ci->Eventsignup_Ticketdetail_model->setWhereIns($whereInArray);
        $signupTickets = $this->ci->Eventsignup_Ticketdetail_model->get();

        if ($signupTickets) {
            $output['status'] = TRUE;
            $output['response']['eventsignupticket'] = $signupTickets;
            $output['response']['total'] = count($signupTickets);
            $output['statusCode'] = STATUS_OK;
            return $output;
        } else {
            $output['status'] = TRUE;
            $output['response']['messages'][] = ERROR_NO_DATA;
            $output['response']['total'] = 0;
            $output['statusCode'] = STATUS_OK;
        }
    }

    public function UpdateEventsignupBooking($inputArray) {
        $output = array();
        $orderlogstatus = 0;
        $transactionId = 0;
        $this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('orderId', 'order id', 'trim|xss_clean|required_strict|alpha_numeric');
        $this->ci->form_validation->set_rules('userId', 'user id', 'is_natural_no_zero');
        if ($this->ci->form_validation->run() == FALSE) {
            $response = $this->ci->form_validation->get_errors();
            $output['status'] = FALSE;
            $output['response']['messages'] = $response['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $orderId = $inputArray['orderId'];
//        $userId = $this->ci->customsession->getData('userId');
        $userId = $this->ci->customsession->getUserId();
        $orderlog['orderId'] = $orderId;
        $orderlog['userId'] = $userId;
        if (isset($inputArray['updateData']) && $inputArray['updateData'] == 1) {
            $orderlogstatus = 1;
            $orderlogs = $this->orderlogHandler->getOrderlog($orderlog);
            $inputArray['eventsignupId'] = $orderlogs['response']['orderLogData']['eventsignup'];
            $orderLogCalculationData = unserialize($orderlogs['response']['orderLogData']['data']);
            //  $orderlogsessiondata = $orderlogs['response']['orderLogData']['data'];
            $orderlogsession = $orderLogCalculationData;
            $viralrefferalCode = $orderlogsession['referralcode'];
            $promotercode = $orderlogsession['promotercode'];
            $updateArray['orderlogstatus'] = $orderlogsession['status'];
            $updateArray['orderlogid'] = $orderlogsession['id'];
            if (isset($orderlogsession['paymentResponse'])) {
                $updateArray['transactionresponse'] = serialize($orderlogsession['paymentResponse']);
                $transactionstatus = isset($orderlogsession['paymentResponse']['ResponseCode']) ? $orderlogsession['paymentResponse']['ResponseCode'] : 0;
                $transactionId = $orderlogsession['paymentResponse']['TransactionID'];
                $thirdPartyGateway=($orderlogsession['paymentResponse']['thirdPartyGateway']==TRUE)?$orderlogsession['paymentResponse']['thirdPartyGateway']:NULL;
                if ($transactionId == 'Al' && $mode != 'free') {
                    $output['status'] = FALSE;
                    $output['response']['messages'][] = "Payment is not Success. Please Try Again";
                    $output['statusCode'] = STATUS_BAD_REQUEST;
                }
                $updateArray['paymenttransactionid'] = $transactionId;
                $mode = $orderlogsession['paymentResponse']['mode'];
                if ($transactionId == 'A1' && $mode != 'free') {
                    $updateArray['transactionstatus'] = 'failed';
                    $updateArray['paymentstatus'] = 'NotVerified';
                } else if ($transactionId != 'A1' && $mode == 'mobikwik') {
                    $updateArray['transactionstatus'] = 'success';
                    $updateArray['paymentstatus'] = 'Captured';
                    $updateArray['settlementdate'] = allTimeFormats('', 11);
                } else if($thirdPartyGateway == TRUE){
                    $updateArray['paymentsource'] = 5;
                    $updateArray['paymentgatewayid'] = 1;
                    $updateArray['transactionstatus'] = 'success';
                    $updateArray['paymentstatus'] = 'NotVerified';
                } else {
                    $updateArray['transactionstatus'] = 'success';
                    $updateArray['paymentstatus'] = 'NotVerified';
                }

                if ($mode == 'free') {
                    $updateArray['paymenttransactionid'] = 'A1';
                }
            }
            $eventInput['eventId'] = $orderlogsession['eventid'];
            $ticketViralsetting = $this->ticketHandler->getViralTickets($eventInput);
            if ($ticketViralsetting['status']) {
                $data['eventticketviralsetting'] = array();
                if ($ticketViralsetting['response']['total'] > 0) {
                    $reffcode = $orderlogsession['referralcode'];
                    $data['eventsignupDetails']['viralReferralCode'] = isset($viralrefferalCode) ? $viralrefferalCode : '';
                    $reffcode = $this->generateRandomString(10);
                    $updateArray['viralrefferalCode'] = $reffcode;
                    $updateArray['referalcode'] = $reffcode;
                }
            }
            $updateArray['eventsignupid'] = $inputArray['eventsignupId'];
            $updateArray['eventId'] = $eventInput['eventId'];
            $updateArray['eventUrl'] = $eventdata['eventUrl'];
            $updateArray['eventTitle'] = $eventdata['title'];

            /*$uniqueNum = substr($eventInput['eventId'], 1, 4) . $inputArray['eventsignupId'];
            $updateArray['barcodenumber'] = $uniqueNum;*/

            if ($promotercode != '') {
                $this->promoterHandler = new Promoter_handler();
                $promoterInput['eventId'] = $orderlogs['response']['orderLogData']['eventid'];
                $promoterInput['promoterCode'] = $promotercode;
                $promoterStatus = $this->promoterHandler->checkPromoterCode($promoterInput);

                if ($promoterStatus['status'] && $promoterStatus['response']['total'] > 0) {
                    $updateArray['promotercode'] = $promotercode;
                }
            }
            if ($orderlogstatus == 1) {
                $updateESResponse = $this->updateEventsignuptransactionsuccess($updateArray);
                if ($updateESResponse['status']) {
                    $orderlog['viralrefferalCode'] = $viralrefferalCode;
                    $orderlog['eventsignupId'] = $inputArray['eventsignupId'];
                    return $orderlog;
                } else {
                    return $updateESResponse;
                }
            }
        }
    }

    // Function to get the EventSignupDetailData


    public function getEventsignupDetaildata($inputArray) {
        $output = array();
        $eventsignupArray = $inputArray;
        $viralrefferalCode = $inputArray['viralrefferalCode'];
        $eventsignupDetails = $this->getEventsignupDetails($eventsignupArray);
        if ($eventsignupDetails['status'] && $eventsignupDetails['response']['total'] > 0) {
            $data['eventsignupDetails'] = $eventsignupDetails['response']['eventSignupList'][0];
            $eventInput['eventId'] = $data['eventsignupDetails']['eventid'];
            $eventId = $data['eventsignupDetails']['eventid'];
            // Currency Code for the EventsignUp
            $currencyId['currencyId'] = $data['eventsignupDetails']['fromcurrencyid'];
            $currenyCodeDetail = $this->currencyHanlder->getCurrencyDetailById($currencyId);
            if ($currenyCodeDetail['status'] && $currenyCodeDetail['response']['total'] > 0) {
                $data['eventsignupDetails']['currencyCode'] = $currenyCodeDetail['response']['currencyList']['detail']['currencyCode'];
                $data['eventsignupDetails']['currencySymbol'] = $currenyCodeDetail['response']['currencyList']['detail']['currencySymbol'];
            } else {
                return $currenyCodeDetail;
            }

            if (!isset($inputArray['userId'])) {
                $inputArray['userId'] = $data['eventsignupDetails']['userid'];
            }

            // Get the Event Extra Charges If Any
            if ($data['eventsignupDetails']['eventextrachargeid'] > 0) {
                $this->eventextrachargeHandler = new Eventextracharge_handler();
                $extracharge['eventextrachargeId'] = $data['eventsignupDetails']['eventextrachargeid'];
                //$pos = strpos($extracharge['eventextrachargeId'], ',');
                $extraArr = explode(',',$extracharge['eventextrachargeId']);
                $extracharge['isExtraIdsArray'] = true;
                $extrachargeDetail = $this->eventextrachargeHandler->getExtrachargeById($extracharge);
                if ($extrachargeDetail['status'] && $extrachargeDetail['response']['total'] > 0) {
                    
                    $extraChargeResponse = $extrachargeDetail['response']['eventExtrachargeList'];
                    foreach($extraChargeResponse as $extrachargeArr) {
                        $extraLabelArr[] = $extrachargeArr['label'];
                    }
                    $data['eventsignupDetails']['extraChargeLabel'] = implode('+',$extraLabelArr);//$extrachargeDetail['response']['eventExtrachargeList']['label'];
                } else {
                    return $extrachargeDetail;
                }
            }

            //  Get Payment Mode 
            if ($data['eventsignupDetails']['totalamount'] > 0 && $data['eventsignupDetails']['paymentmodeid'] == 1) {
                $eventsignupMode = 'Card';
            } elseif ($data['eventsignupDetails']['paymentmodeid'] == 4) {
                $eventsignupMode = 'Offline';
            } elseif ($data['eventsignupDetails']['paymentmodeid'] == 5) {
                $eventsignupMode = 'Spot';
            } else {
                $eventsignupMode = 'Free';
            }
            // Event Details
            //$event = $this->eventHandler->getEventDetails($eventInput);
            $event = $this->eventHandler->getEventDetailsForBooking($eventInput);
        } else {
            return $eventsignupDetails;
        }
        if ($event['status'] && $event['response']['total'] > 0) {
            $eventdata = $event['response']['details'];
            $this->salespersonHandler = new Salesperson_handler();
            if ($eventdata['eventDetails']['contactdisplay'] == 0) {
                $eventdata['eventDetails']['contactdetails'] = '';
                if (isset($eventdata['eventDetails']['salespersonid']) && $eventdata['eventDetails']['salespersonid'] > 0) {
                    $input['salesPersonId'] = $eventdata['eventDetails']['salespersonid'];
                    if ($input['salesPersonId'] > 0) {
                        $salesDataArr = $this->salespersonHandler->getSalesPersonDetails($input);
                        // echo "<pre>";print_r($salesDataArr);exit;
                        if ($salesDataArr['status'] && $salesDataArr['response']['total'] > 0) {
                            $salesDetails = $salesDataArr['response']['details'][0];
                            if ($eventdata['eventDetails']['contactdisplay'] == 0) {
                                $salepersonDetails = "<p>Name:" . $salesDetails['name'] . "</p><p>Email:" . $salesDetails['email'] . "</p><p>Mobile:" . $salesDetails['mobile'] . "</p>";
                                $eventdata['eventDetails']['contactdetails'] = $salepersonDetails;
                            }
                        }
                    }
                }
            }
            if (strlen(trim($eventdata['eventDetails']['contactdetails'])) <= 0) {
                $eventdata['eventDetails']['contactdetails'] = "<p>Email:" . GENERAL_INQUIRY_EMAIL . "</p><p>Mobile:" . GENERAL_INQUIRY_MOBILE . "</p>";
            }
            // Get Event Setting and Ticketing Options
            $eventsettingTicketingoptions = $this->eventHandler->getTicketOptions($eventInput);
        } else {
            return $event;
        }
        if ($eventsettingTicketingoptions['status'] && $eventsettingTicketingoptions['response']['total'] > 0) {
            $eventsettings = $eventsettingTicketingoptions['response']['ticketingOptions'][0];
            // Tickets Type Limiting option settings
            $eventsignupid = $eventsignupArray['eventsignupId'];
            $eventsignuptickets['eventsignupids'] = array($eventsignupid);
            $eventsignuptickets['transactiontype'] = 'all';
            $Attinput['eventsignupids'] = array($eventsignupArray['eventsignupId']);
            if ($eventsettings['collectmultipleattendeeinfo'] == 0) {
                $Attinput['primary'] = 1;
            }
            $selectAttendeeResponse = $this->attendeeHandler->getListByEventsignupIds($Attinput);
        } else {
            return $eventsettingTicketingoptions;
        }
        $primaryAttendeeId = 0;
        $indexAttendeeResponse = array();
        if ($selectAttendeeResponse['status'] && $selectAttendeeResponse['response']['total'] > 0) {
            foreach ($selectAttendeeResponse['response']['attendeeList'] as $value) {
                if ($primaryAttendeeId == 0 && $value['primary'] == 1) {
                    $primaryAttendeeId = $value['id'];
                }
                $attendeeIds[] = $value['id'];
                $indexAttendeeResponse[$value['id']] = $value;
            }
        } else {
            return $selectAttendeeResponse;
        }

        $inputCustom['displayonticket'] = 1;
        $inputCustom['eventId'] = $eventId;
        $inputCustom['mobileNumber'] = 1;
        $reqCustomfields = $this->configureHandler->getCustomFields($inputCustom);
        $displayTicketCustomFields = array('Ticket Name');
        if ($reqCustomfields['status']) {
            if ($reqCustomfields['response']['total'] > 0) {
                $indexedReqCustomFields = array();
                $AttendeeFieldTypes = array();
                $mobiledisplayonTicketstatus = 1;
                $eventsignupTickets = explode(',', $data['eventsignupDetails']['transactionticketids']);
                foreach ($reqCustomfields['response']['customFields'] as $customFieldid => $customFields) {
                    $indexedReqCustomFields[$customFields['id']] = $customFields['fieldname'];
                    if ($customFields['fieldname'] == 'Mobile No' && $customFields['displayonticket'] == 0) {
                        $mobiledisplayonTicketstatus = 0;
                    }
                    if ($customFields['fieldtype'] == 'file') {
                        $AttendeeFieldTypes[] = $customFields['id'];
                    }
                    if ($customFields['displayonticket'] == 1) {
                        if ($customFields['fieldlevel'] == 'ticket' && in_array($customFields['ticketid'], $eventsignupTickets)) {
                            $displayTicketCustomFields[] = $customFields['fieldname'];
                        } else if ($customFields['fieldlevel'] == 'event') {
                            $displayTicketCustomFields[] = $customFields['fieldname'];
                        }
                    }
                }
            }
            $inputTkt['eventId'] = $eventId;
            $inputTkt['status'] = 0;
            $selectTicketResponse = $this->ticketHandler->getTicketName($inputTkt);
        } else {
            return $reqCustomfields;
        }
        if ($selectTicketResponse['status'] && $selectTicketResponse['response']['total'] > 0) {
            $ticketDataIdIndexed = commonHelperGetIdArray($selectTicketResponse['response']['ticketName']);
        } else {
            return $selectTicketResponse;
        }
        if (count($attendeeIds) > 0) {
            $attendeesArray['attendeeids'] = $attendeeIds;
            $attendeesArray['eventId'] = $eventsignupArray['eventsignupId'];
            $attendeesArray['customfieldIds'] = array_keys($indexedReqCustomFields);
            $attendeesArray['customfieldFileIds'] = $AttendeeFieldTypes;
            $selectAttendeedetailResponse = $this->attendeedetailHandler->getEventsignupattendees($attendeesArray);
        } else {
            $output['response']['messages'][] = ERROR_NO_ATTENDEES_DATA;
            $output['status'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $attendees['mainAttendee'] = $attendees['otherAttendee'] = array();
        if ($selectAttendeedetailResponse['status'] && $selectAttendeedetailResponse['response']['total'] > 0) {
            foreach ($selectAttendeedetailResponse['response']['attendeeDetails'] as $attendeeDetail) {
                $customfieldName = $indexedReqCustomFields[$attendeeDetail['customfieldid']];
                if ($attendeeDetail['attendeeid'] == $primaryAttendeeId) {
                    $attendees['mainAttendee'][0]['Ticket Name'] = $ticketDataIdIndexed[$indexAttendeeResponse[$attendeeDetail['attendeeid']]['ticketid']]['name'];
                    $attendees['mainAttendee'][0][$customfieldName] = $attendeeDetail['value'];
                }

                if (!isset($attendees['otherAttendee'][$attendeeDetail['attendeeid']]['Ticket Name'])) {
                    $attendees['otherAttendee'][$attendeeDetail['attendeeid']]['Ticket Name'] = $ticketDataIdIndexed[$indexAttendeeResponse[$attendeeDetail['attendeeid']]['ticketid']]['name'];
                }
                $attendees['otherAttendee'][$attendeeDetail['attendeeid']][$customfieldName] = $attendeeDetail['value'];
            }
        } else {
            return $selectAttendeedetailResponse;
        }
        $organizerid = $eventdata['ownerId'];
        $userId = $inputArray['userId'];
        $userIdList = array($organizerid, $userId);
        $userArray['userIdList'] = array_unique($userIdList);
        $usersDetailsArr = $this->userHandler->getUserDetails($userArray);
        $usersDetailsIdsArr = commonHelperGetIdArray($usersDetailsArr['response']['userData']);
        if (isset($usersDetailsIdsArr[$organizerid]) && count($usersDetailsIdsArr[$organizerid]) > 0) {
            $organizerdetails = $usersDetailsIdsArr[$organizerid];
        } else {
            $output['status'] = TRUE;
            $output['response']['messages'][] = ERROR_NO_USER;
            $output['response']['total'] = 0;
            $output['statusCode'] = STATUS_OK;
            return $output;
        }
        if (isset($usersDetailsIdsArr[$userId]) && count($usersDetailsIdsArr[$userId]) > 0) {
            $userDetails = $usersDetailsIdsArr[$userId];
        } else {
            $output['status'] = TRUE;
            $output['response']['messages'][] = ERROR_NO_USER;
            $output['response']['total'] = 0;
            $output['statusCode'] = STATUS_OK;
            return $output;
        }
        $eventsignupticketdetails = $this->eventsignupticketdetailHandler->getListByEventsignupIds($eventsignuptickets);
        if ($eventsignupticketdetails['status'] == true && $eventsignupticketdetails['response']['total'] > 0) {
            //$ticketsIds = array();
            $eventsignupticketdetails = $eventsignupticketdetails['response']['eventSignupTicketDetailList'];
            foreach ($eventsignupticketdetails as $tkey => $tval) {
                $eventsignuptickets[$tkey]['ticketId'] = $tval['ticketid'];
            }
            $ticketViralsetting = $this->ticketHandler->getViralTickets($eventInput);
            if ($ticketViralsetting['status']) {
                $data['eventticketviralsetting'] = array();
                if ($ticketViralsetting['response']['total'] > 0) {
                    //print_r($ticketViralsetting['response']['viralData'][0]);exit;
                    //    $reffcode = $data['eventsignupDetails']['referralcode'];
                    $data['eventsignupDetails']['viralReferralCode'] = isset($viralrefferalCode) ? $viralrefferalCode : '';
                    // Commented for booking flow
                    //$reffcode = $this->generateRandomString(10);
                    /*  $updateArray['viralrefferalCode'] = $reffcode;
                      $updateArray['referalcode'] = $reffcode; */
                    $data['eventticketviralsetting'] = $ticketViralsetting['response']['viralData'];
                    $data['eventticketviralsetting']['refferalCode'] = $data['eventsignupDetails']['referralcode'];
                    /*   if (strlen($data['eventsignupDetails']['referralcode']) < 10) {
                      $data['eventsignupDetails']['referralcode'] = $reffcode;
                      } */
                }
            } else {
                $ticketViralsetting = array();
                $data['eventticketviralsetting'] = $ticketViralsetting;
            }
            // echo "<pre>";print_r( $data['eventticketviralsetting']);exit;
            if ($data['eventsignupDetails']['barcodenumber'] == 0) {
                // Barcode
                $uniqueNum = substr($data['eventsignupDetails']['eventid'], 1, 4) . $data['eventsignupDetails']['id'];
                $updateArray['barcodenumber'] = $uniqueNum;
                $data['eventsignupDetails']['barcodenumber'] = $uniqueNum;
            }
            /*
             * Commented for booking Flow
             * $updateArray['eventsignupid'] = $eventsignupArray['eventsignupId'];
              $updateArray['eventId'] = $eventInput['eventId'];
              $updateArray['eventUrl'] = $eventdata['eventUrl'];
              $updateArray['eventTitle'] = $eventdata['title'];
              if ($promotercode != '') {
              $this->promoterHandler = new Promoter_handler();

              $promoterInput['eventId'] = $eventId;
              $promoterInput['promoterCode'] = $promotercode;
              $promoterStatus = $this->promoterHandler->checkPromoterCode($promoterInput);

              if ($promoterStatus['status'] && $promoterStatus['response']['total'] > 0) {
              $data['eventsignupDetails']['promotercode'] = $promotercode;
              $updateArray['promotercode'] = $promotercode;
              }
              }
              if ($orderlogstatus == 1) {
              $updateESResponse = $this->updateEventsignuptransactionsuccess($updateArray);
              } */
            $inputESTaxes['eventsignupids'] = array($eventsignupArray['eventsignupId']);
            $taxResponse = array();
            $getTaxes = $this->eventsignupTaxHandler->getTaxes($inputESTaxes);
            if ($getTaxes['status']) {
                if ($getTaxes['response']['total'] > 0) {
                    foreach ($getTaxes['response']['taxList'] as $value) {
                        $taxMappingIds[] = $value['taxmappingid'];
                    }
                    if (count($taxMappingIds) > 0) {
                        $inputTaxMapping['ids'] = $taxMappingIds;
                        $taxMappingResponse = $this->taxMappingHandler->getTaxmapping($inputTaxMapping);
                    }
                    if ($taxMappingResponse['status'] && $taxMappingResponse['response']['taxMappingList']) {
                        $indexedTaxMapping = commonHelperGetIdArray($taxMappingResponse['response']['taxMappingList']);
                        $taxes = $this->taxHandler->getTaxList();
                    } else {
                        return $taxMappingResponse;
                    }
                    if ($taxes['status'] && $taxes['response']['total'] > 0) {
                        $indexedTaxes = commonHelperGetIdArray($taxes['response']['taxList']);
                    } else {
                        return $taxes;
                    }


                    foreach ($getTaxes['response']['taxList'] as $value) {
                        $taxLabelIndex = $indexedTaxMapping[$value['taxmappingid']]['taxid'];
                        if (!isset($taxResponse[$value['ticketid']][$indexedTaxes[$taxLabelIndex]['label']])) {
                            $taxResponse[$value['ticketid']][$indexedTaxes[$taxLabelIndex]['label']] = 0;
                        }
                        $taxResponse[$value['ticketid']][$indexedTaxes[$taxLabelIndex]['label']] += $value['taxamount'];
                    }
                }
            }
        } else {
            return $eventsignupticketdetails;
        }
        $data['eventData'] = $eventdata;
        $data['organizerDetails'] = $organizerdetails;
        $data['eventSettings'] = $eventsettings;
        // Main Attendee
        //  Start of ticket list for the Eventsignup
        foreach ($eventsignupticketdetails as $ticketvalues) {
            $ticketDetails[$ticketvalues['ticketid']] = $ticketvalues;
            $ticketDetails[$ticketvalues['ticketid']]['name'] = $ticketDataIdIndexed[$ticketvalues['ticketid']]['name'];
            $ticketDetails[$ticketvalues['ticketid']]['amount'] = round($ticketvalues['amount']);
            $ticketDetails[$ticketvalues['ticketid']]['description'] = $ticketDataIdIndexed[$ticketvalues['ticketid']]['description'];
            $ticketDetails[$ticketvalues['ticketid']]['ticketTaxes'] = isset($taxResponse[$ticketvalues['ticketid']]) ? $taxResponse[$ticketvalues['ticketid']] : array();
        }

        $data['userDetail'] = $userDetails;
        $data['attendees'] = $attendees;
        $data['ticketDetails'] = $ticketDetails;
        $eventAddress = '';
        $eventData = $data['eventData'];
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
        if (isset($eventData['pincode']) && !empty($eventData['pincode'])) {
            $eventAddress .= '-' . $eventData['pincode'];
        }
        $eventAddress = ltrim($eventAddress, ',');
        if (count($data) > 0) {
            $response['eventsignupDetails'] = $data['eventsignupDetails'];
            $response['eventsignupDetails']['paymentMode'] = $eventsignupMode;
            // Get Eventsignup Seat Numbers If Any
            require_once (APPPATH . 'handlers/seating_handler.php');
            $this->seatingHandler = new Seating_handler();
            $seatInputArray['eventId'] = $eventId;
            $seatInputArray['eventsignupId'] = $eventsignupArray['eventsignupId'];
            $seatNumbers = $this->seatingHandler->getseatNumbers($seatInputArray);
            /* echo $this->ci->db->last_query();
              echo "<pre>";print_r($seatNumbers);exit; */
            if ($seatNumbers['status'] && $seatNumbers['response']['total'] > 0) {

                $seatNumbers = $seatNumbers['response']['seats'][$eventsignupArray['eventsignupId']]['GridPosition'];
                //    	$seatNumbers =   $seatNumbers['response']['seats'][487861]['GridPosition'];
                $response['eventsignupDetails']['seatNumbers'] = $seatNumbers;
            }
            $response['eventticketviralsetting'] = $data['eventticketviralsetting'];
            $response['eventData']['id'] = $data['eventData']['id'];
            $response['eventData']['ownerId'] = $data['eventData']['ownerId'];
            $response['eventData']['startDate'] = $data['eventData']['startDate'];
            $response['eventData']['endDate'] = $data['eventData']['endDate'];
            $response['eventData']['title'] = $data['eventData']['title'];
            $response['eventData']['eventUrl'] = $data['eventData']['eventUrl'];
            $response['eventData']['eventMode'] = $data['eventData']['eventMode'];
            $response['eventData']['fullAddress'] = $eventAddress;
            $response['eventData']['location']['venueName'] = $data['eventData']['venueName'];
            if (isset($data['eventData']['categoryName'])) {
                $response['eventData']['categoryName'] = $data['eventData']['categoryName'];
            }
            if (isset($data['eventData']['subCategoryName'])) {
                $response['eventData']['subCategoryName'] = $data['eventData']['subCategoryName'];
            }
            $response['eventData']['location']['cityName'] = $data['eventData']['location']['cityName'];
            $response['eventData']['location']['stateName'] = $data['eventData']['location']['stateName'];
            $response['eventData']['location']['countryName'] = $data['eventData']['location']['countryName'];
            $response['eventData']['eventDetails']['contactdetails'] = $data['eventData']['eventDetails']['contactdetails'];
            $response['eventData']['eventDetails']['contactdisplay'] = $data['eventData']['eventDetails']['contactdisplay'];
            $response['eventData']['eventDetails']['customvalidationfunction'] = $data['eventData']['eventDetails']['customvalidationfunction'];
            $response['eventData']['eventDetails']['customvalidationflag'] = $data['eventData']['eventDetails']['customvalidationflag'];
            if ($data['eventData']['eventDetails']['tnctype'] == 'organizer') {
                $response['eventData']['eventDetails']['tnc'] = $data['eventData']['eventDetails']['organizertnc'];
            } else {
                $response['eventData']['eventDetails']['tnc'] = $data['eventData']['eventDetails']['meraeventstnc'];
            }
            $response['eventData']['location']['timeZoneName'] = $data['eventData']['location']['timeZoneName'];
            $response['eventData']['eventDetails']['contactdetails'] = $data['eventData']['eventDetails']['contactdetails'];

            if (isset($data['eventData']['eventDetails']['confirmationpagescripts']) && strlen($data['eventData']['eventDetails']['confirmationpagescripts']) > 0) {
                $response['eventData']['eventDetails']['confirmationpagescripts'] = $data['eventData']['eventDetails']['confirmationpagescripts'];
            }

            $response['organizerDetails']['id'] = $data['organizerDetails']['id'];
            $response['organizerDetails']['name'] = $data['organizerDetails']['name'];
            $response['organizerDetails']['email'] = $data['organizerDetails']['email'];
            $response['organizerDetails']['phone'] = $data['organizerDetails']['phone'];
            $response['organizerDetails']['mobile'] = $data['organizerDetails']['mobile'];
            $response['userDetail']['id'] = $data['userDetail']['id'];
            $response['userDetail']['name'] = $data['userDetail']['name'];
            $response['userDetail']['email'] = $data['userDetail']['email'];
            $response['userDetail']['phone'] = $data['userDetail']['phone'];
            $response['userDetail']['mobile'] = strlen($data['userDetail']['mobile']) > 0 ? $data['userDetail']['mobile'] : $data['attendees']['mainAttendee'][0]['Mobile No'];
            if ($mobiledisplayonTicketstatus == 0) {
                unset($data['attendees']['mainAttendee'][0]['Mobile No']);
            }
            $response['attendees'] = $data['attendees'];
            $response['displayonTicketFields'] = $displayTicketCustomFields;
            $response['ticketDetails'] = $data['ticketDetails'];
            $response['eventSettings'] = $data['eventSettings'];
            $response['eventticketviralsetting'] = $data['eventticketviralsetting'];
            $output['status'] = TRUE;
            $output['response']['eventSignupDetailData'] = $response;
            $output['response']['total'] = 1;
            $output['statusCode'] = STATUS_OK;
            return $output;
        } else {
            $output['status'] = TRUE;
            $output['response']['messages'][] = ERROR_NO_DATA;
            $output['response']['total'] = 0;
            $output['statusCode'] = STATUS_OK;
            return $output;
        }
    }

    public function getdelegatepassHtml($data) {
        $configCustomDatemsg = json_decode(CONFIGCUSTOMDATEMSG, true);
        $configCustomTimemsg = json_decode(CONFIGCUSTOMTIMEMSG, true);
        $configTransactionDateonPass = json_decode(CONFIGTRANSACTIONDATEONPASS, true);
        $eventdata = $data['eventData']['eventDetails'];
        $Contact = $data['eventData']['eventDetails']['contactdetails'];
        $HighLights = '';
        $Title = $data['eventData']['title'];
        $URL = $data['eventData']['url'];
        $Eventid = $data["eventData"]["id"];
        $EventSignupId = $data['eventsignupDetails']['id'];
        $StartDt = allTimeFormats($data['eventData']['startDate'], 8);
        $EndDt = allTimeFormats($data['eventData']['endDate'], 8);
        $FBSharelink = $data['eventData']['eventDetails']['facebookLink'];
        $TwitterSharelink = $data['eventData']['eventDetails']['facebookLink'];
        $eventsignupticketdata = $data['ticketDetails'];
        $uniqueNum = $data['eventsignupDetails']['barcodenumber'];
        /* $eventstarttimes = explode(' ', $data['eventData']['startDate']);
          $eventstartTime = $eventstarttimes[1];
          $eventendtimes = explode(' ', $data['eventData']['endDate']);
          $eventendTime = $eventendtimes[1]; */

        $convertedStartDate = convertTime($data['eventData']['startDate'], $data['eventData']['location']['timeZoneName'], TRUE);
        $convertedEndDate = convertTime($data['eventData']['endDate'], $data['eventData']['location']['timeZoneName'], TRUE);
        if (isset($configCustomDatemsg[$Eventid]) && !isset($configCustomTimemsg[$Eventid])) {
            $eventDate = $configCustomDatemsg[$Eventid] . " | " . allTimeFormats($convertedStartDate, 2) . " to " . allTimeFormats($convertedEndDate, 2);
        } else if (isset($configCustomTimemsg[$Eventid]) && !isset($configCustomDatemsg[$Eventid])) {
            $eventDate = allTimeFormats($convertedStartDate, 8) . " - " . allTimeFormats($convertedEndDate, 8) . ' | ' . $configCustomTimemsg[$Eventid];
        } else if (isset($configCustomTimemsg[$Eventid]) && isset($configCustomDatemsg[$Eventid])) {
            $eventDate = $configCustomDatemsg[$Eventid] . ' | ' . $configCustomTimemsg[$Eventid];
        } else {
            $eventDate = allTimeFormats($convertedStartDate, 8) . " - " . allTimeFormats($convertedEndDate, 8) . ' | ' . allTimeFormats($convertedStartDate, 2) . " to " . allTimeFormats($convertedEndDate, 2);
        }
        if (empty($uniqueNum) || $uniqueNum == 0) {
            $uniqueNum = substr($data['eventData']['id'], 1, 4) . $EventSignupId;
        }
        $atndscount = 0;
        $atndcount = 0;
        if ($data['eventData']['eventMode'] != 1) {
            $Venue = $data['eventData']['fullAddress'];
        } elseif ($data['eventData']['eventMode'] == 1) {
            $Venue = 'Webinar';
        } else {
            $Venue = '';
        }
        //  $eventDate = date("F j, Y", strtotime($data['eventData']['startDate'])) . " - " . date("F j, Y", strtotime($data['eventData']['endDate'])) . ' | ' . date("g:i A", strtotime($eventstartTime)) . " to " . date("g:i A", strtotime($eventendTime));
        $mainattendeename = isset($data['attendees']['mainAttendee'][0]['Full Name']) ? $data['attendees']['mainAttendee'][0]['Full Name'] : '';
        $tempdata = '<div style="width:800px;margin:0 auto;">
      <div style="background:#EFEFEF;border:1px solid #333;width:800px; float:left;height:auto;margin:0px auto;font-family:calibri,sans-serif;">
      <div style="width:780px;height:auto;float:left;padding:15px 10px;">';
        $tempdata .= '<div style="width:340px;min-height:70px;float:left;padding-left:10px;padding-top:10px">';
		
		
		$orglogo = NULL;
		$orgLogoEvent = json_decode(ORGLOGOONPASS_99105,true);
		if(array_key_exists($data['eventData']['id'],$orgLogoEvent)){ $orglogo  = $orgLogoEvent[$data['eventData']['id']]; }
		
		
		if(strlen(trim($orglogo)) > 0)
		{
			$tempdata .= '<div style="float:left;width:80px; margin-right:20px; "><img src="'.$orglogo.'" /></div>';
		}
		
		//$divWidth = (strlen(trim($orglogo)) > 0)?'240px':'100%';
      $tempdata .= '<div style="float:left;width:240px;margin-left:10px;"><h5 style="margin:0px;padding:0px;font-size:18px">' . stripslashes($Title) . '</h5>
      <p style="font-size:11px;margin:0px;padding:0px;line-height:23px">' . $eventDate . '</p>';
        if ($data['eventData']['eventMode'] != 1) {
            $tempdata .= ' <p style="font-size:11px;margin:0px;padding:0px;">' . nl2br($Venue) . '</p>';
        } elseif ($data['eventData']['eventMode'] == 1) {
            $tempdata .= ' <p style="font-size:11px;margin:0px;padding:0px;">Webinar</p>';
        }
        $tempdata .= '</div></div>';
        $tempdata .= '<div style="width:280px;min-height:70px;float:right;padding-top:10px;padding-right:10px;">
      <p style="font-size:16px;margin:0px;padding:0px;text-align:right">Full Name : ' . $mainattendeename . '
      </p>
      <p style="font-size:14px;margin:0px;padding:0px;line-height:20px;text-align:right">Event ID : ' . $Eventid . '
      </p>
      <p style="font-size:14px;margin:0px;padding:0px;line-height:20px;text-align:right">Registration No : ' . $EventSignupId . '
      </p>
      	<p style="font-size:14px;margin:0px;padding:0px;line-height:20px;text-align:right">Payment Mode : ' . $data['eventsignupDetails']['paymentMode'] . '
      </p>';
        if (isset($data['eventsignupDetails']['seatNumbers']) && count($data['eventsignupDetails']['seatNumbers']) > 0) {
            $tempdata .= '<p style="font-size:14px;margin:0px;padding:0px;line-height:20px;text-align:right">Seat No. : ' . $data['eventsignupDetails']['seatNumbers'] . '
	      </p>';
        }
        if (isset($configTransactionDateonPass[$Eventid])) {
            $tempdata .= '<p style="font-size:14px;margin:0px;padding:0px;line-height:20px;text-align:right">Transaction date : ' . $data['eventsignupDetails']['signupdate'] . '
	      </p>';
        }
        $tempdata .= '</div><div style="clear:both"></div>';
        $tempdata .= '  <table width="780px" align="center" cellspacing="0" cellpadding="5" style="margin-top:15px;font-size:14px;font-family:calibri,sans-serif;border-top:1px solid #999;border-left:1px solid #999;" >
      <tbody><tr>
      <th align="left" style="padding:10px 5px 10px 10px; font-size:16px; border-bottom:1px solid #999;border-right:1px solid #999;">Ticket Type</th>
      <th align="left" style="padding:10px 5px 10px 10px; font-size:16px; border-bottom:1px solid #999; border-right:1px solid #999;">Quantity</th>';
        if ($data['eventSettings']['displayamountonticket'] == 1) {
            $tempdata .= ' <th align="left" style="padding:10px 5px 10px 10px; font-size:16px; border-bottom:1px solid #999;border-right:1px solid #999;">Amount</th>';
        }
        $tempdata .= '</tr>';
        $tc = NULL;
        $uniqueNum = 0;
        if (empty($uniqueNum) || $uniqueNum == 0) {
            $uniqueNum = substr($Eventid, 1, 4) . $EventSignupId;
        }
        $eventsignuptickettaxlabelamount = array();
        $discountamount = 0;
        $ticketamount = 0;
        $eventsignuptaxtotal = 0;
        $taxtotalamount = 0;
        $taxamount = 0;
        $ticketTaxes = array();
        $ticketamount = 0;
        foreach ($data['ticketDetails'] as $key => $value) {
            $ticketamount+= $value['amount'];
            $eventsignuptaxtotal +=$taxtotalamount;
            $taxamount += array_sum($value['ticketTaxes']);
            //	$taxamount+= $taxamount+$tval['totaltaxamount'];
            $discountamount+= $value['discountamount'] + $value['referraldiscountamount'] + $value['bulkdiscountamount'];
            foreach ($value['ticketTaxes'] as $tax => $taxvalues) {
                $ticketTaxes[$tax]+= $taxvalues;
            }
            $totalticketamount = 0;
            $mainattendeecustfields = array();
            $ticketdiscountamount = $value['discountamount'] + $value['referraldiscountamount'] + $value['bulkdiscountamount'];
            $totalticketamount += $value['totalamount'];
            $tempdata.=' <tr>
      <td style="padding:10px 5px 10px 10px; border-bottom:1px solid #999;border-right:1px solid #999;">' . $value['name'] . '</br><p style="font-size:10px;">' . $value['description'] . '</p></td>
      <td style="padding:10px 5px 10px 10px; border-bottom:1px solid #999;border-right:1px solid #999;">' . $value['ticketquantity'] . '</td>';
            if ($data['eventSettings']['displayamountonticket'] == 1) {
                if ($value['amount'] == 0) {
                    $amount = $value['amount'];
                } else {
                    $amount = $value['amount'] . " " . $data['eventsignupDetails']['currencyCode'];
                }
                $tempdata.= '<td style="padding:10px 5px 10px 10px; border-bottom:1px solid #999;border-right:1px solid #999;">' . $amount . '</td> ';
            }
            $tempdata.= ' </tr>';
        }
        $tempdata.= '</tbody></table> <div style="clear:both"></div>';
        $eventsignuptotalamount = $ticketamount > 0 ? round($ticketamount) : 0;
        // if ($data['eventsignupDetails']['eventextrachargeid'] > 0) {
        $eventsignuptotalamount = $data['eventsignupDetails']['totalamount'] > 0 ? round($data['eventsignupDetails']['totalamount']) : 0;
        //  }

        $tempdata.= '<table width="780" align="center" valign="middle" cellspacing="0" cellpadding="5" style="font-size:14px;margin:15px 0px;font-family:calibri,sans-serif;">
      <tbody><tr>
      <td width="500" align="left" valign="top"><p>Organized By : ' . $data['organizerDetails']['name'] . ' | Mobile : ' . $data['organizerDetails']['mobile'] . '</p>';
        if ($data['eventData']['eventDetails']['contactdisplay'] == 1 && strlen($data['eventData']['eventDetails']['contactdetails']) > 0) {
            $tempdata.= '<p>' . $data['eventData']['eventDetails']['contactdetails'] . '</p>';
        }
        $tempdata.= '<p><img style="margin-top:10px" src="../barcode/barcode.php?text=' . $uniqueNum . '&angle=0"></p></td>';
        $tempdata.= ' <td align="right" valign="top" style="line-height:20px;width:300px;">';
        $tickettotal = $ticketamount > 0 ? $ticketamount . " " . $data['eventsignupDetails']['currencyCode'] : 0;
        if ($data['eventSettings']['displayamountonticket'] == 1) {
            $tempdata.= '<p style="float: right;width: 300px; font-size:14px;margin:0; padding:2px 0;">Total Amount : ' . $tickettotal . '</p>';
            if ($discountamount > 0) {
                $tempdata.= '<p style="float: right;width: 300px; font-size:14px;margin:0; padding:2px 0;">Discount : ' . $discountamount . " " . $data['eventsignupDetails']['currencyCode'] . '</p>';
            }
            foreach ($ticketTaxes as $taxname => $taxvalue) {
                $tempdata.= '<p style="float: right;width: 300px;margin:0; padding:2px 0;">' . $taxname . ' : ' . $taxvalue . ' ' . $data['eventsignupDetails']['currencyCode'] . '</p>';
            }
            if ($data['eventsignupDetails']['eventextrachargeamount'] > 0) {
                $eventsignuptotalamount = $eventsignuptotalamount - $data['eventsignupDetails']['eventextrachargeamount'];
            }
            if ($eventsignuptotalamount > 0) {
                $tempdata.= '<p style="float: right;width: 300px; font-size:14px;margin:0; padding:2px 0;">Purchase Amount :' . round($eventsignuptotalamount) . " " . $data['eventsignupDetails']['currencyCode'] . '</p>';
            } else {
                $tempdata.= '<p style="float: right;width: 300px; margin:0; padding:2px 0; font-size:14px;">Purchase Amount : 0 </p>';
            }
            $tempdata.= '<p style="float: right;width: 300px; margin:0; padding:0; font-size:12px;">Inclusive of All Taxes & Excluding Convenience Fee</p>
            		<p style="float: right;width: 300px;height:40px;">&nbsp;</p>
            		<p style="float: right;width: 300px;  margin-top:40px; padding:0; font-size:12px;">Powered By</p>
            		<p class="float: right;width: 300px; margin:10px 0 0 0; padding:0; font-size:12px;">
<img src="' . MELOGO . '" alt="" style="top: 8px;width: 150px;"></p>
      </td>';
        }
        $tempdata.= '</tr>
      </tbody></table>';
        $tempdata.=' <div style="clear:both"></div></div></div>';
        $tempdata.= ' ';
        $tempdata .= ' <p style="padding:0px;margin:6px 0;font-size:12px;font-family:calibri,sans-serif;">Need help? please call us at ' . GENERAL_INQUIRY_MOBILE . '</p>';
        if (count($data['displayonTicketFields']) > 2) {
            if ($data['eventSettings']['collectmultipleattendeeinfo'] == 1 && !empty($data['attendees']['otherAttendee'])) {
                $tempdata .= '<h4 style="font-family:calibri,sans-serif;margin-bottom:8px;font-weight:bold;">Attendees Information
      </h4><table width="800" align="center" cellspacing="0" cellpadding="10" style="font-size:14px;margin-bottom:20px;font-family:calibri,sans-serif;" border="0">
      <tbody><tr>';
                $custkeys = $data['displayonTicketFields'];
                foreach ($custkeys as $h => $v) {
                    $tempdata .= '<th style="border-bottom:1px solid #CCC;background:#E7EAEC" align="left">' . $v . '</th>';
                }
                $tempdata .= '</tr>';
                foreach ($data['attendees']['otherAttendee'] as $attndkey => $attndvalues) {
                    $tempdata.="<tr>";
                    foreach ($custkeys as $key => $val) {
                        $tempdata.='<td style="border-bottom:1px solid #CCC;">' . $attndvalues[$val] . '</td>	';
                    }
                    $tempdata.="</tr>";
                }
            } else {
                $tempdata .= '<h4 style="font-family:calibri,sans-serif;margin-bottom:8px;font-weight:100">Attendees Information
      </h4><table width="800" align="center" cellspacing="0" cellpadding="10" style="font-size:14px;margin-bottom:20px;font-family:calibri,sans-serif;" border="0">
      <tbody><tr>';
                $custkeys = array_keys($data['attendees']['mainAttendee'][0]);
                foreach ($custkeys as $h => $v) {
                    $tempdata .= '<th style="border-bottom:1px solid #CCC;background:#E7EAEC" align="left">' . $v . '</th>';
                }
                $tempdata .= '</tr>';
                foreach ($data['ticketDetails'] as $tkeys => $tvals) {
                    $tempdata.="<tr>";
                    foreach ($custkeys as $key => $val) {
                        if ($val == 'Ticket Name') {
                            $tempdata.='<td style="border-bottom:1px solid #CCC;">' . $tvals['name'] . '</td>	';
                        } else {
                            $tempdata.='<td style="border-bottom:1px solid #CCC;">' . $data['attendees']['mainAttendee'][0][$val] . '</td>	';
                        }
                    }
                    $tempdata.="</tr>";
                }
            }

            $tempdata .= '</tbody></table></div>';
        }

        if (strlen(trim($data['eventData']['eventDetails']['tnc'])) > 0) {
            $tempdata .= '
      		<div style="width:900px;height:auto;margin:0px auto;font-family:calibri,sans-serif;">
      			<p style="padding:0px;margin:6px 0;font-size:16px; font-weight:bold;font-family:calibri,sans-serif;width:900px;">Terms &amp; Conditions</p>';
            $tempdata.= '<p "padding:0px;margin:6px 0;font-size:12px;font-family:calibri,sans-serif;width:900px;">' . $data['eventData']['eventDetails']['tnc'] . '</p>';
            $tempdata .= '</div>';
        }
        // echo $tempdata;exit;
        return $tempdata;
    }

    public function getTransactionsTotal($input) {
        $input['page'] = 1;
        $input['reporttype'] = 'summary';
        $validationResponse = $this->validateGetTransaction($input);
        if (!$validationResponse['status']) {
            return $validationResponse;
        }
        $this->ci->Eventsignup_model->resetVariable();
        //echo 'in';exit;
        $eventId = $input['eventid'];
        $ticketId = isset($input['ticketid']) ? $input['ticketid'] : 0;
//        $reportType = $input['reporttype'];
//        $report_types = $this->ci->config->item('report_types');
//        if (!in_array($input['reporttype'], $report_types)) {
//            $reportType = 'summary';
//        }
        $transactionType = $input['transactiontype'];
        $protect = true;
        $filterType = 'all';
        $transactionStatus = "success";
        if (isset($input['filtertype'])) {
            $filterType = $input['filtertype'];
            $protect = false;
            $transactionStatus = "'success'";
        }
        //get event time zone name
        $eventTimeZoneName = $this->eventHandler->getEventTimeZone($eventId);
        $where_in_ES = $findInSet = $orfindInSet = $notLike = $setOrWhere = array();
        switch ($filterType) {
            case 'month':
                $whereES["MONTH(CONVERT_TZ(" . $this->ci->Eventsignup_model->signupdate . ",'GMT','" . $eventTimeZoneName . "'))"] = "MONTH('" . allTimeFormats('', 11) . "')";
                $whereES["YEAR(CONVERT_TZ(" . $this->ci->Eventsignup_model->signupdate . ",'GMT','" . $eventTimeZoneName . "'))"] = "YEAR('" . allTimeFormats('', 11) . "')";
                break;
            case 'thisweek':
                $whereES["YEARWEEK(CONVERT_TZ(" . $this->ci->Eventsignup_model->signupdate . ",'GMT','" . $eventTimeZoneName . "'))"] = "YEARWEEK('" . allTimeFormats('', 11) . "',1)";
                break;
            case 'today':
                $whereES["DATE(CONVERT_TZ(" . $this->ci->Eventsignup_model->signupdate . ",'GMT','" . $eventTimeZoneName . "'))"] = "'" . allTimeFormats('', 9) . "'";
                break;
            case 'yesterday':
                $whereES["DATE(CONVERT_TZ(" . $this->ci->Eventsignup_model->signupdate . ",'GMT','" . $eventTimeZoneName . "'))"] = " DATE_SUB('" . allTimeFormats('', 6) . "', INTERVAL 1 DAY) ";
                break;
            case 'all':
            default:
                break;
        }
        $whereES[$this->ci->Eventsignup_model->eventid] = $eventId;
        $whereES[$this->ci->Eventsignup_model->deleted] = 0;
        $whereES[$this->ci->Eventsignup_model->transactionstatus] = $transactionStatus;
        //currencies list
        $currencyResponse = $this->currencyHanlder->getCurrencyList(array());
        if ($currencyResponse['status'] && $currencyResponse['response']['total'] > 0) {
            $indexedCurrencyListById = commonHelperGetIdArray($currencyResponse['response']['currencyList'], 'currencyId');
            $indexedCurrencyListByCode = commonHelperGetIdArray($currencyResponse['response']['currencyList'], 'currencyCode');
        } else {
            return $currencyResponse;
        }
//        if (isset($input['promotercode']) && $input['promotercode'] == 'promoter') {
//            $whereES[$this->ci->Eventsignup_model->promotercode . ' != '] = "'organizer'";
//            $whereES[$this->ci->Eventsignup_model->promotercode . ' != '] = '';
//        } elseif (isset($input['promotercode']) && $input['promotercode'] != 'meraevents') {
//            $whereES[$this->ci->Eventsignup_model->promotercode] = "'" . $input['promotercode'] . "'";
//        } elseif (isset($input['promotercode'])) {
//            $setOrWhere[$this->ci->Eventsignup_model->promotercode] = "''";
//            $setOrWhere[$this->ci->Eventsignup_model->promotercode] = "'0'";
//        }
        if ((isset($input['promotercode']) && $input['promotercode'] == 'promoter') || (!isset($input['promotercode']) && $transactionType == 'affiliate')) {
            $where_not_in_ES[$this->ci->Eventsignup_model->promotercode] = array('organizer', '', '0');
            //$whereES[$this->ci->Eventsignup_model->promotercode . ' != '] = '';
            //$whereES[$this->ci->Eventsignup_model->promotercode . ' != '] = '0';
        } elseif (isset($input['promotercode']) && $input['promotercode'] == 'organizer') {
            $whereES[$this->ci->Eventsignup_model->promotercode] = 'organizer';
        } elseif (isset($input['promotercode']) && $input['promotercode'] != 'meraevents') {
            $whereES[$this->ci->Eventsignup_model->promotercode] = $input['promotercode'];
        } elseif (isset($input['promotercode'])) {
            $where_in_ES[$this->ci->Eventsignup_model->promotercode] = array('', '0');
            $where_not_in_ES[$this->ci->Eventsignup_model->paymentgatewayid] = array('7', '8');
            $where_not_in_ES[$this->ci->Eventsignup_model->paymentmodeid] = array('4');
            $whereES[$this->ci->Eventsignup_model->referraldiscountamount] = '0';
        }
        if (isset($input['currencycode'])) {
            $whereES[$this->ci->Eventsignup_model->fromcurrencyid] = $indexedCurrencyListByCode[$input['currencycode']]['currencyId'];
        }
        $where_not_in_ES[$this->ci->Eventsignup_model->paymentstatus] = array('Canceled', 'Refunded');

        if ($ticketId > 0) {
            $findInSet[$ticketId] = $this->ci->Eventsignup_model->transactionticketids;
        }
        switch ($transactionType) {
            case 'all':
                break;
            case 'card':
                $whereES[$this->ci->Eventsignup_model->paymentmodeid] = 1;
                $whereES[$this->ci->Eventsignup_model->totalamount . " > "] = 0;
                $where_not_in_ES[$this->ci->Eventsignup_model->paymentgatewayid] = array('', 'A1', 0, 7, 8);
                break;
            case 'cod':
                $whereES[$this->ci->Eventsignup_model->paymentmodeid] = 2;
                break;
            case 'free':
                $setOrWhere[$this->ci->Eventsignup_model->totalamount] = 0;
                $orfindInSet['free'] = $this->ci->Eventsignup_model->transactiontickettype;
                $where_not_in_ES[$this->ci->Eventsignup_model->paymentmodeid] = 4;
                break;
            case 'offline':
                $whereES[$this->ci->Eventsignup_model->paymentmodeid] = 4;
                // $where_not_in_ES[$this->ci->Eventsignup_model->totalamount] = '0';
                break;
            case 'boxoffice':
                $where_in_ES[$this->ci->Eventsignup_model->paymentgatewayid] = array(7, 8);
                break;
            case 'viral':
                $whereES[$this->ci->Eventsignup_model->referraldiscountamount . " > "] = 0;
                break;
            case 'affiliate':
                $newData = array('', '0');
                if (isset($where_not_in_ES[$this->ci->Eventsignup_model->promotercode])) {
                    $addedData = $where_not_in_ES[$this->ci->Eventsignup_model->promotercode];
                    $newData = array_merge($newData, $addedData);
                }
                //exclude me sales
                $where_not_in_ES[$this->ci->Eventsignup_model->promotercode] = array_unique($newData);
                $notLike[$this->ci->Eventsignup_model->promotercode] = 'OFFLINE_';
                break;
            case 'cancel':
                unset($where_not_in_ES[$this->ci->Eventsignup_model->paymentstatus]);
                $where_in_ES[$this->ci->Eventsignup_model->paymentstatus] = array('Canceled');
                break;
            case 'refund':
                unset($where_not_in_ES[$this->ci->Eventsignup_model->paymentstatus]);
                $where_in_ES[$this->ci->Eventsignup_model->paymentstatus] = array('Refunded');
                break;
            default:
                break;
        }
        $selectESCount['totalcount'] = 'COUNT( ' . $this->ci->Eventsignup_model->id . ' )';
        $this->ci->Eventsignup_model->setSelect($selectESCount);
        $this->ci->Eventsignup_model->setWhereNotIn($where_not_in_ES);
        $this->ci->Eventsignup_model->setWhere($whereES);
        $this->ci->Eventsignup_model->setNotLike($notLike);
        $this->ci->Eventsignup_model->setOrWhere($setOrWhere, ' or ', ' = ', $orfindInSet);
        $this->ci->Eventsignup_model->setWhereIns($where_in_ES);
        $this->ci->Eventsignup_model->setFindInSet($findInSet);
        $this->ci->Eventsignup_model->setGroupBy(array());
        $this->ci->Eventsignup_model->setRecords(0, 0);
        $selectESCountResponse = $this->ci->Eventsignup_model->get($protect);
        //echo $this->ci->db->last_query();exit;
        $response = array('grandTotalResponse' => array('totalquantiy' => 0, 'totalamount' => 0, 'totalpaid' => 0, 'totaldiscount' => 0, 'total' => 0));

        if (count($selectESCountResponse) == 0) {
            $output['status'] = TRUE;
            $output['response']['totalResponse'] = $response;
            $output['response']['total'] = 0;
            $output['response']['messages'][] = ERROR_NO_RECORDS;
            $output['statusCode'] = STATUS_OK;
            return $output;
        }
        $totalTransactionCount = $selectESCountResponse[0]['totalcount'];
        if (REPORTS_TRANSACTION_LIMIT > $totalTransactionCount) {
            $loopCount = 1;
        } else {
            $loopCount = ceil($totalTransactionCount / REPORTS_TRANSACTION_LIMIT);
        }
        $request['eventId'] = $eventId;
        $request['status'] = 0;
        $ticketDetails = $this->ticketHandler->getTicketName($request);
        if ($ticketDetails['status'] && $ticketDetails['response']['total'] > 0) {
            //$indexedTicketArray = commonHelperGetIdArray($ticketDetails['response']['ticketName']);
            foreach ($ticketDetails['response']['ticketName'] as $tickets) {
//                if ($tickets['type'] == 'paid' || $tickets['type'] == 'donation') {
//                    $paidTickets[] = $tickets['id'];
//                } else
                if ($tickets['type'] == 'free') {
                    $freeTickets[] = $tickets['id'];
                }
                $indexedTicketArray[$tickets['id']] = $tickets;
            }
            // $totalResponse['quantity'] = 0;
            //$totalResponse['paid'] = array();
            $paid = $amount = $freeArray = $cardArray = $discount= array();
            $quantity = 0;
            for ($loop = 0; $loop < $loopCount; $loop++) {
                $selectES['id'] = $this->ci->Eventsignup_model->id;
                $selectES['totalamount'] = $this->ci->Eventsignup_model->totalamount;
                $selectES['eventextrachargeamount'] = $this->ci->Eventsignup_model->eventextrachargeamount;
                $selectES['quantity'] = $this->ci->Eventsignup_model->quantity;
                $selectES['fromcurrencyid'] = $this->ci->Eventsignup_model->fromcurrencyid;
                $selectES['tocurrencyid'] = $this->ci->Eventsignup_model->tocurrencyid;
                $selectES['conversionrate'] = $this->ci->Eventsignup_model->conversionrate;
                $selectES['convertedamount'] = $this->ci->Eventsignup_model->convertedamount;
                $selectES['paymentstatus'] = $this->ci->Eventsignup_model->paymentstatus;
                $selectES['discount'] = "round(".$this->ci->Eventsignup_model->referraldiscountamount." + ".$this->ci->Eventsignup_model->discountamount.")";
                $this->ci->Eventsignup_model->setSelect($selectES);
                $this->ci->Eventsignup_model->setWhereNotIn($where_not_in_ES);
                $this->ci->Eventsignup_model->setWhere($whereES);
                $this->ci->Eventsignup_model->setNotLike($notLike);
                $this->ci->Eventsignup_model->setOrWhere($setOrWhere, ' or ', ' = ', $orfindInSet);
                $this->ci->Eventsignup_model->setWhereIns($where_in_ES);
                $this->ci->Eventsignup_model->setFindInSet($findInSet);
                //$groupBy[]=$this->ci->Eventsignup_model->fromcurrencyid;
                //$this->ci->Eventsignup_model->setGroupBy($groupBy);
                $start = $loop * REPORTS_TRANSACTION_LIMIT;
                $this->ci->Eventsignup_model->setRecords(REPORTS_TRANSACTION_LIMIT, $start);
                $seletESResponse = $this->ci->Eventsignup_model->get($protect);
//                echo $this->ci->db->last_query();
//                exit;
                $partialEventsignupIds = array();
                $indexEventSignupIdArray = array();
                $eventSignupIds = $estdResponse = array();
                if (count($seletESResponse) > 0) {
                    // $output['response']['totalTransactionCount'] = $selectESCountResponse[0]['totalcount'];
                    $indexEventSignupIdArray = commonHelperGetIdArray($seletESResponse);
                    $eventSignupIds = array_keys($indexEventSignupIdArray);
                    $inputArray['eventsignupids'] = $eventSignupIds;
                    $inputArray['transactiontype'] = $transactionType;
                    if ($ticketId > 0) {
                        $inputArray['ticketids'] = array($ticketId);
                    }
                    $estdResponse = $this->estdHandler->getAmountTotal($inputArray);
                }//print_r($estdResponse['response']['eventsignupticketdetailResponse']);
                if (count($estdResponse) > 0 && $estdResponse['status'] && $estdResponse['response']['total'] > 0) {
                    foreach ($estdResponse['response']['eventsignupticketdetailResponse'] as $value) {
//                        if (!isset($amount[$indexedCurrencyListById[$indexedTicketArray[$value['ticketid']]['currencyid']]['currencyCode']]) && ((isset($value['amount']) && $value['amount'] > 0) || (isset($value['totalamountsum']) && $value['totalamountsum'] > 0))) {
//                            $amount[$indexedCurrencyListById[$indexedTicketArray[$value['ticketid']]['currencyid']]['currencyCode']] = 0;
//                        }
                        if ($ticketId > 0) {
                            $ticketWiseESIds[$value['eventsignupid']] = $value;
                            if ($value['amount'] > 0) {
                                if (!isset($amount[$indexedCurrencyListById[$indexedTicketArray[$value['ticketid']]['currencyid']]['currencyCode']])) {
                                    $amount[$indexedCurrencyListById[$indexedTicketArray[$value['ticketid']]['currencyid']]['currencyCode']] = 0;
                                }
                                $amount[$indexedCurrencyListById[$indexedTicketArray[$value['ticketid']]['currencyid']]['currencyCode']]+=$value['amount'];
                            }
                        } elseif ($transactionType == 'free') {
                            if ($value['totalamount'] == 0) {
                                if (!isset($freeArray[$value['eventsignupid']])) {
                                    $freeArray[$value['eventsignupid']] = 0;
                                }
                                $freeArray[$value['eventsignupid']] += $value['ticketquantity'];
                                //var_dump($indexedCurrencyListById[$indexedTicketArray[$value['ticketid']]['currencyid']]['currencyCode']);
                                if (!isset($amount[$indexedCurrencyListById[$indexedTicketArray[$value['ticketid']]['currencyid']]['currencyCode']])) {
                                    $amount[$indexedCurrencyListById[$indexedTicketArray[$value['ticketid']]['currencyid']]['currencyCode']] = 0;
                                }
                                $amount[$indexedCurrencyListById[$indexedTicketArray[$value['ticketid']]['currencyid']]['currencyCode']]+=$value['amount'];
                            }
                        } else if ($transactionType == 'card') {
                            if (!isset($cardArray[$value['eventsignupid']])) {
                                $cardArray[$value['eventsignupid']] = 0;
                            }
                            if ($value['amount'] != 0 && $value['totalamount'] != 0) {
                                $cardArray[$value['eventsignupid']] += $value['ticketquantity'];
                            }
                            if (!isset($amount[$indexedCurrencyListById[$indexedTicketArray[$value['ticketid']]['currencyid']]['currencyCode']])) {
                                $amount[$indexedCurrencyListById[$indexedTicketArray[$value['ticketid']]['currencyid']]['currencyCode']] = 0;
                            }
                            $amount[$indexedCurrencyListById[$indexedTicketArray[$value['ticketid']]['currencyid']]['currencyCode']]+=$value['amount'];
                        } else {
                            if ($value['totalamountsum'] > 0) {
                                if (!isset($amount[$indexedCurrencyListById[$indexedTicketArray[$value['ticketid']]['currencyid']]['currencyCode']])) {
                                    $amount[$indexedCurrencyListById[$indexedTicketArray[$value['ticketid']]['currencyid']]['currencyCode']] = 0;
                                }
                                $amount[$indexedCurrencyListById[$indexedTicketArray[$value['ticketid']]['currencyid']]['currencyCode']]+=$value['totalamountsum'];
                            }
                        }
                    }
//                    print_r($amount);
//                    exit;
                    foreach ($seletESResponse as $value) {
//                $indexEventSignupIdArray[$value['id']] = $value;
//                $eventSignupIds[] = $value['id'];
                        if (!isset($ticketWiseESIds) || $transactionType == 'free' || (in_array($value['id'], array_keys($ticketWiseESIds)))) {
                            if ($ticketId > 0) {
                                $quantity+=$ticketWiseESIds[$value['id']]['ticketquantity'];
                                $discount[$indexedCurrencyListById[$value['fromcurrencyid']]['currencyCode']]+=$ticketWiseESIds[$value['id']]['discount'];
                            } elseif ($transactionType == 'free') {
                                $quantity+=$freeArray[$value['id']];
                            } elseif ($transactionType == 'card') {
                                $quantity+=$cardArray[$value['id']];
                            } else {
                                $quantity+=$value['quantity'];
                            }
                            if (strcmp(strtolower($value['paymentstatus']), 'partialrefund') == 0) {
                                $partialEventsignupIds[] = $value['id'];
                            } else {
                                if ($transactionType != 'free' || ( $ticketId > 0 && !in_array($ticketId, $freeTickets))) {
                                    if (($value['convertedamount'] > 0 && $value['conversionrate'] > 1)) {
                                        if (!isset($paid[$indexedCurrencyListById[$value['tocurrencyid']]['currencyCode']])) {
                                            $paid[$indexedCurrencyListById[$value['tocurrencyid']]['currencyCode']] = 0;
                                        }
                                        $totalAmount = $value['totalamount'];
                                        $extraCovertedAmount = 0;
                                        $purchaseTotal = $value['convertedamount'] * $value['quantity'];
                                        if ($ticketId > 0) {
                                            $totalAmount = $ticketWiseESIds[$value['id']]['totalamount'];
                                            $purchaseTotal = round(($ticketWiseESIds[$value['id']]['totalamount'] * $value['convertedamount'] * $value['quantity']) / $value['totalamount'], 2);
                                        } else {
                                            if ($value['eventextrachargeamount'] > 0) {
                                                $convertedAmount = $value['convertedamount'] * $value['quantity'];
                                                $echargeAmount = $value['eventextrachargeamount'];
                                                $extraCovertedAmount = round(($echargeAmount * $convertedAmount) / $totalAmount);
                                            }
                                        }
                                        $paid[$indexedCurrencyListById[$value['tocurrencyid']]['currencyCode']]+=(($purchaseTotal - $extraCovertedAmount) * $value['conversionrate']);
                                    } elseif ($value['convertedamount'] > 0) {
                                        if (!isset($paid['USD'])) {
                                            $paid['USD'] = 0;
                                        }
                                        $totalAmount = $value['totalamount'];
                                        $extraCovertedAmount = 0;
                                        $purchaseTotal = $value['convertedamount'] * $value['quantity'];
                                        if ($ticketId > 0) {
                                            $totalAmount = $ticketWiseESIds[$value['id']]['totalamount'];
                                            $purchaseTotal = round(($ticketWiseESIds[$value['id']]['totalamount'] * $value['convertedamount'] * $value['quantity']) / $value['totalamount'], 2);
                                        } else {
                                            if ($value['eventextrachargeamount'] > 0) {
                                                $convertedAmount = $value['convertedamount'] * $value['quantity'];
                                                $echargeAmount = $value['eventextrachargeamount'];
                                                $extraCovertedAmount = round(($echargeAmount * $convertedAmount) / $totalAmount);
                                            }
                                        }
                                        $paid['USD']+=($purchaseTotal - $extraCovertedAmount);
                                    } elseif ($value['conversionrate'] > 1) {
                                        if (!isset($paid[$indexedCurrencyListById[$value['tocurrencyid']]['currencyCode']])) {
                                            $paid[$indexedCurrencyListById[$value['tocurrencyid']]['currencyCode']] = 0;
                                        }
                                        $purchaseTotal = $value['totalamount'];
                                        $extraChargeAmt = round($value['eventextrachargeamount']);
                                        if ($ticketId > 0) {
                                            $purchaseTotal = $ticketWiseESIds[$value['id']]['totalamount'];
                                            $extraChargeAmt = 0;
                                        }
                                        $paid[$indexedCurrencyListById[$value['tocurrencyid']]['currencyCode']]+=(($purchaseTotal - $extraChargeAmt) * $value['conversionrate']);
                                    } else {
                                        if (!isset($paid[$indexedCurrencyListById[$value['fromcurrencyid']]['currencyCode']])) {
                                            $paid[$indexedCurrencyListById[$value['fromcurrencyid']]['currencyCode']] = 0;
                                        }
                                        $purchaseTotal = $value['totalamount'];
                                        $extraChargeAmt = round($value['eventextrachargeamount']);
                                        if ($ticketId > 0) {
                                            $purchaseTotal = $ticketWiseESIds[$value['id']]['totalamount'];
                                            $extraChargeAmt = 0;
                                        }
                                        $paid[$indexedCurrencyListById[$value['fromcurrencyid']]['currencyCode']]+=($purchaseTotal - $extraChargeAmt);
                                    }
                                     if ($ticketId == 0) {
                                        $discount[$indexedCurrencyListById[$value['fromcurrencyid']]['currencyCode']]+=$value['discount'];
                                     }
                                }
                            }
                        }
                    }
                } elseif (count($estdResponse) > 0) {
                    return $estdResponse;
                }
            }
            $selectPartialPayments = $indexedRefunds = array();
            if (count($partialEventsignupIds) > 0) {
                $refundHandler = new Refund_handler();
                $inputRefunds['eventsignupids'] = $partialEventsignupIds;
                $selectPartialPayments = $refundHandler->getRefundList($inputRefunds);
            }
            if (isset($selectPartialPayments['status']) && $selectPartialPayments['status']) {
                if ($selectPartialPayments['response']['total'] > 0) {
                    $indexedRefunds = commonHelperGetIdArray($selectPartialPayments['response']['refundList'], 'eventsignupid');
                }
            } elseif (count($selectPartialPayments) > 0) {
                return $selectPartialPayments;
            }
            //          print_r($seletESResponse);
//            exit;
            foreach ($indexedRefunds as $key => $value) {
                if (($indexEventSignupIdArray[$key]['convertedamount'] > 0 && $indexEventSignupIdArray[$key]['conversionrate'] > 1) || $indexEventSignupIdArray[$key]['conversionrate'] > 1) {
                    if (!isset($paid[$indexedCurrencyListById[$indexEventSignupIdArray[$key]['tocurrencyid']]['currencyCode']])) {
                        $paid[$indexedCurrencyListById[$value['tocurrencyid']]['currencyCode']] = 0;
                    }
                    $paid[$indexedCurrencyListById[$indexEventSignupIdArray[$key]['tocurrencyid']]['currencyCode']]+=(($indexEventSignupIdArray[$key]['convertedamount'] * $indexEventSignupIdArray[$key]['quantity']) * $indexEventSignupIdArray[$key]['conversionrate']) - $value['totalrefundamount'];
                } else {
                    $paid[$indexedCurrencyListById[$value['fromcurrencyid']]['currencyCode']]+=($value['totalamount']);
                }
//                    elseif ($indexEventSignupIdArray[$key]['convertedamount'] > 0) {
//                        if (!isset($paid['USD'])) {
//                            $paid['USD'] = 0;
//                        }
//                        $paid['USD']+=($indexEventSignupIdArray[$key]['convertedamount']);
//                    }
            }
            if (count($paid) > 0) {
                foreach ($paid as $curr => $paidAmount) {
                    $paid[$curr] = round($paidAmount);
                }
            } else {
                $paid[''] = 0;
            }
            if (count($amount) == 0) {
                $amount[''] = 0;
            }
            if (count($discount) == 0) {
                $discount[''] = 0;
            }
            $output['status'] = TRUE;
            $output['statusCode'] = STATUS_OK;
            $output['response']['total'] = 1;
            //$output['response']['grandTotalResponse'] = array('totalquantity' => $totalResponse['quantity'], 'totalamount' => $totalResponse['amount'], 'totalpaid' => $totalResponse['paid'], 'totaldiscount' => $totalResponse['discount']);
            $output['response']['grandTotalResponse'] = array('totalquantity' => $quantity, 'totalamount' => $amount, 'totalpaid' => $paid,'totaldiscount'=>$discount);
            return $output;
        } else {
            return $ticketDetails;
        }
    }

//    public function getAllSaleCurrencies($input) {
//        $this->ci->form_validation->pass_array($input);
//        $this->ci->form_validation->set_rules('eventid', 'eventid', 'is_natural_no_zero|required_strict');
//        $this->ci->form_validation->set_rules('ticketid', 'ticketid', 'is_natural_no_zero');
//        $this->ci->form_validation->set_rules('filtertype', 'filtertype', 'required_strict');
//        $this->ci->form_validation->set_rules('currencyid', 'currencyid', 'required_strict');
//        //$this->ci->form_validation->set_rules('page', 'page', 'is_natural_no_zero|required_strict');
//        //$this->ci->form_validation->set_rules('filtertype', 'filtertype', 'is_natural_no_zero|required_strict');
//        if ($this->ci->form_validation->run() == FALSE) {
//            $response = $this->ci->form_validation->get_errors();
//            $output['status'] = FALSE;
//            $output['response']['messages'] = $response['message'];
//            $output['statusCode'] = 400;
//            return $output;
//        }
//        $eventId = $input['eventid'];
//        $ticketId = isset($input['ticketid']) ? $input['ticketid'] : 0;
//        $filterType = isset($input['filtertype']) ? $input['filtertype'] : 'all';
//        $currencyId = $input['currencyid'];
//        switch ($filterType) {
//            case 'month':
//                $whereES['MONTH(' . $this->ci->Eventsignup_model->signupdate . ")"] = 'MONTH(' . date('Y-m-d H:i:s') . ')';
//                break;
//            case 'thisweek':
//                $whereES['YEARWEEK(' . $this->ci->Eventsignup_model->signupdate . ",1)"] = 'YEARWEEK(' . date('Y-m-d H:i:s') . ',1)';
//                break;
//            case 'today':
//                $whereES['DATE(' . $this->ci->Eventsignup_model->signupdate . ")"] = date('Y-m-d');
//                break;
//            case 'yesterday':
//                $whereES[$this->ci->Eventsignup_model->signupdate . " <= "] = " DATE_SUB(" . date('Y-m-d 00:00:00') . ", INTERVAL -1 DAY) ";
//                break;
//            case 'all':
//            default:
//                break;
//        }
//        $selectCurrencies['currencyid'] = 'DISTINCT CASE WHEN ' . $this->ci->Eventsignup_model->convertedamount . ' > 0 THEN 2 ELSE ' . $this->ci->Eventsignup_model->fromcurrencyid . ' END ';
//        $this->ci->Eventsignup_model->setSelect($selectCurrencies);
//        $where_not_in_ES[$this->ci->Eventsignup_model->paymentstatus] = array('Canceled', 'Refunded');
//        $this->ci->Eventsignup_model->setWhereNotIn($where_not_in_ES);
//        $this->ci->Eventsignup_model->setWhere($whereES);
//        //$this->ci->Eventsignup_model->setOrWhere($setOrWhere);
//        //$this->ci->Eventsignup_model->setWhereIns($where_in_ES);
//        //$this->ci->Eventsignup_model->setFindInSet($findInSet);
//        $selectESCurrencies = $this->ci->Eventsignup_model->get();
//        //currencies list
//        $currencyResponse = $this->currencyHanlder->getCurrencyList(array());
//        if ($currencyResponse['status'] && $currencyResponse['response']['total'] > 0) {
//            $indexedCurrencyListById = commonHelperGetIdArray($currencyResponse['response']['currencyList'], 'currencyId');
//        } else {
//            return $currencyResponse;
//        }
//        $currencyData = array();
//        if (count($selectESCurrencies) > 0) {
//            foreach ($selectESCurrencies as $value) {
//                $currencyData[$value['currencyid']] = $indexedCurrencyListById[$value['currencyid']]['currencyCode'];
//            }
//        }
//        $output['status'] = TRUE;
//        $output['statusCode'] = 200;
//        $output['response']['total'] = count($currencyData);
//        $output['response']['currencyData'] = $currencyData;
//        return $output;
//    }

    public function getWeekwiseSales($input) {
        $this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($input);
        $this->ci->form_validation->set_rules('eventid', 'eventid', 'is_natural_no_zero|required_strict');
        $this->ci->form_validation->set_rules('ticketid', 'ticketid', 'is_natural_no_zero');
        $this->ci->form_validation->set_rules('filtertype', 'filtertype', 'required_strict');
        //$this->ci->form_validation->set_rules('currencyid', 'currencyid', 'required_strict');
        //$this->ci->form_validation->set_rules('page', 'page', 'is_natural_no_zero|required_strict');
        //$this->ci->form_validation->set_rules('filtertype', 'filtertype', 'is_natural_no_zero|required_strict');
        if ($this->ci->form_validation->run() == FALSE) {
            $response = $this->ci->form_validation->get_errors();
            $output['status'] = FALSE;
            $output['response']['messages'] = $response['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $this->ci->Eventsignup_model->resetVariable();
        $eventId = $input['eventid'];
        $ticketId = isset($input['ticketid']) ? $input['ticketid'] : 0;
        $transactionStatus = "success";
        if (isset($input['filtertype'])) {
            $filterType = $input['filtertype'];
            $protect = false;
            $transactionStatus = "'success'";
        }
        //$currencyId = $input['currencyid'];
        //$transactionType = 'summary';
        //get event time zone name
        $eventTimeZoneName = $this->eventHandler->getEventTimeZone($eventId);
        $findInSet = $where_in_ES = $notLike = $setOrWhere = array();
        switch ($filterType) {
            case 'month':
                $whereES["MONTH(CONVERT_TZ(" . $this->ci->Eventsignup_model->signupdate . ",'GMT','" . $eventTimeZoneName . "'))"] = "MONTH('" . allTimeFormats('', 11) . "')";
                $whereES["YEAR(CONVERT_TZ(" . $this->ci->Eventsignup_model->signupdate . ",'GMT','" . $eventTimeZoneName . "'))"] = "YEAR('" . allTimeFormats('', 11) . "')";
                break;
            case 'thisweek':
                $whereES["YEARWEEK(CONVERT_TZ(" . $this->ci->Eventsignup_model->signupdate . ",'GMT','" . $eventTimeZoneName . "'))"] = "YEARWEEK('" . allTimeFormats('', 11) . "',1)";
                break;
            case 'today':
                $whereES["DATE(CONVERT_TZ(" . $this->ci->Eventsignup_model->signupdate . ",'GMT','" . $eventTimeZoneName . "'))"] = "'" . allTimeFormats('', 9) . "'";
                break;
            case 'yesterday':
                $whereES["DATE(CONVERT_TZ(" . $this->ci->Eventsignup_model->signupdate . ",'GMT','" . $eventTimeZoneName . "'))"] = " DATE_SUB('" . allTimeFormats('', 6) . "', INTERVAL 1 DAY) ";
                break;
            case 'all':
            default:
                break;
        }
        $whereES[$this->ci->Eventsignup_model->eventid] = $eventId;
        $whereES[$this->ci->Eventsignup_model->deleted] = 0;
        $whereES[$this->ci->Eventsignup_model->transactionstatus] = $transactionStatus;
//        if ($currencyId == 1) {
//            $whereES[$this->ci->Eventsignup_model->fromcurrencyid] = $currencyId;
//            $whereES[$this->ci->Eventsignup_model->convertedamount] = 0;
//        } elseif ($currencyId == 2) {
//            $setOrWhere[$this->ci->Eventsignup_model->fromcurrencyid] = $currencyId;
//            $setOrWhere[$this->ci->Eventsignup_model->convertedamount . ' > '] = 0;
//        } else {
//            $whereES[$this->ci->Eventsignup_model->fromcurrencyid] = $currencyId;
//        }
        //currencies list
//        $currencyResponse = $this->currencyHanlder->getCurrencyList(array());
//        if ($currencyResponse['status'] && $currencyResponse['response']['total'] > 0) {
//            $indexedCurrencyListById = commonHelperGetIdArray($currencyResponse['response']['currencyList'], 'currencyId');
//        } else {
//            return $currencyResponse;
//        }
        $where_not_in_ES[$this->ci->Eventsignup_model->paymentstatus] = array('Canceled', 'Refunded');

        if ($ticketId > 0) {
            $findInSet[$ticketId] = $this->ci->Eventsignup_model->transactionticketids;
        }
        $selectESCount['totalcount'] = 'COUNT( ' . $this->ci->Eventsignup_model->id . ' )';
        $this->ci->Eventsignup_model->setSelect($selectESCount);
        $this->ci->Eventsignup_model->setWhereNotIn($where_not_in_ES);
        $this->ci->Eventsignup_model->setWhere($whereES);
        $this->ci->Eventsignup_model->setOrWhere($setOrWhere);
        $this->ci->Eventsignup_model->setWhereIns($where_in_ES);
        $this->ci->Eventsignup_model->setFindInSet($findInSet);
        $protect = false;
        $selectESCountResponse = $this->ci->Eventsignup_model->get($protect);
//        echo $this->ci->db->last_query();
//        exit;
        $response = array('weekWiseData' => array('totalquantiy' => 0, 'totalamount' => 0, 'totalpaid' => 0, 'totaldiscount' => 0, 'total' => 0));

        if (count($selectESCountResponse) == 0) {
            $output['status'] = TRUE;
            $output['response']['totalResponse'] = $response;
            $output['response']['total'] = 0;
            $output['response']['messages'][] = ERROR_NO_RECORDS;
            $output['statusCode'] = STATUS_OK;
            return $output;
        }
        $totalTransactionCount = $selectESCountResponse[0]['totalcount'];
        if (REPORTS_TRANSACTION_LIMIT > $totalTransactionCount) {
            $loopCount = 1;
        } else {
            $loopCount = ceil($totalTransactionCount / REPORTS_TRANSACTION_LIMIT);
        }
        // $request['eventId'] = $eventId;
        //$request['status'] = 0;
        //$amount = array();
        $quantity = array();
        for ($loop = 0; $loop < $loopCount; $loop++) {
            $selectES['id'] = $this->ci->Eventsignup_model->id;
            if ($ticketId == 0) {
                // $selectES['totalamount'] = 'SUM(CASE WHEN ' . $this->ci->Eventsignup_model->convertedamount . '=0 THEN ' . $this->ci->Eventsignup_model->totalamount . ' ELSE ' . $this->ci->Eventsignup_model->convertedamount . ' END)';
                $selectES['quantity'] = 'SUM(' . $this->ci->Eventsignup_model->quantity . ')';
                //$selectES['fromcurrencyid'] = $this->ci->Eventsignup_model->fromcurrencyid;
                $groupBy[] = "year(CONVERT_TZ(" . $this->ci->Eventsignup_model->signupdate . ",'GMT','" . $eventTimeZoneName . "'))";
                $groupBy[] = "week(CONVERT_TZ(" . $this->ci->Eventsignup_model->signupdate . ",'GMT','" . $eventTimeZoneName . "'))";
                $this->ci->Eventsignup_model->setGroupBy($groupBy);
            }

            $this->ci->Eventsignup_model->setSelect($selectES);
            $this->ci->Eventsignup_model->setWhereNotIn($where_not_in_ES);
            $this->ci->Eventsignup_model->setWhere($whereES);
            $this->ci->Eventsignup_model->setOrWhere($setOrWhere);
            $this->ci->Eventsignup_model->setWhereIns($where_in_ES);
            $this->ci->Eventsignup_model->setFindInSet($findInSet);
            $start = $loop * REPORTS_TRANSACTION_LIMIT;
            $this->ci->Eventsignup_model->setRecords(REPORTS_TRANSACTION_LIMIT, $start);
            $seletESResponse = $this->ci->Eventsignup_model->get($protect);
            //echo $this->ci->db->last_query();exit;
            //print_r($seletESResponse);
            //$partialEventsignupIds = array();
            $indexEventSignupIdArray = array();
            $eventSignupIds = $estdResponse = array();
            if (count($seletESResponse) > 0) {
                if ($ticketId > 0) {
                    $indexEventSignupIdArray = commonHelperGetIdArray($seletESResponse);
                    $eventSignupIds = array_keys($indexEventSignupIdArray);
                    $inputArray['eventsignupids'] = $eventSignupIds;
                    $inputArray['ticketids'] = array($ticketId);
                    $estdResponse = $this->estdHandler->getWeekwiseTotalQuantity($inputArray);
                } else {
                    $i = 1;
                    foreach ($seletESResponse as $value) {
                        $quantity['week ' . $i++] = $value['quantity'];
//                        if (!empty($indexedCurrencyListById[$value['fromcurrencyid']]['currencyCode'])) {
//                            $amount['week ' . $i][$indexedCurrencyListById[$value['fromcurrencyid']]['currencyCode']] = round($value['totalamount'], 2);
//                        }
                    }
                }
            }
            if (count($estdResponse) > 0 && $estdResponse['status'] && $estdResponse['response']['total'] > 0) {
                $i = 1;
                foreach ($estdResponse['response']['eventsignupticketdetailResponse'] as $value) {
//                    if ($value['totalamountsum'] > 0 && !empty($indexEventSignupIdArray[$value['eventsignupid']]['fromcurrencyid'])) {
//                        $amount[$indexedCurrencyListById[$indexEventSignupIdArray[$value['eventsignupid']]['fromcurrencyid']]['currencyCode']]+=$value['totalamountsum'];
//                    }
                    $quantity['week ' . $i] = $value['quantity'];
                    $i++;
                }
            } elseif (count($estdResponse) > 0) {
                return $estdResponse;
            }
        }
//        $whereES[$this->ci->Eventsignup_model->paymentstatus] = "'" . PartialRefund . "'";
//        $selectES['id'] = $this->ci->Eventsignup_model->id;
//        $this->ci->Eventsignup_model->setSelect($selectES);
//        //$this->ci->Eventsignup_model->setWhereNotIn($where_not_in_ES);
//        $this->ci->Eventsignup_model->setWhere($whereES);
//        $this->ci->Eventsignup_model->setOrWhere($setOrWhere);
//        $this->ci->Eventsignup_model->setWhereIns($where_in_ES);
//        $this->ci->Eventsignup_model->setFindInSet($findInSet);
//        $start = $loop * REPORTS_TRANSACTION_LIMIT;
//        $this->ci->Eventsignup_model->setRecords(REPORTS_TRANSACTION_LIMIT, $start);
//        $seletESResponse = $this->ci->Eventsignup_model->get($protect);
//        if (count($seletESResponse) > 0) {
//            foreach ($seletESResponse as $value) {
//                $partialEventsignupIds[] = $value['id'];
//            }
//        }
//        $selectPartialPayments = $indexedRefunds = array();
//        if (count($partialEventsignupIds) > 0) {
//            $refundHandler = new Refund_handler();
//            $inputRefunds['eventsignupids'] = $partialEventsignupIds;
//            $inputRefunds['sum'] = true;
//            $selectPartialPayments = $refundHandler->getRefundList($inputRefunds);
//        }
//        if (isset($selectPartialPayments['status']) && $selectPartialPayments['status']) {
//            if ($selectPartialPayments['response']['total'] > 0) {
//                $amount['INR']-=$selectPartialPayments['response']['refundList'][0]['totalrefundamount'];
//            }
//        } elseif (count($selectPartialPayments) > 0) {
//            return $selectPartialPayments;
//        }
//
//        if (count($amount) == 0) {
//            $amount[''] = 0;
//        }
        $output['status'] = TRUE;
        $output['statusCode'] = STATUS_OK;
        $output['response']['total'] = 1;
        //$output['response']['grandTotalResponse'] = array('totalquantity' => $totalResponse['quantity'], 'totalamount' => $totalResponse['amount'], 'totalpaid' => $totalResponse['paid'], 'totaldiscount' => $totalResponse['discount']);
        $output['response']['weekWiseData'] = array('quantity' => $quantity);
        $inputTransactionTotal['eventid'] = $eventId;
        $inputTransactionTotal['ticketid'] = $ticketId;
        $inputTransactionTotal['filtertype'] = $filterType;
        $inputTransactionTotal['reporttype'] = 'summary';
        $inputTransactionTotal['transactiontype'] = 'all';
        $totalTransactionsResponse = $this->getTransactionsTotal($inputTransactionTotal);
        if ($totalTransactionsResponse['status'] && $totalTransactionsResponse['response']['total'] > 0) {
            $totalTransactionsData = $totalTransactionsResponse['response']['grandTotalResponse'];
        } else {
            return $totalTransactionsResponse;
        }
        $output['response']['totalTransactionsData'] = $totalTransactionsData;
        return $output;
//        } else {
//            return $ticketDetails;
//        }
    }

    public function getSalesEffortTotals($input) {
        $eventId = $input['eventid'];
        $protect = true;
        $filterType = 'all';
        $transactionStatus = "success";
        $this->ci->Eventsignup_model->resetVariable();
        if (isset($input['filtertype'])) {
            $filterType = $input['filtertype'];
            $protect = false;
            $transactionStatus = "'success'";
        }
        //get event time zone name
        $eventTimeZoneName = $this->eventHandler->getEventTimeZone($eventId);
        switch ($filterType) {
            case 'month':
                $whereES["MONTH(CONVERT_TZ(" . $this->ci->Eventsignup_model->signupdate . ",'GMT','" . $eventTimeZoneName . "'))"] = "MONTH('" . allTimeFormats('', 11) . "')";
                $whereES["YEAR(CONVERT_TZ(" . $this->ci->Eventsignup_model->signupdate . ",'GMT','" . $eventTimeZoneName . "'))"] = "YEAR('" . allTimeFormats('', 11) . "')";
                break;
            case 'thisweek':
                $whereES["YEARWEEK(CONVERT_TZ(" . $this->ci->Eventsignup_model->signupdate . ",'GMT','" . $eventTimeZoneName . "'))"] = "YEARWEEK('" . allTimeFormats('', 11) . "',1)";
                break;
            case 'today':
                $whereES["DATE(CONVERT_TZ(" . $this->ci->Eventsignup_model->signupdate . ",'GMT','" . $eventTimeZoneName . "'))"] = "'" . allTimeFormats('', 9) . "'";
                break;
            case 'yesterday':
                $whereES["DATE(CONVERT_TZ(" . $this->ci->Eventsignup_model->signupdate . ",'GMT','" . $eventTimeZoneName . "'))"] = " DATE_SUB('" . allTimeFormats('', 6) . "', INTERVAL 1 DAY) ";
                break;
            case 'all':
            default:
                break;
        }
        $selectESCount['totalcount'] = 'COUNT( ' . $this->ci->Eventsignup_model->id . ' )';
        $this->ci->Eventsignup_model->setSelect($selectESCount);
        $whereES[$this->ci->Eventsignup_model->eventid] = $eventId;
        $whereES[$this->ci->Eventsignup_model->deleted] = 0;
        $whereES[$this->ci->Eventsignup_model->transactionstatus] = $transactionStatus;
        $where_not_in_ES[$this->ci->Eventsignup_model->paymentstatus] = array('Canceled', 'Refunded');
        $this->ci->Eventsignup_model->setWhereNotIn($where_not_in_ES);
        $this->ci->Eventsignup_model->setWhere($whereES);
        $this->ci->Eventsignup_model->setRecords(0, 0);
        $selectESCountResponse = $this->ci->Eventsignup_model->get($protect);
//        echo $this->ci->db->last_query();
//        exit;
        if (count($selectESCountResponse) == 0) {
            $response = array('salesEffortResponse' => array('organizer' => array('totalquantity' => 0, 'totalamount' => 0), 'promoter' => array('totalquantity' => 0, 'totalamount' => 0), 'offlinepromoter' => array('totalquantity' => 0, 'totalamount' => 0), 'boxoffice' => array('totalquantity' => 0, 'totalamount' => 0), 'viral' => array('totalquantity' => 0, 'totalamount' => 0), 'meraevents' => array('totalquantity' => 0, 'totalamount' => 0)));
            $output['status'] = TRUE;
            $output['response']['totalResponse'] = $response;
            $output['response']['total'] = 0;
            $output['statusCode'] = STATUS_OK;
            return $output;
        }
        $totalTransactionCount = $selectESCountResponse[0]['totalcount'];
        $loopCount = ceil($totalTransactionCount / REPORTS_DISPLAY_LIMIT);
        $inputArray = array();
        $currencyResponse = $this->currencyHanlder->getCurrencyList($inputArray);
        if ($currencyResponse['status'] && $currencyResponse['response']['total'] > 0) {
            $currencyList = $currencyResponse['response']['currencyList'];
            $indexedCurrencyList = commonHelperGetIdArray($currencyList, 'currencyId');
        } else {
            return $currencyResponse;
        }
        $organizerTotal = array('totalquantity' => 0);
        $promoterTotal = array('totalquantity' => 0);
        $offlinePromoterTotal = array('totalquantity' => 0);
        $boxofficeTotal = array('totalquantity' => 0);
        $viralTotal = array('totalquantity' => 0);
        $meraeventsTotal = array('totalquantity' => 0);
        for ($loop = 0; $loop < $loopCount; $loop++) {
            $selectES['id'] = $this->ci->Eventsignup_model->id;
            $selectES['totalamount'] = $this->ci->Eventsignup_model->totalamount;
            $selectES['quantity'] = $this->ci->Eventsignup_model->quantity;
            $selectES['fromcurrencyid'] = $this->ci->Eventsignup_model->fromcurrencyid;
            $selectES['tocurrencyid'] = $this->ci->Eventsignup_model->tocurrencyid;
            $selectES['promotercode'] = $this->ci->Eventsignup_model->promotercode;
            $selectES['paymentgatewayid'] = $this->ci->Eventsignup_model->paymentgatewayid;
            $selectES['paymentmodeid'] = $this->ci->Eventsignup_model->paymentmodeid;
            $selectES['eventextrachargeamount'] = $this->ci->Eventsignup_model->eventextrachargeamount;
            //referraldiscountamount
            $selectES['referraldiscountamount'] = $this->ci->Eventsignup_model->referraldiscountamount;
            $selectES['conversionrate'] = $this->ci->Eventsignup_model->conversionrate;
            $selectES['convertedamount'] = $this->ci->Eventsignup_model->convertedamount;
            $this->ci->Eventsignup_model->setSelect($selectES);
            $this->ci->Eventsignup_model->setWhereNotIn($where_not_in_ES);
            $this->ci->Eventsignup_model->setWhere($whereES);
            $start = $loop * REPORTS_DISPLAY_LIMIT;
            $this->ci->Eventsignup_model->setRecords(REPORTS_DISPLAY_LIMIT, $start);
            $selectESResponse = $this->ci->Eventsignup_model->get($protect);
            //echo $this->ci->db->last_query();exit;
            if (count($selectESResponse) > 0) {
                // print_r($selectESResponse);exit;
                foreach ($selectESResponse as $key => $value) {
                    $qty = 0;
                    $currecncyCode = '';
                    $total = 0;
                    if (($value['conversionrate'] > 1) && ($value['convertedamount'] > 0)) {
                        $totalAmount = $value['totalamount'];
                        $extraCovertedAmount = 0;
                        $purchaseTotal = $value['convertedamount'] * $value['quantity'];
                        if ($value['eventextrachargeamount'] > 0) {
                            $convertedAmount = $value['convertedamount'] * $value['quantity'];
                            $echargeAmount = $value['eventextrachargeamount'];
                            $extraCovertedAmount = ($echargeAmount * $convertedAmount) / $totalAmount;
                        }
                        $qty = $value['quantity'];
                        $currecncyCode = $indexedCurrencyList[$value['tocurrencyid']]['currencyCode'];
                        $total = round(($purchaseTotal - $extraCovertedAmount) * $value['conversionrate'], 2);
                    } elseif ($value['convertedamount'] > 0) {
                        $totalAmount = $value['totalamount'];
                        $extraCovertedAmount = 0;
                        $purchaseTotal = $value['convertedamount'] * $value['quantity'];
                        if ($value['eventextrachargeamount'] > 0) {
                            $convertedAmount = $value['convertedamount'] * $value['quantity'];
                            $echargeAmount = $value['eventextrachargeamount'];
                            $extraCovertedAmount = ($echargeAmount * $convertedAmount) / $totalAmount;
                        }
                        $qty = $value['quantity'];
                        $currecncyCode = 'USD';
                        $total = round($purchaseTotal - $extraCovertedAmount, 2);
                    } elseif ($value['conversionrate'] > 1) {
                        $qty = $value['quantity'];
                        $currecncyCode = $indexedCurrencyList[$value['tocurrencyid']]['currencyCode'];
                        $purchaseTotal = $value['totalamount'];
                        $extraChargeAmt = $value['eventextrachargeamount'];
                        $total = round(($purchaseTotal - $extraChargeAmt) * $value['conversionrate'], 2);
                    } else {
                        $qty = $value['quantity'];
                        $currecncyCode = $indexedCurrencyList[$value['fromcurrencyid']]['currencyCode'];
                        $total = round($value['totalamount'] - $value['eventextrachargeamount'], 2);
                    }
                    if ($value['paymentmodeid'] == 4) {
                        $offlinePromoterTotal['totalquantity']+=$qty;
                        if (!empty($currecncyCode)) {
                            if (!isset($offlinePromoterTotal['totalamount'][$currecncyCode])) {
                                $offlinePromoterTotal['totalamount'][$currecncyCode] = 0;
                            }
                            $offlinePromoterTotal['totalamount'][$currecncyCode]+=$total;
                        }
                    } elseif (!empty($value['promotercode']) && strcmp($value['promotercode'], 'organizer') == 0) {

                        $organizerTotal['totalquantity']+=$qty;
                        if (!empty($currecncyCode)) {
                            if (!isset($organizerTotal['totalamount'][$currecncyCode])) {
                                $organizerTotal['totalamount'][$currecncyCode] = 0;
                            }
                            $organizerTotal['totalamount'][$currecncyCode]+=$total;
                        }
                    } elseif (!empty($value['promotercode']) && $value['promotercode'] != '0') {
                        $promoterTotal['totalquantity']+=$qty;
                        if (!empty($currecncyCode)) {
                            if (!isset($promoterTotal['totalamount'][$currecncyCode])) {
                                $promoterTotal['totalamount'][$currecncyCode] = 0;
                            }
                            $promoterTotal['totalamount'][$currecncyCode]+=$total;
                        }
                    } elseif ($value['referraldiscountamount'] > 0) {
                        $viralTotal['totalquantity']+=$qty;
                        if (!empty($currecncyCode)) {
                            if (!isset($viralTotal['totalamount'][$currecncyCode])) {
                                $viralTotal['totalamount'][$currecncyCode] = 0;
                            }
                            $viralTotal['totalamount'][$currecncyCode]+=$total;
                        }
                    } elseif ($value['paymentgatewayid'] == '7' || $value['paymentgatewayid'] == '8') {
                        $boxofficeTotal['totalquantity']+=$qty;
                        if (!empty($currecncyCode)) {
                            if (!isset($boxofficeTotal['totalamount'][$currecncyCode])) {
                                $boxofficeTotal['totalamount'][$currecncyCode] = 0;
                            }
                            $boxofficeTotal['totalamount'][$currecncyCode]+=$total;
                        }
                    } else {
                        $meraeventsTotal['totalquantity']+=$qty;
                        if (!empty($currecncyCode)) {
                            if (!isset($meraeventsTotal['totalamount'][$currecncyCode])) {
                                $meraeventsTotal['totalamount'][$currecncyCode] = 0;
                            }
                            $meraeventsTotal['totalamount'][$currecncyCode]+=$total;
                        }
                    }
                }
            }
        }
        if (!isset($organizerTotal['totalamount'])) {
            $organizerTotal['totalamount'] = 0;
        }
        if (!isset($promoterTotal['totalamount'])) {
            $promoterTotal['totalamount'] = 0;
        }
        if (!isset($offlinePromoterTotal['totalamount'])) {
            $offlinePromoterTotal['totalamount'] = 0;
        }
        if (!isset($boxofficeTotal['totalamount'])) {
            $boxofficeTotal['totalamount'] = 0;
        }
        if (!isset($viralTotal['totalamount'])) {
            $viralTotal['totalamount'] = 0;
        }
        if (!isset($meraeventsTotal['totalamount'])) {
            $meraeventsTotal['totalamount'] = 0;
        }
        $response['organizer'] = $organizerTotal;
        $response['promoter'] = $promoterTotal;
        $response['offlinepromoter'] = $offlinePromoterTotal;
        $response['boxoffice'] = $boxofficeTotal;
        $response['viral'] = $viralTotal;
        $response['meraevents'] = $meraeventsTotal;
        $output['status'] = TRUE;
        $output['statusCode'] = STATUS_OK;
        $output['response']['salesEffortResponse'] = $response;
        $output['response']['total'] = count($response);
        return $output;
    }

    public function getUserEventSignupDetail($inputArray) {

        $userId = $this->ci->customsession->getData('userId');
        $this->ci->Eventsignup_model->resetVariable();
        $selectInput = $orderBy = $whereCondition = $whereNotInCond = $upcomingEventList = array();
        $selectInput['eventId'] = $this->ci->Eventsignup_model->eventid;
        $selectInput['eventSignupId'] = $this->ci->Eventsignup_model->id;
        $selectInput['totalAmount'] = $this->ci->Eventsignup_model->totalamount;
        $selectInput['quantity'] = $this->ci->Eventsignup_model->quantity;
        $selectInput['currencyid'] = $this->ci->Eventsignup_model->fromcurrencyid;
        $selectInput['barcodenumber'] = $this->ci->Eventsignup_model->barcodenumber;
        $selectInput['eventMonth'] = " MonthName( " . $this->ci->Eventsignup_model->signupdate . ") ";
        $selectInput['eventMonthNum'] = " Month( " . $this->ci->Eventsignup_model->signupdate . ") ";
        $selectInput['signupdate'] = $this->ci->Eventsignup_model->signupdate;

        if (isset($inputArray['eventSignupId']) && $inputArray['eventSignupId'] > 0) {
            $whereCondition[$this->ci->Eventsignup_model->id] = $inputArray['eventSignupId'];
        }

        $whereCondition[$this->ci->Eventsignup_model->deleted] = 0;
        $whereCondition[$this->ci->Eventsignup_model->userid] = $userId;
        $whereCondition[$this->ci->Eventsignup_model->transactionstatus] = 'success';
        $whereNotInCond[$this->ci->Eventsignup_model->paymentstatus] = array('Canceled', 'Refunded');
        $orderBy[] = $this->ci->Eventsignup_model->signupdate . " DESC ";
        $orderBy[] = "eventMonthNum DESC ";

        $this->ci->Eventsignup_model->setSelect($selectInput);
        $this->ci->Eventsignup_model->setWhere($whereCondition);
        $this->ci->Eventsignup_model->setWhereNotIn($whereNotInCond);
        $this->ci->Eventsignup_model->setOrderBy($orderBy);

        $eventSignupList = $this->ci->Eventsignup_model->get();
        if ($eventSignupList != FALSE && count($eventSignupList) > 0) {
            // Currency Code for the EventsignUp
            foreach ($eventSignupList as $key => $value) {
                $currencyId['currencyId'] = $value['currencyid'];
                $currenyCodeDetail = $this->currencyHanlder->getCurrencyDetailById($currencyId);
                if ($currenyCodeDetail['status'] && $currenyCodeDetail['response']['total'] > 0) {
                    $eventSignupList[$key]['currencyCode'] = $currenyCodeDetail['response']['currencyList']['detail']['currencyCode'];
                } else {
                    $eventSignupList[$key]['currencyCode'] = '';
                }
            }
            $output['status'] = TRUE;
            $output['response']['eventSignupList'] = $eventSignupList;
            $output['messages'] = array();
            $output['response']['total'] = count($eventSignupList);
            $output['statusCode'] = STATUS_OK;
        } else {//No records are fetched    $eventSignupList != FALSE && 
            $output['status'] = TRUE;
            $output['messages'][] = ERROR_NO_DATA;
            $output['response']['total'] = 0;
            $output['statusCode'] = STATUS_OK;
        }
        return $output;
    }

    /*
     * Function to add the event signup data
     *
     * @access	public
     * @param	$inputArray contains required fields to save the event signup details
     * @return	eventSignupId - If success
     *           error - failure
     */

    public function add($inputArray) {

        $this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('eventid', 'event id', 'trim|xss_clean|required_strict');

        if ($this->ci->form_validation->run() === FALSE) {
            $response = $this->ci->form_validation->get_errors('message');
            $output['status'] = FALSE;
            $output['response']['messages'] = $response['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }

        $paymentGateway = '';
        if (isset($inputArray['paymentGateway']) && $inputArray['paymentGateway'] != '') {
            $paymentGateway = $inputArray['paymentGateway'];
        }
        $paymentGatewayId = 0;
        if ($paymentGateway != '') {
            $paymentInput['paymentGateway'] = strtolower($paymentGateway);
            $paymentGatewayHandler = new Paymentgateway_handler();
            $paymentGatewayData = $paymentGatewayHandler->getPaymentGatewayDetail($paymentInput);
            $paymentGatewayId = $paymentGatewayData['response']['paymentGateways'][0][$this->ci->Paymentgateway_model->id];
        } else {
            $paymentGatewayId = $inputArray['paymentGatewayId'];
        }
        $this->ci->Eventsignup_model->resetVariable();
        $createEventSignup[$this->ci->Eventsignup_model->userid] = $inputArray['userid'];
        $createEventSignup[$this->ci->Eventsignup_model->signupdate] = allTimeFormats('', 11);
        $createEventSignup[$this->ci->Eventsignup_model->eventid] = $inputArray['eventid'];
        $createEventSignup[$this->ci->Eventsignup_model->quantity] = $inputArray['quantity'];
        $createEventSignup[$this->ci->Eventsignup_model->fromcurrencyid] = $inputArray['fromcurrencyid'];
        $createEventSignup[$this->ci->Eventsignup_model->attendeeid] = $inputArray['attendeeid'];
        $createEventSignup[$this->ci->Eventsignup_model->transactionstatus] = ($inputArray['transactionstatus'] != '') ? $inputArray['transactionstatus'] : 'pending';
        $createEventSignup[$this->ci->Eventsignup_model->paymentgatewayid] = $paymentGatewayId;
        $createEventSignup[$this->ci->Eventsignup_model->paymentstatus] = ($inputArray['paymentstatus'] != '') ? $inputArray['paymentstatus'] : 'NotVerified';
        $createEventSignup[$this->ci->Eventsignup_model->totalamount] = $inputArray['totalamount'];
        $createEventSignup[$this->ci->Eventsignup_model->transactiontickettype] = $inputArray['transactiontickettype'];
        $createEventSignup[$this->ci->Eventsignup_model->transactionticketids] = $inputArray['transactionticketids'];
        $createEventSignup[$this->ci->Eventsignup_model->paymentmodeid] = $inputArray['paymentmodeid'] ? $inputArray['paymentmodeid'] : 1;

        $createEventSignup[$this->ci->Eventsignup_model->discountamount] = $inputArray['discountamount'] ? $inputArray['discountamount'] : 0;
        $createEventSignup[$this->ci->Eventsignup_model->referraldiscountamount] = $inputArray['referraldiscountamount'] ? $inputArray['referraldiscountamount'] : 0;
        $createEventSignup[$this->ci->Eventsignup_model->tocurrencyid] = $inputArray['tocurrencyid'] ? $inputArray['tocurrencyid'] : $inputArray['fromcurrencyid'];
        $createEventSignup[$this->ci->Eventsignup_model->discount] = $inputArray['discount'] ? $inputArray['discount'] : 'X';
        $createEventSignup[$this->ci->Eventsignup_model->discountcodeid] = (isset($inputArray['discountcodeid']) && $inputArray['discountcodeid']) ? $inputArray['discountcodeid'] : 0;
        if (isset($inputArray['paymenttransactionid']) && $inputArray['paymenttransactionid'] != '') {
            $createEventSignup[$this->ci->Eventsignup_model->paymenttransactionid] = $inputArray['paymenttransactionid'];
        }
        $createEventSignup[$this->ci->Eventsignup_model->transactionresponse] = (isset($inputArray['transactionresponse']) && $inputArray['transactionresponse']) ? $inputArray['transactionresponse'] : 0;
        $createEventSignup[$this->ci->Eventsignup_model->settlementdate] = $inputArray['settlementdate'] ? $inputArray['settlementdate'] : 0;
        $createEventSignup[$this->ci->Eventsignup_model->ticketwidgettransaction] = (isset($inputArray['ticketwidgettransaction']) && $inputArray['ticketwidgettransaction']) ? $inputArray['ticketwidgettransaction'] : 0;
        $createEventSignup[$this->ci->Eventsignup_model->depositdate] = $inputArray['depositdate'] ? $inputArray['depositdate'] : 0;
        /*  $createEventSignup[$this->ci->Eventsignup_model->referralcode] = $inputArray['referralcode'] ? $inputArray['referralcode'] : 0;
          $createEventSignup[$this->ci->Eventsignup_model->promotercode] = $inputArray['promotercode'] ? $inputArray['promotercode'] : 0; */

        if (isset($inputArray['referralcode']) && $inputArray['referralcode'] != '') {
            $createEventSignup[$this->ci->Eventsignup_model->referralcode] = $inputArray['referralcode'];
        }
        if (isset($inputArray['bookingtype']) && $inputArray['bookingtype'] != '') {
            $createEventSignup[$this->ci->Eventsignup_model->bookingtype] = $inputArray['bookingtype'];
        }
        if (isset($inputArray['promotercode']) && $inputArray['promotercode'] != '') {
            $createEventSignup[$this->ci->Eventsignup_model->promotercode] = $inputArray['promotercode'];
        }
        $createEventSignup[$this->ci->Eventsignup_model->barcodenumber] = $inputArray['barcodenumber'] ? $inputArray['barcodenumber'] : 0;
        $createEventSignup[$this->ci->Eventsignup_model->eventextrachargeamount] = $inputArray['eventextrachargeamount'] ? $inputArray['eventextrachargeamount'] : 0;
        $createEventSignup[$this->ci->Eventsignup_model->eventextrachargeid] = $inputArray['eventextrachargeid'] ? $inputArray['eventextrachargeid'] : 0;
        if (isset($inputArray['paymentsourceid'])) {
            $createEventSignup[$this->ci->Eventsignup_model->paymentsourceid] = $inputArray['paymentsourceid'];
        }
        if ($inputArray['extrafield'] && $inputArray['extrainfo'] != '') {
            $createEventSignup[$this->ci->Eventsignup_model->extrafield] = $inputArray['extrafield'] ? $inputArray['extrafield'] : 0;
            $createEventSignup[$this->ci->Eventsignup_model->extrainfo] = $inputArray['extrainfo'] ? $inputArray['extrainfo'] : 0;
        }
        $createEventSignup[$this->ci->Eventsignup_model->convertedamount] = $inputArray['convertedamount'] ? $inputArray['convertedamount'] : 0;
        if (isset($inputArray['createdby'])) {
            $createEventSignup[$this->ci->Eventsignup_model->createdby] = $inputArray['createdby'];
        }
        if (isset($inputArray['modifiedby'])) {
            $createEventSignup[$this->ci->Eventsignup_model->modifiedby] = $inputArray['modifiedby'];
        }
        $createEventSignup[$this->ci->Eventsignup_model->useragent] = $_SERVER['HTTP_USER_AGENT'];

        $this->ci->Eventsignup_model->setInsertUpdateData($createEventSignup);
        $eventSignUpId = $this->ci->Eventsignup_model->insert_data(); //Inserting into table and getting inserted id
        if ($eventSignUpId) {
            //Inserting record in the ticketdiscount table
            $output['status'] = TRUE;
            $output['response']['messages'][] = SUCCESS_EVENTSIGNUP_ADDED;
            $output['response']['eventSignUpId'] = $eventSignUpId;
            $output['statusCode'] = STATUS_OK;
            return $output;
        }
        $output['status'] = FALSE;
        $output['response']['messages'][] = ERROR_EVENTSIGNUP_ADDED;
        $output['statusCode'] = STATUS_BAD_REQUEST;
        return $output;
    }

    // Generate random string for Refferal code if viral Ticket
    public function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }

    public function updateEventsignuptransactionsuccess($inputArray) {
        $this->emailHandler = new Email_handler();
        $this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('eventId', 'event id', 'required_strict|is_natural_no_zero');
        $this->ci->form_validation->set_rules('eventsignupid', 'eventsignupid', 'required_strict|is_natural_no_zero');
        $this->ci->form_validation->set_rules('transactionresponse', 'transactionresponse', 'required_strict');
        $this->ci->form_validation->set_rules('transactionstatus', 'transactionstatus', 'required_strict');
        if ($this->ci->form_validation->run() == FALSE) {
            $response = $this->ci->form_validation->get_errors('message');
            $output['status'] = FALSE;
            $output['response']['messages'] = $response['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }

        /* check whether the user already bookmarked it or not */
        $this->ci->Eventsignup_model->resetVariable();
        $selectInput[$this->ci->Eventsignup_model->id] = $this->ci->Eventsignup_model->id;
        $selectInput[$this->ci->Eventsignup_model->referralcode] = $this->ci->Eventsignup_model->referralcode;
        $this->ci->Eventsignup_model->setSelect($selectInput);
        $where[$this->ci->Eventsignup_model->id] = $inputArray['eventsignupid'];
        $where[$this->ci->Eventsignup_model->eventid] = $inputArray['eventId'];
        $this->ci->Eventsignup_model->setWhere($where);
        $eventsignupresponse = $this->ci->Eventsignup_model->get();
        if (is_array($eventsignupresponse) && count($eventsignupresponse) > 0) {
            if (isset($inputArray['referalcode'])) {
                $updatesignupData[$this->ci->Eventsignup_model->referralcode] = $inputArray['referalcode'];
            }
            if (isset($inputArray['promotercode'])) {
                $updatesignupData[$this->ci->Eventsignup_model->promotercode] = $inputArray['promotercode'];
            }
            $updatesignupData[$this->ci->Eventsignup_model->transactionresponse] = $inputArray['transactionresponse'];
            $updatesignupData[$this->ci->Eventsignup_model->transactionstatus] = $inputArray['transactionstatus'];
            $updatesignupData[$this->ci->Eventsignup_model->paymentstatus] = $inputArray['paymentstatus'];

            if (isset($inputArray['paymenttransactionid']) && $inputArray['paymenttransactionid'] != '') {
                $updatesignupData[$this->ci->Eventsignup_model->paymenttransactionid] = $inputArray['paymenttransactionid'];
            }
			if (isset($inputArray['paymentsource']) && $inputArray['paymentsource'] != '') {
                $updatesignupData[$this->ci->Eventsignup_model->paymentsourceid] = $inputArray['paymentsource'];
            }
            if (isset($inputArray['paymentgatewayid']) && $inputArray['paymentgatewayid'] != '') {
                $updatesignupData[$this->ci->Eventsignup_model->paymentgatewayid] = $inputArray['paymentgatewayid'];
            }
            if(isset($inputArray['barcodenumber']) && $inputArray['barcodenumber'] > 0) {
            $updatesignupData[$this->ci->Eventsignup_model->barcodenumber] = $inputArray['barcodenumber'];
            }
            if ($inputArray['settlementdate'] != '') {
                $updatesignupData[$this->ci->Eventsignup_model->settlementdate] = $inputArray['settlementdate'];
            }

            $this->ci->Eventsignup_model->setInsertUpdateData($updatesignupData);
            $updateStatus = $this->ci->Eventsignup_model->update_data();
            if ($updateStatus) {
                $output['status'] = TRUE;
                $output["response"]["message"][] = SUCCESS_EVENTSIGNUP_UPDATE;
                $output["response"]["total"] = 1;
                $output['statusCode'] = STATUS_UPDATED;
                return $output;
            }
            $output['status'] = FALSE;
            $output["response"]["message"][] = ERROR_EVENTSIGNUP_UPDATE;
            $output["response"]["total"] = 0;
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        } else {
            $output['status'] = FALSE;
            $output["response"]["message"][] = ERROR_NOT_EVENTSIGNUPID;
            $output["response"]["total"] = 0;
            $output['statusCode'] = STATUS_INVALID_INPUTS;
            return $output;
        }
    }

    // getting multiple event signups data
    public function getMultipleEventSignupData($inputArray) {

        $eventIds = $inputArray['eventId'];
        $this->ci->Eventsignup_model->resetVariable();
        $selectInput['eventsignupid'] = $this->ci->Eventsignup_model->id;
        $selectInput['eventid'] = $this->ci->Eventsignup_model->eventid;
        $selectInput['quantity'] = 'sum( ' . $this->ci->Eventsignup_model->quantity . ' )';
        $selectInput['totalamount'] = 'sum( ' . $this->ci->Eventsignup_model->totalamount . ' )';
        $this->ci->Eventsignup_model->setSelect($selectInput);
        $whereInArray[$this->ci->Eventsignup_model->eventid] = $eventIds;
        $where[$this->ci->Eventsignup_model->deleted] = 0;
        $where[$this->ci->Eventsignup_model->userid] = $inputArray['userId'];
        $where[$this->ci->Eventsignup_model->transactionstatus] = 'success';
        if (isset($inputArray['promotroCode'])) {
            $whereInArray[$this->ci->Eventsignup_model->promotercode] = $inputArray['promotroCode'];
        }
        // SET paymentmodeid as 4 for offline
        $where[$this->ci->Eventsignup_model->paymentmodeid] = 4;
        $where_not_in[$this->ci->Eventsignup_model->paymentstatus] = array('Canceled', 'Refunded');
        $this->ci->Eventsignup_model->setWhereNotIn($where_not_in);
        $this->ci->Eventsignup_model->setWhere($where);
        $this->ci->Eventsignup_model->setWhereIns($whereInArray);
        $this->ci->Eventsignup_model->setGroupBy($this->ci->Eventsignup_model->eventid);
        $eventSignupIds = $this->ci->Eventsignup_model->get();
        if (count($eventSignupIds) > 0) {
            $output['status'] = TRUE;
            $output['response']['eventsignupData'] = $eventSignupIds;
            $output['response']['total'] = count($eventSignupIds);
            $output['statusCode'] = STATUS_OK;
            return $output;
        } else {
            $output['status'] = TRUE;
            $output['response']['total'] = 0;
            $output['response']['messages'][] = ERROR_NO_RECORDS;
            $output['statusCode'] = STATUS_OK;
            return $output;
        }
    }

    function getSuccessTxnDatewiseData($input) {
        $inputArray = array();
        $inputArray['eventId'] = $input['eventId'];
        $dataArray = $this->ci->Eventsignup_model->getDateWiseData($inputArray);
        if ($dataArray != FALSE) {
            $output['status'] = TRUE;
            $output['response']['txnData'] = $dataArray;
            $output['statusCode'] = STATUS_OK;
            return $output;
        }
    }

    public function updateEventSignUp($updateEventSignUp) {
        $this->ci->Eventsignup_model->resetVariable();
        if (isset($updateEventSignUp['attendeeid']) && $updateEventSignUp['attendeeid'] != '') {
            $updateSignUpData['attendeeid'] = $updateEventSignUp['attendeeid'];
        }

        if (isset($updateEventSignUp['barcodenumber']) && $updateEventSignUp['barcodenumber'] > 0) {
            $updateSignUpData['barcodenumber'] = $updateEventSignUp['barcodenumber'];
        }

        if (isset($updateEventSignUp['paymentGatewayId']) && $updateEventSignUp['paymentGatewayId'] != '') {
            $updateSignUpData['paymentgatewayid'] = $updateEventSignUp['paymentGatewayId'];
        }

        if (isset($updateEventSignUp['toCurrencyId']) && $updateEventSignUp['toCurrencyId'] != '') {
            $updateSignUpData['tocurrencyid'] = $updateEventSignUp['toCurrencyId'];
        }

        if (isset($updateEventSignUp['transactionStatus']) && $updateEventSignUp['transactionStatus'] != '') {
            $updateSignUpData['transactionstatus'] = $updateEventSignUp['transactionStatus'];
        }

        if (isset($updateEventSignUp['transactionId']) && $updateEventSignUp['transactionId'] != '') {
            $updateSignUpData['paymenttransactionid'] = $updateEventSignUp['transactionId'];
        }

        $where['id'] = $updateEventSignUp['eventSignUpId'];
        $this->ci->Eventsignup_model->setInsertUpdateData($updateSignUpData);
        $this->ci->Eventsignup_model->setWhere($where);
        $response = $this->ci->Eventsignup_model->update_data();
        if ($response) {
            $output['status'] = TRUE;
            $output["response"]["messages"][] = SUCCESS_EVENTSIGNUP_UPDATE;
            $output['statusCode'] = STATUS_UPDATED;
            return $output;
        } else {
            $output['status'] = FALSE;
            $output["response"]["messages"][] = ERROR_EVENTSIGNUP_UPDATE;
            $output['statusCode'] = STATUS_SERVER_ERROR;
            return $output;
        }
    }

    public function isValidCode($inputArray) {
        $this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('type', 'type', 'required_strict');
        $this->ci->form_validation->set_rules('code', 'code', 'required_strict');
        if ($this->ci->form_validation->run() == FALSE) {
            $validationStatus = $this->ci->form_validation->get_errors();
            $output['status'] = FALSE;
            $output['response']['messages'] = $validationStatus['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $type = $inputArray['type'];
        $code = $inputArray['code'];
        $this->ci->Eventsignup_model->resetVariable();
        $select['id'] = $this->ci->Eventsignup_model->id;
        if ($type == 'referral') {
            $where[$this->ci->Eventsignup_model->referralcode] = $code;
        } else {
            $output['status'] = FALSE;
            $output['response']['messages'][] = ERROR_INVALID_INPUT;
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $where[$this->ci->Eventsignup_model->transactionstatus] = 'success';
        $this->ci->Eventsignup_model->setSelect($select);
        $this->ci->Eventsignup_model->setWhere($where);
        $this->ci->Eventsignup_model->setRecords(1);
        $referralCodeResponse = $this->ci->Eventsignup_model->get();
        if (count($referralCodeResponse) > 0) {
            $output['status'] = TRUE;
            $output['response']['total'] = 1;
            $output['response']['codeData'] = array('valid' => true);
            return $output;
        }
        $output['status'] = TRUE;
        $output['response']['total'] = 0;
        $output['response']['codeData'] = array('valid' => false);
        if ($type == 'referral') {
            $output['response']['messages'][] = ERROR_INVALID_REFERRAL_CODE;
        }
        $output['statusCode'] = STATUS_INVALID;
        return $output;
    }

    public function checkReffCodeAvailable($inputArray) {
        $this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('eventid', 'eventid', 'required_strict|is_natural_no_zero');
        if ($this->ci->form_validation->run() == FALSE) {
            $validationStatus = $this->ci->form_validation->get_errors();
            $output['status'] = FALSE;
            $output['response']['messages'] = $validationStatus['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $eventId = $inputArray['eventid'];
//        $select['count'] = 'COUNT(' . $this->ci->Eventsignup_model->id . ')';
        $this->ci->Eventsignup_model->resetVariable();
        $where[$this->ci->Eventsignup_model->transactionstatus] = 'success';
        $where[$this->ci->Eventsignup_model->referralcode . ' != '] = '';
        $where[$this->ci->Eventsignup_model->eventid] = $eventId;
        //$this->ci->Eventsignup_model->setSelect($select);
        $this->ci->Eventsignup_model->setWhere($where);
        //$this->ci->Eventsignup_model->setRecords(1);
        $referralCodeCount = $this->ci->Eventsignup_model->getCount();
//        if ($referralCodeCount > 0) {
//            $output['status'] = TRUE;
//            $output['response']['total'] = 1;
//            $output['response']['codeData'] = array('valid' => true);
//            return $output;
//        }
//        $output['status'] = TRUE;
//        $output['response']['total'] = 0;
//        //$output['response']['codeData'] = array('valid' => false);
//        $output['statusCode'] = STATUS_INVALID;
        return $referralCodeCount;
    }

    public function getSuccessfullTransactionsByEventId($eventId, $signupid = '', $select = '', $ticketId = '') {
        $transactions = array();
        $this->ci->Eventsignup_model->resetVariable();
        if ($select == '') {
            $select = '*';
            $this->ci->Eventsignup_model->setSelect($select);
        } else {
            $selectValues = array();
            $selectValues['count'] = 'count(id)';
            $this->ci->Eventsignup_model->setSelect($selectValues);
        }

        if ($signupid == '') {
            $whereES[$this->ci->Eventsignup_model->eventid] = $eventId;
        } else {
            $whereES[$this->ci->Eventsignup_model->id] = $signupid;
        }
        if ($ticketId != '') {
            $findInSet = array();
            $findInSet[$ticketId] = $this->ci->Eventsignup_model->transactionticketids;
            $this->ci->Eventsignup_model->setFindInSet($findInSet);
        }
        $whereES[$this->ci->Eventsignup_model->deleted] = 0;
        $whereES[$this->ci->Eventsignup_model->transactionstatus] = 'success';
        // $where_not_in_ES[$this->ci->Eventsignup_model->paymenttransactionid] = array('', 0, 'A1');
        $where_not_in_ES[$this->ci->Eventsignup_model->paymentstatus] = array('Canceled', 'Refunded');
        $this->ci->Eventsignup_model->setWhereNotIn($where_not_in_ES);
        $this->ci->Eventsignup_model->setWhere($whereES);
        $transactions = $this->ci->Eventsignup_model->get();
        if ($transactions) {
            $output['status'] = TRUE;
            $output['response']['eventsignupData'] = $transactions;
            $output['response']['total'] = count($transactions);
            $output['statusCode'] = STATUS_OK;
            return $output;
        } else {
            $output['status'] = FALSE;
            $output['response']['messages'][] = ERROR_INTERNAL_DB_ERROR;
            $output['statusCode'] = STATUS_SERVER_ERROR;
            return $output;
        }
    }

    public function updateEventSignUpForMicrositEvents($updateEventSignUp) {
        $this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($updateEventSignUp);
        $this->ci->form_validation->set_rules('id', 'id', 'required_strict');
        $this->ci->form_validation->set_rules('totalamount', 'totalamount', 'required_strict');
        $this->ci->form_validation->set_rules('transactionresponse', 'transactionresponse', 'required_strict');
        $this->ci->form_validation->set_rules('paymenttransactionid', 'paymenttransactionid', 'required_strict');
        if ($this->ci->form_validation->run() == FALSE) {
            $validationStatus = $this->ci->form_validation->get_errors();
            $output['status'] = FALSE;
            $output['response']['messages'] = $validationStatus['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $this->ci->Eventsignup_model->resetVariable();
        $updateSignUpData['signupdate'] = date('Y-m-d H:i:s');
        $updateSignUpData['paymenttransactionid'] = $updateEventSignUp['paymenttransactionid'];
        $updateSignUpData['transactionresponse'] = $updateEventSignUp['transactionresponse'];
        $updateSignUpData['totalamount'] = $updateEventSignUp['totalamount'];

        $where['id'] = $updateEventSignUp['id'];
        $this->ci->Eventsignup_model->setInsertUpdateData($updateSignUpData);
        $this->ci->Eventsignup_model->setWhere($where);
        $response = $this->ci->Eventsignup_model->update_data();
        if ($response) {
            $output['status'] = TRUE;
            $output["response"]["messages"][] = SUCCESS_EVENTSIGNUP_UPDATE;
            $output['statusCode'] = STATUS_UPDATED;
            return $output;
        } else {
            $output['status'] = FALSE;
            $output["response"]["messages"][] = ERROR_EVENTSIGNUP_UPDATE;
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
    }

    /*
     * Function to add the payment gateway response data
     *
     * @access	public
     * @param	$inputArray contains
     *          - eventSignupId (integer)
     */

    public function saveGatewayPaymentResponse($inputArray) {

        $this->ci->load->model('Ebspaymentdetail_model');
        $this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('eventSignupId', 'event signup id', 'trim|xss_clean|required_strict');

        if ($this->ci->form_validation->run() === FALSE) {
            $response = $this->ci->form_validation->get_errors('message');
            $output['status'] = FALSE;
            $output['response']['messages'] = $response['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $this->ci->Ebspaymentdetail_model->resetVariable();
        $createPaymentDetail[$this->ci->Ebspaymentdetail_model->eventsignupid] = $inputArray['eventSignupId'];
        $createPaymentDetail[$this->ci->Ebspaymentdetail_model->paymentid] = $inputArray['paymentId'];
        $createPaymentDetail[$this->ci->Ebspaymentdetail_model->transactionid] = $inputArray['transactionId'];
        $createPaymentDetail[$this->ci->Ebspaymentdetail_model->statuscode] = $inputArray['statusCode'];
        $createPaymentDetail[$this->ci->Ebspaymentdetail_model->statusmessage] = $inputArray['statusMessage'];

        $this->ci->Ebspaymentdetail_model->setInsertUpdateData($createPaymentDetail);
        $responseCode = $this->ci->Ebspaymentdetail_model->insert_data(); //Inserting into table and getting inserted id
        if ($responseCode) {
            //Inserting record in the `ebspaymentdetail` table
            $output['status'] = TRUE;
            $output['response']['messages'][] = SUCCESS_ADDED_GATEWAYDATA;
            $output['statusCode'] = STATUS_OK;
            return $output;
        }
        $output['status'] = FALSE;
        $output['response']['messages'][] = SOMETHING_WRONG;
        $output['statusCode'] = STATUS_SERVER_ERROR;
        return $output;
    }

    public function getEventSignupData($inputArray) {
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('eventsignupId', 'Registration Number', 'is_natural_no_zero|required_strict');
        if ($this->ci->form_validation->run() == FALSE) {
            $errorMsg = $this->ci->form_validation->get_errors();
            $output = parent::createResponse(FALSE, $errorMsg['message'], STATUS_BAD_REQUEST);
            return $output;
        }
        $this->ci->Eventsignup_model->resetVariable();
        $selectInput['id'] = $this->ci->Eventsignup_model->id;
        $selectInput['userid'] = $this->ci->Eventsignup_model->userid;
        $selectInput['eventid'] = $this->ci->Eventsignup_model->eventid;
        $selectInput['quantity'] = $this->ci->Eventsignup_model->quantity;
        $selectInput['barcodenumber'] = $this->ci->Eventsignup_model->barcodenumber;
        $selectInput['fromcurrencyid'] = $this->ci->Eventsignup_model->fromcurrencyid;
        $selectInput['tocurrencyid'] = $this->ci->Eventsignup_model->tocurrencyid;
        $selectInput['discount'] = $this->ci->Eventsignup_model->discount;
        $selectInput['discountcodeid'] = $this->ci->Eventsignup_model->discountcodeid;
        $selectInput['paymentmodeid'] = $this->ci->Eventsignup_model->paymentmodeid;
        $selectInput['paymentgatewayid'] = $this->ci->Eventsignup_model->paymentgatewayid;
        $selectInput['paymenttransactionid'] = $this->ci->Eventsignup_model->paymenttransactionid;
        $selectInput['referralcode'] = $this->ci->Eventsignup_model->referralcode;
        $selectInput['promotercode'] = $this->ci->Eventsignup_model->promotercode;
        $selectInput['totalamount'] = $this->ci->Eventsignup_model->totalamount;
        $selectInput['eventextrachargeid'] = $this->ci->Eventsignup_model->eventextrachargeid;
        $selectInput['eventextrachargeamount'] = $this->ci->Eventsignup_model->eventextrachargeamount;
        $selectInput['transactiontickettype'] = $this->ci->Eventsignup_model->transactiontickettype;
        $selectInput['transactionticketids'] = $this->ci->Eventsignup_model->transactionticketids;
        $this->ci->Eventsignup_model->setSelect($selectInput);
        $where[$this->ci->Eventsignup_model->id] = $inputArray['eventsignupId'];
        $where[$this->ci->Eventsignup_model->deleted] = 0;
        $where[$this->ci->Eventsignup_model->transactionstatus] = 'success';
        if (isset($inputArray['transactionstatus'])) {
            $where[$this->ci->Eventsignup_model->transactionstatus] = $inputArray['transactionstatus'];
        }
        $this->ci->Eventsignup_model->setWhere($where);
        $where_not_in_ES[$this->ci->Eventsignup_model->paymentstatus] = array('Refunded', 'Canceled');
        $this->ci->Eventsignup_model->setWhereNotIn($where_not_in_ES);
        $this->ci->Eventsignup_model->setRecords(1);
        $eventSignupUserId = $this->ci->Eventsignup_model->get();
        if (count($eventSignupUserId) > 0) {
            $output['status'] = TRUE;
            $output['response']['eventSignupData'] = $eventSignupUserId;
            $output['response']['messages'] = '';
            $output['statusCode'] = STATUS_OK;
            return $output;
        } else if (count($eventSignupUserId) == 0) {
            $output['status'] = TRUE;
            $output['response']['messages'][] = ERROR_NO_DATA;
            $output['statusCode'] = STATUS_OK;
            $output['response']['total'] = 0;
            return $output;
        }
    }

    public function downloadAllImages($input) {

        // For Download All Files in Export
        $eventId = $input['eventid'];
        $ticketId = isset($input['ticketid']) ? $input['ticketid'] : 0;
        $reportType = $input['reporttype'];
        $report_types = $this->ci->config->item('report_types');
        if (!in_array($input['reporttype'], $report_types)) {
            $reportType = 'summary';
        }
        $this->ci->Eventsignup_model->resetVariable();
        $transactionType = $input['transactiontype'];
        //$page = $input['page'];
        //$start = ($page - 1) * REPORTS_DISPLAY_LIMIT;
        $setOrWhere = $where_in_ES = array();
        $groupBy = $findInSet = $notLike = $whereES = array();
        //$selectTicketResponse = $partialEventsignupIds = $ticketDataIdIndexed = $selectAttendeeResponse = $attendeeIds = $selectAttendeedetailResponse = $commentResponse = array();
        $whereES[$this->ci->Eventsignup_model->eventid] = $eventId;
        $whereES[$this->ci->Eventsignup_model->deleted] = 0;
        $whereES[$this->ci->Eventsignup_model->transactionstatus] = 'success';

        if ((isset($input['promotercode']) && $input['promotercode'] == 'promoter') || (!isset($input['promotercode']) && $transactionType == 'affiliate')) {
            $where_not_in_ES[$this->ci->Eventsignup_model->promotercode] = array('organizer', '', '0');
            //$whereES[$this->ci->Eventsignup_model->promotercode . ' != '] = '';
            //$whereES[$this->ci->Eventsignup_model->promotercode . ' != '] = '0';
        } elseif (isset($input['promotercode']) && $input['promotercode'] == 'organizer') {
            $whereES[$this->ci->Eventsignup_model->promotercode] = 'organizer';
        } elseif (isset($input['promotercode']) && $input['promotercode'] != 'meraevents') {
            $whereES[$this->ci->Eventsignup_model->promotercode] = $input['promotercode'];
            $where_not_in_ES[$this->ci->Eventsignup_model->promotercode] = array('organizer', '', '0');
        } elseif (isset($input['promotercode'])) {
            $where_in_ES[$this->ci->Eventsignup_model->promotercode] = array('', '0');
            $where_not_in_ES[$this->ci->Eventsignup_model->paymentgatewayid] = array('7', '8');
            $where_not_in_ES[$this->ci->Eventsignup_model->paymentmodeid] = array('4');
            $whereES[$this->ci->Eventsignup_model->referraldiscountamount] = '0';
        }
        if (isset($input['currencycode'])) {
            //currencies list
            $currencyResponse = $this->currencyHanlder->getCurrencyList();
            if ($currencyResponse['status'] && $currencyResponse['response']['total'] > 0) {
                //$indexedCurrencyListById = commonHelperGetIdArray($currencyResponse['response']['currencyList'], 'currencyId');
                $indexedCurrencyListByCode = commonHelperGetIdArray($currencyResponse['response']['currencyList'], 'currencyCode');
            } else {
                return $currencyResponse;
            }
            $whereES[$this->ci->Eventsignup_model->fromcurrencyid] = $indexedCurrencyListByCode[$input['currencycode']]['currencyId'];
        }
        $where_not_in_ES[$this->ci->Eventsignup_model->paymentstatus] = array('Canceled', 'Refunded');

        if ($ticketId > 0) {
            $findInSet[$ticketId] = $this->ci->Eventsignup_model->transactionticketids;
        }
        //  $whereES = array();
        switch ($transactionType) {
            case 'all':
                break;
            case 'card':
                $whereES[$this->ci->Eventsignup_model->paymentmodeid] = 1;
                $whereES[$this->ci->Eventsignup_model->totalamount . " > "] = 0;
                $where_not_in_ES[$this->ci->Eventsignup_model->paymentgatewayid] = array('', 'A1', 0, 7, 8);
                break;
            case 'cod':
                $whereES[$this->ci->Eventsignup_model->paymentmodeid] = 2;
                break;
            case 'free':
                $setOrWhere[$this->ci->Eventsignup_model->totalamount] = 0;
                $orfindInSet['free'] = $this->ci->Eventsignup_model->transactiontickettype;
                $where_not_in_ES[$this->ci->Eventsignup_model->paymentmodeid] = 4;
                break;
            case 'offline':
                $whereES[$this->ci->Eventsignup_model->paymentmodeid] = 4;
                // $where_not_in_ES[$this->ci->Eventsignup_model->totalamount] = '0';
                break;
            case 'boxoffice':
                $where_in_ES[$this->ci->Eventsignup_model->paymentgatewayid] = array(7, 8);
                break;
            case 'viral':
                $whereES[$this->ci->Eventsignup_model->referraldiscountamount . " > "] = 0;
                break;
            case 'affiliate':
                $newData = array('', '0');
                if (isset($where_not_in_ES[$this->ci->Eventsignup_model->promotercode])) {
                    $addedData = $where_not_in_ES[$this->ci->Eventsignup_model->promotercode];
                    $newData = array_merge($newData, $addedData);
                }
                //exclude me sales
                $where_not_in_ES[$this->ci->Eventsignup_model->promotercode] = array_unique($newData);
                $notLike[$this->ci->Eventsignup_model->promotercode] = 'OFFLINE_';
                break;
            default:
                break;
        }

        //to get total count
        $selectESCount['totalcount'] = 'COUNT( ' . $this->ci->Eventsignup_model->id . ' )';
        $this->ci->Eventsignup_model->setSelect($selectESCount);
        $this->ci->Eventsignup_model->setWhereNotIn($where_not_in_ES);
        $this->ci->Eventsignup_model->setWhere($whereES);
        $this->ci->Eventsignup_model->setOrWhere($setOrWhere);
        $this->ci->Eventsignup_model->setNotLike($notLike);
        $this->ci->Eventsignup_model->setWhereIns($where_in_ES);
        $this->ci->Eventsignup_model->setFindInSet($findInSet);
        $this->ci->Eventsignup_model->setGroupBy($groupBy);
        $this->ci->Eventsignup_model->setRecords(0, 0);
        $seletESCountResponse = $this->ci->Eventsignup_model->get();
        //to get eventsignupIds By setting records Limit
        $eventsignupTotal = $seletESCountResponse[0]['totalcount'];
        $loopLength = ceil($eventsignupTotal / REPORTS_TRANSACTION_LIMIT);
        //$handle = NULL;
        //$transresult = 0;
        // $eventEventsignupIds = '';
        for ($exportLoop = 0; $exportLoop < $loopLength; $exportLoop++) {
            $select['eventsignupids'] = 'GROUP_CONCAT(' . $this->ci->Eventsignup_model->id . ')';
            $this->ci->Eventsignup_model->setSelect($select);
            $this->ci->Eventsignup_model->setWhereNotIn($where_not_in_ES);
            $this->ci->Eventsignup_model->setWhere($whereES);
            $this->ci->Eventsignup_model->setOrWhere($setOrWhere);
            $this->ci->Eventsignup_model->setNotLike($notLike);
            $this->ci->Eventsignup_model->setWhereIns($where_in_ES);
            $this->ci->Eventsignup_model->setFindInSet($findInSet);
            $this->ci->Eventsignup_model->setGroupBy($groupBy);
            $selectESResponse = $this->ci->Eventsignup_model->get();
//            if ($exportLoop == 1 || $exportLoop == ($loopLength - 1)) {
//                $eventEventsignupIds = $eventEventsignupIds . ",";
//            }
            $eventEventsignupIds = $selectESResponse[0]['eventsignupids'];
            $eventEventsignupIdsArr = explode(",", $eventEventsignupIds);
            $inputDownloadAll['eventid'] = $eventId;
            $inputDownloadAll['eventsignupids'] = $eventEventsignupIdsArr;
            if ($ticketId > 0) {
                $inputDownloadAll['ticketid'] = $ticketId;
            }
            $getFileFieldDataResponse = $this->getFileTypeCustomFieldData($inputDownloadAll);
            if ($getFileFieldDataResponse['status'] && $getFileFieldDataResponse['response']['total']) {
                foreach ($getFileFieldDataResponse['response']['attendeedetailData'] as $esId => $ticketDataArr) {
                    foreach ($ticketDataArr as $customDataArr) {
                        foreach ($customDataArr as $key => $fileDataArr) {
                            foreach ($fileDataArr as $customId => $data) {
                                $attendeecustomFilepaths[] = $data['value'];
                            }
                        }
                    }
                }
            } else {
                return $getFileFieldDataResponse;
            }
        }
        $response['status'] = true;
        $response['response']['messages'] = array();
        $response['response']['total'] = count($attendeecustomFilepaths);
        $response['response']['filepath'] = $attendeecustomFilepaths;
        return $response;
    }

    // Checking the Eventsignup Id is Valid or Not
    public function checkEventsignup($inputArray) {
        $inputArray['userId'] = $this->ci->customsession->getData('userId');
        $AdminuserId = $this->ci->customsession->getData('uid');
        if (isset($inputArray['userEmail'])) {
            $this->ci->form_validation->set_rules('userEmail', 'User Email', 'valid_email|required_strict');
        }
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('eventsignupId', 'Registration Number', 'is_natural_no_zero|required_strict');
        if ($inputArray['userId'] != false) {
            $this->ci->form_validation->set_rules('userId', 'User Id', 'is_natural_no_zero|required_strict');
        }
        if (!empty($inputArray) && $this->ci->form_validation->run() == FALSE) {
            $errorMsg = $this->ci->form_validation->get_errors();
            $output = parent::createResponse(FALSE, $errorMsg['message'], STATUS_BAD_REQUEST);
            return $output;
        }
        $this->ci->Eventsignup_model->resetVariable();
        $selectInput['userid'] = $this->ci->Eventsignup_model->userid;
        $this->ci->Eventsignup_model->setSelect($selectInput);
        $where[$this->ci->Eventsignup_model->id] = $inputArray['eventsignupId'];
        $where[$this->ci->Eventsignup_model->deleted] = 0;
        if ($AdminuserId == false && ($AdminuserId != 1 || $AdminuserId != 2)) {
            if (isset($inputArray['userEmail'])) {
                $eventSignupDetails = $this->getEventSignupData($inputArray);
                if (count($eventSignupDetails) > 0) {
                    $userId = $eventSignupDetails['response']['eventSignupData'][0]['userid'];
                    $userInputArray['userIdList'] = array($userId);
                    $userHandler = new User_handler();
                    $userDetails = $userHandler->getUserDetails($userInputArray);
                    if ($userDetails['response']['total'] > 0) {
                        $email = $userDetails['response']['userData'][0]['email'];
                        $userId = $userDetails['response']['userData'][0]['id'];
                        if ($email === $inputArray['userEmail']) {
                            $inputArray['userId'] = $userId;
                        } else {
                            $output['status'] = FALSE;
                            $output['response']['messages'][] = ERROR_NO_DATA;
                            $output['response']['total'] = 0;
                            $output['statusCode'] = STATUS_NO_DATA;
                            return $output;
                        }
                    } else {
                        return $userDetails;
                    }
                }
            }
            $where[$this->ci->Eventsignup_model->userid] = $userId;
        }
        $this->ci->Eventsignup_model->setWhere($where);
        $this->ci->Eventsignup_model->setRecords(1);
        $eventSignupUserId = $this->ci->Eventsignup_model->get();
        if (count($eventSignupUserId) > 0) {
            $output['status'] = TRUE;
            $output['response']['messages'][] = '';
            $output['response']['userId'] = $userId;
            $output['response']['total'] = count($eventSignupUserId);
            $output['statusCode'] = STATUS_OK;
            return $output;
        }
        $output['status'] = FALSE;
        $output['response']['messages'][] = ERROR_NO_DATA;
        $output['response']['total'] = 0;
        $output['statusCode'] = STATUS_NO_DATA;
        return $output;
    }

    public function getUserEventSignupDetailData($inputArray) {
        $userId = $this->ci->customsession->getData('userId');
        $this->ci->Eventsignup_model->resetVariable();
        $selectInput = $orderBy = $whereCondition = $whereNotInCond = $upcomingEventList = array();
        $selectInput['eventId'] = $this->ci->Eventsignup_model->eventid;
        $selectInput['eventSignupId'] = $this->ci->Eventsignup_model->id;
        $selectInput['totalAmount'] = $this->ci->Eventsignup_model->totalamount;
        $selectInput['quantity'] = $this->ci->Eventsignup_model->quantity;
        $selectInput['currencyid'] = $this->ci->Eventsignup_model->fromcurrencyid;
        $selectInput['barcodenumber'] = $this->ci->Eventsignup_model->barcodenumber;
        $selectInput['eventMonth'] = " MonthName( " . $this->ci->Eventsignup_model->signupdate . ") ";
        $selectInput['eventMonthNum'] = " Month( " . $this->ci->Eventsignup_model->signupdate . ") ";
        $selectInput['signupdate'] = $this->ci->Eventsignup_model->signupdate;

        if (isset($inputArray['eventSignupId']) && $inputArray['eventSignupId'] > 0) {
            $whereCondition[$this->ci->Eventsignup_model->id] = $inputArray['eventSignupId'];
        }

        $whereCondition[$this->ci->Eventsignup_model->deleted] = 0;
        $whereCondition[$this->ci->Eventsignup_model->userid] = $userId;
        $whereCondition[$this->ci->Eventsignup_model->transactionstatus] = 'success';
        $whereNotInCond[$this->ci->Eventsignup_model->paymentstatus] = array('Canceled', 'Refunded');
        $orderBy[] = "eventMonthNum DESC ";
        $orderBy[] = $this->ci->Eventsignup_model->signupdate . " DESC ";

        $this->ci->Eventsignup_model->setSelect($selectInput);
        $this->ci->Eventsignup_model->setWhere($whereCondition);
        $this->ci->Eventsignup_model->setWhereNotIn($whereNotInCond);
        $this->ci->Eventsignup_model->setOrderBy($orderBy);

        $eventSignupList = $this->ci->Eventsignup_model->get();
        if ($eventSignupList != FALSE && count($eventSignupList) > 0) {
            $output['status'] = TRUE;
            $output['messages'] = array();
            $output['response']['eventSignupList'] = $eventSignupList;
            $output['response']['total'] = count($eventSignupList);
            $output['statusCode'] = STATUS_OK;
        } else {//No records are fetched    $eventSignupList != FALSE && 
            $output['status'] = TRUE;
            $output['messages'][] = ERROR_NO_DATA;
            $output['response']['total'] = 0;
            $output['statusCode'] = STATUS_OK;
        }
        return $output;
    }

    public function getSoldTicketCount($input) {
        $this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($input);
        $this->ci->form_validation->set_rules('eventIdList', 'eventIdList', 'is_array');
        if ($this->ci->form_validation->run() == FALSE) {
            $responseErrors = $this->ci->form_validation->get_errors();
            $output['status'] = FALSE;
            $output['response']['messages'] = $responseErrors['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $eventIdList = $input['eventIdList'];
        $selectES['eventid']=$this->ci->Eventsignup_model->eventid;
        $selectES['totalsoldtickets']='SUM('.$this->ci->Eventsignup_model->quantity.')';
        $where[$this->ci->Eventsignup_model->transactionstatus]='success';
        $whereIns[$this->ci->Eventsignup_model->eventid]=$eventIdList;
        $whereNotIn[$this->ci->Eventsignup_model->paymentstatus]=array('Canceled','Refunded');
        $groupBy[]=$this->ci->Eventsignup_model->eventid;
        $this->ci->Eventsignup_model->setSelect($selectES);
        $this->ci->Eventsignup_model->setWhereNotIn($whereNotIn);
        $this->ci->Eventsignup_model->setWhereIns($whereIns);
        $this->ci->Eventsignup_model->setWhere($where);
        $this->ci->Eventsignup_model->setGroupBy($groupBy);
        $eventSaleCount=$this->ci->Eventsignup_model->get();
        //echo $this->ci->db->last_query();exit;
        if ($eventSaleCount != FALSE && count($eventSaleCount) > 0) {
            $output['status'] = TRUE;
            $output['response']['ticketSaleCount'] = $eventSaleCount;
            $output['response']['total'] = count($eventSaleCount);
            $output['statusCode'] = STATUS_OK;
            return $output;
        }
        $output['status'] = TRUE;
        $output['response']['messages'][] = ERROR_NO_DATA;
        $output['response']['total'] = 0;
        $output['statusCode'] = STATUS_OK;
        return $output;
    }
}

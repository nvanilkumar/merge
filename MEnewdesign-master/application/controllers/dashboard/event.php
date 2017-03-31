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
 * @Created     24-06-2015
 * @Last Modified On  24-06-2015
 * @Last Modified By  Sridevi
 */
require_once(APPPATH . 'handlers/common_handler.php');
require_once(APPPATH . 'handlers/event_handler.php');
require_once(APPPATH . 'handlers/solr_handler.php');
require_once(APPPATH . 'handlers/ticket_handler.php');
require_once(APPPATH . 'handlers/file_handler.php');
require_once(APPPATH . 'handlers/category_handler.php');
require_once(APPPATH . 'handlers/currency_handler.php');
require_once(APPPATH . 'handlers/timezone_handler.php');
require_once(APPPATH . 'handlers/eventsignup_handler.php');
require_once(APPPATH . 'handlers/collaborator_handler.php');

class Event extends CI_Controller {

    var $eventHandler;
    var $ticketHandler;
    var $fileHandler;
    var $solrHandler;
    var $categoryHandler;
    var $currencyHandler;
    var $defaultCountryId;
    var $cookieHandler;
    var $commonHandler;
    var $timezoneHandler;
    var $tickettaxHandler;
    var $eventSignupHandler;

    public function __construct() {
        parent::__construct();
        $this->eventHandler = new Event_handler();
        $this->commonHandler = new Common_handler();
        $this->categoryHandler = new Category_handler();
        $this->currencyHandler = new Currency_handler();
        $this->timezoneHandler = new Timezone_handler();
        $this->tickettaxHandler = new Tickettax_handler();
    }

    /*
     * Function to get the form for creating event
     *
     * @access	public
     * @return	Html that contains create event form
     */

    public function create() {
        $data = $this->createEditDetails();
        $data1['pageTitle'] = 'Create Event';
        $data1['content'] = 'ticket';

        $currencyListResponse = $this->currencyHandler->getCurrencyList();
        $currencyList = array();
        if ($currencyListResponse['status'] && $currencyListResponse['response']['total'] > 0) {
            $currencyList = $currencyListResponse['response']['currencyList'];
        }
        $data1['currencyDetails'] = $currencyList;
//        print_r($data);
//        print_r($data1);
//        exit;
        $data['ticketView'] = $this->load->view('includes/elements/ticket', $data1, true);
        $data['eventTicketDetails'] = array();
        $this->load->view('templates/event_template', $data);
    }

    public function ticket_view() {
        $data['content'] = 'ticket';

        $currencyListResponse = $this->currencyHandler->getCurrencyList();
        $currencyList = array();
        if ($currencyListResponse['status'] && $currencyListResponse['response']['total'] > 0) {
            $currencyList = $currencyListResponse['response']['currencyList'];
        }
        $data['currencyDetails'] = $currencyList;
        $this->load->view('includes/elements/ticket.php', $data);
    }

    /**
     * To edit the event
     * @param type $id --edit event id
     */
    public function edit($id) {

        $data = $this->createEditDetails();
        $data['pageTitle'] = 'Edit Event';

        $data['eventId'] = $id;
        $data['editEvent'] = true;
        $eventDetails = $this->eventHandler->getEventDetails($data);
        $data['eventTimeZoneName'] = $eventDetails['response']['details']['location']['timeZoneName'];
        $inputArray['countryName'] = $eventDetails['response']['details']['location']['countryName'];
        $inputArray['stateName'] = $eventDetails['response']['details']['location']['stateName'];
        $inputArray['cityName'] = $eventDetails['response']['details']['location']['cityName'];
        $inputArray['status'] = 1;
        $eventtickettaxes = $this->tickettaxHandler->getTaxes($inputArray);
        if (!$eventDetails['status']) {
            $output['status'] = FALSE;
            $output['response']['messages'][] = ERROR_INVALID_EVENTID;
            $output['statusCode'] = 400;
            return $output;
        }

        $ticketdetails = $this->eventHandler->getActualEventTicketDetails($data);


        $data['eventDetails'] = $eventDetails['response']['details'];
        $timeZoneName = $data['eventDetails']['location']['timeZoneName'];
        $startdatetime = explode(" ", convertTime($data['eventDetails']['startDate'], $timeZoneName, true));
        $data['eventDetails']['convertedStartDate'] = allTimeFormats($startdatetime[0], 1);
        $data['eventDetails']['StartDate'] = $startdatetime[0];
        $data['eventDetails']['StartTime'] = $startdatetime[1];
        $data['eventDetails']['convertedStartTime'] = allTimeFormats($startdatetime[1], 2);
        $enddatetime = explode(" ", convertTime($data['eventDetails']['endDate'], $timeZoneName, true));
        $data['eventDetails']['convertedEndDate'] = allTimeFormats($enddatetime[0], 1);
        $data['eventDetails']['convertedEndTime'] = allTimeFormats($enddatetime[1], 2);
        $data1['content'] = 'ticket';
        $currencyListResponse = $this->currencyHandler->getCurrencyList();
        $currencyList = array();
        if ($currencyListResponse['status'] && $currencyListResponse['response']['total'] > 0) {
            $currencyList = $currencyListResponse['response']['currencyList'];
        }
        $data1['eventDetails'] = $eventDetails['response']['details'];
        $data1['timeZoneName'] = $timeZoneName;
        $data1['currencyDetails'] = $currencyList;
        $data1['eventTicketDetails'] = $ticketdetails['response']['ticketList'];
        $data1['eventtickettaxes'] = $eventtickettaxes['response']['taxList'];
        $data1['ticketTaxDetails'] = $ticketdetails['response']['taxList'];
        $data1['oldTicketDetails'] = $ticketdetails['response']['taxDetails'];
        $ticketTaxIds = array();
        foreach ($ticketdetails['response']['taxDetails'] as $key => $taxArr) {
            foreach ($taxArr as $taxmappArr) {
                $ticketTaxIds[$key][] = $taxmappArr['taxid'];
            }
        }
        $data1['ticketTaxIds'] = $ticketTaxIds;
        //print_r($data1);exit;
        $this->eventSignupHandler = new Eventsignup_handler();
        $transactions = $this->eventSignupHandler->getSuccessfullTransactionsByEventId($data['eventId'], '', 'count');
        $data['transactionsCount'] = 0;
        $data1['transactionsCount'] = 0;
        if ($transactions['status'] == TRUE) {
            $data['transactionsCount'] = $transactions['response']['eventsignupData'][0]['count'];
            $data1['transactionsCount'] = $data['transactionsCount'];
        }
        // successfull transaction tickets
        if (isset($data1['eventTicketDetails']) && !empty($data1['eventTicketDetails'])) {
            foreach ($data1['eventTicketDetails'] as $tkey => $tvalue) {
                $transactionsTickets = array();
                $transactionsTickets = $this->eventSignupHandler->getSuccessfullTransactionsByEventId($data['eventId'], '', 'count', $tvalue['id']);
                if ($transactionsTickets['status'] == TRUE) {
                    $data1['transactionsTicketCount'][$tvalue['id']] = $transactionsTickets['response']['eventsignupData'][0]['count'];
                }
            }
        }
        
        require_once(APPPATH . 'handlers/eventextracharge_handler.php');
        $this->eventExtraChargeHandler = new Eventextracharge_handler();
        $extraInput['eventid'] = $id;
        $extraCharges = $this->eventExtraChargeHandler->getExtrachargeByEventId($extraInput);
        $extraChargeArray = array();
        
        $organiserFee = '';
        if($extraCharges['status'] && $extraCharges['response']['total'] > 0) {
            
            $feeArray = $this->config->item('organizer_fees');
            $extraChargeArray = $extraCharges['response']['eventExtrachargeList'];
            $extraCount = 0;
            foreach($extraChargeArray as $extraCharge) {
                foreach($feeArray as $feeKey => $feeVal) {
                    if($feeVal['label'] == $extraCharge['label']) {
                        $organiserFee = $feeKey;
                        $extraCount++;
                    }
                }
            }
            if($extraCount == count($feeArray)) {
                $organiserFee = 'both';
            }
        }
        $data1['organiser_fee'] = $organiserFee;
        $data['ticketView'] = $this->load->view('includes/elements/ticket', $data1, true);
        $this->load->view('templates/event_template', $data);
    }

    /**
     * To bring the create & update event related details
     * @return string
     */
    public function createEditDetails() {

        $inputArray = $this->input->get();
        $cookieData = $this->commonHandler->headerValues($inputArray);
        $categoryData = array("major" => 1);
        $categoryList = $this->categoryHandler->getCategoryList($categoryData);

      if (count($cookieData) > 0) {
            $data['countryList'] = isset($cookieData['countryList']) ? $cookieData['countryList'] : array();
           $this->defaultCountryId = isset($cookieData['defaultCountryId']) ? $cookieData['defaultCountryId'] : $this->defaultCountryId;
       }
        $data = $cookieData;
        
        if ($categoryList['status'] == TRUE && $categoryList['response']['total'] > 0) {
            $data['categoryList'] = $categoryList['response']['categoryList'];
        }

        $timeZoneList = $this->timezoneHandler->timeZoneList();
        if ($timeZoneList['status'] == TRUE && $timeZoneList['response']['total'] > 0) {
            $data['timeZoneList'] = $timeZoneList['response']['timeZoneList'];
        }


        $data['defaultCountryId'] = $this->defaultCountryId;
        $data['defaultCityId'] = '';
        $footerValues = $this->commonHandler->footerValues();
        $data['cityList'] = $footerValues['cityList'];
        //Pick a theme related images list
        $image_path = $this->config->item('images_content_path');
        $data['pickThemeImages'] = get_theme_images_array($image_path);
        $data['content'] = 'create_event_view';
        $data['pageTitle'] = PAGETITLE_CREATE_EVENT;
        $data['saleButtonTitleList'] = saleButtonTitle();

        $data['userType'] = $this->customsession->getData('userType');

        $data['jsArray'] = array(
            $this->config->item('js_public_path') . 'additional-methods',
            $this->config->item('js_public_path') . 'create_event',
            $this->config->item('js_public_path') . 'bootstrap-tagsinput',
            $this->config->item('js_public_path') . 'tags-custom',
            $this->config->item('js_public_path') . 'bootstrap-timepicker',
            //$this->config->item('js_public_path') . 'onscrollScript',
            $this->config->item('js_public_path') . 'tinymce/tinymce',
            $this->config->item('js_public_path') . 'bootstrap-select',
            $this->config->item('js_public_path') . 'common' );
        $data['cssArray'] = array(
                /* $this->config->item('css_public_path') . 'create_event_news',
                  $this->config->item('css_public_path') . 'bootstrap-select',
                  $this->config->item('css_public_path') . 'bootstrap-tagsinput', */
        );

        return $data;
    }

    /**
     * Selected Event Dashboard Page
     * @return string
     */
    public function home($eventId, $filtertype = 'all', $ticketId = 0) {
        $inputArray['eventId'] = $eventId;
        $eventData = $this->config->item('eventData');
        $timeZoneData['timezoneId'] = $eventData["event" . $eventId]['timezoneId'];
        $timeZoneDetails = $this->timezoneHandler->details($timeZoneData);
        if ($timeZoneDetails['status']) {
            $timeZoneName = $timeZoneDetails['response']['detail'][1]['name'];
        }
        $data['eventTimeZoneName'] = $timeZoneName;
        $data['eventDetail'] = array();
        if (isset($eventData["event" . $eventId])) {
            $data['eventDetail'] = $eventData["event" . $eventId];
            $data['isCurrentEvent'] = (strtotime($eventData["event" . $eventId]['endDateTime']) > strtotime(allTimeFormats('', 11))) ? TRUE : FALSE;
        }
        $ticketHandler = new Ticket_handler();
        $ticketInput = array('eventId' => $eventId);
        $ticketInput['eventTimeZoneName'] = $timeZoneName;
        $ticketArray = array();
        $tikcetlistdata = $ticketHandler->getActualEventTicketList($ticketInput);
        // echo "<pre>";print_r($tikcetlistdata);exit;
        if ($tikcetlistdata['status'] == TRUE && count($tikcetlistdata['response']['total']) > 0) {
            $ticketArray = $tikcetlistdata['response']['ticketList'];
            $data['taxDetails'] = $tikcetlistdata['response']['taxDetails'];
            $data['taxList'] = $tikcetlistdata['response']['taxList'];
        }
        $this->eventSignupHandler = new Eventsignup_handler();
        //$inputCurrencies['eventid'] = $eventId;
        //$this->eventSignupHandler->getAllSaleCurrencies($inputCurrencies);
        //$ticketInput['type'] = "all";
        //$totalSaleDataArray = $this->eventSignupHandler->getSuccessTxnDatewiseData($ticketInput);
        $inputTotal['eventid'] = $eventId;
        // $inputTotal['reporttype'] = 'summary';
        $inputTotal['transactiontype'] = 'all';
        $inputTotal['currencyid'] = '1';
        $inputTotal['filtertype'] = $filtertype;
        if ($ticketId > 0) {
            $inputTotal['ticketid'] = $ticketId;
        }
        $totalSaleDataArray = $this->eventSignupHandler->getWeekwiseSales($inputTotal);
        $inputTransactionTotal['eventid'] = $eventId;
        $inputTransactionTotal['reporttype'] = 'summary';
        $inputTransactionTotal['transactiontype'] = 'all';
        //$totalSaleAmountArray = $this->eventSignupHandler->getTransactionsTotal($inputTransactionTotal);
        //print_r($totalSaleDataArray);exit;
        $totalSaleTicket = 0;
        $totalSaleAmount = array();
        //print_r($totalSaleDataArray);exit;
        if ($totalSaleDataArray['status'] == TRUE && $totalSaleDataArray['response']['total'] > 0) {
            $txnData = $totalSaleDataArray['response']['weekWiseData'];
            $totalSaleAmountArray = $totalSaleDataArray['response']['totalTransactionsData'];
//            foreach ($txnData as $saleData) {
//                $totalSaleTicket += $saleData['quantity'];
//                $totalSaleAmount[] = array("amount" => $saleData['totalAmount'], "currency" => $saleData['currencyId']);
//                ;
//            }
            $data['weekWiseData'] = $txnData;
            // print_r($txnData);exit;
            //$totalSaleTicket = $txnData['quantity'];
//            $totalSaleTicket = 0;
//            foreach ($txnData['quantity'] as $key => $value) {
//                $totalSaleTicket+=$value;
//            }
            //$totalSaleAmount = $txnData['totalamount'];
            //$totalSaleAmount = array();
//            foreach ($txnData['amount'] as $key => $value) {
//                foreach ($value as $code => $amount) {
//                    $totalSaleAmount[$code]+= $amount;
//                }
//            }
        } else {
            
        }
        // print_r($totalSaleAmountArray);exit;
        // if ($totalSaleAmountArray['status'] && $totalSaleAmountArray['response']['total'] > 0) {
        // $amountData = $totalSaleAmountArray['response']['grandTotalResponse'];
        //print_r($amountData);exit;
        if (isset($totalSaleAmountArray)) {
            $totalSaleTicket = $totalSaleAmountArray['totalquantity'];
            $totalSaleAmount = $totalSaleAmountArray['totalpaid'];
        }
        //} else {
        // }
        $totalSaleData = array('quantity' => $totalSaleTicket, "currencySale" => $totalSaleAmount);
        $inputEffort['eventid'] = $eventId;
        $salesEffortDataResponse = $this->eventSignupHandler->getSalesEffortTotals($inputEffort);
        if ($salesEffortDataResponse['status'] && $salesEffortDataResponse['response']['total'] > 0) {
            $salesEffortDataArray = $salesEffortDataResponse['response']['salesEffortResponse'];
            foreach ($salesEffortDataArray as $key => $value) {
                if ($key == 'meraevents') {
                    $salesEffortData = array('quantity' => $value['totalquantity'], "currencySale" => $value['totalamount']);
                    break;
                }
            }
        }
        $collaboratorHandler = new Collaborator_handler();
        $inputCollaborator['eventid'] = $eventId;
        $collaboratorList = $collaboratorHandler->getList($inputCollaborator);
        if ($collaboratorList['status'] && $collaboratorList['response']['total'] > 0) {
            $data['collaboratorList'] = $collaboratorList['response']['collaboratorList'];
        } else {
            $data['errors'][] = $collaboratorList['messages'][0];
        }
        $data['filterType'] = $filtertype;
        $data['ticketId'] = $ticketId;
        $data['totalSaleData'] = $totalSaleData;
        //print_r($data);exit;
        $data['salesEffortData'] = $salesEffortData;
        $data['ticketList'] = $ticketArray;
        $data['pageName'] = 'Event Dashboard';
        $data['pageTitle'] = 'MeraEvents | Manage Event';
        $data['hideLeftMenu'] = 0;
        $data['content'] = 'event_home_view';
        $data['currencyCode'] = 'INR';
        $data['eventId'] = $eventId;
        $data['jsArray'] = array(
            $this->config->item('js_public_path') . 'dashboard/home');
        $this->load->view('templates/dashboard_template', $data);
    }

    public function noAccess() {
        $message = $this->uri->segment(3);
        $data['pageName'] = 'Event Dashboard';
        $data['pageTitle'] = 'Event Dashboard';
        $data['hideLeftMenu'] = 1;
        $data['content'] = 'no_acess_view';
        $data['message'] = $message;
        $this->load->view('templates/dashboard_template', $data);
    }

    //To open event terms and conditions in new window
    public function termsAndConditions($eventId) {
        $inputArray['eventId'] = $eventId;
        $eventDetails = $this->eventHandler->getEventDetails($inputArray);
        if ($eventDetails['status']) {
            $data['eventName'] = $eventDetails['response']['details']['title'];
            if ($eventDetails['response']['details']['eventDetails']['tnctype'] == 'organizer') {
                if ($eventDetails['response']['details']['eventDetails']['organizertnc'] != '') {
                    $data['tncDetails'] = $eventDetails['response']['details']['eventDetails']['organizertnc'];
                }
            } else if ($eventDetails['response']['details']['eventDetails']['tnctype'] == 'meraevents') {
                if ($eventDetails['response']['details']['eventDetails']['meraeventstnc'] != '') {
                    $data['tncDetails'] = $eventDetails['response']['details']['eventDetails']['meraeventstnc'];
                }
            }
            $data['pageTitle'] = 'Terms And Conditions';
        } else {
            $data['errors'] = isset($eventDetails['response']['messages']) ? $eventDetails['response']['messages'] : '';
        }
        $this->load->view('dashboard/tnc_popup_view', $data);
    }

}

?>
<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/**
 * Default landing page controller
 *
 * @package		CodeIgniter
 * @author		Qison  Dev Team
 * @copyright	Copyright (c) 2015, MeraEvents.
 * @Version		Version 1.0
 * @Since       Class available since Release Version 1.0 
 * @Created     03-08-2015
 * @Last Modified On  03-08-2015
 * @Last Modified By  Qison  Dev Team
 */
require_once(APPPATH . 'handlers/reports_handler.php');
require_once (APPPATH . 'handlers/event_handler.php');
require_once (APPPATH . 'handlers/ticket_handler.php');

class Reports extends CI_Controller {

    var $reportsHandler;
    var $eventHandler;

    public function __construct() {
        parent::__construct();
        $this->load->library(array('acl'));
        $this->reportsHandler = new Reports_handler();
        $this->eventHandler = new Event_handler();
        $_GET['eventId'] = $this->uri->segment(3);
    }

    public function transaction($eventId, $reportType, $transactionType, $page) {
        //$inputArray['eventId'] = 15090;
        //$ticketId=isset($this->input->get('ticketId'))?$this->input->get('ticketId'):0;
        $getVar = $this->input->get();
        $inputArray['eventid'] = $eventId;
        $inputArray['reporttype'] = $reportType;
        $inputArray['transactiontype'] = $transactionType;
        $inputArray['page'] = $page;
        $promotercode = isset($getVar['promotercode']) ? $getVar['promotercode'] : '';
        $currencycode = isset($getVar['currencycode']) ? $getVar['currencycode'] : '';
        if (!empty($promotercode)) {
            $inputArray['promotercode'] = $promotercode;
        }
        if (!empty($currencycode)) {
            $inputArray['currencycode'] = $currencycode;
        }
        $ticketid = isset($getVar['ticketid']) && $getVar['ticketid'] > 0 ? $getVar['ticketid'] : 0;
        if ($ticketid > 0) {
            $inputArray['ticketid'] = $ticketid;
        }
//        if (isset($getVar['promotercode'])) {
//            $inputArray['promotercode'] = $getVar['promotercode'];
//        }
//        if (isset($getVar['currencycode'])) {
//            $inputArray['currencycode'] = $getVar['currencycode'];
//        }
//        if (isset($getVar['ticketid']) && $getVar['ticketid'] > 0) {
//            $inputArray['ticketid'] = $getVar['ticketid'];
//        }
        $data = array();
        $data['content'] = 'transaction_reports_view';
        $data['headerFields'] = array();
        $tableHeaderResponse = $this->reportsHandler->getHeaderFields($inputArray);

        if ($tableHeaderResponse['status'] && $tableHeaderResponse['response']['total'] > 0) {
            $data['headerFields'] = $tableHeaderResponse['response']['headerFields'];
        }
        //$input['eventId'] = $eventId;
        $data['eventTitle'] = commonHelperGetEventName($eventId);
        $ticketHandler = new Ticket_handler();
        $inputTicket['eventId'] = $eventId;
        //To skip passing free tickets 
        if ($transactionType == 'card') {
            $inputTicket['ticketType'] = "paid only";
        }
        $getEventTickets = $ticketHandler->getTicketName($inputTicket);
        if ($getEventTickets['status']) {
            if ($getEventTickets['response']['total'] > 0) {
                $data['ticketsData'] = $getEventTickets['response']['ticketName'];
//                $indexedTickets=  commonHelperGetIdArray($getEventTickets['response']['ticketName']);
//                $ticketIds=  array_keys($indexedTickets);
            }
        } else {
            $data['errors'][] = $getEventTickets['response']['messages'][0];
        }
//        if(isset($ticketIds)){
//            if(!in_array($ticketId, $ticketIds)){
//               $data['errors'][] = ERROR_; 
//            }
//        }
        if ($transactionType != 'incomplete') {
            $grandTotal = $this->reportsHandler->getGrandTotal($inputArray);
            //print_r($grandTotal);exit;
            if ($grandTotal['status'] && $grandTotal['response']['total'] > 0) {
                $data['grandTotal'] = $grandTotal['response']['grandTotalResponse'];
            } else {
                $data['errors'][] = $grandTotal['response']['messages'][0];
            }
        }

        //$transactionListResponse = $this->reportsHandler->getTransactions($inputArray);
        $transactionListResponse = $this->reportsHandler->getReportDetails($inputArray);
        if ($transactionListResponse['status'] && $transactionListResponse['response']['total'] > 0) {
            $data['transactionList'] = $transactionListResponse['response']['transactionList'];
            if (isset($transactionListResponse['response']['downloadAllRequired'])) {
                $data['downloadAllRequired'] = $transactionListResponse['response']['downloadAllRequired'];
            }
            $data['totalTransactionCount'] = $transactionListResponse['response']['totalTransactionCount'];
        } else {
            $data['errors'][] = $transactionListResponse['response']['messages'][0];
            //$this->load->view('templates/dashboard_template', $data);
        }
        //print_r($data);exit;
        //check file custom field exists
        $data['fileCustomFieldArray'] = array();
        $inputFileCustExists['eventid'] = $eventId;
        $fileCustExistsResponse = $this->reportsHandler->checkFileUploadExists($inputFileCustExists);
        if ($fileCustExistsResponse['status']) {
            if ($fileCustExistsResponse['response']['total'] > 0) {
                $fileCustomFieldData = $fileCustExistsResponse['response']['fileCustomFieldData'];
                $data['fileCustomFieldArray'] = $fileCustomFieldData;
            }
        } else {
            $data['errors'][] = $fileCustExistsResponse['response']['messages'][0];
        }
        $data['page'] = 1;
        $data['hideLeftMenu'] = 0;
        $data['transactionType'] = $transactionType;
        $data['eventId'] = $eventId;
        $data['ticketId'] = $ticketid;
        $data['reportType'] = $reportType;
        $data['promoterCode'] = $promotercode;
        $data['currencyCode'] = $currencycode;
        $data['pageTitle'] = 'MeraEvents | Export Reports';
        $data['jsArray'] = array(//$this->config->item('js_public_path') . 'dashboard/reports.min.js.gz'
            $this->config->item('js_public_path') . 'dashboard/jszip',
            $this->config->item('js_public_path') . 'dashboard/jszip-utils',
            $this->config->item('js_public_path') . 'dashboard/FileSaver',
            $this->config->item('js_public_path') . 'dashboard/reports');
        $this->load->view('templates/dashboard_template', $data);
    }

    public function saleseffort($eventId) {
        $data['eventId'] = $eventId;
        $data['hideLeftMenu'] = 0;
        $data['eventTitle'] = commonHelperGetEventName($eventId);
        $data['content'] = 'sales_effort_reports_view';
        $data['jsArray'] = array();
        $data['cssArray'] = array();
        $inputSales['eventid'] = $eventId;
        $salesData = $this->reportsHandler->getSalesEffortData($inputSales);
        //print_r($salesData);exit;
        if ($salesData['status'] && $salesData['response']['total'] > 0) {
            $data['salesData'] = $salesData['response']['salesEffortResponse'];
        } else {
            $data['errors'][] = $salesData['response']['messages'][0];
        }
        //print_r($data);exit;
        $data['pageTitle'] = 'MeraEvents | Sales Effort';
        $this->load->view('templates/dashboard_template', $data);
    }

}

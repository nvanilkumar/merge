<?php

/**
 * Maintaing promoter related data
 *
 * @author     Qison dev team
 * @copyright  2015-2005 The PHP Group
 * @version    CVS: $Id:$
 * @since      File available since Sprint 2
 * @deprecated File deprecated in Release 2.0.0
 */
/*
 * Place includes, constant defines and $_GLOBAL settings here.
 */

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require (APPPATH . 'libraries/REST_Controller.php');
require_once(APPPATH . 'handlers/reports_handler.php');

class Reports extends REST_Controller {

    var $reportsHandler;

    public function __construct() {
        parent::__construct();
        $this->reportsHandler = new Reports_handler();
    }

    /*
     * Function to get the transaction list
     *
     * @access	public
     * @param		Get contains
     * 				eventid - Integer
     *                              reporttype - string
     *                              transactiontype - string
     * 				page - Integer
     * @return	transactionList
     */

    public function getTransactions_post() {
        $inputArray = $this->input->post();
        $output = $this->reportsHandler->getTransactions($inputArray);
        $responseArray = array('response' => $output['response']);
        $this->response($responseArray, $output['statusCode']);
    }

    /*
     * Function to get the transaction list
     *
     * @access	public
     * @param		Get contains
     * 				eventid - Integer
     *                              reporttype - string
     *                              transactiontype - string
     * 				page - Integer
     * @return	transactionList
     */

    public function exportTransactions_post() {
        $inputArray = $this->input->post();
        $output = $this->reportsHandler->exportTransactions($inputArray);
        $responseArray = array('response' => $output['response']);
        $this->response($responseArray, $output['statusCode']);
    }

    public function emailTransactions_post() {
        $inputArray = $this->input->post();
        $output = $this->reportsHandler->emailTransactions($inputArray);
        $responseArray = array('response' => $output['response']);
        $this->response($responseArray, $output['statusCode']);
    }

    public function getTransactionsTotal_post() {
        $inputArray = $this->input->post();
        $output = $this->reportsHandler->getGrandTotal($inputArray);
        $responseArray = array('response' => $output['response']);
        $this->response($responseArray, $output['statusCode']);
    }

    public function getReportDetails_post() {
        $inputArray = $this->input->post();
        $output = $this->reportsHandler->getReportDetails($inputArray);
        $responseArray = array('response' => $output['response']);
        $this->response($responseArray, $output['statusCode']);
    }

    public function getExportReports_post() {
        $inputArray = $this->input->post();
        $output = $this->reportsHandler->getExportReports($inputArray);
        $responseArray = array('response' => $output['response']);
        $this->response($responseArray, $output['statusCode']);
    }

    public function salesEffortReports_post() {
        $inputArray = $this->input->post();
        $output = $this->reportsHandler->getSalesEffortData($inputArray);
        $responseArray = array('response' => $output['response']);
        $this->response($responseArray, $output['statusCode']);
    }

    public function getWeekwiseSales_post() {
        $inputArray = $this->input->post();
        $output = $this->reportsHandler->getWeekwiseSales($inputArray);
        $responseArray = array('response' => $output['response']);
        $this->response($responseArray, $output['statusCode']);
    }

    public function downloadImages_post() {
        $inputArray = $this->input->post();
        $output = $this->reportsHandler->getFileUploadImages($inputArray);
        $responseArray = array('response' => $output['response']);
        $this->response($responseArray, $output['statusCode']);
    }

}

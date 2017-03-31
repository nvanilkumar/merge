<?php

/**
 * Reports related business logic will be defined in this class
 * @package		CodeIgniter
 * @author		Qison  Dev Team
 * @param		
 * @addTicket		
 * @copyright	Copyright (c) 2015, MeraEvents.
 * @Version		Version 1.0
 * @Since       Class available since Release Version 1.0
 * @Created     03-08-2015
 * @Last Modified 03-08-2015
 */
require_once (APPPATH . 'handlers/handler.php');

//require_once (APPPATH . 'handlers/tickettax_handler.php');
//require_once (APPPATH . 'handlers/currency_handler.php');

class Eventsignup_Ticketdetail_handler extends Handler {

    var $ci;

    //var $tickettaxHandler;
    // var $currencyHandler;

    public function __construct() {
        parent::__construct();
        $this->ci = parent::$CI;
        // Eventsignupticketdetail_model
        $this->ci->load->model('Eventsignupticketdetail_model');
        //$this->tickettaxHandler = new TicketTax_handler();
        // $this->currencyHandler = new Currency_handler();
    }

    public function getListByEventsignupIds($input) {
        $this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($input);
        $this->ci->form_validation->set_rules('eventsignupids', 'eventsignupids', 'required_strict|is_array');
        //$this->ci->form_validation->set_rules('reporttype', 'reporttype', 'required_strict');
        $this->ci->form_validation->set_rules('transactiontype', 'transactiontype', 'required_strict|is_valid_type[transaction]');
        $this->ci->form_validation->set_rules('ticketids', 'ticketids', 'is_array');
        if ($this->ci->form_validation->run() == FALSE) {
            $response = $this->ci->form_validation->get_errors();
            $output['status'] = FALSE;
            $output['response']['messages'] = $response['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $this->ci->Eventsignupticketdetail_model->resetVariable();
        $eventSignupIds = $input['eventsignupids'];
        $ticketIds = isset($input['ticketids']) ? $input['ticketids'] : array();
        $transactionType = $input['transactiontype'];
        //$nullify = isset($input['nullify']) ? $input['nullify'] : 0;
        //$setOrWhere[$this->ci->Eventsignupticketdetail_model->amount] = 0;
        $whereESTD = array();
        if ($transactionType == 'incomplete') {
            $selectESTD['failedcount'] = 'count(1)';
        }
        if ($transactionType == 'free') {
            $whereESTD[$this->ci->Eventsignupticketdetail_model->totalamount] = 0;
        }
        $selectESTD['ticketid'] = $this->ci->Eventsignupticketdetail_model->ticketid;
        $selectESTD['eventsignupid'] = $this->ci->Eventsignupticketdetail_model->eventsignupid;
        $selectESTD['amount'] = $this->ci->Eventsignupticketdetail_model->amount;
        $selectESTD['totalamount'] = $this->ci->Eventsignupticketdetail_model->totalamount;
        $selectESTD['ticketquantity'] = $this->ci->Eventsignupticketdetail_model->ticketquantity;
        $selectESTD['bulkdiscountamount'] = $this->ci->Eventsignupticketdetail_model->bulkdiscountamount;
        $selectESTD['discountamount'] = $this->ci->Eventsignupticketdetail_model->discountamount;
        $selectESTD['referraldiscountamount'] = $this->ci->Eventsignupticketdetail_model->referraldiscountamount;
        $where_in_ESTD[$this->ci->Eventsignupticketdetail_model->eventsignupid] = array_values($eventSignupIds);
        if (count($ticketIds) > 0) {
            //No need of this condition for free with no tic selection
//            if($nullify == 0){
//                $where_in_ESTD[$this->ci->Eventsignupticketdetail_model->ticketid] = $ticketIds;
//            }
            $where_in_ESTD[$this->ci->Eventsignupticketdetail_model->ticketid] = $ticketIds;
        }
        $this->ci->Eventsignupticketdetail_model->setSelect($selectESTD);
        $this->ci->Eventsignupticketdetail_model->setWhere($whereESTD);
        $this->ci->Eventsignupticketdetail_model->setWhereIns($where_in_ESTD);
        $groupBy = array($this->ci->Eventsignupticketdetail_model->eventsignupid, $this->ci->Eventsignupticketdetail_model->ticketid);
        $this->ci->Eventsignupticketdetail_model->setGroupBy($groupBy);
        $orderBy = array($this->ci->Eventsignupticketdetail_model->eventsignupid . ' desc');
        $this->ci->Eventsignupticketdetail_model->setOrderBy($orderBy);
        $selectESTDResponse = $this->ci->Eventsignupticketdetail_model->get();
        //echo $this->ci->db->last_query();exit;
        if (count($selectESTDResponse) == 0) {
            $output['status'] = TRUE;
            $output['response']['total'] = 0;
            $output['response']['messages'][] = ERROR_NO_RECORDS;
            $output['statusCode'] = STATUS_OK;
            return $output;
        }
        $output['status'] = TRUE;
        $output['response']['eventSignupTicketDetailList'] = $selectESTDResponse;
        $output['response']['total'] = count($selectESTDResponse);
        $output['statusCode'] = STATUS_OK;
        return $output;
    }

    public function getAmountTotal($input) {
        $this->ci->form_validation->pass_array($input);
        $this->ci->form_validation->set_rules('eventsignupids', 'eventsignupids', 'required_strict|is_array');
        $this->ci->form_validation->set_rules('ticketids', 'ticketids', 'is_array');
        $this->ci->form_validation->set_rules('transactiontype', 'transactiontype', 'is_valid_type[transaction]');
        if ($this->ci->form_validation->run() == FALSE) {
            $response = $this->ci->form_validation->get_errors();
            $output['status'] = FALSE;
            $output['response']['messages'] = $response['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $eventSignupIds = $input['eventsignupids'];
        $this->ci->Eventsignupticketdetail_model->resetVariable();
        $ticketIds = isset($input['ticketids']) ? $input['ticketids'] : array();
        $transactionType = isset($input['transactiontype']) ? $input['transactiontype'] : 'all';
        $selectESTD['ticketid'] = $this->ci->Eventsignupticketdetail_model->ticketid;
        $whereESTD = array();
        if (count($ticketIds) > 0 || $transactionType == 'free' || $transactionType == 'card') {
            if (count($ticketIds) > 0) {
                if ($transactionType == 'free') {
                    $whereESTD[$this->ci->Eventsignupticketdetail_model->totalamount] = 0;
                }
                $where_in_ESTD[$this->ci->Eventsignupticketdetail_model->ticketid] = ($ticketIds);
            }
            $selectESTD['amount'] = $this->ci->Eventsignupticketdetail_model->amount;
            $selectESTD['discount'] = "round(".$this->ci->Eventsignupticketdetail_model->discountamount." + ".$this->ci->Eventsignupticketdetail_model->referraldiscountamount." + ".$this->ci->Eventsignupticketdetail_model->bulkdiscountamount.")";
            $selectESTD['ticketquantity'] = $this->ci->Eventsignupticketdetail_model->ticketquantity;
            $selectESTD['totalamount'] = $this->ci->Eventsignupticketdetail_model->totalamount;
            $selectESTD['eventsignupid'] = $this->ci->Eventsignupticketdetail_model->eventsignupid;
            $groupBy = array();
        } else {
            $selectESTD['totalamountsum'] = 'SUM(ROUND(' . $this->ci->Eventsignupticketdetail_model->amount . '))';
            $groupBy = array($this->ci->Eventsignupticketdetail_model->ticketid);
        }
        $where_in_ESTD[$this->ci->Eventsignupticketdetail_model->eventsignupid] = ($eventSignupIds);
        $this->ci->Eventsignupticketdetail_model->setSelect($selectESTD);
        $this->ci->Eventsignupticketdetail_model->setWhere($whereESTD);
        $this->ci->Eventsignupticketdetail_model->setWhereIns($where_in_ESTD);
        $this->ci->Eventsignupticketdetail_model->setGroupBy($groupBy);
        $selectESTDResponse = $this->ci->Eventsignupticketdetail_model->get();
        //echo $this->ci->db->last_query();exit;
        if (count($selectESTDResponse) == 0) {
            $output['status'] = TRUE;
            $output['response']['total'] = 0;
            $output['response']['messages'][] = ERROR_NO_RECORDS;
            $output['statusCode'] = STATUS_OK;
            return $output;
        } else {
            $output['status'] = TRUE;
            $output['response']['total'] = count($selectESTDResponse);
            $output['response']['eventsignupticketdetailResponse'] = $selectESTDResponse;
            $output['response']['messages'] = array();
            $output['statusCode'] = STATUS_OK;
            return $output;
        }
    }

    public function getWeekwiseTotalQuantity($input) {
        $this->ci->form_validation->pass_array($input);
        $this->ci->form_validation->set_rules('eventsignupids', 'eventsignupids', 'required_strict|is_array');
        $this->ci->form_validation->set_rules('ticketids', 'ticketids', 'required_strict|is_array');
        if ($this->ci->form_validation->run() == FALSE) {
            $response = $this->ci->form_validation->get_errors();
            $output['status'] = FALSE;
            $output['response']['messages'] = $response['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $eventSignupIds = $input['eventsignupids'];
        $this->ci->Eventsignupticketdetail_model->resetVariable();
        $ticketIds = isset($input['ticketids']) ? $input['ticketids'] : array();
        if (count($ticketIds) > 0) {
            $where_in_ESTD[$this->ci->Eventsignupticketdetail_model->ticketid] = ($ticketIds);
        }
        //$selectESTD['totalamountsum'] = 'SUM(' . $this->ci->Eventsignupticketdetail_model->amount . ')';
        $selectESTD['quantity'] = 'SUM(' . $this->ci->Eventsignupticketdetail_model->ticketquantity . ')';
        $groupBy[] = 'week(cts)';
        $this->ci->Eventsignup_model->setGroupBy($groupBy);
        $where_in_ESTD[$this->ci->Eventsignupticketdetail_model->eventsignupid] = ($eventSignupIds);
        $this->ci->Eventsignupticketdetail_model->setSelect($selectESTD);
        $this->ci->Eventsignupticketdetail_model->setWhereIns($where_in_ESTD);
        $this->ci->Eventsignupticketdetail_model->setGroupBy($groupBy);
        $selectESTDResponse = $this->ci->Eventsignupticketdetail_model->get();
        //echo $this->ci->db->last_query();exit;
        if (count($selectESTDResponse) == 0) {
            $output['status'] = TRUE;
            $output['response']['total'] = 0;
            $output['response']['messages'][] = ERROR_NO_RECORDS;
            $output['statusCode'] = STATUS_OK;
            return $output;
        } else {
            $output['status'] = TRUE;
            $output['response']['total'] = count($selectESTDResponse);
            $output['response']['eventsignupticketdetailResponse'] = $selectESTDResponse;
            $output['response']['messages'] = array();
            $output['statusCode'] = STATUS_OK;
            return $output;
        }
    }

    public function add($inputArray) {

        $this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('eventsignupid', 'event signup id', 'required_strict');

        if ($this->ci->form_validation->run() === FALSE) {
            $response = $this->ci->form_validation->get_errors('message');
            $output['status'] = FALSE;
            $output['response']['messages'] = $response['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $this->ci->Eventsignupticketdetail_model->resetVariable();
        $createEventSignupTicketDetails[$this->ci->Eventsignupticketdetail_model->eventsignupid] = $inputArray['eventsignupid'];
        $createEventSignupTicketDetails[$this->ci->Eventsignupticketdetail_model->ticketid] = $inputArray['ticketid'];
        $createEventSignupTicketDetails[$this->ci->Eventsignupticketdetail_model->ticketquantity] = $inputArray['ticketquantity'];
        $createEventSignupTicketDetails[$this->ci->Eventsignupticketdetail_model->amount] = $inputArray['amount'];
        $createEventSignupTicketDetails[$this->ci->Eventsignupticketdetail_model->totalamount] = $inputArray['totalamount'];

        $createEventSignupTicketDetails[$this->ci->Eventsignupticketdetail_model->totaltaxamount] = $inputArray['totaltaxamount'] ? $inputArray['totaltaxamount'] : 0;
        $createEventSignupTicketDetails[$this->ci->Eventsignupticketdetail_model->discountcode] = $inputArray['discountcode'] ? $inputArray['discountcode'] : 0;
        $createEventSignupTicketDetails[$this->ci->Eventsignupticketdetail_model->discountcodeid] = $inputArray['discountcodeid'] ? $inputArray['discountcodeid'] : 0;
        $createEventSignupTicketDetails[$this->ci->Eventsignupticketdetail_model->discountamount] = $inputArray['discountamount'] ? $inputArray['discountamount'] : 0;
        $createEventSignupTicketDetails[$this->ci->Eventsignupticketdetail_model->bulkdiscountamount] = $inputArray['bulkdiscountamount'] ? $inputArray['bulkdiscountamount'] : 0;
        $createEventSignupTicketDetails[$this->ci->Eventsignupticketdetail_model->referraldiscountamount] = $inputArray['referraldiscountamount'] ? $inputArray['referraldiscountamount'] : 0;

        $this->ci->Eventsignupticketdetail_model->setInsertUpdateData($createEventSignupTicketDetails);
        $eventSignUpTicketDetailId = $this->ci->Eventsignupticketdetail_model->insert_data(); //Inserting into table and getting inserted id

        if ($eventSignUpTicketDetailId) {
            //Inserting record in the ticketdiscount table
            $output['status'] = TRUE;
            $output['response']['messages'][] = SUCCESS_EVENTSIGNUP_ADDED;
            $output['response']['eventSignUpTicketDetailId'] = $eventSignUpTicketDetailId;
            $output['statusCode'] = STATUS_CREATED;
            return $output;
        }
        $output['status'] = FALSE;
        $output['response']['messages'][] = ERROR_EVENTSIGNUP_ADDED;
        $output['statusCode'] = STATUS_SERVER_ERROR;
        return $output;
    }

    public function getListByTicketAndSignupIds($inputArray) {
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('eventSignUpIdList', 'eventSignUpIdList', 'required_strict|is_array');
        $this->ci->form_validation->set_rules('ticketId', 'ticketId', 'is_natural_no_zero');
        if ($this->ci->form_validation->run() == FALSE) {
            $response = $this->ci->form_validation->get_errors();
            $output['status'] = FALSE;
            $output['response']['messages'] = $response['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $this->ci->Eventsignupticketdetail_model->resetVariable();
        $selectESTD['ticketid'] = $this->ci->Eventsignupticketdetail_model->ticketid;
        $selectESTD['ticketid'] = $this->ci->Eventsignupticketdetail_model->ticketid;
        $selectESTD['eventsignupid'] = $this->ci->Eventsignupticketdetail_model->eventsignupid;

        $where_in_ESTD[$this->ci->Eventsignupticketdetail_model->eventsignupid] = $inputArray['eventSignUpIdList'];
        if (isset($inputArray['ticketId'])) {
            $where[$this->ci->Eventsignupticketdetail_model->ticketid] = $inputArray['ticketId'];
        }
        $where[$this->ci->Eventsignupticketdetail_model->deleted] = 0;
        $this->ci->Eventsignupticketdetail_model->setSelect($selectESTD);
        $this->ci->Eventsignupticketdetail_model->setWhere($where);
        $this->ci->Eventsignupticketdetail_model->setWhereIns($where_in_ESTD);
        $eventSignupIdList = $this->ci->Eventsignupticketdetail_model->get();

        if (count($eventSignupIdList) == 0) {
            $output['status'] = TRUE;
            $output['response']['total'] = 0;
            $output['response']['messages'][] = ERROR_NO_RECORDS;
            $output['statusCode'] = STATUS_OK;
            return $output;
        }
        $output['status'] = TRUE;
        $output['response']['eventSignupIdList'] = $eventSignupIdList;
        $output['response']['total'] = count($eventSignupIdList);
        $output['statusCode'] = STATUS_OK;
        return $output;
    }

    public function update($inputArray) {

        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('eventsignupid', 'event signup id', 'required_strict');
        $this->ci->form_validation->set_rules('ticketid', 'ticketid', 'required_strict');
        $this->ci->form_validation->set_rules('amount', 'amount', 'required_strict');
        $this->ci->form_validation->set_rules('ticketquantity', 'ticketquantity', 'required_strict');
        $this->ci->form_validation->set_rules('totalamount', 'totalamount', 'required_strict');

        if ($this->ci->form_validation->run() === FALSE) {
            $response = $this->ci->form_validation->get_errors('message');
            $output['status'] = FALSE;
            $output['response']['messages'] = $response['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }

        $this->ci->Eventsignupticketdetail_model->resetVariable();
        $createEventSignupTicketDetails[$this->ci->Eventsignupticketdetail_model->ticketid] = $inputArray['ticketid'];
        $createEventSignupTicketDetails[$this->ci->Eventsignupticketdetail_model->ticketquantity] = $inputArray['ticketquantity'];
        $createEventSignupTicketDetails[$this->ci->Eventsignupticketdetail_model->amount] = $inputArray['amount'];
        $createEventSignupTicketDetails[$this->ci->Eventsignupticketdetail_model->totalamount] = $inputArray['totalamount'];

        $createEventSignupTicketDetails[$this->ci->Eventsignupticketdetail_model->totaltaxamount] = $inputArray['totaltaxamount'] ? $inputArray['totaltaxamount'] : 0;
        $createEventSignupTicketDetails[$this->ci->Eventsignupticketdetail_model->discountcode] = $inputArray['discountcode'] ? $inputArray['discountcode'] : 0;
        $createEventSignupTicketDetails[$this->ci->Eventsignupticketdetail_model->discountcodeid] = $inputArray['discountcodeid'] ? $inputArray['discountcodeid'] : 0;
        $createEventSignupTicketDetails[$this->ci->Eventsignupticketdetail_model->discountamount] = $inputArray['discountamount'] ? $inputArray['discountamount'] : 0;
        $createEventSignupTicketDetails[$this->ci->Eventsignupticketdetail_model->bulkdiscountamount] = $inputArray['bulkdiscountamount'] ? $inputArray['bulkdiscountamount'] : 0;
        $createEventSignupTicketDetails[$this->ci->Eventsignupticketdetail_model->referraldiscountamount] = $inputArray['referraldiscountamount'] ? $inputArray['referraldiscountamount'] : 0;

        $this->ci->Eventsignupticketdetail_model->setInsertUpdateData($createEventSignupTicketDetails);
        $where['eventsignupid'] = $inputArray['eventsignupid'];
        $this->ci->Eventsignupticketdetail_model->setWhere($where);
        $eventSignUpTicketDetailId = $this->ci->Eventsignupticketdetail_model->update_data(); //Inserting into table and getting inserted id

        if ($eventSignUpTicketDetailId) {
            //Inserting record in the ticketdiscount table
            $output['status'] = TRUE;
            $output['response']['messages'][] = SUCCESS_EVENTSIGNUP_ADDED;
            $output['response']['eventSignUpTicketDetailId'] = $eventSignUpTicketDetailId;
            $output['statusCode'] = STATUS_UPDATED;
            return $output;
        }
        $output['status'] = FALSE;
        $output['response']['messages'][] = ERROR_EVENTSIGNUP_ADDED;
        $output['statusCode'] = STATUS_SERVER_ERROR;
        return $output;
    }

}

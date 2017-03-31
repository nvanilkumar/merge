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
require_once (APPPATH . 'handlers/handler.php');
require_once (APPPATH . 'handlers/ticketdiscount_handler.php');
require_once (APPPATH . 'handlers/promoter_handler.php');
require_once (APPPATH . 'handlers/offlinepromoterdiscounts_handler.php');

class Discount_handler extends Handler {

    var $ci;

    public function __construct() {
        parent::__construct();
        $this->ci = parent::$CI;
        $this->ci->load->model('Discount_model');
    }

    public function add($inputArray) {
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('discountName', 'Discount Name', 'required_strict|namerule|max_length[25]');
        $this->ci->form_validation->set_rules('eventId', 'Event Id', 'is_natural_no_zero|required_strict');
        $this->ci->form_validation->set_rules('discountStartDate', 'Discount Start Date', 'required_strict|date');
        $this->ci->form_validation->set_rules('discountStartTime', 'Discount Start Time', 'required_strict|specialTime');
        $this->ci->form_validation->set_rules('discountEndDate', 'Discount End Date', 'required_strict|date');
        $this->ci->form_validation->set_rules('discountEndTime', 'Discount End Time', 'required_strict|specialTime');
        $this->ci->form_validation->set_rules('discountValue', 'Discount Value', 'required_strict|numeric');
        $this->ci->form_validation->set_rules('amountType', 'Amount Type', 'required_strict|amountType');
        if ($inputArray['type'] == 'normal') {
            $this->ci->form_validation->set_rules('usageLimit', 'Usage Limit', 'numeric|required_strict|max_length[4]');
            $this->ci->form_validation->set_rules('discountCode', 'discount Code', 'required_strict|max_length[25]');
        }
        if ($inputArray['type'] == 'bulk') {
            $this->ci->form_validation->set_rules('ticketsFrom', 'Tickets From', 'is_natural_no_zero|required_strict');
            $this->ci->form_validation->set_rules('ticketsUpto', 'Tickets Upto', 'is_natural_no_zero');
        }
        $this->ci->form_validation->set_rules('type', 'Discount Type', 'required_strict|discountType');
        $this->ci->form_validation->set_rules('ticketIds', 'Ticket Id Array', 'required_strict|is_array');       
        if ($this->ci->form_validation->run() === FALSE) {
            $error_messages = $this->ci->form_validation->get_errors('message');
            $output = parent::createResponse(FALSE, $error_messages['message'], STATUS_BAD_REQUEST);
            return $output;
        }

        if (!empty($inputArray['discountCode'])) {
            //Discount code check
            $result = $this->discountCodeCheck($inputArray);
            if (!$result['status']) {
                return $result;
            }
        }
        $startTimeDate['time'] = urldecode($inputArray['discountStartTime']);
        $startTimeDate['date'] = urldecode($inputArray['discountStartDate']);
        $startTime = convertTime(formatToMysqlDate($startTimeDate), $inputArray['eventTimeZoneName']);
        $endTimeDate['time'] = urldecode($inputArray['discountEndTime']);
        $endTimeDate['date'] = urldecode($inputArray['discountEndDate']);
        $endTime = convertTime(formatToMysqlDate($endTimeDate), $inputArray['eventTimeZoneName']);
        //Start date should be greater than current date
        if(strtotime($inputArray['discountStartDate']) < (strtotime(convertTime(onlyCurrentDate(),$inputArray['eventTimeZoneName'])))){
            $output['status'] = FALSE;
            $output["response"]["messages"][] = ERROR_START_DATE_GREATER_THAN_NOW;
            $output['statusCode'] = STATUS_INVALID_INPUTS;
            return $output;            
        } 
        //End date should be greater than current date
        if(strtotime($inputArray['discountEndDate']) < (strtotime(convertTime(onlyCurrentDate(),$inputArray['eventTimeZoneName'])))){
            $output['status'] = FALSE;
            $output["response"]["messages"][] = ERROR_END_DATE_GREATER_THAN_NOW;
            $output['statusCode'] = STATUS_INVALID_INPUTS;
            return $output;            
        }        
        //End date should be greater than start date
        if (strtotime($startTime) > strtotime($endTime)) {
            $output['status'] = FALSE;
            $output["response"]["messages"][] = ERROR_EVENT_START_DATE_GREATER;
            $output['statusCode'] = STATUS_INVALID_INPUTS;
            return $output;
        }
        if($inputArray['discountValue'] > 100 && $inputArray['amountType'] == "percentage") {
            $output['status'] = FALSE;
            $output["response"]["messages"][] = ERROR_DISCOUNT_PERCENTAGE_EXCEEDED;
            $output['statusCode'] = STATUS_INVALID_INPUTS;
            return $output;
        }
        $this->ci->Discount_model->resetVariable();
        $createDiscount[$this->ci->Discount_model->name] = $inputArray['discountName'];
        $createDiscount[$this->ci->Discount_model->eventid] = $inputArray['eventId'];
        $createDiscount[$this->ci->Discount_model->startdatetime] = $startTime;
        $createDiscount[$this->ci->Discount_model->enddatetime] = $endTime;
        $createDiscount[$this->ci->Discount_model->type] = $inputArray['type'];
        $createDiscount[$this->ci->Discount_model->calculationmode] = $inputArray['amountType'];
        if (isset($inputArray['ticketsFrom'])) {
            $createDiscount[$this->ci->Discount_model->minticketstobuy] = $inputArray['ticketsFrom'];
        }
        if (isset($inputArray['ticketsUpto'])) {
            $createDiscount[$this->ci->Discount_model->maxticketstobuy] = $inputArray['ticketsUpto'];
        }
        if (isset($inputArray['usageLimit'])) {
            $createDiscount[$this->ci->Discount_model->usagelimit] = $inputArray['usageLimit'];
        }
        $createDiscount[$this->ci->Discount_model->value] = $inputArray['discountValue'];
        if (isset($inputArray['discountCode'])) {
            $createDiscount[$this->ci->Discount_model->code] = $inputArray['discountCode'];
        }

        $createDiscount[$this->ci->Discount_model->status] = 1;
        $this->ci->Discount_model->setInsertUpdateData($createDiscount);
        $discountId = $this->ci->Discount_model->insert_data(); //Inserting into table and getting inserted id
        if ($discountId) {
            //Inserting record in the ticketdiscount table
            $input['discountId'] = $discountId;
            $input['ticketIds'] = $inputArray['ticketIds'];
            $this->ticketDiscountHandler = new Ticketdiscount_handler();
            $ticketDiscountData = $this->ticketDiscountHandler->addTicketDiscount($input);
            if ($ticketDiscountData) {
                $output = parent::createResponse(TRUE, SUCCESS_DISCOUNT_ADDED, STATUS_UPDATED);
                return $output;
            }
        }
        $output = parent::createResponse(FALSE, ERROR_SOMETHING_WENT_WRONG, STATUS_BAD_REQUEST);
        return $output;
    }

    /*
     * Function to get the Discount Details
     *
     * @access public
     * @param $inputArray contains
     *     eventId - integer
     *     discountId - integer(optional)
     * @return array
     */

    function getDiscountList($inputArray) {
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('eventId', 'event id', 'required_strict|is_natural_no_zero');
        $this->ci->form_validation->set_rules('discountId', 'discount id', 'is_natural_no_zero');

        if (!empty($inputArray) && $this->ci->form_validation->run() == FALSE) {
            $response = $this->ci->form_validation->get_errors('message');
            $output['status'] = FALSE;
            $output['response']['messages'] = $response['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $this->ci->Discount_model->resetVariable();
        $selectData['id'] = $this->ci->Discount_model->id;
        $selectData['name'] = $this->ci->Discount_model->name;
        $selectData['eventid'] = $this->ci->Discount_model->eventid;
        $selectData['type'] = $this->ci->Discount_model->type;
        $selectData['calculationmode'] = $this->ci->Discount_model->calculationmode;
        $selectData['startdatetime'] = $this->ci->Discount_model->startdatetime;
        $selectData['enddatetime'] = $this->ci->Discount_model->enddatetime;
        $selectData['value'] = $this->ci->Discount_model->value;
        $selectData['minticketstobuy'] = $this->ci->Discount_model->minticketstobuy;
        $selectData['maxticketstobuy'] = $this->ci->Discount_model->maxticketstobuy;
        $selectData['usagelimit'] = $this->ci->Discount_model->usagelimit;
        $selectData['code'] = $this->ci->Discount_model->code;
        $selectData['totalused'] = $this->ci->Discount_model->totalused;
        $selectData['status'] = $this->ci->Discount_model->status;
        $this->ci->Discount_model->setSelect($selectData);
        $where[$this->ci->Discount_model->eventid] = $inputArray['eventId'];
        $where[$this->ci->Discount_model->deleted] = 0;
        if (isset($inputArray['discountId']) && $inputArray['discountId'] != '') {
            $where[$this->ci->Discount_model->id] = $inputArray['discountId'];
        }
        if (isset($inputArray['code']) && $inputArray['code'] != '') {
            $where[$this->ci->Discount_model->code] = $inputArray['code'];
        }
        if (isset($inputArray['type']) && $inputArray['type'] != '') {
            $where[$this->ci->Discount_model->type] = $inputArray['type'];
        }
        if (isset($inputArray['id']) && $inputArray['id'] != '') {
            $where[$this->ci->Discount_model->id] = $inputArray['id'];
        }
        $this->ci->Discount_model->setWhere($where);
        $allDiscountList = $this->ci->Discount_model->get();
        if (count($allDiscountList) == 0) {
            $output = parent::createResponse(TRUE, ERROR_NO_DISCOUNT, STATUS_OK, 0, 'discountList', array());
            return $output;
        }
        $output = parent::createResponse(TRUE, array(), STATUS_OK, count($allDiscountList), 'discountList', $allDiscountList);
        return $output;
    }

    public function updateDiscountStatus($inputArray){
       $allDiscountList=$this->getDiscountList($inputArray);
        if($allDiscountList['response']['total'] > 0){
            $this->ci->Discount_model->resetVariable();
            foreach($allDiscountList['response']['discountList'] as $discount){
                if(($discount['enddatetime']<=currentDateTime())||(($inputArray['type']=='normal')?($discount['totalused']>$discount['usagelimit']):0)){//Both dates are in utc format only
                    $updateDiscount[$this->ci->Discount_model->status] = 0;
                }else if(($discount['enddatetime']>=currentDateTime())&&(($inputArray['type']=='normal')?($discount['totalused']<=$discount['usagelimit']):1)){
                     $updateDiscount[$this->ci->Discount_model->status] = 1;
                }
                $this->ci->Discount_model->setInsertUpdateData($updateDiscount);
                $where[$this->ci->Discount_model->eventid] = $inputArray['eventId'];
                $where[$this->ci->Discount_model->id] = $discount['id'];
                $where[$this->ci->Discount_model->type] = $discount['type'];
                $this->ci->Discount_model->setWhere($where);
                $discountId = $this->ci->Discount_model->update_data();            
            }
        }       
    }
    
    //Update the discount related data
    public function update($inputArray) {
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('discountName', 'Discount Name', 'required_strict|namerule|max_length[25]');
        $this->ci->form_validation->set_rules('eventId', 'Event Id', 'is_natural_no_zero|required_strict');
        $this->ci->form_validation->set_rules('discountStartDate', 'Discount Start Date', 'required_strict');
        $this->ci->form_validation->set_rules('discountStartTime', 'Discount Start Time', 'required_strict|specialTime');
        $this->ci->form_validation->set_rules('discountEndDate', 'Discount End Date', 'required_strict');
        $this->ci->form_validation->set_rules('discountEndTime', 'Discount End Time', 'required_strict|specialTime');
        $this->ci->form_validation->set_rules('discountValue', 'Discount Value', 'required_strict|numeric');
        $this->ci->form_validation->set_rules('amountType', 'Amount Type', 'required_strict|amountType');
        if ($inputArray['type'] == 'normal') {
            $this->ci->form_validation->set_rules('usageLimit', 'Usage Limit', 'numeric|required_strict|max_length[4]');
            $this->ci->form_validation->set_rules('discountCode', 'discount Code', 'required_strict|max_length[25]');
        }
        if ($inputArray['type'] == 'bulk') {
            $this->ci->form_validation->set_rules('ticketsFrom', 'Tickets From', 'is_natural_no_zero|required_strict');
            $this->ci->form_validation->set_rules('ticketsUpto', 'Tickets Upto', 'is_natural_no_zero');
        }
        $this->ci->form_validation->set_rules('type', 'Discount Type', 'required_strict|discountType');
        $this->ci->form_validation->set_rules('ticketIds', 'Ticket Id Array', 'required_strict|is_array');               
        if ($this->ci->form_validation->run() === FALSE) {
            $error_messages = $this->ci->form_validation->get_errors('message');
            $output = parent::createResponse(FALSE, $error_messages['message'], STATUS_BAD_REQUEST);
            return $output;
        }
        $startTimeDate['time'] = urldecode($inputArray['discountStartTime']);
        $startTimeDate['date'] = urldecode($inputArray['discountStartDate']);
        $startTime = convertTime(formatToMysqlDate($startTimeDate), $inputArray['eventTimeZoneName']);
        $endTimeDate['time'] = urldecode($inputArray['discountEndTime']);
        $endTimeDate['date'] = urldecode($inputArray['discountEndDate']);
        $endTime = convertTime(formatToMysqlDate($endTimeDate),$inputArray['eventTimeZoneName']);
        if (strtotime($startTime) > strtotime($endTime)) {
            $output['status'] = FALSE;
            $output["response"]["messages"][] = ERROR_EVENT_START_DATE_GREATER;
            $output['statusCode'] = STATUS_INVALID_INPUTS;
            return $output;
        }
        if($inputArray['discountValue'] > 100 && $inputArray['amountType'] == "percentage") {
            $output['status'] = FALSE;
            $output["response"]["messages"][] = ERROR_DISCOUNT_PERCENTAGE_EXCEEDED;
            $output['statusCode'] = STATUS_INVALID_INPUTS;
            return $output;
        }
        $this->ci->Discount_model->resetVariable();
        $updateDiscount[$this->ci->Discount_model->name] = $inputArray['discountName'];
        $updateDiscount[$this->ci->Discount_model->startdatetime] = $startTime;
        $updateDiscount[$this->ci->Discount_model->enddatetime] = $endTime;
        $updateDiscount[$this->ci->Discount_model->type] = $inputArray['type'];
        $updateDiscount[$this->ci->Discount_model->calculationmode] = $inputArray['amountType'];
        $updateDiscount[$this->ci->Discount_model->value] = $inputArray['discountValue'];
        if (isset($inputArray['ticketsFrom'])) {
            $updateDiscount[$this->ci->Discount_model->minticketstobuy] = $inputArray['ticketsFrom'];
        }
        if (isset($inputArray['ticketsUpto'])) {
            $updateDiscount[$this->ci->Discount_model->maxticketstobuy] = $inputArray['ticketsUpto'];
        }
        if (isset($inputArray['usageLimit'])) {
            $updateDiscount[$this->ci->Discount_model->usagelimit] = $inputArray['usageLimit'];
        }
        $discountInput['eventId']=$inputArray['eventId'];
        $discountInput['discountId']=$inputArray['id'];    
        $discountData=$this->getDiscountList($discountInput);
        if($discountData['status'] && $discountData['response']['total']>0){
            if(((formatToMysqlDate($endTimeDate))>=(convertTime(currentDateTime(), $inputArray['eventTimeZoneName'],TRUE))) && (($inputArray['type']=='normal')?($inputArray['usageLimit']>=$discountData['response']['discountList'][0]['totalused']):1)){                
                    $updateDiscount[$this->ci->Discount_model->status] = 1;
            }
        }

        $this->ci->Discount_model->setInsertUpdateData($updateDiscount);
        $where[$this->ci->Discount_model->eventid] = $inputArray['eventId'];
        $where[$this->ci->Discount_model->id] = $inputArray['id'];
        $where[$this->ci->Discount_model->type] = $inputArray['type'];
        $this->ci->Discount_model->setWhere($where);
        $discountId = $this->ci->Discount_model->update_data(); //Inserting into table and getting inserted id
        if ($discountId) {
            $this->ticketDiscountHandler = new Ticketdiscount_handler();
            $updateTicketDiscount = $this->ticketDiscountHandler->manipulateTicketDiscount($inputArray);
            if ($updateTicketDiscount['status']) {
                $output = parent::createResponse(TRUE, SUCCESS_DISCOUNT_UPDATED, STATUS_UPDATED);
                return $output;
            } else {
                return $updateTicketDiscount;
            }
        }
        $output = parent::createResponse(FALSE, ERROR_SOMETHING_WENT_WRONG, STATUS_BAD_REQUEST);
        return $output;
    }

    //Deleting the discount record
    public function deleteDiscount($inputArray) {
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('eventId', 'event id', 'required_strict|is_natural_no_zero');

        if (!empty($inputArray) && $this->ci->form_validation->run() == FALSE) {
            $response = $this->ci->form_validation->get_errors('message');
            $output = parent::createResponse(FALSE, $response['message'], STATUS_BAD_REQUEST);
            return $output;
        }
        $this->ci->Discount_model->resetVariable();
        $where[$this->ci->Discount_model->eventid] = $inputArray['eventId'];
        $where[$this->ci->Discount_model->id] = $inputArray['id'];
        $where[$this->ci->Discount_model->type] = $inputArray['type'];
        $this->ci->Discount_model->setWhere($where);
        $deleteRecord[$this->ci->Discount_model->deleted] = 1;
        $this->ci->Discount_model->setInsertUpdateData($deleteRecord);
        $deleteStatus = $this->ci->Discount_model->update_data();
        if ($deleteStatus) {
            $output = parent::createResponse(TRUE, SUCCESS_DELETED_DISCOUNT, STATUS_OK);
            return $output;
        } else {
            $output = parent::createResponse(FALSE, ERROR_SOMETHING_WENT_WRONG, STATUS_SERVER_ERROR);
            return $output;
        }
    }

    public function discountCodeCheck($inputArray) {
        $this->ci->Discount_model->resetVariable();
        $selectInput['id'] = $this->ci->Discount_model->id;
        $this->ci->Discount_model->setSelect($selectInput);
        $where[$this->ci->Discount_model->eventid] = $inputArray['eventId'];
        $where[$this->ci->Discount_model->type] = $inputArray['type'];
        $where[$this->ci->Discount_model->code] = $inputArray['discountCode'];
        $where[$this->ci->Discount_model->deleted] = 0;
        $where[$this->ci->Discount_model->status] = 1;
        $this->ci->Discount_model->setWhere($where);
        $DiscountCodeCheck = $this->ci->Discount_model->get();
        //echo $this->ci->db->last_query();
        if (count($DiscountCodeCheck) > 0) {
            $error_messages = "The Discount code is already used for this event";
            $output = parent::createResponse(FALSE, $error_messages, STATUS_BAD_REQUEST);
            return $output;
        } else {
            $output = parent::createResponse(TRUE, array(), STATUS_OK);
            return $output;
        }
    }

    /*
     * Function to get the Discount Details
     *
     * @access	public
     * @param	$inputArray contains
     * 				eventId - integer
     * 				discountId - integer(optional)
     * @return	array
     */

    function getDiscountData($inputArray) {

        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('eventId', 'event id', 'required_strict|is_natural_no_zero');
        $this->ci->form_validation->set_rules('discountId', 'discount id', 'is_array');

        if (!empty($inputArray) && $this->ci->form_validation->run() == FALSE) {
            $response = $this->ci->form_validation->get_errors('message');
            $output['status'] = FALSE;
            $output['response']['messages'] = $response['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $this->ci->Discount_model->resetVariable();
        $selectData['id'] = $this->ci->Discount_model->id;
        $selectData['name'] = $this->ci->Discount_model->name;
        $selectData['type'] = $this->ci->Discount_model->type;
        $selectData['calculationmode'] = $this->ci->Discount_model->calculationmode;
        $selectData['value'] = $this->ci->Discount_model->value;
        $selectData['minticketstobuy'] = $this->ci->Discount_model->minticketstobuy;
        $selectData['maxticketstobuy'] = $this->ci->Discount_model->maxticketstobuy;
        $selectData['usagelimit'] = $this->ci->Discount_model->usagelimit;
        $selectData['code'] = $this->ci->Discount_model->code;
        $selectData['totalused'] = $this->ci->Discount_model->totalused;

        $this->ci->Discount_model->setSelect($selectData);
        $where[$this->ci->Discount_model->eventid] = $inputArray['eventId'];
        if (isset($inputArray['discountId']) && count($inputArray['discountId']) > 0) {
            $whereIn[$this->ci->Discount_model->id] = $inputArray['discountId'];
        }
        if (isset($inputArray['discountCode']) && count($inputArray['discountCode']) > 0) {
            $where[$this->ci->Discount_model->code] =  "'".$inputArray['discountCode']."'";
            $where[$this->ci->Discount_model->type] = "'normal'";
        }
        if (isset($inputArray['discountType']) && $inputArray['discountType'] != '') {
            $where[$this->ci->Discount_model->type] = "'".$inputArray['discountType']."'";
        }
        // Condition for un expired Discounts
        $where[$this->ci->Discount_model->startdatetime . ' <= '] = "'".currentDateTime()."'";
        $where[$this->ci->Discount_model->enddatetime . ' >= '] = "'".currentDateTime()."'";
        $where[$this->ci->Discount_model->deleted] = 0;
        $where[$this->ci->Discount_model->status] = 1;
        if(isset($inputArray['pageType']) && $inputArray['pageType']=='eventdetail'){
            $where[$this->ci->Discount_model->totalused . ' < '] = $this->ci->Discount_model->usagelimit;
            $this->ci->Discount_model->setRecords(1);
        }

        $this->ci->Discount_model->setWhere($where);
        //$this->ci->Discount_model->setOrWhere($setOrWhere,' and ');
        $this->ci->Discount_model->setWhereIns($whereIn);
        $discountDataArray = $this->ci->Discount_model->get(false);
        //echo $this->ci->db->last_query();exit;
        if ($discountDataArray) {
            $output['status'] = TRUE;
            $output['response']['discountList'] = $discountDataArray;
            $output['response']['total'] = 1;
            $output['response']['messages'] = array();
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

    public function getDiscountCountByEventId($inputArray) {
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('eventid', 'eventid', 'required_strict|is_natural_no_zero');
        if ($this->ci->form_validation->run() === FALSE) {
            $errorMessages = $this->ci->form_validation->get_errors();
            $output['status'] = FALSE;
            $output['response']['messages'] = $errorMessages['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $eventId = $inputArray['eventid'];
        $this->ci->Discount_model->resetVariable();
        $selectData['countdiscount'] = 'COUNT(' . $this->ci->Discount_model->id . ')';
        $this->ci->Discount_model->setSelect($selectData);
        $where[$this->ci->Discount_model->startdatetime . ' <= '] = currentDateTime();
        $where[$this->ci->Discount_model->enddatetime . ' >= '] = currentDateTime();
        $where[$this->ci->Discount_model->deleted] = 0;
        $where[$this->ci->Discount_model->status] = 1;
        $where[$this->ci->Discount_model->eventid] = $eventId;
        $where[$this->ci->Discount_model->type] = 'normal';
        $this->ci->Discount_model->setWhere($where);
        $discountDataArray = $this->ci->Discount_model->get();
        if (count($discountDataArray) > 0) {
            $output['status'] = TRUE;
            $output['response']['discountResponse'] = array('discountCount' => $discountDataArray[0]['countdiscount']);
            $output['response']['total'] = 1;
            $output['response']['messages'] = array();
            $output['statusCode'] = STATUS_OK;
            return $output;
        }
        $output['status'] = TRUE;
        $output['response']['discountResponse'] = array('discountCount' => 0);
        $output['response']['messages'][] = ERROR_NO_DATA;
        $output['response']['total'] = 0;
        $output['statusCode'] = STATUS_OK;
        return $output;
    }
 /*
     * Function to get the discount Id
     *
     * @access	public
     * @param	$inputArray contains
     * 				eventId - integer
     * 				discountCode - string

     * @return	array
     */
   public function getDisountId($inputArray) {
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('eventId', 'event id', 'required_strict|is_natural_no_zero');
        if ($this->ci->form_validation->run() == FALSE) {
            $response = $this->ci->form_validation->get_errors('message');
            $output['status'] = FALSE;
            $output['response']['messages'] = $response['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        
        //promoter Id is set 
        $discountArray=array();
        if(isset($inputArray['promoterId'])){
            $promoterdiscountHandler=new Offlinepromoterdiscounts_handler();
            $list=$promoterdiscountHandler->getPrometerEvetTicketDiscounts($inputArray);
           
            if($list['response']['total'] > 0){
                $listArray=$list['response']['prometerDiscountList'];
                foreach($listArray as $key => $value){
                    $discountArray[]=$value['discountid'];
                }
                
            }
        }
        $where_in=array();
        $this->ci->Discount_model->resetVariable();
        $selectData['id'] = $this->ci->Discount_model->id;
        $selectData['name'] = $this->ci->Discount_model->name;
        $selectData['type'] = $this->ci->Discount_model->type;
        $selectData['totalused'] = $this->ci->Discount_model->totalused;
        $selectData['usagelimit'] = $this->ci->Discount_model->usagelimit;
        $this->ci->Discount_model->setSelect($selectData);
        $where[$this->ci->Discount_model->eventid] = $inputArray['eventId'];
        $where[$this->ci->Discount_model->code] = ($inputArray['discountCode']!='')?$inputArray['discountCode']:'';
        if(isset($inputArray['discountType']) && $inputArray['discountType'] != '') {
            $where[$this->ci->Discount_model->type] = $inputArray['discountType'];
        }
        if(isset($inputArray['bulkDiscount']) == TRUE && $inputArray['discountId'] > 0) {
            $where[$this->ci->Discount_model->id] = $inputArray['discountId'];
        }
        if (count($discountArray) > 0) {
            $where_in[$this->ci->Discount_model->id] = $discountArray;
            $this->ci->Discount_model->setWhereIns($where_in);
        }
         
        
        $where[$this->ci->Discount_model->startdatetime . ' <= '] = currentDateTime();
        $where[$this->ci->Discount_model->enddatetime . ' >= '] = currentDateTime();
        $where[$this->ci->Discount_model->deleted] = 0;
        $where[$this->ci->Discount_model->status] = 1;
        $this->ci->Discount_model->setWhere($where);
        $discountDataArray = $this->ci->Discount_model->get();
        if ($discountDataArray) {
            $output['status'] = TRUE;
            $output['response']['discountCode'] = $discountDataArray;
            $output['response']['total'] = 1;
            $output['response']['messages'] = array();
            $output['statusCode'] = STATUS_OK;
            return $output;
        } else {
            $output['status'] = TRUE;
            $output['response']['messages'][] = ERROR_INVALID_DISCOUNT_CODE;
            $output['response']['total'] = 0;
            $output['statusCode'] = STATUS_OK;
            return $output;
        }
    }


    /*
     * Function to update the discount usage count
     *
     * @access	public
     * @param	$inputArray contains
     * 				eventId - integer
     * 				discountId - integer
     * 				totalused - integer
     * @return	array
     */

    public function updateDiscountUsage($inputArray) {

        $this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('eventId', 'event Id', 'required_strict|is_natural_no_zero');
        $this->ci->form_validation->set_rules('discountId', 'discount Id', 'required_strict|is_natural_no_zero');
        $this->ci->form_validation->set_rules('totalused', 'totalused', 'required_strict|is_natural_no_zero');
        if ($this->ci->form_validation->run() === FALSE) {
            $error_messages = $this->ci->form_validation->get_errors('message');
            $output = parent::createResponse(FALSE, $error_messages, STATUS_BAD_REQUEST);
            return $output;
        }
        $this->ci->Discount_model->resetVariable();
        if (isset($inputArray['totalused'])) {
            $updateDiscount[$this->ci->Discount_model->totalused] = $inputArray['totalused'];
        }
        $this->ci->Discount_model->setInsertUpdateData($updateDiscount);
        $where[$this->ci->Discount_model->eventid] = $inputArray['eventId'];
        $where[$this->ci->Discount_model->id] = $inputArray['discountId'];
        $where[$this->ci->Discount_model->type] = $inputArray['type'];
        $this->ci->Discount_model->setWhere($where);
        $updateStatus = $this->ci->Discount_model->update_data(); //Inserting into table and getting inserted id
        if ($updateStatus) {
            $output = parent::createResponse(TRUE, SUCCESS_DISCOUNT_UPDATED, STATUS_UPDATED);
            return $output;
        }
        $output = parent::createResponse(FALSE, ERROR_SOMETHING_WENT_WRONG, STATUS_BAD_REQUEST);
        return $output;
    }


}

?>
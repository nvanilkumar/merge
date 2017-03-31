<?php

/**
 * event payment gateways related business logic will be defined in this class
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
require_once (APPPATH . 'handlers/event_handler.php');
require_once(APPPATH . 'handlers/paymentgateway_handler.php');
//require_once (APPPATH . 'handlers/ticketdiscount_handler.php');

class EventpaymentGateway_handler extends Handler {

    var $ci;
    var $eventHandler,$paymentgatewayHandler;
    public function __construct() {
        parent::__construct();
        $this->ci = parent::$CI;
        $this->ci->load->model('EventpaymentGateway_model');
    }

    /*
     * Function to get the commonfield Details
     *
     * @access public
     * 
     */

    function getPaymentgatewayByEventId($inputArray) {
        $this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('eventId', 'event id', 'required_strict|is_natural_no_zero');

        if ($this->ci->form_validation->run() == FALSE) {
            $response = $this->ci->form_validation->get_errors('message');
            $output['status'] = FALSE;
            $output['response']['messages'] = $response['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $this->ci->EventpaymentGateway_model->resetVariable();
        $selectInput[$this->ci->EventpaymentGateway_model->paymentgatewayid] = $this->ci->EventpaymentGateway_model->paymentgatewayid;
        $selectInput[$this->ci->EventpaymentGateway_model->status] = $this->ci->EventpaymentGateway_model->status;
        $selectInput[$this->ci->EventpaymentGateway_model->gatewaytext] = $this->ci->EventpaymentGateway_model->gatewaytext;
        $selectInput[$this->ci->EventpaymentGateway_model->extraparams] = $this->ci->EventpaymentGateway_model->extraparams;

        $this->ci->EventpaymentGateway_model->setSelect($selectInput);

        $where[$this->ci->EventpaymentGateway_model->eventid] = $inputArray['eventId'];
        
        if(isset($inputArray['paymentGatewayId']) && $inputArray['paymentGatewayId'] > 0) {
            $where[$this->ci->EventpaymentGateway_model->paymentgatewayid] = $inputArray['paymentGatewayId'];
        }
        
        $where[$this->ci->EventpaymentGateway_model->deleted] = 0;
        if(isset($inputArray['gatewayStatus']) && $inputArray['gatewayStatus']) {
            $where[$this->ci->EventpaymentGateway_model->status] = 1;
        }
        
        $this->ci->EventpaymentGateway_model->setWhere($where);

        $orderBy[] = $this->ci->EventpaymentGateway_model->paymentgatewayid;
        
        $groupBy[] = $this->ci->EventpaymentGateway_model->paymentgatewayid;
        $this->ci->EventpaymentGateway_model->setGroupBy($groupBy);
        $this->ci->EventpaymentGateway_model->setOrderBy($orderBy);

        $eventGatewaysResponse = $this->ci->EventpaymentGateway_model->get();
        if (count($eventGatewaysResponse) == 0) {
            $output = parent::createResponse(TRUE, ERROR_NO_REFUNDS, STATUS_OK, 0);
            return $output;
        }
        $output = parent::createResponse(TRUE, array(), STATUS_OK, count($eventGatewaysResponse), 'eventPaymentGatewayList', $eventGatewaysResponse);
        return $output;
    }
    public function updateEventPaymentGatewayDetail($inputArray) {
        $this->eventHandler = new Event_handler();
        $this->ci->EventpaymentGateway_model->resetVariable();
        $eventPaymentGateways = $this->eventHandler->getEventPaymentGateways($inputArray);
        //Storing event gateway Ids for this event 
        $eventGateWayIds=array();
        foreach($eventPaymentGateways['response']['gatewayList'] as $key=>$value){
            $eventGateWayIds[]=$value['paymentgatewayid'];
        }
        if(!isset($inputArray['gateways'])){
            $output=parent::createResponse(FALSE, ERROR_NO_GATEWAY, STATUS_BAD_REQUEST);
            return $output;
        }
        $this->ci->EventpaymentGateway_model->resetVariable();
        //Adding payment gateway to the event         
        if (count($inputArray['gateways']) > 0) {
            $insertUpdateArraySelected[$this->ci->EventpaymentGateway_model->status] = 1;
            $whereSelected[$this->ci->EventpaymentGateway_model->eventid] = $inputArray['eventId'];
            $this->ci->EventpaymentGateway_model->setWhere($whereSelected);
            $whereInArraySelected[$this->ci->EventpaymentGateway_model->paymentgatewayid] = $inputArray['gateways'];
            $this->ci->EventpaymentGateway_model->setWhereIns($whereInArraySelected);
            $this->ci->EventpaymentGateway_model->setInsertUpdateData($insertUpdateArraySelected);
            $updateStatus = $this->ci->EventpaymentGateway_model->update_data();
        

        //Unselecting payment gateways for the event if it is unchecked       
        if (count($eventGateWayIds) > 0) {
            $unselectedPaymentIds = array_diff($eventGateWayIds, $inputArray['gateways']);
            if (count($unselectedPaymentIds) > 0) {               
                $updatePaymentGatewayUnselected[$this->ci->EventpaymentGateway_model->status] = 0;
                $whereUnselected[$this->ci->EventpaymentGateway_model->eventid] = $inputArray['eventId'];
                $this->ci->EventpaymentGateway_model->setWhere($whereUnselected);
                $whereInArrayUnselected[$this->ci->EventpaymentGateway_model->paymentgatewayid] = $unselectedPaymentIds;
                $this->ci->EventpaymentGateway_model->setWhereIns($whereInArrayUnselected);
                $this->ci->EventpaymentGateway_model->setInsertUpdateData($updatePaymentGatewayUnselected);
                $updateStatus = $this->ci->EventpaymentGateway_model->update_data();
            }
        }
        }
        $output = parent::createResponse(TRUE, SUCCESS_UPDATED_PAYMENT_GATEWAY, STATUS_CREATED);
        return $output;
    }
    
        /**
     * To Inert the event level default Payment gateway list  
     * 
     * @param type $inputArray
     * @return int
     */
    public function insertEventDefaultPaymentEventList($inputArray) {
        $validationStatus = $this->validateEventDefaultPaymentEventList($inputArray);
        if ($validationStatus['error']) {
            $output['status'] = FALSE;
            $output['response']['messages'] = $validationStatus['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $this->ci->EventpaymentGateway_model->resetVariable();
        $this->paymentgatewayHandler=new Paymentgateway_handler();
        $paymentList = $this->paymentgatewayHandler->getDefaultPaymentGatewayList();

        if ($paymentList['response']['total'] > 0) {
            $defaultArray = array();
            foreach ($paymentList['response']['gatewayList'] as $fieldIndex => $fieldValue) {

                $defaultArray[$fieldIndex]['eventid'] = $inputArray['eventId'];
                $defaultArray[$fieldIndex]['paymentgatewayid'] = $fieldValue['id'];
                $defaultArray[$fieldIndex]['status'] = $fieldValue['default'];
            }
            $this->ci->EventpaymentGateway_model->setInsertUpdateData($defaultArray);

            $response = $this->ci->EventpaymentGateway_model->insertMultiple_data();
        }
        if ($response) {
            $output['status'] = TRUE;
            $output['response']['affectedRows'] = $response;
            $output["response"]["messages"] = array();
            $output['statusCode'] = STATUS_CREATED;
            return $output;
        }
        $output['status'] = FALSE;
        $output["response"]["messages"][] = ERROR_SOMETHING_WENT_WRONG;
        $output['statusCode'] = STATUS_SERVER_ERROR;
        return $output;
    }

    public function validateEventDefaultPaymentEventList($inputs) {
        $errorMessages = array();
        $this->ci->form_validation->pass_array($inputs);
        $this->ci->form_validation->set_rules('eventId', 'Event ID', 'is_natural_no_zero|required_strict');

        if ($this->ci->form_validation->run() === FALSE) {
            $errorMessages = $this->ci->form_validation->get_errors();
            return $errorMessages;
        }
        $errorMessages['error'] = FALSE;
        return $errorMessages;
    }

}

?>
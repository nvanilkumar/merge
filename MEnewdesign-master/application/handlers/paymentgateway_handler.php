<?php

/**
 * payment gateway related business logic will be defined in this class
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

//require_once (APPPATH . 'handlers/ticketdiscount_handler.php');

class Paymentgateway_handler extends Handler {

    var $ci;

    public function __construct() {
        parent::__construct();
        $this->ci = parent::$CI;
        $this->ci->load->model('Paymentgateway_model');
    }

    /*
     * Function to get the commonfield Details
     *
     * @access public
     * 
     */

    function getPaymentgatewayList($inputArray,$webApiStatus=FALSE) {
        /* To get the payment gateway data starts here */
        $this->ci->Paymentgateway_model->resetVariable();
        $selectGatewayInput[$this->ci->Paymentgateway_model->id] = $this->ci->Paymentgateway_model->id;
        $selectGatewayInput[$this->ci->Paymentgateway_model->name] = $this->ci->Paymentgateway_model->name;
        if(!$webApiStatus){
          $selectGatewayInput[$this->ci->Paymentgateway_model->merchantid] = $this->ci->Paymentgateway_model->merchantid;
          $selectGatewayInput[$this->ci->Paymentgateway_model->hashkey] = $this->ci->Paymentgateway_model->hashkey;
          $selectGatewayInput[$this->ci->Paymentgateway_model->extraparams] = $this->ci->Paymentgateway_model->extraparams;
        }
        $selectGatewayInput[$this->ci->Paymentgateway_model->description] = $this->ci->Paymentgateway_model->description;        
        $selectGatewayInput[$this->ci->Paymentgateway_model->gatewaytext] = $this->ci->Paymentgateway_model->gatewaytext;
        
        $this->ci->Paymentgateway_model->setSelect($selectGatewayInput);
        
        if(isset($inputArray['gatewayId']) && $inputArray['gatewayId'] > 0) {
            $whereGateway[$this->ci->Paymentgateway_model->id] = $inputArray['gatewayId'];
        }
        $whereGateway[$this->ci->Paymentgateway_model->deleted] = 0;
        $whereGateway[$this->ci->Paymentgateway_model->type] = 'gateway';
        $this->ci->Paymentgateway_model->setWhere($whereGateway);
        
        if(is_array($inputArray['gatewayIds']) && count($inputArray['gatewayIds']) > 0) {
            $whereInGateway[$this->ci->Paymentgateway_model->id] = $inputArray['gatewayIds'];
            $this->ci->Paymentgateway_model->setWhereIns($whereInGateway);
        }
        
        $eventGateways = $this->ci->Paymentgateway_model->get();
        if (count($eventGateways) == 0) {
            $output = parent::createResponse(TRUE, ERROR_NO_REFUNDS, STATUS_OK, 0);
            return $output;
        }
        $output = parent::createResponse(TRUE, array(), STATUS_OK, count($eventGateways), 'paymentgatewayList', $eventGateways);
        return $output;
    }
    
    /**
     * To get the default paymnt gateways for a event at the time of insert
     *       paymentgateway
     */
    public function getDefaultPaymentGatewayList() {
        $this->ci->Paymentgateway_model->resetVariable();
        $selectInput['id'] = $this->ci->Paymentgateway_model->id;
        $selectInput['name'] = $this->ci->Paymentgateway_model->name;
        $selectInput['default'] = $this->ci->Paymentgateway_model->default;
        $selectInput['status'] = $this->ci->Paymentgateway_model->status;

        $this->ci->Paymentgateway_model->setSelect($selectInput);
        $where[$this->ci->Paymentgateway_model->deleted] = 0;
        $where[$this->ci->Paymentgateway_model->status] = 1;
        $where[$this->ci->Paymentgateway_model->type] = "gateway";
        $this->ci->Paymentgateway_model->setWhere($where);
        $gateWayResponse = $this->ci->Paymentgateway_model->get();

        if (count($gateWayResponse) == 0) {
            $output['status'] = TRUE;
            $output['response']['messages'][] = ERROR_NO_DATA;
            $output['response']['total'] = 0;
            $output['statusCode'] = STATUS_OK;
            return $output;
        }
        $output['status'] = TRUE;
        $output['response']['gatewayList'] = $gateWayResponse;
        $output['response']['total'] = count($gateWayResponse);
        $output['response']['message'] = array();
        $output['statusCode'] = STATUS_OK;
        return $output;
    }

    public function getPaymentGatewayDetail($inputArray){
            $this->ci->Paymentgateway_model->resetVariable();
            $selectPaymentGateway[$this->ci->Paymentgateway_model->id] = $this->ci->Paymentgateway_model->id;
            $wherePaymentGatewayCondition[$this->ci->Paymentgateway_model->name] = $inputArray['paymentGateway'];
            $wherePaymentGatewayCondition[$this->ci->Paymentgateway_model->status] = 1;
            $wherePaymentGatewayCondition[$this->ci->Paymentgateway_model->deleted] = 0;

            $this->ci->Paymentgateway_model->setSelect($selectPaymentGateway);
            $this->ci->Paymentgateway_model->setWhere($wherePaymentGatewayCondition);

            $paymentGatewayData = $this->ci->Paymentgateway_model->get();
            if ($paymentGatewayData == '' || count($paymentGatewayData) == 0) {
                $output['status'] = FALSE;
                $output['response']['messages'][] = ERROR_PAYMENT_GATEWAY;
                $output['statusCode'] = STATUS_BAD_REQUEST;
                return $output;
            }else{
                $output['status'] = TRUE;
                $output['response']['messages'][] = ERROR_PAYMENT_GATEWAY;
                $output['response']['paymentGateways']=$paymentGatewayData;
                $output['statusCode'] = STATUS_OK;
                return $output;                
            }
    }
    public function getPaymentGatewayDetailsById($inputArray){
            $this->ci->Paymentgateway_model->resetVariable();
            $selectPaymentGateway[$this->ci->Paymentgateway_model->name] = $this->ci->Paymentgateway_model->name;
            $wherePaymentGatewayCondition[$this->ci->Paymentgateway_model->id] = $inputArray['paymentgatewayid'];
            $wherePaymentGatewayCondition[$this->ci->Paymentgateway_model->status] = 1;
            $wherePaymentGatewayCondition[$this->ci->Paymentgateway_model->deleted] = 0;

            $this->ci->Paymentgateway_model->setSelect($selectPaymentGateway);
            $this->ci->Paymentgateway_model->setWhere($wherePaymentGatewayCondition);

            $paymentGatewayData = $this->ci->Paymentgateway_model->get();
            if ($paymentGatewayData == '' || count($paymentGatewayData) == 0) {
                $output['status'] = FALSE;
                $output['response']['messages'][] = ERROR_PAYMENT_GATEWAY;
                $output['statusCode'] = STATUS_BAD_REQUEST;
                return $output;
            }else{
                $output['status'] = TRUE;
                //$output['response']['messages'][] = ERROR_PAYMENT_GATEWAY;
                $output['response']['paymentGateways']=$paymentGatewayData;
                $output['statusCode'] = STATUS_OK;
                return $output;                
            }
    }
}

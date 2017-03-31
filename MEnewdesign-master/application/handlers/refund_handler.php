<?php

/**
 * refund related business logic will be defined in this class
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

class Refund_handler extends Handler {

    var $ci;

    public function __construct() {
        parent::__construct();
        $this->ci = parent::$CI;
        $this->ci->load->model('Refund_model');
    }

    /*
     * Function to get the refund Details
     *
     * @access public
     * 
     */

    function getRefundList($inputArray) {
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('eventsignupids', 'eventsignupids', 'required_strict|is_array');

        if ($this->ci->form_validation->run() == FALSE) {
            $response = $this->ci->form_validation->get_errors('message');
            $output['status'] = FALSE;
            $output['response']['messages'] = $response['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $eventSignupIds = $inputArray['eventsignupids'];
        $getSum = isset($inputArray['sum']) ? $inputArray['sum'] : false;
        $selectData['id'] = $this->ci->Refund_model->id;
        $selectData['eventsignupid'] = $this->ci->Refund_model->eventsignupid;
        $selectData['totalrefundamount'] = 'SUM(' . $this->ci->Refund_model->amount . ')';
        $this->ci->Refund_model->setSelect($selectData);
        $where[$this->ci->Refund_model->deleted] = 0;
        $this->ci->Refund_model->setWhere($where);
        $whereIn[$this->ci->Refund_model->eventsignupid] = $eventSignupIds;
        $this->ci->Refund_model->setWhereIns($whereIn);
        if (!$getSum) {
            $groupBy[] = $this->ci->Refund_model->eventsignupid;
            $this->ci->Refund_model->setGroupBy($groupBy);
        }
        $refundList = $this->ci->Refund_model->get();
        if (count($refundList) == 0) {
            $output = parent::createResponse(TRUE, ERROR_NO_REFUNDS, STATUS_OK, 0);
            return $output;
        }
        $output = parent::createResponse(TRUE, array(), STATUS_OK, count($refundList), 'refundList', $refundList);
        return $output;
    }

}

?>
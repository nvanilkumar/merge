<?php

/**
 * Payment Source Data will be defined in this class
 * @package		CodeIgniter
 * @author		Qison  Dev Team
 * @param		eventId - required
 * @copyright	Copyright (c) 2015, MeraEvents.
 * @Version		Version 1.0
 * @Since       Class available since Release Version 1.0
 * @Created     16-10-2015
 * @Last Modified 16-10-2015
 */
require_once (APPPATH . 'handlers/handler.php');

class Paymentsource_handler extends Handler {

    var $ci;

    public function __construct() {
        parent::__construct();
        $this->ci = parent::$CI;
        $this->ci->load->model('Paymentsource_model');
    }

    public function getPaymentSourceId($inputArray) {

        $this->ci->Paymentsource_model->resetVariable();
        $selectInput['id'] = $this->ci->Paymentsource_model->id;
        $selectInput['name'] = $this->ci->Paymentsource_model->name;
        $selectInput['eventid'] = $this->ci->Paymentsource_model->eventid;
        $this->ci->Paymentsource_model->setSelect($selectInput);
        $where[$this->ci->Paymentsource_model->deleted] = 0;
        $where[$this->ci->Paymentsource_model->status] = 1;
        if (isset($inputArray['paymentSourceName'])) {
            $where[$this->ci->Paymentsource_model->name] = $inputArray['paymentSourceName'];
        }
        $this->ci->Paymentsource_model->setWhere($where);
        $paymentSource = $this->ci->Paymentsource_model->get();
        if (count($paymentSource) > 0) {
            $output['status'] = TRUE;
            $output['response']['paymentSource'] = $paymentSource;
            $output['response']['total'] = 1;
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

?>
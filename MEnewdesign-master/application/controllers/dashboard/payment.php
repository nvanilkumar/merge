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
 * @Created     04-08-2015
 * @Last Modified On  04-08-2015
 * @Last Modified By  Qison  Dev Team
 */
require_once(APPPATH . 'handlers/payment_handler.php');
require_once(APPPATH . 'handlers/event_handler.php');

class Payment extends CI_Controller {

    var $paymentHandler;
    var $eventHandler;


    public function __construct() {
        parent::__construct();
        $this->paymentHandler = new Payment_handler();
        $this->eventHandler = new Event_handler();
    }

    public function receipts($eventId) {
        $inputArray['eventId'] = $eventId;
        $data['eventTitle'] = commonHelperGetEventName($eventId);
        $payment = $this->paymentHandler->receipts($inputArray);
        $data['paymentreceipt'] = $payment['response']['paymentReceipt'];
        $data['content'] = 'payment_receipt_view';
        $data['pageName'] = 'Payment receipt';
        $data['pageTitle'] = 'MeraEvents | Payment receipts';
        $data['hideLeftMenu'] = 0;
        $this->load->view('templates/dashboard_template', $data);
    }

    public function download() {
        $file = $this->input->get("filePath");
        commonHelperDownload($file);
    }
    public function refund($eventId) {
        $inputArray['eventId'] = $eventId;
        $data['eventName'] = commonHelperGetEventName($eventId);        
        $refundOutput = $this->paymentHandler->getRefundTranscationData($inputArray);
        $data['headerFields'] =$refundOutput['headerFields'];
        $data['eventId'] = $eventId;
        $data['refundOutput']=$refundOutput['refundOutput'];  
        $data['grandTotal']=isset($refundOutput['grandTotal'])?$refundOutput['grandTotal']:array();
        $data['content'] = 'refund_view';
        $data['pageName'] = 'Refund';
        $data['pageTitle'] = 'MeraEvents | Refund';
        $data['hideLeftMenu'] = 0;
        $data['jsArray'] = array(
            $this->config->item('js_public_path') . 'dashboard/payment'  );
        $this->load->view('templates/dashboard_template', $data);
    }
}

?>
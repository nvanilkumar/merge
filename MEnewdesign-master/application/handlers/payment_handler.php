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
require_once(APPPATH . 'handlers/handler.php');
require_once(APPPATH . 'handlers/file_handler.php');
require_once(APPPATH . 'handlers/event_handler.php');
require_once(APPPATH . 'handlers/eventsignup_handler.php');
require_once(APPPATH . 'handlers/eventsignupticketdetail_handler.php');
require_once(APPPATH . 'handlers/reports_handler.php');

class Payment_handler extends Handler {

    var $ci;
    var $fileHandler;
    var $eventHandler;
    var $eventSignupHandler;
    var $reportsHandler;

    public function __construct() {
        parent::__construct();
        $this->ci = parent::$CI;
        $this->fileHandler = new File_handler();
        $this->ci->load->model('Settlement_model');
    }

    public function receipts($inputArray) {
        $this->ci->form_validation->pass_array($inputArray);
        if ($this->ci->form_validation->run('eventId') === FALSE) {
            $error_messages = $this->ci->form_validation->get_errors('message');
            $output = parent::createResponse(FALSE, $error_messages, STATUS_BAD_REQUEST);
            return $output;
        }
        $this->ci->Settlement_model->resetVariable();
        $selectInput['id'] = $this->ci->Settlement_model->id;
        $selectInput['eventid'] = $this->ci->Settlement_model->eventid;
        $selectInput['paymentadvicefileid'] = $this->ci->Settlement_model->paymentadvicefileid;
        $selectInput['chequefileid'] = $this->ci->Settlement_model->chequefileid;
        $selectInput['cyberrecieptfileid'] = $this->ci->Settlement_model->cyberrecieptfileid;
        $selectInput['note'] = $this->ci->Settlement_model->note;
        $selectInput['netamount'] = $this->ci->Settlement_model->netamount;
        $selectInput['amountpaid'] = $this->ci->Settlement_model->amountpaid;
        $selectInput['paymentdate'] = $this->ci->Settlement_model->paymentdate;
        $selectInput['paymenttype'] = $this->ci->Settlement_model->paymenttype;
        $selectInput['currencyid'] = $this->ci->Settlement_model->currencyid;
        $selectInput['status'] = $this->ci->Settlement_model->status;
        $selectInput['cts'] = $this->ci->Settlement_model->cts;
        $this->ci->Settlement_model->setSelect($selectInput);
        $where[$this->ci->Settlement_model->eventid] = $inputArray['eventId'];
        $where[$this->ci->Settlement_model->status] = (isset($request['status'])) ? $request['status'] : 1;
        $this->ci->Settlement_model->setWhere($where);
        $paymentData = $this->ci->Settlement_model->get();
        if ($paymentData) {
            foreach ($paymentData as $val) {
                $fileIdList[] = $val['paymentadvicefileid'];
                $fileIdList[] = $val['chequefileid'];
                $fileIdList[] = $val['cyberrecieptfileid'];
            }
            $fileidsData = array('id', $fileIdList);
            $fileDatas = $this->fileHandler->getFileData($fileidsData);
            if ($fileDatas['status'] && $fileDatas['response']['total'] > 0) {
                $fileData = commonHelperGetIdArray($fileDatas['response']['fileData']);
                $tempArr = array();
                $fileLoop = 0;
                foreach ($paymentData as $pay) {
                    if ($fileData[$pay['chequefileid']]>0) {

                        $paymentData[$fileLoop]['chequefilePath'] = $this->ci->config->item('images_content_path') . $fileData[$pay['chequefileid']]['path'];
                    }
					if ($fileData[$pay['cyberrecieptfileid']]>0) {

                        $paymentData[$fileLoop]['cyberrecieptfilePath'] = $this->ci->config->item('images_content_path') . $fileData[$pay['cyberrecieptfileid']]['path'];
                    }
					if ($fileData[$pay['paymentadvicefileid']]>0) {

                        $paymentData[$fileLoop]['paymentadvicefilePath'] = $this->ci->config->item('images_content_path') . $fileData[$pay['paymentadvicefileid']]['path'];
                    }
                    $fileLoop++;
                }
				

				
                $output['status'] = TRUE;
                $output['response']['paymentReceipt'] = $paymentData;
                $output['statusCode'] = STATUS_OK;
                return $output;
            } else {
                $output['status'] = FALSE;
                $output["response"]["messages"][] = ERROR_NO_DATA;
                $output['statusCode'] = STATUS_NO_DATA;
                return $output;
            }
        } else {
            $output['status'] = FALSE;
            $output["response"]["messages"][] = ERROR_NO_DATA;
            $output['statusCode'] = STATUS_NO_DATA;
            return $output;
        }
    }

    //Getting all the refund related data
    public function getRefundTranscationData($inputArray) {
        $this->eventSignupHandler = new Eventsignup_handler();
        $this->reportsHandler = new Reports_handler();
        $inputArray['reporttype'] = 'detail';
        $inputArray['eventid'] = $inputArray['eventId'];
        $inputArray['transactiontype'] = 'refund';
        $inputArray['page'] = 1;

        $data['headerFields'] = array();
        $tableHeaderResponse = $this->reportsHandler->getHeaderFields($inputArray);

        if ($tableHeaderResponse['status'] && $tableHeaderResponse['response']['total'] > 0) {
            $data['headerFields'] = $tableHeaderResponse['response']['headerFields'];
        }

        $refundOutput = $this->eventSignupHandler->getDetailDisplayInfo($inputArray);
        $data['refundOutput'] = $refundOutput;
        if ($refundOutput['status'] && $refundOutput['response']['total'] > 0) {
            $grandTotal = $this->reportsHandler->getGrandTotal($inputArray);
            if ($grandTotal['status'] && $grandTotal['response']['total'] > 0) {
                $data['grandTotal'] = $grandTotal['response']['grandTotalResponse'];
            }
        }
        return $data;
    }

}

<?php

/**
 * Event Gallery Data will be defined in this class
 * Getting Banners Related data
 * @package		CodeIgniter
 * @author		Qison  Dev Team
 * @param		eventId - required
 * @copyright	Copyright (c) 2015, MeraEvents.
 * @Version		Version 1.0
 * @Since       Class available since Release Version 1.0
 * @Created     21-07-2015
 * @Last Modified 21-06-2015
 */
require_once (APPPATH . 'handlers/handler.php');

class Orderlog_handler extends Handler {

    var $ci;
    var $orderlogHandler;
    public function __construct() {
        parent::__construct();
        $this->ci = parent::$CI;
        $this->ci->load->library('form_validation');
        $this->ci->load->model('Orderlog_model');
    }

        
    /*
     * Function to get the orderlog data
     *
     * @access	public
     * @param	$inputArray contains
     * 				orderId - alphanumeric
     * @return	array
     */

    public function getOrderlog($request) {

        $output = array();
        $this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($request);
        $this->ci->form_validation->set_rules('orderId', 'order Id', 'required_strict');
        if (!isset($request['call']) && isset($request['userId'])) {
            $this->ci->form_validation->set_rules('userId', 'user Id', 'required_strict');
        }
        if (!empty($request) && $this->ci->form_validation->run() == FALSE) {
            $validationStatus = $this->ci->form_validation->get_errors();
            $output['status'] = FALSE;
            $output['response']['messages'] = $validationStatus['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        } else {
            $this->ci->Orderlog_model->resetVariable();
            $selectInput['data'] = $this->ci->Orderlog_model->data;
            $selectInput['status'] = $this->ci->Orderlog_model->status;
            $selectInput['eventsignup'] = $this->ci->Orderlog_model->eventsignup;
            $selectInput['id'] = $this->ci->Orderlog_model->id;
            $selectInput['orderid'] = $this->ci->Orderlog_model->orderid;
            $selectInput['userid'] = $this->ci->Orderlog_model->userid;            
            $this->ci->Orderlog_model->setSelect($selectInput);
            $where[$this->ci->Orderlog_model->orderid] = $request['orderId'];
            $where[$this->ci->Orderlog_model->deleted] = '0';
            $where[$this->ci->Orderlog_model->status] = '1';
            //fetching Event signup Email template  & not deleted
            if (isset($request['userId']) && !isset($request['call'])) {
                $where[$this->ci->Orderlog_model->userid] = $request['userId'];
            }
            $this->ci->Orderlog_model->setWhere($where);
            $eventsignupsorderlogdata = $this->ci->Orderlog_model->get();
            if (is_array($eventsignupsorderlogdata)) {
                if (count($eventsignupsorderlogdata) > 0) {
                    $output['status'] = TRUE;
                    $output['response']['orderLogData'] = $eventsignupsorderlogdata[0];
                    $output['response']['total'] = count($eventsignupsorderlogdata);
                    $output['statusCode'] = STATUS_OK;
                    return $output;
                } else {
                    $output['status'] = TRUE;
                    $output['response']['messages'][] = ERROR_NO_ORDERLOG_FOUND;;
                    $output['response']['total'] = 0;
                    $output['statusCode'] = STATUS_OK;
                    return $output;
                }
            } else {
                $output['status'] = FALSE;
                $output['response']['messages'][] = ERROR_INTERNAL_DB_ERROR;
                $output['statusCode'] = STATUS_SERVER_ERROR;
                return $output;
            }
        }
    }
    
    public function orderLogUpdate($inputArray) {
        $this->ci->form_validation->reset_form_rules();
        $inputArrayValidate=$inputArray['condition'];
        if(isset($inputArray['update']['data'])){
            $inputArrayValidate['data']=$inputArray['update']['data'];           
        }
        $this->ci->form_validation->pass_array($inputArrayValidate);
        $this->ci->form_validation->set_rules('orderId', 'order id', 'trim|xss_clean|required_strict|alpha_numeric');
        if(isset($updatedOrderlogData['allMandatory']) && $updatedOrderlogData['allMandatory']){
            $this->ci->form_validation->set_rules('userId', 'user Id', 'required_strict');
            $this->ci->form_validation->set_rules('eventSignupId', 'event Signup Id', 'required_strict');
            $this->ci->form_validation->set_rules('data', 'data', 'required_strict');
        }
        if ($this->ci->form_validation->run() == FALSE) {
            $response = $this->ci->form_validation->get_errors();
            $output['status'] = FALSE;
            $output['response']['messages'] = $response['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $this->ci->Orderlog_model->resetVariable();
        
        $updateOrderlogInput[$this->ci->Orderlog_model->orderid] = $inputArray['condition']['orderId'];        
        if (isset($inputArray['condition']['eventSignupId']) && $inputArray['condition']['eventSignupId'] != '') {
            $updateOrderlogInput[$this->ci->Orderlog_model->eventsignup] = $inputArray['condition']['eventSignupId'];
        }
        if (isset($inputArray['condition']['userId']) && $inputArray['condition']['userId'] != '') {
            $updateOrderlogInput[$this->ci->Orderlog_model->userid] = $inputArray['condition']['userId'];
        }

        if (isset($inputArray['update']['userId']) && $inputArray['update']['userId'] != '') {
            $updateOrderlogData[$this->ci->Orderlog_model->userid] = $inputArray['update']['userId'];
        }
        if (isset($inputArray['update']['eventSignupId']) && $inputArray['update']['eventSignupId'] != '') {
            $updateOrderlogData[$this->ci->Orderlog_model->eventsignup] = $inputArray['update']['eventSignupId'];
        }
        if (isset($inputArray['update']['data']) && $inputArray['update']['data'] != '') {
            $updateOrderlogData[$this->ci->Orderlog_model->data] = $inputArray['update']['data'];
        }
        $this->ci->Orderlog_model->setWhere($updateOrderlogInput);
        $this->ci->Orderlog_model->setInsertUpdateData($updateOrderlogData);
        
        $updateStatus = $this->ci->Orderlog_model->update_data();
        if ($updateStatus) {
            $output['status'] = TRUE;
            $output['response']['messages'][] = SUCCESS_ORDERLOG_UPDATED;
            $output['statusCode'] = STATUS_UPDATED;
            return $output;
        }
        $output['status'] = FALSE;
        $output['response']['messages'][] = ERROR_ORDERLOG_UPDATED;
        $output['statusCode'] = STATUS_BAD_REQUEST;
        return $output;
    }
}

?>
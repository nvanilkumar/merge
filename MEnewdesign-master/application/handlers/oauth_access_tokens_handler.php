<?php

/**
 * alerts related business logic will be defined in this class
 *
 * @package		CodeIgniter
 * @author		Qison  Dev Team
 * @copyright	Copyright (c) 2015, MeraEvents.
 * @Version		Version 1.0
 * @Since       Class available since Release Version 1.0 
 * @Created     30-07-2015
 * @Last Modified  
 * @Last Modified by Anil Kumar M 
 */
require_once(APPPATH . 'handlers/handler.php');

class Oauth_access_tokens_handler extends Handler {

    var $ci;

    public function __construct() {
        parent::__construct();
        $this->ci = parent::$CI;
        $this->ci->load->model('Oauth_access_tokens_model');
    }

    public function getTokenDetails($inputArray) {
        parent::$CI->form_validation->pass_array($inputArray);
        parent::$CI->form_validation->set_rules('accessToken', 'Access Token', 'required_strict');

        if (parent::$CI->form_validation->run() === FALSE) {
            $errorMessages = $this->ci->form_validation->get_errors();
            $output['status'] = FALSE;
            $output['response']['messages'] = $errorMessages['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $this->ci->Oauth_access_tokens_model->resetVariable();
        $selectInput['access_token'] = $this->ci->Oauth_access_tokens_model->access_token;
        $selectInput['client_id'] = $this->ci->Oauth_access_tokens_model->client_id;
        $selectInput['user_id'] = $this->ci->Oauth_access_tokens_model->user_id;
        $selectInput['expires'] = $this->ci->Oauth_access_tokens_model->expires;
        $this->ci->Oauth_access_tokens_model->setSelect($selectInput);
        
        $where[$this->ci->Oauth_access_tokens_model->access_token] = $inputArray['accessToken'];
        $this->ci->Oauth_access_tokens_model->setWhere($where);
        
        $tokenDetails = $this->ci->Oauth_access_tokens_model->get();

        if ($tokenDetails) {
            $output['status'] = TRUE;
            $output['response']['total'] = count($tokenDetails);
            $output['response']['tokenDetails'] = $tokenDetails;
            $output['statusCode'] = STATUS_OK;
            return $output;
        }
        $output['status'] = TRUE;
        $output['response']['total'] = 0;
        $output["response"]["messages"][] = ERROR_NO_DATA;
        $output['statusCode'] = STATUS_OK;
        return $output;
    }

}

<?php

/**
 * Maintaing promoter related data
 *
 * @author     Qison dev team
 * @copyright  2015-2005 The PHP Group
 * @version    CVS: $Id:$
 * @since      File available since Sprint 2
 * @deprecated File deprecated in Release 2.0.0
 */
/*
 * Place includes, constant defines and $_GLOBAL settings here.
 */

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require (APPPATH . 'libraries/REST_Controller.php');
require_once (APPPATH . 'handlers/thirdpartypayment_handler.php');

class Payment extends REST_Controller {

    var $thirdpartypaymentHandler;

    public function __construct() {
        parent::__construct();
        $this->thirdpartypaymentHandler = new Thirdpartypayment_handler();
    }

    /*
     * Third party payment related db insertion
     * need to done preview & delegate page changes
     * we are accepting json data in post request handling this in bookingHandler
     */

    public function preview_post() {
        $response = $this->thirdpartypaymentHandler->thirdPartyWebsitePayment();
        $statusCode = $response['statusCode'];
        $this->response($response, $statusCode);
    }



}

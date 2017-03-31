<?php

/**
 * Maintaing seating layout related data
 *
 * @author    Qison  Dev Team
 * @copyright  2015-2005 The PHP Group
 * @version    CVS: $Id:$
 * @since      Discounts available since Sprint 4
 * @deprecated File deprecated in Release 2.0.0
 */
/*
 * Place includes, constant defines and $_GLOBAL settings here.
 */

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require_once(APPPATH . 'libraries/REST_Controller.php');
require_once(APPPATH . 'handlers/seating_handler.php');

class Seating extends REST_Controller {

    var $discountHandler;

    public function __construct() {
        parent::__construct();
        $this->seatingHandler = new Seating_handler();
    }

    function checkLayoutExists_post() {
        $inputArray = $this->post();
        $success = $this->seatingHandler->checkLayout($inputArray);
        $resultArray = array('response' => $success['response']);
        $this->response($resultArray, $success['statusCode']);
    }

    function updateSeats_post() {
        $inputArray = $this->post();
        $success = $this->seatingHandler->updateSeats($inputArray);
        $resultArray = array('response' => $success['response']);
        $this->response($resultArray, $success['statusCode']);
    }
    
    function checkUpdateSeats_post() {
        $inputArray = $this->post();
        $success = $this->seatingHandler->checkUpdateSeats($inputArray);
        $resultArray = array('response' => $success['response']);
        $this->response($resultArray, $success['statusCode']);
    }
}

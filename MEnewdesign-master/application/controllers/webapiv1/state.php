<?php

/**
 * Maintaing State related data
 *
 * @author     Gautam <gautam.dharmapuri@qison.com>
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
require (APPPATH . 'handlers/state_handler.php');

class State extends REST_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->helper('common_helper');
        $this->stateHandler = new State_handler();
    }

    /*
     * Function to get the State list
     *
     * @access	public
     * @param		Get contains
     * 				KeyWord - String
     * 				Limit - Integer
     * @return	array
     */

    public function list_post() {
        $inputArray = $this->post();
        $stateList = $this->stateHandler->getStateList($inputArray);

        $responseArray = array('response' => $stateList['response']);
        $this->response($responseArray, $stateList['statusCode']);
    }

}

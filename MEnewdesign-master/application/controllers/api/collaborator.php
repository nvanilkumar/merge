<?php

/**
 * Collaborator related api's
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
require_once(APPPATH . 'handlers/collaborator_handler.php');

class Collaborator extends REST_Controller {

    var $collaboratorHandler;

    public function __construct() {
        parent::__construct();
        $this->collaboratorHandler = new Collaborator_handler();
    }

    function add_post() {
        $inputArray = $this->input->post();
        $output = $this->collaboratorHandler->add($inputArray);
        $responseArray = array('response' => $output['response']);
        $this->response($responseArray, $output['statusCode']);
    }
    function update_post() {
        $inputArray = $this->input->post();
        $output = $this->collaboratorHandler->update($inputArray);
        $responseArray = array('response' => $output['response']);
        $this->response($responseArray, $output['statusCode']);
    }
    function updateStatus_post() {
        $inputArray = $this->input->post();
        $output = $this->collaboratorHandler->changeFieldValue($inputArray);
        $responseArray = array('response' => $output['response']);
        $this->response($responseArray, $output['statusCode']);
    }

}

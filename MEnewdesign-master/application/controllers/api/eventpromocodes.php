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
require_once(APPPATH . 'handlers/eventpromocodes_handler.php');

class Eventpromocodes extends REST_Controller {

    var $eventPromocodesHandler;

    public function __construct() {
        parent::__construct();
        $this->eventPromocodesHandler = new Eventpromocodes_handler();
    }

    function check_post() {
        $inputArray = $this->input->post();
        $output = $this->eventPromocodesHandler->getByPromocodeAndEventId($inputArray);
        $responseArray = array('response' => $output['response']);
        $this->response($responseArray, $output['statusCode']);
    }

}

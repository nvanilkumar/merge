<?php

/**
 * Maintaing Discount related data
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
require_once(APPPATH . 'handlers/discount_handler.php');


class Discount extends REST_Controller {

    var $discountHandler;

    public function __construct() {
        parent::__construct();
        $this->discountHandler = new Discount_handler();
    }

   function add_put() {
        $inputArray = $this->put();
        $success=$this->discountHandler->add($inputArray);	
        print_r($success);
    }
}

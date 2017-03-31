<?php

/**
 * Maintaing delegate info validations related data
 *
 * @package		CodeIgniter
 * @author		Shashi <shashidhar.enjapuri@qison.com>
 * @copyright	Copyright (c) 2015, MeraEvents.
 * @Version		Version 1.0
 * @Since       Class available since Release Version 1.0
 * @Created     07-09-2015
 * @Last Modified 07-09-2015
 * @Last Modified by Shashi
 */
require_once (APPPATH . 'handlers/handler.php');
require_once (APPPATH . 'handlers/file_handler.php');
require_once (APPPATH . 'handlers/timezone_handler.php');
require_once (APPPATH . 'handlers/currency_handler.php');

class Delegate_validations_handler extends Handler {

    var $ci;
	var $timezoneHandler;
	var $currencyHandler;
	var $fileHandler;

    public function __construct() {
        parent::__construct();
        $this->ci = parent::$CI;
        //$this->ci->load->model('Country_model'); //need to enable delegate_validation model, if required
    }

    

    //To check the promocodes for 78492 event
    public function checkPromoCode($inputArray) {
        $result = $select = $countryList = $likeArray = array();
        $pcode = $inputArray['pcode'];

        

        //Setting the result db will return false (no records found)
		$pcodeArr = array("9REI1501", "9REI1502", "9REI1503", "9REI1504", "9REI1505", "9REI1506", "9REI1507");
	
	
        if (in_array($pcode,$pcodeArr)) {
            $output['status'] = TRUE;
            $output['response'] = "valid";
            $output['statusCode'] = STATUS_OK;
            return $output;
        } else {
            $output['status'] = TRUE;
            $output['response'] = "invalid";
            $output['statusCode'] = STATUS_OK;
        }
        return $output;
    }


}

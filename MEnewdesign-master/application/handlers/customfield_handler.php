<?php

/**
 * Country related business logic will be defined in this class
 *
 * @package		CodeIgniter
 * @author		Qison  Dev Team
 * @copyright	Copyright (c) 2015, MeraEvents.
 * @Version		Version 1.0
 * @Since       Class available since Release Version 1.0
 * @Created     11-06-2015
 * @Last Modified 03-07-2015
 * @Last Modified by Sridevi
 */
require_once (APPPATH . 'handlers/handler.php');

//require_once (APPPATH . 'handlers/file_handler.php');
//require_once (APPPATH . 'handlers/timezone_handler.php');
//require_once (APPPATH . 'handlers/currency_handler.php');

class Customfield_handler extends Handler {

    var $ci;

//	var $timezoneHandler;
//	var $currencyHandler;
//	var $fileHandler;

    public function __construct() {
        parent::__construct();
        $this->ci = parent::$CI;
       // $this->ci->load->model('Customfield_model');
    }


    public function getEventsignupcustomfields($customfieldids) {
    	
        $output = array();
/*         $this->ci->form_validation->pass_array($input);
        $this->ci->form_validation->set_rules('eventId', 'eventId', 'required_strict|is_array');
        $this->ci->form_validation->set_rules('ticketIds', 'ticketIds', 'required_strict|is_array');
        if ($this->ci->form_validation->run() == FALSE) {
        	$response = $this->ci->form_validation->get_errors();
        	$output['status'] = FALSE;
        	$output['response']['messages'];
        	$output['statusCode'] = 400;
        	return $output;
        } */
        if (!is_array($customfieldids) || count($customfieldids) == 0) {
        	$validationStatus = $this->ci->form_validation->get_errors();
        	$output['status'] = FALSE;
        	$output['response']['messages'][] = 'Invalid Data';
        	$output['statusCode'] = STATUS_BAD_REQUEST;
        	return $output;
        } else {
        	$customfieldids = implode(',',$customfieldids);
        	$query = "select id,fieldname,displayonticket from customfield where id in ($customfieldids) and (commonfieldid = 1 OR displayonticket=1) order by id desc";
        	$result = $this->ci->db->query($query);
	       $attendeeDetails = $result->result_array();
	     if(is_array($attendeeDetails)){
		        if (count($attendeeDetails) > 0) {
		        	$output['status'] = TRUE;
		        	$output['response']['attendeeDetails'] = $attendeeDetails;
		        	$output['statusCode'] = STATUS_OK;
		        	return $output;
		        }else {
		            $output['status'] = TRUE;
		            $output['response']['messages'][] = ERROR_NO_DATA;
		            $output['statusCode'] = STATUS_OK;
		            return $output;
		        }
        
	      }else{
	      	$output['status'] = FALSE;
	      	$output['response']['messages'][] = ERROR_INTERNAL_DB_ERROR;
	      	$output['statusCode'] = STATUS_SERVER_ERROR;
	      	return $output;
	        }
        
        }
    }
    
}

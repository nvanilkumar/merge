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
require_once(APPPATH . 'handlers/promoter_handler.php');

class Promote extends REST_Controller {

	public function __construct() {
		parent::__construct();
		$this->promoterHandler=new Promoter_handler(); 
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
    public function setStatus_post(){
        $id=$this->input->post('id');
        $output=$this->promoterHandler->setPromoterStatus($id);
	$responseArray=array('response'=>$output['response']);        
	$this->response($responseArray,$output['statusCode']);
    }     
    public function offlineTickets_get(){
          $inputArray = $this->get();
          $output = $this->promoterHandler->offlineTicketsByEvent($inputArray);
          $responseArray=array('response'=>$output['response']);        
	  $this->response($responseArray,$output['statusCode']);
    }
    public function ticketsData_get() {
        $inputArray = $this->get();
        $output = $this->promoterHandler->getTicketData($inputArray);
        $responseArray=array('response'=>$output['response']);        
       $this->response($responseArray,$output['statusCode']);
    }
    public function addGlobalPromoter_post(){
        $inputArray = $this->input->post();
        $output=$this->promoterHandler->insertGlobalPromoter($inputArray);
	$responseArray=array('response'=>$output['response']);        
	$this->response($responseArray,$output['statusCode']);
    }
    public function checkGlobalCodeAvailability_post(){
        $inputArray = $this->input->post();
        $output=$this->promoterHandler->checkPromoterCode($inputArray);
	$responseArray=array('response'=>$output['response']);        
	$this->response($responseArray,$output['statusCode']);
    }
}

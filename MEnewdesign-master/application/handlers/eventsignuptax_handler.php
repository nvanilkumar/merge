<?php

/**
 * eventsignupTax related business logic will be defined in this class
 * Getting TicketTax Related data
 * @package		CodeIgniter
 * @author		Qison  Dev Team
 * @param		CountryId - required
 *                      cityId,categoryId,type (optional)
 * @copyright	Copyright (c) 2015, MeraEvents.
 * @Version		Version 1.0
 * @Since       Class available since Release Version 1.0 
 * @Created     16-06-2015
 * @Last Modified 16-06-2015
 */
require_once(APPPATH . 'handlers/handler.php');

//require_once(APPPATH . 'handlers/country_handler.php');

class Eventsignuptax_handler extends Handler {

    var $ci;

    public function __construct() {
        parent::__construct();
        $this->ci = parent::$CI;
        $this->ci->load->model('Eventsignuptax_model');
    }

    /*
     * Function to get the Taxes based on country,state,city
     *
     * @access	public
     * @param	$inputArray contains
     * 			countryName - string
     * 			stateName - string (optional)
     * 			cityName - string (optional)
     * @return	array
     */

    public function getTaxes($inputArray) {
        $validateStatus=  $this->validateGetTaxes($inputArray);
        if(!$validateStatus['status']){
            return $validateStatus;
        }
        $eventSignupIds = $inputArray['eventsignupids'];
        $this->ci->Eventsignuptax_model->resetVariable();
        $selectESTax[$this->ci->Eventsignuptax_model->eventsignupid] = $this->ci->Eventsignuptax_model->eventsignupid;
        $selectESTax[$this->ci->Eventsignuptax_model->ticketid] = $this->ci->Eventsignuptax_model->ticketid;
        $selectESTax[$this->ci->Eventsignuptax_model->taxmappingid] = $this->ci->Eventsignuptax_model->taxmappingid;
        $selectESTax[$this->ci->Eventsignuptax_model->taxamount] = $this->ci->Eventsignuptax_model->taxamount;
        $this->ci->Eventsignuptax_model->setSelect($selectESTax);
        $whereIn[$this->ci->Eventsignuptax_model->eventsignupid] = $eventSignupIds;
        $this->ci->Eventsignuptax_model->setWhereIns($whereIn);
        $finalTaxArray = $this->ci->Eventsignuptax_model->get();
        if (count($finalTaxArray) > 0) {
            $output['status'] = TRUE;
            $output['response']['taxList'] = $finalTaxArray;
            $output['response']['messages'] = array();
            $output['response']['total'] = count($finalTaxArray);
            $output['statusCode'] = STATUS_OK;
            return $output;
        } else {
            $output['status'] = TRUE;
            $output['response']['messages'][] = ERROR_NO_TAX;
            $output['response']['total'] = 0;
            $output['statusCode'] = STATUS_NO_DATA;
            return $output;
        }
    }

    public function validateGetTaxes($inputArray) {
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('eventsignupids', 'eventsignupids', 'required_strict|is_array');
        if ($this->ci->form_validation->run() == FALSE) {
            $response = $this->ci->form_validation->get_errors();
            $output['status'] = FALSE;
            $output['response']['messages'] = $response['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        } else {
            $output['status'] = TRUE;
            $output['response']['messages'] = [];
            $output['statusCode'] = STATUS_OK;
            return $output;
        }
    }
    
    /*
     * Function to add the Taxes for the event signup tickets
     *
     * @access	public
     * @param	$inputArray contains
     * 			eventSignupId - integer
     * 			ticketId - integer
     * 			ticketMappingId - integer
     * @return	array
     */
    public function add($inputArray) {
        
        $this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('eventSignupId', 'event signup id', 'trim|xss_clean|required_strict');
        $this->ci->form_validation->set_rules('ticketId', 'ticket id', 'trim|xss_clean|required_strict');
        $this->ci->form_validation->set_rules('ticketMappingId', 'tax mapping id', 'trim|xss_clean|required_strict');

        if ($this->ci->form_validation->run() === FALSE) {
            $response = $this->ci->form_validation->get_errors('message');
            $output['status'] = FALSE;
            $output['response']['messages'] = $response['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $this->ci->Eventsignuptax_model->resetVariable();
        $createEventSignupTax[$this->ci->Eventsignuptax_model->eventsignupid] = $inputArray['eventSignupId'];
        $createEventSignupTax[$this->ci->Eventsignuptax_model->ticketid] = $inputArray['ticketId'];
        $createEventSignupTax[$this->ci->Eventsignuptax_model->taxmappingid] = $inputArray['ticketMappingId'];
        $createEventSignupTax[$this->ci->Eventsignuptax_model->taxamount] = $inputArray['taxAmount'];

        $this->ci->Eventsignuptax_model->setInsertUpdateData($createEventSignupTax);
        $eventSignUpTaxId = $this->ci->Eventsignuptax_model->insert_data(); //Inserting into table and getting inserted id
        if ($eventSignUpTaxId) {
            //Inserting record in the ticketdiscount table
            $output['status'] = TRUE;
            $output['response']['messages'][] = SUCCESS_EVENTSIGNUPTAX_ADDED;
            $output['response']['eventSignUpTaxId'] = $eventSignUpTaxId;
            $output['statusCode'] = STATUS_OK;
            return $output;
        }
        $output['status'] = FALSE;
        $output['response']['messages'][] = ERROR_EVENTSIGNUPTAX_ADDED;
        $output['statusCode'] = STATUS_SERVER_ERROR;
        return $output;
    }
    
    public function addArray($inputMultiArray) {
        
        if (count($inputMultiArray) == 0) {
            
            $output['status'] = FALSE;
            $output['response']['messages'] = ERROR_EVENTSIGNUPTAX_ADDED;
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        
        foreach($inputMultiArray as $inputArray) {
            
            $this->ci->Eventsignuptax_model->resetVariable();
            $createEventSignupTax[$this->ci->Eventsignuptax_model->eventsignupid] = $inputArray['eventSignupId'];
            $createEventSignupTax[$this->ci->Eventsignuptax_model->ticketid] = $inputArray['ticketId'];
            $createEventSignupTax[$this->ci->Eventsignuptax_model->taxmappingid] = $inputArray['ticketMappingId'];
            $createEventSignupTax[$this->ci->Eventsignuptax_model->taxamount] = $inputArray['taxAmount'];
    
            $this->ci->Eventsignuptax_model->setInsertUpdateData($createEventSignupTax);
            $eventSignUpTaxId = $this->ci->Eventsignuptax_model->insert_data(); //Inserting into table and getting inserted id
            if(!$eventSignUpTaxId) {
                $output['status'] = FALSE;
                $output['response']['messages'][] = ERROR_EVENTSIGNUPTAX_ADDED;
                $output['statusCode'] = STATUS_SERVER_ERROR;
                return $output;
            }
        }
        $output['status'] = TRUE;
        $output['response']['messages'][] = SUCCESS_EVENTSIGNUPTAX_ADDED;
        $output['statusCode'] = STATUS_OK;
        return $output;
        
    }

}

?>
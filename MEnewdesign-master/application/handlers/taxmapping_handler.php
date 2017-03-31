<?php

/**
 * taxmapping related business logic will be defined in this class
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

class Taxmapping_handler extends Handler {

    var $ci;

    public function __construct() {
        parent::__construct();
        $this->ci = parent::$CI;
        $this->ci->load->model('Taxmapping_model');
    }

    /*
     * Function to get the tax mapping details 
     *
     * @access	public
     * @param	$inputArray contains
     * @return	array
     */

    public function getTaxmapping($inputArray) {
        $validateStatus=  $this->validateGetTaxmapping($inputArray);
        if(!$validateStatus['status']){
            return $validateStatus;
        }
        $taxMappingIds = $inputArray['ids'];
        $this->ci->Taxmapping_model->resetVariable();
        $selectTaxMapping['id'] = $this->ci->Taxmapping_model->id;
        $selectTaxMapping['taxid'] = $this->ci->Taxmapping_model->taxid;
        $selectTaxMapping['value'] = $this->ci->Taxmapping_model->value;
        $this->ci->Taxmapping_model->setSelect($selectTaxMapping);
        $whereIn[$this->ci->Taxmapping_model->id] = $taxMappingIds;
        $this->ci->Taxmapping_model->setWhereIns($whereIn);
        $finalTaxArray = $this->ci->Taxmapping_model->get();
        if (count($finalTaxArray) > 0) {
            $output['status'] = TRUE;
            $output['response']['taxMappingList'] = $finalTaxArray;
            $output['response']['messages'] = array();
            $output['response']['total'] = count($finalTaxArray);
            $output['statusCode'] = STATUS_OK;
            return $output;
        } else {
            $output['status'] = TRUE;
            $output['response']['messages'][] = ERROR_NO_TAX_MAPPING;
            $output['response']['total'] = 0;
            $output['statusCode'] = STATUS_OK;
            return $output;
        }
    }

    public function validateGetTaxmapping($inputArray) {
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('ids', 'ids', 'required_strict|is_array');
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

}

?>
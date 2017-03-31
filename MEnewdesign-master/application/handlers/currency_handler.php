<?php

/**
 * All images path details will be fetched from this class
 *
 * @package		CodeIgniter
 * @author		Qison  Dev Team
 * @copyright	Copyright (c) 2015, MeraEvents.
 * @Version		Version 1.0
 * @Since       Class available since Release Version 1.0 
 * @Created     11-06-2015
 * @Last Modified 11-06-2015
 */
require_once(APPPATH . 'handlers/handler.php');

class Currency_handler extends Handler {

    var $ci;

    public function __construct() {
        parent::__construct();
        $this->ci = parent::$CI;
        $this->ci->load->model('Currency_model');
    }

    public function getCurrencyList($inputArray = NULL) {
        $memcacheEnable = $this->ci->config->item('memcacheEnabled');
        if(isset($inputArray['idList']) && count($inputArray['idList']) > 0) {
                $memcacheEnable = FALSE;
        }
		$cacheResults = FALSE;
        if ($memcacheEnable == TRUE) {
            $this->ci->load->library('memcached_library');
            $cacheResults = $this->ci->memcached_library->get(MEMCACHE_ALL_CURRENCY);
        }
		
        if ($cacheResults == FALSE) {
            $this->ci->Currency_model->resetVariable();
            $select['currencyId'] = $this->ci->Currency_model->id;
            $select['currencyName'] = $this->ci->Currency_model->name;
            $select['currencyCode'] = $this->ci->Currency_model->code;
            $select['currencySymbol'] = $this->ci->Currency_model->symbol;
            $this->ci->Currency_model->setSelect($select);
            $condition[$this->ci->Currency_model->status] = 1;
            $condition[$this->ci->Currency_model->deleted] = 0;
            $this->ci->Currency_model->setWhere($condition);
            if(isset($inputArray['idList']) && count($inputArray['idList']) > 0){
                    $this->ci->Currency_model->setWhereIn(array("id" , $inputArray['idList']));
            }                        
            else {
                $this->ci->Currency_model->setWhereIn(array());
            }
			
			if (isset($inputArray['timeStamp']) && count($inputArray['timeStamp']) > 0) {
			
				$where[$this->ci->Currency_model->mts.' >='] = allTimeFormats($inputArray['timeStamp'], 11);
				$this->ci->Currency_model->setWhere($where);
			}
			
            $currencyDetails = $this->ci->Currency_model->get();
            if ($currencyDetails == FALSE) {
                $output['status'] = TRUE;
                $output['response']['messages'][] = ERROR_NO_CURRENCIES;
                $output['response']['total'] = 0;
                $output['statusCode'] = STATUS_OK;
                return $output;
            }
            if ($memcacheEnable == TRUE ) {
                $this->ci->memcached_library->add(MEMCACHE_ALL_CURRENCY, $currencyDetails, MEMCACHE_ALL_CURRENCY_TTL);
            }
            $output['status'] = TRUE;
            $output['response']['messages'] = array();
            $output['response']['total'] = count($currencyDetails);
            $output['response']['currencyList'] = ($currencyDetails);
            $output['statusCode'] = STATUS_OK;
            return $output;
        }
        $output['status'] = TRUE;
        $output['response']['messages'] = array();
        $output['response']['total'] = count($cacheResults);
        $output['response']['currencyList'] = ($cacheResults);
        $output['statusCode'] = STATUS_OK;
        return $output;
    }

    public function getCurrencyDetailById($input) {
		$this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($input);
        $this->ci->form_validation->set_rules('currencyId', 'currencyId', 'required_strict|is_natural_no_zero');
        if ($this->ci->form_validation->run() == FALSE) {
            $response = $this->ci->form_validation->get_errors();
            $output['status'] = FALSE;
            $output['response']['messages'] = $response['message'];
            $output['response']['total'] = 0;
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $currencyId = $input['currencyId'];
        if ($this->ci->config->item('memcache_enabled')) {
            $currencyList = $this->getCurrencyList();
            $currencyListTemp=array();
            if ($currencyList['status'] && $currencyList['response']['total'] > 0) {
                $currencyListTemp = commonHelperGetIdArray($currencyList['response']['currencyList']);
            } else {
                return $currencyList;
            }
            if (array_key_exists($currencyId, array_keys($currencyListTemp))) {
                $output['status'] = TRUE;
                $output['response']['currencyList']['detail'] = $currencyListTemp[$currencyId];
                $output['response']['messages'] = [];
                $output['response']['total'] = 1;
                $output['statusCode'] = STATUS_OK;
                return $output;
            } else {
                $output['status'] = TRUE;
                $output['response']['messages'][] = ERROR_NO_CURRENCIES;
                $output['response']['total'] = 0;
                $output['statusCode'] = STATUS_OK;
                return $output;
            }
        }
        $this->ci->Currency_model->resetVariable();
        $select['currencyId'] = $this->ci->Currency_model->id;
        $select['currencyName'] = $this->ci->Currency_model->name;
        $select['currencyCode'] = $this->ci->Currency_model->code;
        $select['currencySymbol'] = $this->ci->Currency_model->symbol;
        $this->ci->Currency_model->setSelect($select);
        $condition[$this->ci->Currency_model->status] = 1;
        $condition[$this->ci->Currency_model->deleted] = 0;
        $condition[$this->ci->Currency_model->id] = $currencyId;
        $this->ci->Currency_model->setWhere($condition);
	$this->ci->Currency_model->setWhereIn(array());
        $currencyResponse = $this->ci->Currency_model->get();
        if ($currencyResponse == FALSE) {
            $output['status'] = TRUE;
            $output['response']['messages'][] = ERROR_NO_CURRENCIES;
            $output['response']['total'] = 0;
            $output['statusCode'] = STATUS_OK;
            return $output;
        }
        $output['status'] = TRUE;
        $output['response']['messages'] = [];
        $output['response']['currencyList']['detail'] = $currencyResponse[0];
        $output['response']['total'] = count($currencyResponse);
        $output['statusCode'] = STATUS_OK;
        return $output;
    }

	
	/*
     * Function to get the currency details using currency code
     *
     * @access	public
     * @param
     *      	currencyCode - string
     * @return	gives the response regards the saving signup data
     */
	public function getCurrencyDetailByCode($input) {
		
		$this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($input);
        if(!isset($input['currencyname'])){
        $this->ci->form_validation->set_rules('currencyCode', 'currency code', 'required_strict');
        if ($this->ci->form_validation->run() == FALSE) {
            $response = $this->ci->form_validation->get_errors();
            $output['status'] = FALSE;
            $output['response']['messages'] = $response['message'];
            $output['response']['total'] = 0;
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        }
        $this->ci->Currency_model->resetVariable();
        $select['currencyId'] = $this->ci->Currency_model->id;
        $select['currencyName'] = $this->ci->Currency_model->name;
        $select['currencyCode'] = $this->ci->Currency_model->code;
        $select['currencySymbol'] = $this->ci->Currency_model->symbol;
        $this->ci->Currency_model->setSelect($select);
		$condition = array();
        $condition[$this->ci->Currency_model->status] = 1;
        $condition[$this->ci->Currency_model->deleted] = 0;
         if(isset($input['currencyCode'])){
        $condition[$this->ci->Currency_model->code] = $input['currencyCode'];
         }
        if(isset($input['currencyname'])){
            $condition[$this->ci->Currency_model->name] = $input['currencyname'];
        }
        $this->ci->Currency_model->setWhere($condition);
	$whereIn = array();
	$this->ci->Currency_model->setWhereIn($whereIn);
        $currencyResponse = $this->ci->Currency_model->get();
        if ($currencyResponse == FALSE) {
            $output['status'] = TRUE;
            $output['response']['messages'][] = ERROR_NO_CURRENCIES;
            $output['response']['total'] = 0;
            $output['statusCode'] = STATUS_OK;
            return $output;
        }
        $output['status'] = TRUE;
        $output['response']['messages'] = [];
        $output['response']['currencyList']['detail'] = $currencyResponse[0];
        $output['response']['total'] = count($currencyResponse);
        $output['statusCode'] = STATUS_OK;
        return $output;
    }
	
		}

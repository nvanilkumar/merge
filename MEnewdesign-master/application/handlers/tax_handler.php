<?php

/**
 * tax related business logic will be defined in this class
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

class Tax_handler extends Handler {

    var $ci;

    public function __construct() {
        parent::__construct();
        $this->ci = parent::$CI;
        $this->ci->load->model('Tax_model');
    }

    public function getTaxList() {
        $this->ci->Tax_model->resetVariable();
        $select['id'] = $this->ci->Tax_model->id;
        $select['label'] = $this->ci->Tax_model->label;
        $this->ci->Tax_model->setSelect($select);
        $where[$this->ci->Tax_model->status] = 1;
        $where[$this->ci->Tax_model->deleted] = 0;
        $this->ci->Tax_model->setWhere($where);
        $taxResponse = $this->ci->Tax_model->get();
        if (count($taxResponse) == 0) {
            $output['status'] = TRUE;
            $output['response']['messages'][] = ERROR_NO_TAX;
            $output['response']['total'] = 0;
            $output['statusCode'] = STATUS_OK;
            return $output;
        }
        $output['status'] = TRUE;
        $output['response']['taxList'] = $taxResponse;
        $output['response']['total'] = count($taxResponse);
        $output['statusCode'] = STATUS_OK;
        return $output;
    }

}

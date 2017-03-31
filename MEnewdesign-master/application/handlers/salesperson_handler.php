<?php
require_once(APPPATH . 'handlers/handler.php');
//error_reporting(-1);
class Salesperson_handler extends Handler {
    var $ci;
    public function __construct() {
        parent::__construct();
        $this->ci = parent::$CI;
        $this->ci->load->model('Salesperson_model');
    }

    public function getSalesPersonDetails($request) {
        $output = array();
        $validationStatus = $this->salesPersonListValidation($request);
        if ($validationStatus['error'] == TRUE) {
            $output['status'] = FALSE;
            $output['response']['messages'] = $validationStatus['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $this->ci->Salesperson_model->resetVariable();
        $selectInput['id'] = $this->ci->Salesperson_model->id;
        $selectInput['name'] = $this->ci->Salesperson_model->name;
        $selectInput['mobile'] = $this->ci->Salesperson_model->mobile;
        $selectInput['email'] = $this->ci->Salesperson_model->email;
        $selectInput['signature'] = $this->ci->Salesperson_model->signature;
        $selectInput['userid'] = $this->ci->Salesperson_model->userid;
        $selectInput['deleted'] = $this->ci->Salesperson_model->deleted;
        $selectInput['status'] = $this->ci->Salesperson_model->status;
        
        $this->ci->Salesperson_model->setSelect($selectInput);

        //fetching active sales persons & not deleted
        $where[$this->ci->Salesperson_model->id] = $request['salesPersonId'];
        $where[$this->ci->Salesperson_model->deleted] = 0;
        
        $this->ci->Salesperson_model->setWhere($where);
        
        $salesPersonDetails = $this->ci->Salesperson_model->get();
        if (count($salesPersonDetails) == 0) {
            $output['status'] = TRUE;
            $output['response']['messages'][] = ERROR_NO_DATA;
            $output['response']['total'] = 0;
            $output['statusCode'] = STATUS_OK;
            return $output;
        } else {
            $output['status'] = TRUE;
            $output['response']['details'] = $salesPersonDetails;
            $output['response']['total'] = 1;
            $output['statusCode'] = STATUS_OK;
            return $output;
        }
    }
    
       public function salesPersonListValidation($inputs) {
        //$errorMessages = array();
           $this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($inputs);

        $this->ci->form_validation->set_rules('salesPersonId', 'salesPersonId', 'required_strict|is_natural_no_zero');
                
        if ($this->ci->form_validation->run() === FALSE) {
            
            $errorMessages = $this->ci->form_validation->get_errors();
            return $errorMessages;
        }

        $errorMessages['error'] = FALSE; 
        return $errorMessages;
    }
}
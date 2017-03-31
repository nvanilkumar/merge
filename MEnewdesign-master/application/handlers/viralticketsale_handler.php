<?php

/**
 * Connected to MAXMIND GEO IP 3rd party data( from local) to get the IP corresponding country name & city name
 *
 * @package		CodeIgniter
 * @author		Qison  Dev Team
 * @copyright	Copyright (c) 2015, MeraEvents.
 * @Version		Version 1.0
 * @Since       Class available since Release Version 1.0 
 * @Created     12-06-2015
 * @Last Modified 12-06-2015
 */
require_once(APPPATH . 'handlers/handler.php');

class Viralticketsale_handler extends Handler {

    var $ci;

    public function __construct() {
        parent::__construct();
        $this->ci = parent::$CI;
        $this->ci->load->model('ViralticketSale_model');
    }

    function getViralticketSaleData($inputArray) {
        $this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('referralcode', 'referralcode', 'required_strict|alpha_numeric');
        if ($this->ci->form_validation->run() == FALSE) {
            $response = $this->ci->form_validation->get_errors();
            $output['status'] = FALSE;
            $output['response']['messages'] = $response['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $this->ci->ViralticketSale_model->resetVariable();
        $selectInput['id'] = $this->ci->ViralticketSale_model->id;
        $selectInput['referreruserid'] = $this->ci->ViralticketSale_model->referreruserid;
        $selectInput['viralticketsettingid'] = $this->ci->ViralticketSale_model->viralticketsettingid;
        $this->ci->ViralticketSale_model->setSelect($selectInput);
        $where[$this->ci->ViralticketSale_model->referralcode] = $inputArray['referralcode'];
        $this->ci->ViralticketSale_model->setWhere($where);
        $ticketData = $this->ci->ViralticketSale_model->get();
        if (count($ticketData) > 0) {
            $output['status'] = TRUE;
            $output['response']['viralTicket'] = $ticketData[0];
            $output['response']['total'] = count($ticketData);
            $output['statusCode'] = STATUS_OK;
            return $output;
        } else {
            $output['status'] = TRUE;
            $output['response']['messages'][] = ERROR_NO_DATA;
            $output['response']['total'] = 0;
            $output['statusCode'] = STATUS_OK;
            return $output;
        }
    }
    
        /*
     * Function to add the viral ticket sale data
     *
     * @access	public
     * @param	$inputArray contains
     *          - referrerUserId (integer)
     *          - eventSignupTicketDetailId (integer)
     *          - viralTicketSettingId (integer)
     *          - referrerUserPointId (integer)
     *          - referralCode (string)
     */

    public function addViralTicketSale($inputArray) {
        $this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('referrerUserId', 'referrer user id', 'trim|xss_clean|required_strict');
        $this->ci->form_validation->set_rules('eventSignupTicketDetailId', 'event signup ticketdetail id', 'trim|xss_clean|required_strict');
        $this->ci->form_validation->set_rules('viralTicketSettingId', 'viral ticket setting id', 'trim|xss_clean|required_strict');
        $this->ci->form_validation->set_rules('referrerUserPointId', 'referrer user point id', 'trim|xss_clean|required_strict');
        $this->ci->form_validation->set_rules('referralCode', 'referral code', 'trim|xss_clean|required_strict');

        if ($this->ci->form_validation->run() === FALSE) {
            $response = $this->ci->form_validation->get_errors('message');
            $output['status'] = FALSE;
            $output['response']['messages'] = $response['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $this->ci->ViralticketSale_model->resetVariable();
        $createViralTicketSale[$this->ci->ViralticketSale_model->referreruserid] = $inputArray['referrerUserId'];
        $createViralTicketSale[$this->ci->ViralticketSale_model->eventsignupticketdetailid] = $inputArray['eventSignupTicketDetailId'];
        $createViralTicketSale[$this->ci->ViralticketSale_model->viralticketsettingid] = $inputArray['viralTicketSettingId'];
        $createViralTicketSale[$this->ci->ViralticketSale_model->referreruserpointid] = $inputArray['referrerUserPointId'];
        $createViralTicketSale[$this->ci->ViralticketSale_model->referralcode] = $inputArray['referralCode'];

        $this->ci->ViralticketSale_model->setInsertUpdateData($createViralTicketSale);
        $userPointId = $this->ci->ViralticketSale_model->insert_data(); //Inserting into table and getting inserted id
        if ($userPointId) {
            //Inserting record in the viralticketsale table
            $output['status'] = TRUE;
            $output['response']['messages'][] = SUCCESS_VIRALTICKETSALE_ADDED;
            $output['response']['userPointId'] = $userPointId;
            $output['statusCode'] = STATUS_OK;
            return $output;
        }
        $output['status'] = FALSE;
        $output['response']['messages'][] = SOMETHING_WRONG;
        $output['statusCode'] = STATUS_SERVER_ERROR;
        return $output;
    }

}

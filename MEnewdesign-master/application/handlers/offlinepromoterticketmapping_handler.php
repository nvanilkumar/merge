<?php

/**
 * offflien promoter ticket mapping Source Data will be defined in this class
 * @package		CodeIgniter
 * @author		Qison  Dev Team
 * @param		eventId - required
 * @copyright	Copyright (c) 2015, MeraEvents.
 * @Version		Version 1.0
 * @Since       Class available since Release Version 1.0
 * @Created     13-11-2015
 * @Last Modified 13-11-2015
 */
require_once (APPPATH . 'handlers/handler.php');
require_once(APPPATH . 'handlers/messagetemplate_handler.php');

class Offlinepromoterticketmapping_handler extends Handler {

    var $ci;

    public function __construct() {
        parent::__construct();
        $this->ci = parent::$CI;
        $this->ci->load->model('Offlinepromoterticketmapping_model');
    }

    public function getOfflinePromoterTicketMappingData($inputArray) {
        $this->ci->Offlinepromoterticketmapping_model->resetVariable();
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('eventId', 'Event Id', 'required_strict|is_natural_no_zero');
        if ($this->ci->form_validation->run() == FALSE) {
            $response = $this->ci->form_validation->get_errors();
            $output['status'] = FALSE;
            $output['response']['messages'] = $response['message'];
            $output['response']['total'] = 0;
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $selectInput['id'] = $this->ci->Offlinepromoterticketmapping_model->id;
        $selectInput['status'] = $this->ci->Offlinepromoterticketmapping_model->status;
        $selectInput['promoterid'] = $this->ci->Offlinepromoterticketmapping_model->promoterid;
        $selectInput['ticketid'] = $this->ci->Offlinepromoterticketmapping_model->ticketid;
        $selectInput['eventid'] = $this->ci->Offlinepromoterticketmapping_model->eventid;
        $this->ci->Offlinepromoterticketmapping_model->setSelect($selectInput);
        $whereInArray[$this->ci->Offlinepromoterticketmapping_model->promoterid] = $inputArray['promoterIds'];
        $where[$this->ci->Offlinepromoterticketmapping_model->eventid] = $inputArray['eventId'];
        if (isset($inputArray['ticketStatus'])) {
            $where[$this->ci->Offlinepromoterticketmapping_model->status] = 1;
        }
        $where[$this->ci->Offlinepromoterticketmapping_model->deleted] = 0;
        $this->ci->Offlinepromoterticketmapping_model->setWhere($where);
        $this->ci->Offlinepromoterticketmapping_model->setWhereIns($whereInArray);
        $offlinePromoterTicketData = $this->ci->Offlinepromoterticketmapping_model->get();
        if ($offlinePromoterTicketData) {
            $output['status'] = TRUE;
            $output["response"]["offlinepromoterticketmapping"] = $offlinePromoterTicketData;
            $output['statusCode'] = STATUS_OK;
            $output['response']['total'] = count($offlinePromoterTicketData);
            return $output;
        } else {
            $output['status'] = TRUE;
            $output["response"]["messages"][] = ERROR_NO_OFFLINE_TICKETS;
            $output['statusCode'] = STATUS_OK;
            $output['response']['total'] = 0;
            return $output;
        }
    }

    public function addOfflineTickets($input, $password, $eventDetails) {
        $this->ci->Offlinepromoterticketmapping_model->resetVariable();
        foreach ($input['ticketIds'] as $key => $value) {
            $createOfflineTicket[$this->ci->Offlinepromoterticketmapping_model->ticketid] = $value;
            $createOfflineTicket[$this->ci->Offlinepromoterticketmapping_model->promoterid] = $input['promoterid'];
            $createOfflineTicket[$this->ci->Offlinepromoterticketmapping_model->eventid] = $input['eventId'];
            $this->ci->Offlinepromoterticketmapping_model->setInsertUpdateData($createOfflineTicket);
            $offlineTickets = $this->ci->Offlinepromoterticketmapping_model->insert_data();
        }
        $this->emailHandler = new Email_handler();
        $this->ci->load->library('parser');
        $templateInputs['type'] = $input['templateType'];
        $templateInputs['mode'] = $input['templateMode'];
        $this->messagetemplateHandler = new Messagetemplate_handler();
        $promoterTemplate = $this->messagetemplateHandler->getTemplateDetail($templateInputs);
        $templateId = $promoterTemplate['response']['templateDetail']['id'];
        $from = $promoterTemplate['response']['templateDetail']['fromemailid'];
        $to = $input['email'];
        $templateMessage = $promoterTemplate['response']['templateDetail']['template'];
        $subject = 'MeraEvents - Promoter Invite';
        $data['passwordLabel'] = "";
        $data['password'] = "";
        $data['address'] = "";
        $data['webinar'] = '';
        $data['userName'] = ucfirst($input['name']);
        if ($password) {
            $data['passwordLabel'] = "Your password: ";
            $data['password'] = $password;
        }

        $data['date'] = allTimeFormats($eventDetails['response']['details']['startDate'], 7);
        $data['date'].='   -   ';
        $data['date'].=allTimeFormats($eventDetails['response']['details']['endDate'], 7);
        if ($eventDetails['response']['details']['eventMode'] == 1) {
            $data['webinar'] = 'This is a Webinar Event';
        } else if ($eventDetails['response']['details']['eventMode'] == 0) {
            $data['address'] = $eventDetails['response']['details']['location']['venueName'];
            if ($eventDetails['response']['details']['location']['address1']) {
                $data['address'].=',' . $eventDetails['response']['details']['location']['address1'];
            }
            if ($eventDetails['response']['details']['location']['address2']) {
                $data['address'].=',' . $eventDetails['response']['details']['location']['address2'];
            }
            if ($eventDetails['response']['details']['location']['cityName']) {
                $data['address'].=',' . $eventDetails['response']['details']['location']['cityName'];
            }
        }
        //$data['pricingTabUrl']=commonHelperGetPageUrl('dashboard-ticketwidget-pricing-tab', $inputArray['eventId'].'&'.$inputArray['code']);
        $data['year'] = allTimeFormats(' ', 17);
        $data['loginLink'] = site_url() . "login/";
        $data['email'] = $input['email'];
        $data['title'] = $eventDetails['response']['details']['title'];
        $data['eventUrl'] = $eventDetails['response']['details']['eventUrl'];
        $data['eventMode'] = $eventDetails['response']['details']['eventMode'];
        $data['siteUrl'] = site_url();
        $data['supportLink'] = commonHelperGetPageUrl('contactUs');
        $data['meraeventLogoPath'] = $this->ci->config->item('images_static_path') . 'me-logo.png';
        $message = $this->ci->parser->parse_string($templateMessage, $data, TRUE);
        $sentmessageInputs['messageid'] = $templateId;
        $emailResponse = $this->emailHandler->sendEmail($from, $to, $subject, $message, '', '', '', $sentmessageInputs);
        if ($emailResponse['status']) {
            $output = parent::createResponse(TRUE, SUCCESS_ADDED_PROMOTER, STATUS_CREATED);
            return $output;
        }
    }

    public function getOfflineTickets($inputArray) {

        $this->ci->Offlinepromoterticketmapping_model->resetVariable();
        $selectInput['id'] = $this->ci->Offlinepromoterticketmapping_model->id;
        $selectInput['status'] = $this->ci->Offlinepromoterticketmapping_model->status;
        $selectInput['promoterid'] = $this->ci->Offlinepromoterticketmapping_model->promoterid;
        $selectInput['ticketid'] = $this->ci->Offlinepromoterticketmapping_model->ticketid;
        $selectInput['eventid'] = $this->ci->Offlinepromoterticketmapping_model->eventid;
        $this->ci->Offlinepromoterticketmapping_model->setSelect($selectInput);
        $where[$this->ci->Offlinepromoterticketmapping_model->promoterid] = $inputArray['id'];
        $where[$this->ci->Offlinepromoterticketmapping_model->eventid] = $inputArray['eventId'];
        if (isset($inputArray['ticketStatus'])) {
            $where[$this->ci->Offlinepromoterticketmapping_model->status] = 1;
        }
        $where[$this->ci->Offlinepromoterticketmapping_model->deleted] = 0;
        $this->ci->Offlinepromoterticketmapping_model->setWhere($where);
        $offlinePromoterTicketData = $this->ci->Offlinepromoterticketmapping_model->get();
        if ($offlinePromoterTicketData) {
            $output['status'] = TRUE;
            $output["response"]["offline"] = $offlinePromoterTicketData;
            $output['statusCode'] = STATUS_OK;
            return $output;
        } else {
            $output['status'] = FALSE;
            $output["response"]["messages"][] = ERROR_NO_DATA;
            $output['statusCode'] = STATUS_NO_DATA;
            return $output;
        }
    }
    
        public function updateOfflinePromoterTickets($inputArray) {
        $this->ci->Offlinepromoterticketmapping_model->resetVariable();
        $dbTicketIdList = $inputArray['dbTicketIdList'];
        $unselectedTicketIds = array();
        if (count($dbTicketIdList) > 0) {
            //Updating the status which are not seleted while editing 
            $unselectedTicketIds = array_diff($dbTicketIdList, $inputArray['ticketIds']); //status=0
            if (count($unselectedTicketIds) > 0) {
                $updateTicket[$this->ci->Offlinepromoterticketmapping_model->status] = 0;
                $whereInArray[$this->ci->Offlinepromoterticketmapping_model->ticketid] = $unselectedTicketIds;
                $this->ci->Offlinepromoterticketmapping_model->setWhereIns($whereInArray);
                $where[$this->ci->Offlinepromoterticketmapping_model->promoterid] = $inputArray['id'];
                $where[$this->ci->Offlinepromoterticketmapping_model->eventid] = $inputArray['eventId'];
                $this->ci->Offlinepromoterticketmapping_model->setWhere($where);
                $this->ci->Offlinepromoterticketmapping_model->setInsertUpdateData($updateTicket);
                $updateTicketsData = $this->ci->Offlinepromoterticketmapping_model->update_data();
            }
        }
        if (count($inputArray['ticketIds']) > 0) {
            foreach ($inputArray['ticketIds'] as $key => $value) {
                if (!in_array($value, $dbTicketIdList)) { //Adding new ticket id
                    $createTicket[$this->ci->Offlinepromoterticketmapping_model->ticketid] = $value;
                    $createTicket[$this->ci->Offlinepromoterticketmapping_model->status] = 1;
                    $createTicket[$this->ci->Offlinepromoterticketmapping_model->promoterid] = $inputArray['id'];
                    $createTicket[$this->ci->Offlinepromoterticketmapping_model->eventid] = $inputArray['eventId'];
                    $this->ci->Offlinepromoterticketmapping_model->setInsertUpdateData($createTicket);
                    $ticketsData = $this->ci->Offlinepromoterticketmapping_model->insert_data();
                } else if (!in_array($value, $unselectedTicketIds)) {//Updating the status of selected tickets
                    $updateTicket[$this->ci->Offlinepromoterticketmapping_model->status] = 1;
                    $whereArray[$this->ci->Offlinepromoterticketmapping_model->ticketid] = $value;
                    $whereArray[$this->ci->Offlinepromoterticketmapping_model->promoterid] = $inputArray['id'];
                    $whereArray[$this->ci->Offlinepromoterticketmapping_model->eventid] = $inputArray['eventId'];
                    $this->ci->Offlinepromoterticketmapping_model->setWhere($whereArray);
                    $this->ci->Offlinepromoterticketmapping_model->setInsertUpdateData($updateTicket);
                    $updateTicketsData = $this->ci->Offlinepromoterticketmapping_model->update_data();
                }
            }
        }
        $output = parent::createResponse(TRUE, "Updated the ticket discount data", STATUS_UPDATED);
        return $output;
    }

}

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

class Attendee_handler extends Handler {

    var $ci;

    public function __construct() {
        parent::__construct();
        $this->ci = parent::$CI;
        $this->ci->load->model('Attendee_model');
    }

    public function getListByEventsignupIds($input) {
        $this->ci->form_validation->pass_array($input);
        $this->ci->form_validation->set_rules('eventsignupids', 'eventsignupids', 'required_strict|is_array');
        $this->ci->form_validation->set_rules('ticketids', 'ticketids', 'is_array');
        $this->ci->form_validation->set_rules('primary', 'primary', 'enable');
        if ($this->ci->form_validation->run() == FALSE) {
            $response = $this->ci->form_validation->get_errors();
            $output['status'] = FALSE;
            $output['response']['messages'] = $response['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $eventSignupIds = $input['eventsignupids'];
        $primary = isset($input['primary']) ? $input['primary'] : 0;
        $ticketIds = isset($input['ticketids']) ? $input['ticketids'] : array();
        $this->ci->Attendee_model->resetVariable();
        $select['id'] = $this->ci->Attendee_model->id;
        $select['ticketid'] = $this->ci->Attendee_model->ticketid;
        $select['primary'] = $this->ci->Attendee_model->primary;
        $select['eventsignupid'] = $this->ci->Attendee_model->eventsignupid;
        $this->ci->Attendee_model->setSelect($select);
        $where_in[$this->ci->Attendee_model->eventsignupid] = $eventSignupIds;
        if ($primary == 1) {
            $where_in[$this->ci->Attendee_model->primary] = $primary;
        }
        if (count($ticketIds) > 0) {
            $where_in[$this->ci->Attendee_model->ticketid] = $ticketIds;
        }
        $this->ci->Attendee_model->setWhereIns($where_in);
        
        $where[$this->ci->Attendee_model->deleted] = 0;
        $this->ci->Attendee_model->setWhere($where);

        $attendeeResponse = $this->ci->Attendee_model->get();
        //echo $this->ci->db->last_query();exit;
        if (count($attendeeResponse) == 0) {
            $output['status'] = TRUE;
            $output['response']['messages'][] = ERROR_NO_ATTENDEES_DATA;
            $output['response']['total'] = 0;
            $output['statusCode'] = STATUS_OK;
            return $output;
        }
        $output['status'] = TRUE;
        $output['response']['attendeeList'] = $attendeeResponse;
        $output['response']['total'] = count($attendeeResponse);
        $output['statusCode'] = STATUS_OK;
        return $output;
    }

    /*
     * Function to add Attendee
     * @access	public
     * @param
     *          - eventSignupId (required)
     *          - ticketId (required)
     *          - primary (optional)
     *          - order (optional)
     * @return	response with either status `TRUE` or `FALSE`
     */
    public function add($insertArr) {

        $this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($insertArr);
        $this->ci->form_validation->set_rules('eventSignupId', 'event signup id', 'required_strict|is_natural_no_zero');
        $this->ci->form_validation->set_rules('ticketId', 'ticket id', 'required_strict|is_natural_no_zero');

        if (!empty($insertArr) && $this->ci->form_validation->run() == FALSE) {
            $response = $this->ci->form_validation->get_errors('message');
            $output['status'] = FALSE;
            $output['response']['messages'] = $response['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $this->ci->Attendee_model->resetVariable();
        $createAttendee[$this->ci->Attendee_model->eventsignupid] = $insertArr['eventSignupId'];
        $createAttendee[$this->ci->Attendee_model->ticketid] = $insertArr['ticketId'];
        if ($insertArr['primary'] != '') {
            $createAttendee[$this->ci->Attendee_model->primary] = $insertArr['primary'];
        }
        if ($insertArr['order'] != '') {
            $createAttendee[$this->ci->Attendee_model->order] = $insertArr['order'];
        }
        $this->ci->Attendee_model->setInsertUpdateData($createAttendee);
        $addAttendeeId = $this->ci->Attendee_model->insert_data();

        if ($addAttendeeId) {
            $output['status'] = TRUE;
            $output['response']['messages'][] = SUCCESS_ATTENDEE_ADDED;
            $output['response']['attendeeId'] = $addAttendeeId;
            $output['statusCode'] = STATUS_CREATED;
            return $output;
        } else {
            $output['status'] = FALSE;
            $output['response']['messages'][] = ERROR_ADD_ATTENDEE;
            $output['statusCode'] = STATUS_SERVER_ERROR;
            return $output;
        }
    }

}

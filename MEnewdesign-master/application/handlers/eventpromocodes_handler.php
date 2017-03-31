<?php

/**
 * Collaborator related business logic will be defined in this class
 *
 * @package		CodeIgniter
 * @author		Qison  Dev Team
 * @copyright	Copyright (c) 2015, MeraEvents.
 * @Version		Version 1.0
 * @Since       Class available since Release Version 1.0 
 * @Created     11-06-2015
 * @Last Modified 11-06-2015
 */
require_once (APPPATH . 'handlers/handler.php');

//require_once(APPPATH . 'handlers/messagetemplate_handler.php');
//require_once(APPPATH . 'handlers/ticket_handler.php');

class Eventpromocodes_handler extends Handler {

    var $ci;

    public function __construct() {
        parent::__construct();
        $this->ci = parent::$CI;
        $this->ci->load->model('Eventpromocodes_model');
    }

    public function getByPromocodeAndEventId($inputArray) {
        $this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('eventid', 'eventid', 'required_strict|is_natural_no_zero');
        $this->ci->form_validation->set_rules('promocode', 'promocode', 'required_strict');
        //$this->ci->form_validation->set_rules('quantity', 'quantity', 'required_strict|is_natural_no_zero');
        if ($this->ci->form_validation->run() == FALSE) {
            $response = $this->ci->form_validation->get_errors('message');
            $output['status'] = FALSE;
            $output['response']['messages'] = $response['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $eventId = $inputArray['eventid'];
        $promocode = $inputArray['promocode'];
        //$quantity = $inputArray['quantity'];
        $this->ci->Eventpromocodes_model->resetVariable();
        $select['id'] = $this->ci->Eventpromocodes_model->id;
        $select['remainingquantity'] = "(".$this->ci->Eventpromocodes_model->totalquantity." - ". $this->ci->Eventpromocodes_model->soldquantity.")";
        $select['totalquantity'] = $this->ci->Eventpromocodes_model->totalquantity;
        $select['soldquantity'] = $this->ci->Eventpromocodes_model->soldquantity;
        $select['promocode'] = $this->ci->Eventpromocodes_model->promocode;
        $where[$this->ci->Eventpromocodes_model->eventid] = $eventId;
        $where[$this->ci->Eventpromocodes_model->deleted] = 0;
        $where[$this->ci->Eventpromocodes_model->status] = 1;
        $where[$this->ci->Eventpromocodes_model->promocode] = $promocode;

        $this->ci->Eventpromocodes_model->setWhere($where);
        $this->ci->Eventpromocodes_model->setSelect($select);
        $this->ci->Eventpromocodes_model->setRecords(1);
        $eventPromoCodesList = $this->ci->Eventpromocodes_model->get();

        if (count($eventPromoCodesList) == 0) {
            $output['status'] = TRUE;
            $output['response']['total'] = 0;
            $output['response']['messages'][] = ERROR_NO_DATA;
            return $output;
        }
        $output['status'] = TRUE;
        $output['response']['eventPromoCodesList'] = $eventPromoCodesList;
        $output['response']['total'] = count($eventPromoCodesList);
        $output['response']['messages'][] = [];
        return $output;
    }

    public function update($inputArray) {
        $this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('eventid', 'eventid', 'required_strict|is_natural_no_zero');
        $this->ci->form_validation->set_rules('quantity', 'quantity', 'required_strict|is_natural_no_zero');
        $this->ci->form_validation->set_rules('eventsignupid', 'eventsignupid', 'required_strict|is_natural_no_zero');
        if ($this->ci->form_validation->run() == FALSE) {
            $response = $this->ci->form_validation->get_errors('message');
            $output['status'] = FALSE;
            $output['response']['messages'] = $response['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $eventId = $inputArray['eventid'];
        $eventSignupId = $inputArray['eventsignupid'];
        $quantity = $inputArray['quantity'];
        require_once (APPPATH . 'handlers/attendee_handler.php');
        $attendeeHandler=new Attendee_handler();
        $inputAttendee['eventsignupids']=array($eventSignupId);
        $attendeeResponse=$attendeeHandler->getListByEventsignupIds($inputAttendee);
        //print_r($attendeeResponse);exit;
        if($attendeeResponse['status'] && $attendeeResponse['response']['total']>0){
            foreach ($attendeeResponse['response']['attendeeList'] as $key => $value) {
                $attendeeIds[]=$value['id'];
            }
            require_once (APPPATH . 'handlers/attendeedetail_handler.php');
            $attendeeDetailHandler=new Attendeedetail_handler();
            $inputAttendeeDetail['attendeeids']=$attendeeIds;
            $eventIdsData=  json_decode(EVENT_PROMOCODES,true);
            $inputAttendeeDetail['customfieldids']=array($eventIdsData[$eventId]);
            $attendeeDetailResponse=$attendeeDetailHandler->getListByAttendeeIds($inputAttendeeDetail);
            $promoCodes=[];
            //print_r($attendeeDetailResponse);exit;
            if($attendeeDetailResponse['status'] && $attendeeDetailResponse['response']['total']>0){
                foreach ($attendeeDetailResponse['response']['attendeedetailList'] as $value) {
                    if(!in_array($value['value'],$promoCodes)){
                        $promoCodes[]=$value['value'];
                    }
                }
                //print_r($promoCodes);exit;
                foreach ($promoCodes as $value) {
                    $this->ci->Eventpromocodes_model->resetVariable();
                    $updateCodes=array();
                    $setData[$this->ci->Eventpromocodes_model->soldquantity] = $this->ci->Eventpromocodes_model->soldquantity." + ". $quantity;
                    $this->ci->Eventpromocodes_model->setInsertUpdateData($updateCodes);
                    $where[$this->ci->Eventpromocodes_model->eventid]=$eventId;
                    $where[$this->ci->Eventpromocodes_model->promocode]=$value;
                    $this->ci->Eventpromocodes_model->setWhere($where);
                    $updateStatus[] = $this->ci->Eventpromocodes_model->update_data($setData);
                    //echo $this->ci->db->last_query();exit;
                }
                $output['status'] = TRUE;
                $output['response']['messages'][] = array();
                $output['response']['updateStatus']=true;
                $output['response']['total']=count($promoCodes);
                $output['statusCode'] = STATUS_OK;
                return $output;
            }else{
                return $attendeeDetailResponse;
            }
        }else{
            return $attendeeResponse;
        }
    }
}

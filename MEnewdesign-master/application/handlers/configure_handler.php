<?php

/**
 * City related business logic will be defined in this class
 *
 * @package		CodeIgniter
 * @author		Qison  Dev Team
 * @copyright	Copyright (c) 2015, MeraEvents.
 * @Version		Version 1.0
 * @Since       Class available since Release Version 1.0 
 * @Created     30-07-2015
 * @Last Modified  
 * @Last Modified by Anil Kumar M 
 */
require_once(APPPATH . 'handlers/handler.php');
require_once(APPPATH . 'handlers/ticket_handler.php');
require_once(APPPATH . 'handlers/event_handler.php');
require_once(APPPATH . 'handlers/eventsignup_handler.php');
require_once (APPPATH . 'handlers/user_handler.php');
require_once (APPPATH . 'handlers/orderlog_handler.php');

class Configure_handler extends Handler {

    var $searchHandler;
    var $ci;

    public function __construct() {
        parent::__construct();
        $this->ci = parent::$CI;
    }

    /*
     * Function to get the custom fields of an event or order
     *
     * @access	public
     * @param	$inputArray contains
     * 				Either eventId - integer
     * 				Or orderId - integer are required
     * 				collectMultipleAttendeeInfo - 1 or 0
     * 				customFieldId - integer
     * 				ticketid - integer
     * @return	array
     */

    public function getCustomFields($inputArray) {

        if (isset($inputArray['orderId']) && $inputArray['orderId'] == '' && $inputArray['eventId'] == '') {
            $output['status'] = FALSE;
            $output['response']['messages'][] = ERROR_EVENTID_ORDERID_REQUIRED;
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }

        if (isset($inputArray['orderId']) && $inputArray['orderId'] != '') {
            $this->ci->form_validation->reset_form_rules();
            $this->ci->form_validation->pass_array($inputArray);
            $this->ci->form_validation->set_rules('orderId', 'order id', 'alpha_numeric');

            if ($this->ci->form_validation->run() == FALSE) {
                $response = $this->ci->form_validation->get_errors('message');
                $output['status'] = FALSE;
                $output['response']['messages'][] = $response['message'];
                $output['statusCode'] = STATUS_BAD_REQUEST;
                return $output;
            }

            //$this->eventsignupHandler = new Eventsignup_handler();

            $orderLogInput['orderId'] = $inputArray['orderId'];
            $this->orderlogHandler = new Orderlog_handler();
            $orderLogData = $this->orderlogHandler->getOrderlog($orderLogInput);
            if ($orderLogData['status'] && $orderLogData['response']['total'] == 0) {

                $output['status'] = TRUE;
                $output['response']['messages'][] = ERROR_NO_DATA;
                $output['statusCode'] = STATUS_OK;
                return $output;
            }
            $orderLogSessionData = unserialize($orderLogData['response']['orderLogData']['data']);

            $eventId = $orderLogSessionData['eventid'];
        } elseif ($inputArray['eventId'] != '') {
            $validationStatus = $this->validateGetCustomFields($inputArray);
            if ($validationStatus['error']) {
                $output['status'] = FALSE;
                $output['response']['messages'] = $validationStatus['message'];
                $output['statusCode'] = STATUS_BAD_REQUEST;
                return $output;
            }
            $eventId = $inputArray['eventId'];
        }
        $fieldTypes=array();
        if (isset($inputArray['fieldtypes']) && is_array($inputArray['fieldtypes']) && count($inputArray['fieldtypes']) > 0) {
            $fieldTypes = $inputArray['fieldtypes'];
        }
        $ticketIds = isset($inputArray['ticketid']) ? $inputArray['ticketid'] : array();
        $commonfieldids = isset($inputArray['commonfieldids']) ? $inputArray['commonfieldids'] : array();
        $this->ci->load->model('Customfield_model');
        $this->ci->Customfield_model->resetVariable();
        $selectInput['id'] = $this->ci->Customfield_model->id;
        $selectInput['eventid'] = $this->ci->Customfield_model->eventid;
        $selectInput['fieldname'] = $this->ci->Customfield_model->fieldname;
        $selectInput['fieldtype'] = $this->ci->Customfield_model->fieldtype;
        $selectInput['commonfieldid'] = $this->ci->Customfield_model->commonfieldid;
        $selectInput['fieldmandatory'] = $this->ci->Customfield_model->fieldmandatory;
        $selectInput['order'] = $this->ci->Customfield_model->order;
        $selectInput['displayonticket'] = $this->ci->Customfield_model->displayonticket;
        $selectInput['displaystatus'] = $this->ci->Customfield_model->displaystatus;
        $selectInput['fieldlevel'] = $this->ci->Customfield_model->fieldlevel;
        $selectInput['ticketid'] = $this->ci->Customfield_model->ticketid;
        $selectInput['customvalidation'] = $this->ci->Customfield_model->customvalidation;

        $this->ci->Customfield_model->setSelect($selectInput);


        $where[$this->ci->Customfield_model->eventid] = $eventId;
        $where[$this->ci->Customfield_model->deleted] = 0;
        if (!isset($inputArray['allfields']) || (isset($inputArray['allfields']) && $inputArray['allfields'] == 0)) {
            $where[$this->ci->Customfield_model->displaystatus] = 1;
            if ($ticketIds == '') {
                $where[$this->ci->Customfield_model->fieldlevel] = 'event';
            }
        }
        if (isset($inputArray['customFieldId']) && $inputArray['customFieldId'] > 0) {
            $where[$this->ci->Customfield_model->id] = $inputArray['customFieldId'];
        }
        if (isset($inputArray['activeCustomField']) && $inputArray['activeCustomField'] > 0) {
            $where[$this->ci->Customfield_model->displaystatus] = 1;
        }
        if (isset($inputArray['collectMultipleAttendeeInfo']) && $inputArray['collectMultipleAttendeeInfo'] == 0) {
            $where[$this->ci->Customfield_model->fieldlevel] = 'event';
        }
        $setOrwhere = array();
        if (isset($inputArray['displayonticket']) && $inputArray['displayonticket'] == 1) {
            $setOrwhere[$this->ci->Customfield_model->displayonticket] = 1;
            $setOrwhere[$this->ci->Customfield_model->commonfieldid] = 1;
        }
        if (isset($inputArray['mobileNumber']) && $inputArray['mobileNumber'] == 1) {
            $setOrwhere[$this->ci->Customfield_model->commonfieldid] = 3;
        }
        $whereIns = array();
        if (count($ticketIds) > 0) {
            $whereIns[$this->ci->Customfield_model->ticketid] = $ticketIds;
        }
        if (count($commonfieldids) > 0) {
            $whereIns[$this->ci->Customfield_model->commonfieldid] = $commonfieldids;
        }
        if (count($fieldTypes) > 0) {
            $whereIns[$this->ci->Customfield_model->fieldtype] = $fieldTypes;
        }
        $this->ci->Customfield_model->setWhere($where);
        $this->ci->Customfield_model->setWhereIns($whereIns);
        $this->ci->Customfield_model->setOrWhere($setOrwhere);
        $orderBy = array();
        $orderBy[] = $this->ci->Customfield_model->order;
        $orderBy[] = $this->ci->Customfield_model->fieldlevel;
        if (isset($inputArray['statuslabels']) && $inputArray['statuslabels'] == 1) {
            $orderBy[] = $this->ci->Customfield_model->displaystatus . " DESC ";
        }
        $this->ci->Customfield_model->setOrderBy($orderBy);
        $customFieldResponse = $this->ci->Customfield_model->get();
        //echo $this->ci->db->last_query();
        //exit;
        if (count($customFieldResponse) == 0) {
            $output['status'] = TRUE;
            $output['response']['messages'][] = ERROR_NO_DATA;
            $output['response']['total'] = 0;
            $output['statusCode'] = STATUS_OK;
            return $output;
        }
        $ticketHandler = new Ticket_handler();
        $inputTickets['eventId'] = $eventId;
        if (isset($inputArray['ticketDetailInput']) && count($inputArray['ticketDetailInput']) > 0) {
            $inputTickets['ticketIds'] = $inputArray['ticketDetailInput'];
        }
//        $this->eventHandler = new Event_handler();
//        $event['eventId']=$eventId;
//        $event=$this->eventHandler->getEventTimeZoneName($event);
//        $inputTickets['timeZoneName'] = $event['response']['details']['location']['timeZoneName'];

        if(isset($inputArray['eventTimezoneId']) && $inputArray['eventTimezoneId'] > 0) {
            $inputTickets['eventTimezoneId'] = $inputArray['eventTimezoneId'];
        }
        if(isset($inputArray['disableSessionLockTickets']) && $inputArray['disableSessionLockTickets']) {
            $inputTickets['disableSessionLockTickets'] = $inputArray['disableSessionLockTickets'];
        }
        $eventTickets = $ticketHandler->getEventTicketList($inputTickets);
        if ($eventTickets['status'] && $eventTickets['response']['total'] > 0) {
            $indexedTickets = commonHelperGetIdArray($eventTickets['response']['ticketList']);
        } else {
            return $eventTickets;
        }
        foreach ($customFieldResponse as $key => $value) {
            if (isset($indexedTickets[$value['ticketid']])) {
                $customFieldResponse[$key]['ticketName'] = $indexedTickets[$value['ticketid']]['name'];
            } else {
                $customFieldResponse[$key]['ticketName'] = '';
            }
        }
        $output['status'] = TRUE;
        $output['response']['customFields'] = $customFieldResponse;
        $output['response']['total'] = count($customFieldResponse);
        $output['response']['messages'] = array();
        $output['statusCode'] = STATUS_OK;
        return $output;
    }

    /**
     * To get the event  & ticket level custom fields
     * $data['eventId']
     */
    public function getCustomFieldValues($inputArray) {
        $validationStatus = $this->validateGetCustomFieldValues($inputArray);

        if ($validationStatus['error']) {
            $output['status'] = FALSE;
            $output['response']['messages'] = $validationStatus['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $this->ci->load->model('Customfieldvalue_model');
        $this->ci->Customfieldvalue_model->resetVariable();
        $selectInput['id'] = $this->ci->Customfieldvalue_model->id;
        $selectInput['customfieldid'] = $this->ci->Customfieldvalue_model->customfieldid;
        $selectInput['value'] = $this->ci->Customfieldvalue_model->value;
        $selectInput['isdefault'] = $this->ci->Customfieldvalue_model->isdefault;


        $this->ci->Customfieldvalue_model->setSelect($selectInput);

        if (isset($inputArray['customFieldId']) && $inputArray['customFieldId'] > 0) {
            $where[$this->ci->Customfieldvalue_model->customfieldid] = $inputArray['customFieldId'];
        }

        $where[$this->ci->Customfieldvalue_model->deleted] = 0;
        $this->ci->Customfieldvalue_model->setWhere($where);

        if (isset($inputArray['customFieldIdArray']) && count($inputArray['customFieldIdArray']) > 0) {
            $whereIn[$this->ci->Customfieldvalue_model->customfieldid] = $inputArray['customFieldIdArray'];
            $this->ci->Customfieldvalue_model->setWhereIns($whereIn);
        }
        $customFieldResponse = $this->ci->Customfieldvalue_model->get();
        if (count($customFieldResponse) == 0) {
            $output['status'] = TRUE;
            $output['response']['messages'][] = ERROR_NO_DATA;
            $output['response']['total'] = 0;
            $output['statusCode'] = STATUS_OK;
            return $output;
        }
        if (isset($inputArray['customFieldValueIds'])) {
            $fieldValuesInArray = array();
            foreach ($customFieldResponse as $value) {
                $fieldValuesInArray[] = $value['id'];
            }
        } else {
            $fieldValuesInArray = $customFieldResponse;
        }
        $output['status'] = TRUE;
        $output['response']['fieldValuesInArray'] = $fieldValuesInArray;
        $output['response']['total'] = count($fieldValuesInArray);
        $output['response']['messages'] = array();
        $output['statusCode'] = STATUS_OK;
        return $output;
    }

    //To validate the cityStateInsert 
    public function validateGetCustomFields($inputs) {
        $errorMessages = array();
        $this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($inputs);
        $this->ci->form_validation->set_rules('eventId', 'Event ID or Order ID', 'is_natural_no_zero|required_strict');
        $this->ci->form_validation->set_rules('collectMultipleAttendeeInfo', 'collectMultipleAttendeeInfo', 'enable');
        $this->ci->form_validation->set_rules('displayonticket', 'displayonticket', 'enable');
        $this->ci->form_validation->set_rules('allfields', 'allfields', 'enable');
        $this->ci->form_validation->set_rules('ticketid', 'ticketid', 'is_natural');
        if ($this->ci->form_validation->run() === FALSE) {
            $errorMessages = $this->ci->form_validation->get_errors();
            return $errorMessages;
        }
        $errorMessages['error'] = FALSE;
        return $errorMessages;
    }

    //To validate the Customfield Values related information 
    public function validateGetCustomFieldValues($inputs) {
        $errorMessages = array();
        $this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($inputs);
        if (isset($inputs['customFieldId'])) {
            $this->ci->form_validation->set_rules('customFieldId', 'Customfield id', 'is_natural_no_zero|required_strict');
        }
        if (isset($inputs['customFieldIdArray'])) {
            $this->ci->form_validation->set_rules('customFieldIdArray', 'Customfield id array', 'required_strict|is_array');
        }


        if ($this->ci->form_validation->run() === FALSE) {
            $errorMessages = $this->ci->form_validation->get_errors();
            return $errorMessages;
        }
        $errorMessages['error'] = FALSE;
        return $errorMessages;
    }

    /**
     * To insert the custome fields
     * @param type $inputArray[eventId]
     * @param type $inputArray
     * @return int
     */
    public function manageCustomField($inputArray) {
        $this->ci->load->model('Customfield_model');
        $validationStatus = $this->insertCustomFieldValidate($inputArray);
        if ($validationStatus['error']) {
            $output['status'] = FALSE;
            $output['response']['messages'] = $validationStatus['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $eventId = $inputArray['eventId'];
        $customFieldId = isset($inputArray['customFieldId']) ? $inputArray['customFieldId'] : 0;
        $insertCustomFieldsArray = array();
        $customFieldValues = isset($inputArray['customFieldValues']) ? $inputArray['customFieldValues'] : array();
        $this->ci->Customfield_model->resetVariable();
        //$insertCustomFieldsArray[$this->ci->Customfield_model->deleted] = ($inputArray['deleted']) ? $inputArray['deleted'] : 0;
        if ($customFieldId > 0) {
            $insertCustomFieldsArray[$this->ci->Customfield_model->fieldname] = $inputArray['fieldName'];
            $insertCustomFieldsArray[$this->ci->Customfield_model->fieldtype] = $inputArray['fieldType'];
            $insertCustomFieldsArray[$this->ci->Customfield_model->fieldmandatory] = isset($inputArray['fieldMandatory']) ? $inputArray['fieldMandatory'] : 0;
            $where[$this->ci->Customfield_model->id] = $customFieldId;
            $this->ci->Customfield_model->setWhere($where);
//            $insertCustomFieldsArray[$this->ci->Customfield_model->order] = $inputArray['order'];
        } else {
            $inputCheck['eventid'] = $eventId;
            $inputCheck['fieldname'] = $inputArray['fieldName'];
            $inputCheck['ticketid'] = $inputArray['ticketId'];
            $isExists = $this->checkFeildAlreadyExists($inputCheck);
            if ($isExists['status'] && $isExists['response']['total'] > 0) {
                if ($isExists['response']['isExists']) {
                    $output['status'] = FALSE;
                    $output["response"]["messages"][] = ERROR_DUPLICATE_CUSTOM_FIELD;
                    $output['statusCode'] = STATUS_CONFLICT;
                    return $output;
                }
            } else {
                return $isExists;
            }
            $insertCustomFieldsArray[$this->ci->Customfield_model->eventid] = $inputArray['eventId'];
            $insertCustomFieldsArray[$this->ci->Customfield_model->fieldname] = $inputArray['fieldName'];
            $insertCustomFieldsArray[$this->ci->Customfield_model->fieldtype] = $inputArray['fieldType'];
            $insertCustomFieldsArray[$this->ci->Customfield_model->fieldmandatory] = isset($inputArray['fieldMandatory']) ? $inputArray['fieldMandatory'] : 0;

            $insertCustomFieldsArray[$this->ci->Customfield_model->displayonticket] = ($inputArray['displayOnTicket']) ? $inputArray['displayOnTicket'] : 0;
            $insertCustomFieldsArray[$this->ci->Customfield_model->ticketid] = ($inputArray['ticketId'] > 0) ? $inputArray['ticketId'] : "";
            $insertCustomFieldsArray[$this->ci->Customfield_model->fieldlevel] = ($inputArray['ticketId'] > 0) ? 'ticket' : "event";
            $insertCustomFieldsArray[$this->ci->Customfield_model->order] = $this->maxCustomFieldOrder($inputArray);
        }
        $this->ci->Customfield_model->setInsertUpdateData($insertCustomFieldsArray);
        if ($customFieldId > 0) {
            $response = $this->ci->Customfield_model->update_data();
            $mangeStatus = 'update';
        } else {
            $response = $this->ci->Customfield_model->insert_data();
            $mangeStatus = "Insert";
            $inputArray['customFieldId'] = $response;
        }


        if ($response) {
            if ($mangeStatus == 'update') {
                $this->changeCustomfieldValuesStatus($inputArray);
            }
            //Insert the custom field values
            if (count($customFieldValues) > 0) {
                $customFieldValuesResponse = $this->insertCustomfieldValues($inputArray);
                return $customFieldValuesResponse;
            } else {
                $output['status'] = TRUE;
                $output['response']['affectedRows'] = $response;
                $output['response']['total'] = 1;
                $output["response"]["messages"][] = SUCCESS_CUSTOMFIELD;
                $output['statusCode'] = STATUS_CREATED;
            }
        }
        $output['status'] = FALSE;
        $output["response"]["messages"][] = ERROR_SOMETHING_WENT_WRONG;
        $output['statusCode'] = STATUS_SERVER_ERROR;
        return $output;
    }

    /**
     * To validate the custom field information
     * @param type $inputs
     * @return boolean
     */
    public function insertCustomFieldValidate($inputs) {
        $errorMessages = array();
        $this->ci->form_validation->pass_array($inputs);
        $this->ci->form_validation->set_rules('eventId', 'Event ID', 'is_natural_no_zero|required_strict');
        $this->ci->form_validation->set_rules('fieldName', 'Field Name', 'required_strict');
        $this->ci->form_validation->set_rules('ticketId', 'ticketId', 'is_natural');
        $this->ci->form_validation->set_rules('customfieldId', 'customfieldId', 'is_natural_no_zero');
        if ($this->ci->form_validation->run() === FALSE) {
            $errorMessages = $this->ci->form_validation->get_errors();
            return $errorMessages;
        }
        $errorMessages['error'] = FALSE;
        return $errorMessages;
    }

    /**
     * To insert the custome field values
     * @param type $inputArray[eventId]
     * @param type $inputArray
     * @return int
     */
    public function insertCustomfieldValues($inputArray) {
        $this->ci->load->model('Customfieldvalue_model');
        $validationStatus = $this->insertCustomFieldValidate($inputArray);
        if ($validationStatus['error']) {
            $output['status'] = FALSE;
            $output['response']['messages'] = $validationStatus['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }

        $insertCustomFieldsValueArray = array();
        foreach ($inputArray['customFieldValues'] as $key => $value) {
            if (strlen($value) > 0) {
                $insertCustomFieldsValueArray[$key]['value'] = $value;
                $insertCustomFieldsValueArray[$key]['customfieldid'] = $inputArray['customFieldId'];
            }
        }
        if (strlen($value) > 0) {
            $this->ci->Customfieldvalue_model->resetVariable();
            $this->ci->Customfieldvalue_model->setInsertUpdateData($insertCustomFieldsValueArray);
            $response = $this->ci->Customfieldvalue_model->insertMultiple_data();
        }
        $output['status'] = TRUE;
        $output['response']['affectedRows'] = $response;
        $output['response']['total'] = 1;
        $output["response"]["messages"][] = SUCCESS_CUSTOMFIELD;
        $output['statusCode'] = STATUS_CREATED;
        return $output;
    }

    /**
     * To validate the custom field information
     * @param type $inputs
     * @return boolean
     */
    public function insertCustomfieldValuesValidate($inputs) {
        $errorMessages = array();
        $this->ci->form_validation->pass_array($inputs);
        $this->ci->form_validation->set_rules('customfieldid', 'Customfield Id', 'is_natural_no_zero|required_strict');

        if ($this->ci->form_validation->run() === FALSE) {
            $errorMessages = $this->ci->form_validation->get_errors();
            return $errorMessages;
        }
        $errorMessages['error'] = FALSE;
        return $errorMessages;
    }

    public function changeCustomfieldStatus($data) {
        $this->ci->form_validation->pass_array($data);
        $this->ci->form_validation->set_rules('eventid', 'Event ID', 'is_natural_no_zero|required_strict');
        $this->ci->form_validation->set_rules('customfieldid', 'customfieldid', 'is_natural_no_zero');
        $this->ci->form_validation->set_rules('value', 'value', 'is_natural_no_zero');
        $this->ci->form_validation->set_rules('field', 'field type', 'required_strict');
        if ($this->ci->form_validation->run() === FALSE) {
            $output['status'] = FALSE;
            $output['response']['messages'] = $this->ci->form_validation->get_errors();
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $this->ci->load->model('Customfield_model');
        $eventId = $data['eventid'];
        $customfieldId = $data['customfieldid'];
        $field = $data['field'];
        $status = isset($data['value']) ? $data['value'] : 0;
        $insertArray = array();
        $inputCustomfields['eventId'] = $eventId;
        //$inputCustomfields['collectMultipleAttendeeInfo'] = 0;
        $inputCustomfields['allfields'] = 1;
        $inputCustomfields['customFieldId'] = $customfieldId;
        $customfieldsResponse = $this->getCustomFields($inputCustomfields);
        if ($customfieldsResponse['status'] && $customfieldsResponse['response']['total'] > 0) {
            $customFieldData = $customfieldsResponse['response']['customFields'];
        } else {
            return $customfieldsResponse;
        }
        $this->ci->Customfield_model->resetVariable();
        foreach ($customFieldData as $value) {
            if ($data['field'] == 'mandatory') {
                $status = 1;
                if ($value['fieldmandatory'] == 1) {
                    $status = 0;
                }
                $insertArray[$this->ci->Customfield_model->fieldmandatory] = $status;
            } elseif ($data['field'] == 'displayonticket') {
                $status = 1;
                if ($value['displayonticket'] == 1) {
                    $status = 0;
                }
                $insertArray[$this->ci->Customfield_model->displayonticket] = $status;
            } elseif ($data['field'] == 'displaystatus') {
                $status = 1;
                if ($value['displaystatus'] == 1) {
                    $status = 0;
                }
                $insertArray[$this->ci->Customfield_model->displaystatus] = $status;
            } elseif ($data['field'] == 'order') {
                if ($status > 0) {
                    $insertArray[$this->ci->Customfield_model->order] = $status;
                }
            }
        }
        if (count($insertArray) > 0) {
            $where[$this->ci->Customfield_model->eventid] = $eventId;
            $where[$this->ci->Customfield_model->id] = $customfieldId;
            $this->ci->Customfield_model->setWhere($where);

            $this->ci->Customfield_model->setInsertUpdateData($insertArray);
            $updateStatus = $this->ci->Customfield_model->update_data();
            $response = array('status' => $updateStatus, 'field' => $field, 'value' => $status, 'customfieldid' => $customfieldId);
            $output['status'] = TRUE;
            $output["response"]["updateCustomFieldResponse"] = $response;
            $output["response"]["messages"][] = SUCCESS_CUSTOMFIELD;
            $output["response"]["total"] = 1;
            $output['statusCode'] = STATUS_UPDATED;
            return $output;
        }
        $output['status'] = FALSE;
        $output['response']['messages'][] = ERROR_INVALID_DATA;
        $output['statusCode'] = STATUS_NO_DATA;
        return $output;
    }

    public function changeCustomfieldValuesStatus($data) {
        $data['customFieldValueIds'] = TRUE;
        $customFieldValuesResponse = $this->getCustomFieldValues($data);
        if ($customFieldValuesResponse['status'] && ($customFieldValuesResponse['response']['total']) > 0) {
            $this->ci->load->model('Customfieldvalue_model');

            $whereInArray = array();
            $whereInArray[] = "id";
            $whereInArray[] = $customFieldValuesResponse['response']['fieldValuesInArray'];
            $this->ci->Customfieldvalue_model->resetVariable();
            $this->ci->Customfieldvalue_model->setWhereIn($whereInArray);

            $insertArray = array();
            $insertArray[$this->ci->Customfieldvalue_model->deleted] = 1;
            $this->ci->Customfieldvalue_model->setInsertUpdateData($insertArray);
            $this->ci->Customfieldvalue_model->update_data();
        } else {
            return $customFieldValuesResponse;
        }

        $output['status'] = TRUE;
        $output["response"]["messages"][] = SUCCESS_CUSTOMFIELD;
        $output['statusCode'] = STATUS_OK;
        return $output;
    }

    /**
     * To Inert the event level default Custom fields 
     * 
     * @param type $inputArray
     * @return int
     */
    public function insertEventDefaultCustomFiedls($inputArray) {
        $validationStatus = $this->validateGetCustomFields($inputArray);
        if ($validationStatus['error']) {
            $output['status'] = FALSE;
            $output['response']['messages'] = $validationStatus['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $customFieldList = $this->getCommonCustomFields();

        if ($customFieldList['response']['total'] > 0) {
            $defaultCustomFieldsArray = array();
            foreach ($customFieldList['response']['customFields'] as $fieldIndex => $fieldValue) {
                $defaultCustomFieldsArray[$fieldIndex]['fieldname'] = $fieldValue['name'];
                $defaultCustomFieldsArray[$fieldIndex]['fieldtype'] = $fieldValue['type'];
                $defaultCustomFieldsArray[$fieldIndex]['commonfieldid'] = $fieldValue['id'];
                $defaultCustomFieldsArray[$fieldIndex]['order'] = $fieldValue['order'];
                $defaultCustomFieldsArray[$fieldIndex]['eventid'] = $inputArray['eventId'];
                $defaultCustomFieldsArray[$fieldIndex]['fieldmandatory'] = $fieldValue['fieldmandatory'];
                $defaultCustomFieldsArray[$fieldIndex]['displaystatus'] = $fieldValue['displaystatus'];
                $defaultCustomFieldsArray[$fieldIndex]['displayonticket'] = $fieldValue['displayonticket'];
            }
            $this->ci->load->model('Customfield_model');
            $this->ci->Customfield_model->resetVariable();
            $this->ci->Customfield_model->setInsertUpdateData($defaultCustomFieldsArray);

            $response = $this->ci->Customfield_model->insertMultiple_data();
        }
        if ($response) {
            $output['status'] = TRUE;
            $output['response']['affectedRows'] = $response;
            $output["response"]["messages"] = array();
            $output['statusCode'] = STATUS_CREATED;
            return $output;
        }
        $output['status'] = FALSE;
        $output["response"]["messages"][] = ERROR_SOMETHING_WENT_WRONG;
        $output['statusCode'] = STATUS_SERVER_ERROR;
        return $output;
    }

    /**
     * 
     * @param type $inputArray
     */
    public function maxCustomFieldOrder($inputArray) {
        if ($inputArray['order'] > 0) {
            return $inputArray['order'];
        } else {
            $this->ci->load->model('Customfield_model');
            $response = $this->ci->Customfield_model->getMaxCustomFieldOrderNo($inputArray);
            $order = (count($response) > 0) ? $response[0]['ordering'] : 1;
            $order++;
            return $order;
        }
    }

    public function checkFeildAlreadyExists($inputArray) {
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('eventid', 'Event ID', 'is_natural_no_zero|required_strict');
        $this->ci->form_validation->set_rules('fieldname', 'fieldname', 'required_strict');
        $this->ci->form_validation->set_rules('ticketid', 'ticketid', 'is_natural');
        if ($this->ci->form_validation->run() === FALSE) {
            $errorMessages = $this->ci->form_validation->get_errors();
            $output['status'] = FALSE;
            $output['response']['messages'] = $errorMessages;
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $eventId = $inputArray['eventid'];
        $filedName = $inputArray['fieldname'];
        $ticketId = isset($inputArray['ticketid']) ? $inputArray['ticketid'] : 0;
        $this->ci->load->model('Customfield_model');
        $this->ci->Customfield_model->resetVariable();
        $selectInput['id'] = $this->ci->Customfield_model->id;
        $selectInput['eventid'] = $this->ci->Customfield_model->eventid;
        $selectInput['fieldname'] = $this->ci->Customfield_model->fieldname;
        $this->ci->Customfield_model->setSelect($selectInput);

        $where[$this->ci->Customfield_model->eventid] = $eventId;
        $where[$this->ci->Customfield_model->deleted] = 0;
        $where[$this->ci->Customfield_model->fieldname] = $filedName;
        $whereIns = $setOrWhere = array();
        if ($ticketId > 0) {
            $whereIns[$this->ci->Customfield_model->ticketid] = $ticketId;
//            $whereIns[$this->ci->Customfield_model->commonfieldid . ' > '] = 0;
        }
        //$like[$this->ci->Customfield_model->fieldname] = $filedName;
        $this->ci->Customfield_model->setWhere($where);
        $this->ci->Customfield_model->setOrWhere($whereIns);
        //$this->ci->Customfield_model->setLike($like);
        $customFieldResponse = $this->ci->Customfield_model->get();
        $output['status'] = TRUE;
        $output["response"]["messages"] = array();
        $output["response"]["total"] = 1;
        $output['statusCode'] = STATUS_OK;
        $output['response']['isExists'] = TRUE;
        if (count($customFieldResponse) == 0) {
            $output['response']['isExists'] = FALSE;
        }
        return $output;
    }

    /**
     * To get the event   level common custom fields
     *      * $data['eventId']
     */
    public function getCommonCustomFields() {
        $this->ci->load->model('Commonfield_model');
        $this->ci->Commonfield_model->resetVariable();
        $selectInput['id'] = $this->ci->Commonfield_model->id;
        $selectInput['name'] = $this->ci->Commonfield_model->name;
        $selectInput['type'] = $this->ci->Commonfield_model->type;
        $selectInput['order'] = $this->ci->Commonfield_model->order;
        $selectInput['displaystatus'] = $this->ci->Commonfield_model->displaystatus;
        $selectInput['displayonticket'] = $this->ci->Commonfield_model->displayonticket;
        $selectInput['fieldmandatory'] = $this->ci->Commonfield_model->fieldmandatory;

        $this->ci->Commonfield_model->setSelect($selectInput);
        $where[$this->ci->Commonfield_model->deleted] = 0;
        $where[$this->ci->Commonfield_model->status] = 1;

        $this->ci->Commonfield_model->setWhere($where);
        $customFieldResponse = $this->ci->Commonfield_model->get();

        if (count($customFieldResponse) == 0) {
            $output['status'] = TRUE;
            $output['response']['messages'][] = ERROR_NO_DATA;
            $output['response']['total'] = 0;
            $output['statusCode'] = STATUS_OK;
            return $output;
        }
        $output['status'] = TRUE;
        $output['response']['customFields'] = $customFieldResponse;
        $output['response']['total'] = count($customFieldResponse);
        $output['response']['message'] = array();
        $output['statusCode'] = STATUS_OK;
        return $output;
    }

    public function getCustomFieldForms($inputArray) {

        $this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('orderId', 'order id', 'alpha_numeric');

        if ($this->ci->form_validation->run() == FALSE) {
            $response = $this->ci->form_validation->get_errors('message');
            $output['status'] = FALSE;
            $output['response']['messages'] = $response['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }

        $this->eventsignupHandler = new Eventsignup_handler();

        $orderLogInput['orderId'] = $inputArray['orderId'];
        $this->orderlogHandler = new Orderlog_handler();
        $orderLogData = $this->orderlogHandler->getOrderlog($orderLogInput);
        if ($orderLogData['status'] && $orderLogData['response']['total'] == 0) {
            $output['status'] = FALSE;
            $output['response']['messages'][] = ERROR_NO_DATA;
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $orderLogSessionData = unserialize($orderLogData['response']['orderLogData']['data']);
        $ticketArray = $orderLogSessionData['ticketarray'];
        $eventId = $orderLogSessionData['eventid'];

        $ticketIds = isset($inputArray['ticketid']) ? $inputArray['ticketid'] : array();
        $commonfieldids = isset($inputArray['commonfieldids']) ? $inputArray['commonfieldids'] : array();
        $this->ci->load->model('Customfield_model');
        $this->ci->Customfield_model->resetVariable();
        $selectInput['id'] = $this->ci->Customfield_model->id;
        $selectInput['fieldname'] = $this->ci->Customfield_model->fieldname;
        $selectInput['fieldtype'] = $this->ci->Customfield_model->fieldtype;
        $selectInput['commonfieldid'] = $this->ci->Customfield_model->commonfieldid;
        $selectInput['fieldmandatory'] = $this->ci->Customfield_model->fieldmandatory;
        $selectInput['fieldlevel'] = $this->ci->Customfield_model->fieldlevel;
        $selectInput['ticketid'] = $this->ci->Customfield_model->ticketid;
        $this->ci->Customfield_model->setSelect($selectInput);

        $where[$this->ci->Customfield_model->eventid] = $eventId;
        $where[$this->ci->Customfield_model->deleted] = 0;
        if (!isset($inputArray['allfields']) || (isset($inputArray['allfields']) && $inputArray['allfields'] == 0)) {
            $where[$this->ci->Customfield_model->displaystatus] = 1;
        }
        if (isset($inputArray['customFieldId']) && $inputArray['customFieldId'] > 0) {
            $where[$this->ci->Customfield_model->id] = $inputArray['customFieldId'];
        }

        // Getting Ticketing options of the event
        $ticketOptionInput['eventId'] = $eventId;
        $collectMultipleAttendeeInfo = 0;
        $this->eventHandler = new Event_handler();
        $ticketOptionArray = $this->eventHandler->getTicketOptions($ticketOptionInput);
        if ($ticketOptionArray['status'] && $ticketOptionArray['response']['total'] > 0) {
            $collectMultipleAttendeeInfo = $ticketOptionArray['response']['ticketingOptions'][0]['collectmultipleattendeeinfo'];
        }

        if ($collectMultipleAttendeeInfo == 0) {
            $where[$this->ci->Customfield_model->fieldlevel] = 'event';
        }

        $whereIns = array();
        if (count($ticketIds) > 0) {
            $whereIns[$this->ci->Customfield_model->ticketid] = $ticketIds;
        }
        if (count($commonfieldids) > 0) {
            $whereIns[$this->ci->Customfield_model->commonfieldid] = $commonfieldids;
        }
        $this->ci->Customfield_model->setWhere($where);
        $this->ci->Customfield_model->setWhereIns($whereIns);
        $orderBy[] = $this->ci->Customfield_model->fieldlevel;
        $this->ci->Customfield_model->setOrderBy($orderBy);
        $customFieldResponse = $this->ci->Customfield_model->get();
        if (count($customFieldResponse) == 0) {
            $output['status'] = TRUE;
            $output['response']['messages'][] = ERROR_NO_DATA;
            $output['response']['total'] = 0;
            $output['statusCode'] = STATUS_OK;
            return $output;
        }

        $ticketHandler = new Ticket_handler();
        $inputTickets['eventId'] = $eventId;
//        $event['eventId']=$eventId;
//        $event=$this->eventHandler->getEventTimeZoneName($event);
//        $inputTickets['timeZoneName'] = $event['response']['details']['location']['timeZoneName'];
        $eventTickets = $ticketHandler->getEventTicketList($inputTickets);
        if ($eventTickets['status'] && $eventTickets['response']['total'] > 0) {
            $indexedTickets = commonHelperGetIdArray($eventTickets['response']['ticketList']);
        } else {
            return $eventTickets;
        }

        foreach ($customFieldResponse as $key => $value) {

                 $fieldLevel = $value['fieldlevel'];
            $ticketId = $value['ticketid'];

            unset($value['fieldlevel']);
            unset($value['ticketid']);

            $userId = $this->ci->customsession->getUserId();
            $value['defaultValue'] = '';
            if ($userId != '' && $userId > 0 && $fieldLevel == 'event') {

                $this->userHandler = new User_handler();
                $userDataArray = array();
                $userInput['ownerId'] = $userId;
                $userData = $this->userHandler->getUserData($userInput);
                if ($userData['status'] && $userData['response']['total'] > 0) {
                    $userDataArray = $userData['response']['userData'];
                }

                if ($value['fieldname'] == 'Full Name') {
                    $value['defaultValue'] = $userDataArray['name'];
                }
                if ($value['fieldname'] == 'Email Id') {
                    $value['defaultValue'] = $userDataArray['email'];
                }
                if ($value['fieldname'] == 'Mobile No') {
                    $value['defaultValue'] = ($userDataArray['mobile'] != '') ? $userDataArray['mobile'] : $userDataArray['phone'];
                }
                if ($value['fieldname'] == 'Address') {
                    $value['defaultValue'] = $userDataArray['address'];
                }
                if ($value['fieldname'] == 'Country') {
                    $value['defaultValue'] = $userDataArray['Country'];
                }
                if ($value['fieldname'] == 'State') {
                    $value['defaultValue'] = $userDataArray['State'];
                }
                if ($value['fieldname'] == 'City') {
                    $value['defaultValue'] = $userDataArray['City'];
                }
                if ($value['fieldname'] == 'Pin Code') {
                    $value['defaultValue'] = $userDataArray['PinCode'];
                }
                if ($value['fieldname'] == 'Company Name') {
                    $value['defaultValue'] = $userDataArray['CompanyName'];
                }
            }

            if ($fieldLevel == 'event') {
                $commonFields[] = $value;
            } elseif ($fieldLevel == 'ticket') {
                $value['ticketName'] = $indexedTickets[$ticketId]['name'];
                $ticketFields[$ticketId][] = $value;
            }
        }

        $countField = 1;
        $customFieldIdArr = array();
        foreach ($ticketArray as $ticketId => $ticketQuantity) {
            $formFieldsArr['id'] = $ticketId;
            
            //$formFieldsArr['ticketName'] = $ticketFields[$ticketId][0]['ticketName'];
            $formFieldsArr['ticketName'] = $indexedTickets[$ticketId]['name'];
            unset($ticketFields[$ticketId][0]['ticketName']);

            for ($i = 0; $i < $ticketQuantity; $i++, $countField++) {
                $tempFormFields = $formFields = array();
                if (is_array($ticketFields[$ticketId]) && count($ticketFields[$ticketId]) > 0) {
                    $tempFormFields = array_merge($commonFields, $ticketFields[$ticketId]);
                } else {
                    $tempFormFields = $commonFields;
                }
                foreach ($tempFormFields as $formField) {
                    $formField['fieldnameid'] = str_replace(" ", "", preg_replace("/[^A-Za-z0-9\s\s+]/", "", $formField['fieldname'])) . $countField;
                    $formFields[] = $formField;
                    $customFieldIdArr[] = $formField['id'];
                }
                $formFieldsArr['formFields'] = $formFields;
                
                $customFieldArr[] = $formFieldsArr;
            }
        }
        $count = 0;
        foreach ($customFieldArr as $customfield => $custValues) {
            $count++;
            foreach ($custValues['formFields'] as $custField => $custVals) {
                if ($count > 1) {
                    $customFieldArr[$customfield]['formFields'][$custField]['defaultValue'] = '';
                }
            }
        }

        $finalCustomFieldValArr = array();
        $customFieldIdArr = array_unique($customFieldIdArr);
        if(isset($inputArray['isValuesRequired']) && $inputArray['isValuesRequired'] && count($customFieldIdArr) > 0) {
            $inputValuesArray['customFieldIdArray'] = $customFieldIdArr;
            $customFieldValArr = $this->getCustomFieldValues($inputValuesArray);
            if($customFieldValArr['status'] && $customFieldValArr['response']['total'] > 0) {
                $tempCustomFieldValArr = $customFieldValArr['response']['fieldValuesInArray'];
                foreach($tempCustomFieldValArr as $tempCustomFieldVal) {
                    $finalCustomFieldValArr[$tempCustomFieldVal['customfieldid']][] = $tempCustomFieldVal;
    }
        }
        }

        $count = 0;
        foreach ($customFieldArr as $customfield => $custValues) {
            $count++;
            foreach ($custValues['formFields'] as $custField => $custVals) {
                if ($count > 1) {
                    $customFieldArr[$customfield]['formFields'][$custField]['defaultValue'] = '';
                }
            }
        }

        $output['status'] = TRUE;
        $output['response']['customFields'] = $customFieldArr;
        $output['response']['customFieldValues'] = $finalCustomFieldValArr;
        $output['response']['messages'] = array();
        $output['statusCode'] = STATUS_OK;
        return $output;
    }
    
    /*
     * Function to get the custom fields of an event or order
     *
     * @access	public
     * @param	$inputArray contains
     * 				Either eventId - integer
     * 				Or orderId - integer are required
     * 				collectMultipleAttendeeInfo - 1 or 0
     * 				customFieldId - integer
     * 				ticketid - integer
     * @return	array
     */

    public function getCustomFieldsOfEvents($inputArray) {

        $this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('eventIds', 'eventIds', 'required_strict|is_array');
        if ($this->ci->form_validation->run() === FALSE) {
            $errorMessages = $this->ci->form_validation->get_errors();
            $output['status'] = FALSE;
            $output['response']['messages'] = $errorMessages['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        
        $fieldTypes=array();
        if (isset($inputArray['fieldtypes']) && is_array($inputArray['fieldtypes']) && count($inputArray['fieldtypes']) > 0) {
            $fieldTypes = $inputArray['fieldtypes'];
        }
        $ticketIds = isset($inputArray['ticketid']) ? $inputArray['ticketid'] : array();
        $commonfieldids = isset($inputArray['commonfieldids']) ? $inputArray['commonfieldids'] : array();
        $this->ci->load->model('Customfield_model');
        $this->ci->Customfield_model->resetVariable();
        $selectInput['id'] = $this->ci->Customfield_model->id;
        $selectInput['eventid'] = $this->ci->Customfield_model->eventid;
        $selectInput['fieldname'] = $this->ci->Customfield_model->fieldname;
        $selectInput['fieldtype'] = $this->ci->Customfield_model->fieldtype;
        $selectInput['commonfieldid'] = $this->ci->Customfield_model->commonfieldid;
        $selectInput['fieldmandatory'] = $this->ci->Customfield_model->fieldmandatory;
        $selectInput['order'] = $this->ci->Customfield_model->order;
        $selectInput['displayonticket'] = $this->ci->Customfield_model->displayonticket;
        $selectInput['displaystatus'] = $this->ci->Customfield_model->displaystatus;
        $selectInput['fieldlevel'] = $this->ci->Customfield_model->fieldlevel;
        $selectInput['ticketid'] = $this->ci->Customfield_model->ticketid;
        $selectInput['customvalidation'] = $this->ci->Customfield_model->customvalidation;

        $this->ci->Customfield_model->setSelect($selectInput);

        $where[$this->ci->Customfield_model->deleted] = 0;
        if (!isset($inputArray['allfields']) || (isset($inputArray['allfields']) && $inputArray['allfields'] == 0)) {
            $where[$this->ci->Customfield_model->displaystatus] = 1;
            if ($ticketIds == '') {
                $where[$this->ci->Customfield_model->fieldlevel] = 'event';
            }
        }
        if (isset($inputArray['customFieldId']) && $inputArray['customFieldId'] > 0) {
            $where[$this->ci->Customfield_model->id] = $inputArray['customFieldId'];
        }
        if (isset($inputArray['excludeCommonFields']) && $inputArray['excludeCommonFields']) {
            $where[$this->ci->Customfield_model->commonfieldid] = 0;
        }
        if (isset($inputArray['activeCustomField']) && $inputArray['activeCustomField'] > 0) {
            $where[$this->ci->Customfield_model->displaystatus] = 1;
        }
        if (isset($inputArray['collectMultipleAttendeeInfo']) && $inputArray['collectMultipleAttendeeInfo'] == 0) {
            $where[$this->ci->Customfield_model->fieldlevel] = 'event';
        }
        $setOrwhere = array();
        if (isset($inputArray['displayonticket']) && $inputArray['displayonticket'] == 1) {
            $setOrwhere[$this->ci->Customfield_model->displayonticket] = 1;
            $setOrwhere[$this->ci->Customfield_model->commonfieldid] = 1;
        }
        if (isset($inputArray['mobileNumber']) && $inputArray['mobileNumber'] == 1) {
            $setOrwhere[$this->ci->Customfield_model->commonfieldid] = 3;
        }
        $whereIns = array();
        if (count($ticketIds) > 0) {
            $whereIns[$this->ci->Customfield_model->ticketid] = $ticketIds;
        }
        if (count($commonfieldids) > 0) {
            $whereIns[$this->ci->Customfield_model->commonfieldid] = $commonfieldids;
        }
        if (count($fieldTypes) > 0) {
            $whereIns[$this->ci->Customfield_model->fieldtype] = $fieldTypes;
        }
        
        $whereIns[$this->ci->Customfield_model->eventid] = $inputArray['eventIds'];
        
        $this->ci->Customfield_model->setWhere($where);
        $this->ci->Customfield_model->setWhereIns($whereIns);
        $this->ci->Customfield_model->setOrWhere($setOrwhere);
        $orderBy = array();
        $orderBy[] = $this->ci->Customfield_model->order;
        $orderBy[] = $this->ci->Customfield_model->fieldlevel;
        if (isset($inputArray['statuslabels']) && $inputArray['statuslabels'] == 1) {
            $orderBy[] = $this->ci->Customfield_model->displaystatus . " DESC ";
        }
        $this->ci->Customfield_model->setOrderBy($orderBy);
        $customFieldResponse = $this->ci->Customfield_model->get();
        //echo $this->ci->db->last_query();
        //exit;
        if (count($customFieldResponse) == 0) {
            $output['status'] = TRUE;
            $output['response']['messages'][] = ERROR_NO_DATA;
            $output['response']['total'] = 0;
            $output['statusCode'] = STATUS_OK;
            return $output;
        }
        $output['status'] = TRUE;
        $output['response']['customFields'] = $customFieldResponse;
        $output['response']['total'] = count($customFieldResponse);
        $output['response']['messages'] = array();
        $output['statusCode'] = STATUS_OK;
        return $output;
    }

}

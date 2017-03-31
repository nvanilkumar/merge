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
require_once (APPPATH . 'handlers/customfield_handler.php');
require_once (APPPATH . 'handlers/configure_handler.php');

//require_once (APPPATH . 'handlers/file_handler.php');
//require_once (APPPATH . 'handlers/timezone_handler.php');
//require_once (APPPATH . 'handlers/currency_handler.php');

class Attendeedetail_handler extends Handler {

    var $ci;
    var $customfieldHandler;
    var $configureHandler;

//	var $timezoneHandler;
//	var $currencyHandler;
//	var $fileHandler;

    public function __construct() {
        parent::__construct();
        $this->ci = parent::$CI;
        $this->ci->load->model('Attendeedetail_model');
        $this->customfieldHandler = new Customfield_handler();
    }

    public function getListByAttendeeIds($input) {
        $this->ci->form_validation->pass_array($input);
        $this->ci->form_validation->set_rules('attendeeids', 'attendeeids', 'required_strict|is_array');
        $this->ci->form_validation->set_rules('commonfieldids', 'commonfieldids', 'is_array');
        $this->ci->form_validation->set_rules('customfieldids', 'customfieldids', 'is_array');
        if ($this->ci->form_validation->run() == FALSE) {
            $response = $this->ci->form_validation->get_errors();
            $output['status'] = FALSE;
            $output['response']['messages'] = $response['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $attendeeIds = $input['attendeeids'];
        $commonFieldIds = isset($input['commonfieldids']) ? $input['commonfieldids'] : array();
        $customFieldIds = isset($input['customfieldids']) ? $input['customfieldids'] : array();
        $this->ci->Attendeedetail_model->resetVariable();
        $select['id'] = $this->ci->Attendeedetail_model->id;
        $select['attendeeid'] = $this->ci->Attendeedetail_model->attendeeid;
        $select['value'] = $this->ci->Attendeedetail_model->value;
        $select['commonfieldid'] = $this->ci->Attendeedetail_model->commonfieldid;
        $select['customfieldid'] = $this->ci->Attendeedetail_model->customfieldid;
        //$select['contactdetails'] = 'GROUP_CONCAT(' . $this->ci->Attendeedetail_model->value . ' SEPARATOR "\r\n")';
        $this->ci->Attendeedetail_model->setSelect($select);
        $where_in[$this->ci->Attendeedetail_model->deleted] = 0;
        $where_in[$this->ci->Attendeedetail_model->attendeeid] = $attendeeIds;
        if (count($commonFieldIds) > 0) {
            $where_in[$this->ci->Attendeedetail_model->commonfieldid] = $commonFieldIds;
        }
        if (count($customFieldIds) > 0) {
            $where_in[$this->ci->Attendeedetail_model->customfieldid] = $customFieldIds;
        }
        //$this->ci->Attendeedetail_model->setGroupBy($this->ci->Attendeedetail_model->attendeeid);
        // $groupConcat['contactdetails']=$this->ci->Attendeedetail_model->attendeeid;
        //$this->ci->Attendeedetail_model->setGroupConcat($groupConcat,'\r\n');
        $this->ci->Attendeedetail_model->setWhereIns($where_in);
        $groupBy = array();
        $this->ci->Attendeedetail_model->setGroupBy($groupBy);
        $attendeedetailResponse = $this->ci->Attendeedetail_model->get();
        //echo $this->ci->db->last_query();
//        exit;
        if (count($attendeedetailResponse) == 0) {
            $output['status'] = TRUE;
            $output['response']['messages'][] = ERROR_NO_ATTENDEEDETAIL_DATA;
            $output['response']['total'] = 0;
            $output['statusCode'] = STATUS_OK;
            return $output;
        }
        $output['status'] = TRUE;
        $output['statusCode'] = STATUS_OK;
        $output['response']['attendeedetailList'] = $attendeedetailResponse;
        $output['response']['total'] = count($attendeedetailResponse);
        return $output;
    }

    public function getContactDetailsByAttendeeIds($input) {
        $this->ci->form_validation->pass_array($input);
        $this->ci->form_validation->set_rules('attendeeids', 'attendeeids', 'required_strict|is_array');
        $this->ci->form_validation->set_rules('commonfieldids', 'commonfieldids', 'required_strict|is_array');
        if ($this->ci->form_validation->run() == FALSE) {
            $response = $this->ci->form_validation->get_errors();
            $output['status'] = FALSE;
            $output['response']['messages'] = $response['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $attendeeIds = $input['attendeeids'];
        $commonFieldIds = isset($input['commonfieldids']) ? $input['commonfieldids'] : array();
        $this->ci->Attendeedetail_model->resetVariable();
        $select['id'] = $this->ci->Attendeedetail_model->id;
        $select['attendeeid'] = $this->ci->Attendeedetail_model->attendeeid;
        //$select['value'] = $this->ci->Attendeedetail_model->value;
        //$select['commonfieldtype'] = $this->ci->Attendeedetail_model->commonfieldtype;
        $select['contactdetails'] = 'GROUP_CONCAT(' . $this->ci->Attendeedetail_model->value . ' SEPARATOR "\r\n")';
        $this->ci->Attendeedetail_model->setSelect($select);
        $where_in[$this->ci->Attendeedetail_model->deleted] = 0;
        $where_in[$this->ci->Attendeedetail_model->attendeeid] = $attendeeIds;
        $where_in[$this->ci->Attendeedetail_model->commonfieldid] = $commonFieldIds;
        $groupBy[] = $this->ci->Attendeedetail_model->attendeeid;
        $this->ci->Attendeedetail_model->setGroupBy($groupBy);
        // $groupConcat['contactdetails']=$this->ci->Attendeedetail_model->attendeeid;
        //$this->ci->Attendeedetail_model->setGroupConcat($groupConcat,'\r\n');
        $this->ci->Attendeedetail_model->setWhereIns($where_in);
        $orderBy[] = $this->ci->Attendeedetail_model->commonfieldid;
        $this->ci->Attendeedetail_model->setOrderBy($orderBy);
        $attendeedetailResponse = $this->ci->Attendeedetail_model->get();
//        echo $this->ci->db->last_query();
//        exit;
        if (count($attendeedetailResponse) == 0) {
            $output['status'] = TRUE;
            $output['response']['messages'][] = ERROR_NO_ATTENDEEDETAIL_DATA;
            $output['response']['total'] = 0;
            $output['statusCode'] = STATUS_OK;
            return $output;
        }
        $output['status'] = TRUE;
        $output['statusCode'] = STATUS_OK;
        $output['response']['contactDetailsList'] = $attendeedetailResponse;
        $output['response']['total'] = count($attendeedetailResponse);
        return $output;
    }

    public function getEventsignupattendees($inputArray) {
        $output = array();
        if (!is_array($inputArray['attendeeids']) || count($inputArray['attendeeids']) == 0) {
            $validationStatus = $this->ci->form_validation->get_errors();
            $output['status'] = FALSE;
            $output['response']['messages'] = $validationStatus['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        } else {
            $this->ci->Attendeedetail_model->resetVariable();
            $selectInput['customfieldid'] = $this->ci->Attendeedetail_model->customfieldid;
            $selectInput['attendeeid'] = $this->ci->Attendeedetail_model->attendeeid;
            $selectInput['commonfieldid'] = $this->ci->Attendeedetail_model->commonfieldid;
            $selectInput['value'] = $this->ci->Attendeedetail_model->value;
            $this->ci->Attendeedetail_model->setSelect($selectInput);
            $whereInArray[$this->ci->Attendeedetail_model->attendeeid] = $inputArray['attendeeids'];
            if (isset($inputArray['customfieldIds'])) {
                $whereInArray[$this->ci->Attendeedetail_model->customfieldid] = $inputArray['customfieldIds'];
            }
            $this->ci->Attendeedetail_model->setWhereIns($whereInArray);
           
            //Order by array
            $orderBy = array();
            $orderBy[] = $this->ci->Attendeedetail_model->attendeeid;
            $this->ci->Attendeedetail_model->setOrderBy($orderBy);
            $attendeeDetails = $this->ci->Attendeedetail_model->get();
            if (count($attendeeDetails) > 0) {
            	if(!empty($inputArray['customfieldFileIds'])){
            		require_once (APPPATH . 'handlers/file_handler.php');
            		$fileHandler = new File_handler();
            		foreach($attendeeDetails as $key => $values){
            			if(in_array($values['customfieldid'], $inputArray['customfieldFileIds'])){
            				$galleryImageIdArray[] = $values['value'];
            				// getting file path for Image and thumbnail from file table
            				$fileData = $fileHandler->getFileData(array('id', $galleryImageIdArray));
            				$fileDataTemp = array();
            				if ($fileData['status'] && $fileData['response']['total'] > 0) {
            					$fileDataTemp = commonHelperGetIdArray($fileData['response']['fileData']);
            					$attendeeDetails[$key]['value']= $this->ci->config->item('images_content_cloud_path').$fileDataTemp[$values['value']]['path'];
            				}
            			}
            		}
            	}
                $output['status'] = TRUE;
                $output['response']['total'] = count($attendeeDetails);
                $output['response']['attendeeDetails'] = $attendeeDetails;
                //$output['response']['customfields'] = $customfields;
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
    }

    /*
     * Function to add Attendee details
     * @access	public
     * @param
     *          - customFieldId (required)
     *          - attendeeId (required)
     *          - value (optional)
     *          - commonFieldId (optional)
     * @return	response with either status `TRUE` or `FALSE`
     */

    public function add($insertArr) {

        $this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($insertArr);
        $this->ci->form_validation->set_rules('customFieldId', 'Custom Field Id', 'is_natural_no_zero|required_strict');
        $this->ci->form_validation->set_rules('attendeeId', 'Attendee Id', 'is_natural_no_zero|required_strict');

        if (!empty($inputArray) && $this->ci->form_validation->run() == FALSE) {
            $validationStatus = $this->ci->form_validation->get_errors();
            $output['status'] = FALSE;
            $output['response']['messages'] = $validationStatus['message'];
            $output['response']['total'] = 0;
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $this->ci->Attendeedetail_model->resetVariable();
        $createAttendeeDetails[$this->ci->Attendeedetail_model->customfieldid] = $insertArr['customFieldId'];
        $createAttendeeDetails[$this->ci->Attendeedetail_model->value] = $insertArr['value'];
        $createAttendeeDetails[$this->ci->Attendeedetail_model->attendeeid] = $insertArr['attendeeId'];
        $createAttendeeDetails[$this->ci->Attendeedetail_model->commonfieldid] = $insertArr['commonFieldId'];

        $this->ci->Attendeedetail_model->setInsertUpdateData($createAttendeeDetails);
        $addAttendeeDetail = $this->ci->Attendeedetail_model->insert_data();
        if ($addAttendeeDetail) {

            $output['status'] = TRUE;
            $output['response']['messages'][] = SUCCESS_ATTENDEE_DETAIL_ADDED;
            $output['statusCode'] = STATUS_OK;
            return $output;
        } else {

            $output['status'] = FALSE;
            $output['response']['messages'][] = ERROR_ADD_ATTENDEE_DETAIL;
            $output['statusCode'] = STATUS_SERVER_ERROR;
            return $output;
        }
    }

    // add multiple attendeed details
    public function addMultiple($insertArr) {

        $this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($insertArr);
        $this->ci->form_validation->set_rules('customFieldId', 'Custom Field Id', 'is_natural_no_zero|required_strict');
        $this->ci->form_validation->set_rules('attendeeId', 'Attendee Id', 'is_natural_no_zero|required_strict');

        if (!empty($inputArray) && $this->ci->form_validation->run() == FALSE) {
            $validationStatus = $this->ci->form_validation->get_errors();
            $output['status'] = FALSE;
            $output['response']['messages'] = $validationStatus['message'];
            $output['response']['total'] = 0;
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $this->ci->Attendeedetail_model->resetVariable();
        foreach ($insertArr as $key => $value) {
            $createAttendeeDetails[$key][$this->ci->Attendeedetail_model->customfieldid] = $value['customFieldId'];
            $createAttendeeDetails[$key][$this->ci->Attendeedetail_model->value] = $value['value'];
            $createAttendeeDetails[$key][$this->ci->Attendeedetail_model->attendeeid] = $value['attendeeId'];
            $createAttendeeDetails[$key][$this->ci->Attendeedetail_model->commonfieldid] = $value['commonFieldId'];
        }
        $this->ci->Attendeedetail_model->setInsertUpdateData($createAttendeeDetails);
        $addAttendeeDetail = $this->ci->Attendeedetail_model->insertMultiple_data();
        if ($addAttendeeDetail) {
            $output['status'] = TRUE;
            $output['response']['messages'][] = SUCCESS_ATTENDEE_DETAIL_ADDED;
            $output['statusCode'] = STATUS_CREATED;
            return $output;
        } else {
            $output['status'] = FALSE;
            $output['response']['messages'][] = ERROR_ADD_ATTENDEE_DETAIL;
            $output['statusCode'] = STATUS_SERVER_ERROR;
            return $output;
        }
    }
    
    public function addArray($insertMultiArr) {

        
        if (count($insertMultiArr) == 0) {
            
            $output['status'] = FALSE;
            $output['response']['messages'] = ERROR_ADD_ATTENDEE_DETAIL;
            $output['response']['total'] = 0;
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        
        foreach($insertMultiArr as $insertArr) {
            
            $this->ci->Attendeedetail_model->resetVariable();
            $createAttendeeDetails[$this->ci->Attendeedetail_model->customfieldid] = $insertArr['customFieldId'];
            $createAttendeeDetails[$this->ci->Attendeedetail_model->value] = $insertArr['value'];
            $createAttendeeDetails[$this->ci->Attendeedetail_model->attendeeid] = $insertArr['attendeeId'];
            $createAttendeeDetails[$this->ci->Attendeedetail_model->commonfieldid] = $insertArr['commonFieldId'];
    
            $this->ci->Attendeedetail_model->setInsertUpdateData($createAttendeeDetails);
            $addAttendeeDetail = $this->ci->Attendeedetail_model->insert_data();
            if(!$addAttendeeDetail) {
                
                $output['status'] = FALSE;
                $output['response']['messages'][] = ERROR_ADD_ATTENDEE_DETAIL;
                $output['statusCode'] = STATUS_SERVER_ERROR;
                return $output;
            }
        }
        
        $output['status'] = TRUE;
        $output['response']['messages'][] = SUCCESS_ATTENDEE_DETAIL_ADDED;
        $output['statusCode'] = STATUS_OK;
        return $output;
    }

}

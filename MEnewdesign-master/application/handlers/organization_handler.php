<?php

/**
 * Organization related Events(grouped)
 *
 * @package		CodeIgniter
 * @author		Qison  Dev Team
 * @copyright	Copyright (c) 2015, MeraEvents.
 * @Version		Version 1.0
 * @Since       Class available since Release Version 1.0 
 * @Created    07-12-2015
 * @Last Modified  
 * @Last Modified by Raviteja V
 */
require_once(APPPATH . 'handlers/handler.php');
require_once (APPPATH . 'handlers/event_handler.php');
require_once (APPPATH . 'handlers/organizer_handler.php');
require_once (APPPATH . 'handlers/user_handler.php');

class Organization_handler extends Handler {

    var $ci,$eventHandler,$organizerHandler,$userHandler;

    public function __construct() {
        parent::__construct();
        $this->ci = parent::$CI;
        $this->ci->load->model('organization_model');
        $this->ci->load->library('form_validation');
        $this->eventHandler = new Event_handler();
        $this->organizerHandler = new Organizer_handler();
    }

    /**
     * To get the Events for the Organization
     */
    public function getOrganizationDetails($inputArray = array()) {
    	require_once (APPPATH . 'handlers/file_handler.php');
    	$this->fileHandler = new File_handler();
    	$this->ci->form_validation->reset_form_rules();
    	$this->ci->form_validation->pass_array($inputArray);
    	$this->ci->form_validation->set_rules('orgid', 'org id ', 'required_strict|is_natural_no_zero');
    	$this->ci->form_validation->set_rules('type', 'Type ', 'required_strict');
    	if ($this->ci->form_validation->run() == FALSE) {
    		$response = $this->ci->form_validation->get_errors();
    		$output['status'] = FALSE;
    		$output['response']['messages'] = $response['message'];
    		$output['statusCode'] = STATUS_BAD_REQUEST;
    		return $output;
    	}
        $this->ci->organization_model->resetVariable();
    	$selectInput['id'] = $this->ci->organization_model->id;
    	$selectInput['name'] = $this->ci->organization_model->name;
    	$selectInput['information'] = $this->ci->organization_model->information;
    	$selectInput['intendedfor'] = $this->ci->organization_model->intendedfor;
    	$selectInput['logopathid'] = $this->ci->organization_model->logopathid;
    	$selectInput['bannerpathid'] = $this->ci->organization_model->bannerpathid;
    	$selectInput['eventsnumber'] = $this->ci->organization_model->eventsnumber;
    	$selectInput['viewcount'] = $this->ci->organization_model->viewcount;
    
    	$this->ci->organization_model->setSelect($selectInput);
    	$where[$this->ci->organization_model->id] = $inputArray['orgid'];
    	$where[$this->ci->organization_model->deleted] = '0';
    	$where[$this->ci->organization_model->status] = '1';
    	$this->ci->organization_model->setWhere($where);
    	//Order by array
    	$orderBy = array();
    	$orderBy[] = $this->ci->organization_model->id;
    	$this->ci->organization_model->setOrderBy($orderBy);
   
    	$Organizationdata = $this->ci->organization_model->get();
    	if (is_array($Organizationdata) && count($Organizationdata) > 0) {
    		$galleryImageIdArray = array();
    		foreach ($Organizationdata as $gallerykey => $gallery) {
    			$galleryImageIdArray[] = $gallery['bannerpathid'];
    			$galleryImageIdArray[] = $gallery['logopathid'];
    		}
    		// getting file path for Image and thumbnail from file table
    		$fileData = $this->fileHandler->getFileData(array('id', $galleryImageIdArray));
    		$fileDataTemp = array();
    		if ($fileData['status'] && $fileData['response']['total'] > 0) {
    			$fileDataTemp = commonHelperGetIdArray($fileData['response']['fileData']);
    		}
    		$response = array();
    		foreach ($Organizationdata as $gallerykey => $gallery) {
    			$imagepath = $fileDataTemp[$gallery['bannerpathid']]['path'];
    			$thumbnailpath = $fileDataTemp[$gallery['logopathid']]['path'];
    			if (isset($imagepath)) {
    				$imagepath = $this->ci->config->item('images_cloud_path') . $imagepath;
    			} else {
    				$imagepath = '';
    			}
    			$Organizationdata[$gallerykey]['bannerPath'] = $imagepath;
    			if (isset($thumbnailpath)) {
    				$thumbnailpath = $this->ci->config->item('images_content_cloud_path') . $thumbnailpath;
    			} else {
    				$thumbnailpath = '';
    			}
    			$Organizationdata[$gallerykey]['logoPath'] = $imagepath;
    		}
    		$orgId['id'] = $Organizationdata[0]['id'];
    		$orgId['type'] = $inputArray['type'];
    		$OrganizationEvents = $this->organizationUserEvents($orgId);
    		if(!$OrganizationEvents['status'] || empty($OrganizationEvents['response']['OrganizationEventsData'])){
    			return $OrganizationEvents;
    		}
    		$output=array();
    		$output['response']['organizationData'] = $Organizationdata;
    		$output['response']['OrganizationEventsData'] =$OrganizationEvents['response']['OrganizationEventsData']; 
    		$output['status'] = TRUE;
    		$output['response']['messages'] = array();
    		$output['response']['total'] = count($Organizationdata);
    		$output['response']['totalcount'] = $OrganizationEvents['response']['totalcount'];
    		$output['statusCode'] = STATUS_OK;
    		return $output;
    	}
    	$output['status'] = TRUE;
    	$output['response']['messages'][] =ERROR_NO_DATA ;
    	$output['response']['total'] =0;
    	$output['response']['OrganizationEventsData'] = array();
    	$output['response']['organizationData'] = array();
    	$output['statusCode'] = STATUS_OK;
    	return $output;
    
    }
    
    public function organizationUserEvents($inputArray=array()){

        $uidsArray = $this->getOrgUserIds($inputArray);
        if (is_array($uidsArray) && $uidsArray['response']['total'] > 0) {
            $userIdsArray = array();
            $userIdsArray = $uidsArray['response']['userIds'];
            $userIdsArray['type']= 'upcoming';
            if(isset($inputArray['type']) && strlen($inputArray['type'])>0){
            	$userIdsArray['type'] = $inputArray['type'];
            }
            if(isset($inputArray['page']) && strlen($inputArray['page'])>0){
            	$userIdsArray['page'] = $inputArray['page'];
            }
            $userIdsArray['gettotal'] = 1;
            $orgEventsDataCount =  $this->eventHandler->getOrganizationEventsCount($userIdsArray);
            if($orgEventsDataCount['response']['totalCount'] == 0 ){
            	$output['status'] = TRUE;
                $output['response']['messages'][] =ERROR_NO_DATA ;
                $output['response']['total'] = 0;
                $output['response']['totalcount'] = $orgEventsDataCount['response']['totalCount'];
                $output['statusCode'] = STATUS_OK;
                return $output;
            }
            unset($userIdsArray['gettotal']);
            $orgEventsData = $this->eventHandler->getOrganizationEvents($userIdsArray);
            if($inputArray['callType']  == 'ajax'){
            	$orgEventsData['response']['eventDetails'] = array_values($orgEventsData['response']['eventDetails']);
            }
            if($orgEventsData['response']['total'] > 0){
                $output['status'] = TRUE;
                $output['response']['messages'][] = '';
                $output['response']['OrganizationEventsData'] = $orgEventsData['response']['eventDetails'];
                $output['response']['total'] = $orgEventsData['response']['total'];
                $output['response']['totalcount'] = $orgEventsDataCount['response']['totalCount'];
                $output['response']['PageType'] = $userIdsArray['type'] ;
                $output['statusCode'] = STATUS_OK;
                return $output;
            }else{
                $output['status'] = TRUE;
                $output['response']['messages'][] =ERROR_NO_DATA ;
                $output['response']['total'] = 0;
                $output['statusCode'] = STATUS_OK;
                return $output;
            }
           // echo "<pre>";print_r($output);exit;
         
        }
        $output['status'] = TRUE;
    	$output['response']['messages'][] =ERROR_NO_DATA ;
    	$output['response']['total'] =0;
    	$output['statusCode'] = STATUS_OK;
    	return $output;
    }
    
    public function updateViewCount($orgData){
        $this->ci->form_validation->pass_array($orgData);
    	$this->ci->form_validation->set_rules('id', 'id ', 'required_strict|is_natural_no_zero');
    	if ($this->ci->form_validation->run() == FALSE) {
    		$response = $this->ci->form_validation->get_errors();
    		$output['status'] = FALSE;
    		$output['response']['messages'] = $response['message'];
    		$output['statusCode'] = STATUS_BAD_REQUEST;
    		return $output;
    	}
        $this->ci->organization_model->resetVariable();
        $Organizationdata['viewcount'] = $orgData['viewcount']+1;
        $where = array($this->ci->organization_model->id => $orgData['id']);
        $this->ci->organization_model->setInsertUpdateData($Organizationdata);
        $this->ci->organization_model->setWhere($where);
        $response = $this->ci->organization_model->update_data();
        if ($response) {
            $output['status'] = TRUE;
            $output["response"]["messages"] = array();
            $output['statusCode'] = STATUS_UPDATED;
            return $output;
        }
        $output['status'] = FALSE;
        $output["response"]["messages"][] = SOMETHING_WENT_WRONG;
        $output['statusCode'] = STATUS_BAD_REQUEST;
        return $output;
    }
    
    public function getOrgUserIds($inputArray) {
        $this->ci->form_validation->reset_form_rules();
    	$this->ci->form_validation->pass_array($inputArray);
    	$this->ci->form_validation->set_rules('id', 'id ', 'required_strict|is_natural_no_zero');
    	if ($this->ci->form_validation->run() == FALSE) {
    		$response = $this->ci->form_validation->get_errors();
    		$output['status'] = FALSE;
    		$output['response']['messages'] = $response['message'];
    		$output['statusCode'] = STATUS_BAD_REQUEST;
    		return $output;
    	}
        $this->ci->load->model('organization_user_model');
        $this->ci->organization_user_model->resetVariable();
    	$selectInput['userid'] = $this->ci->organization_user_model->userid;    	    
    
        $this->ci->organization_user_model->setSelect($selectInput);
    	$where[$this->ci->organization_user_model->organizationid] = $inputArray['id'];
    	$where[$this->ci->organization_user_model->deleted] = 0;
    	$where[$this->ci->organization_user_model->status] = 1;
    	$this->ci->organization_user_model->setWhere($where);
    	//Order by array
    	$orderBy = array();
    	$orderBy[] = $this->ci->organization_user_model->id;
    	$this->ci->organization_model->setOrderBy($orderBy);
    	$OrganizationUserData = $this->ci->organization_user_model->get();
        if (is_array($OrganizationUserData) && count($OrganizationUserData) > 0) {
            $userIdsArray = array();
            foreach ($OrganizationUserData as $key => $value) {
                $userIdsArray['userids'][$key] = $value['userid'];
            }
            $output['status'] = TRUE;
            $output['response']['messages'][] = '';
            $output['response']['userIds'] = $userIdsArray;
            $output['response']['total'] = count($OrganizationUserData);
            $output['statusCode'] = STATUS_OK;
        } else {
            $output['status'] = TRUE;
            $output['response']['messages'][] = ERROR_NO_DATA;
            $output['response']['total'] = 0;
            $output['statusCode'] = STATUS_OK;
        }
        return $output;
    }

    public function contactMailOrganizer($input) {
        $uidsArray = $this->getOrgUserIds($input);
        if (is_array($uidsArray) && $uidsArray['response']['total'] > 0) {
            $userIdsArray = $emailinput = array();
            $userIdsArray = $uidsArray['response']['userIds'];
            $orgemailResponse = $this->organizerHandler->getOrganizerContactData($userIdsArray);
            if ($orgemailResponse && $orgemailResponse['response']['total'] > 0) {
                $contactdata = $orgemailResponse['response']['cotactDetails'];
                foreach ($contactdata as $id => $data) {
                    if (!empty($data['email'])) {
                        $emailinput['orgMailIds'][$data['userid']] = '"' . $data['email'] . '"';
                    } else {
                        $uids['userIdList'][$id] = $data['userid'];
                    }
                }
                $userids['userIdList'] = $userIdsArray['userids'];
                $this->userHandler = new User_handler();
                $userData = $this->userHandler->getUserDetails($userids);
                
                foreach ($userData['response']['userData'] as $key => $data) {
                    $input['toMail'][$data['id']]['name'] = $data['name'];
                    //getting mailid from user table as there is no mailid in organizer table
                    if (count($uids) > 0) {
                        $emailinput['userMailIds'][$data['id']] = '"' . $data['email'] . '"';
                    }
                }
                $mail['toMail']['mail'] = $emailinput['orgMailIds'];

                if (isset($emailinput['userMailIds'])) {
                    $userMailIds = $emailinput['userMailIds'];
                    //merge of user table ids and organizer mail ids
                    if (count($emailinput['orgMailIds']) > 0) {
                        $orgMailIds = $emailinput['orgMailIds'];
                        $mail['toMail']['mail'] = array_merge($userMailIds, $orgMailIds);
                    } else {
                        $mail['toMail']['mail'] = $userMailIds;
                    }
                }
                //grouping mail id and name based on their userid
                foreach ($mail['toMail']['mail'] as $key => $value) {
                    foreach ($userIdsArray['userids'] as $ids) {
                        if ($key == $ids) {
                            $input['toMail'][$key]['mail'] = $value;
                        }
                    }
                }
                $mailResponse = $this->eventHandler->mailInvitation($input);
                if ($mailResponse && $mailResponse['status'] == TRUE) {
                    $output['status'] = TRUE;
                    $output["response"]["messages"][] = Email_SENT;
                    $output['statusCode'] = STATUS_MAIL_SENT;
                    return $output;
                } else {
                    $output['status'] = FALSE;
                    $output['response']['messages'] = $mailResponse['response']['messages'];
                    $output['statusCode'] = $mailResponse['statusCode'];
                    return $output;
                }
            } else {
                $output['status'] = FALSE;
                $output["response"]["messages"][] = ERROR_NO_DATA;
                $output['statusCode'] = STATUS_OK;
                $output['response']['total'] = 0;
                return $output;
            }
        }
    }

}

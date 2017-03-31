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
require_once(APPPATH . 'handlers/messagetemplate_handler.php');
require_once (APPPATH . 'handlers/event_handler.php');
require_once (APPPATH . 'handlers/user_handler.php');
require_once (APPPATH . 'handlers/collaboratoraccess_handler.php');
require_once(APPPATH . 'handlers/email_handler.php');

//require_once(APPPATH . 'handlers/ticket_handler.php');

class Collaborator_handler extends Handler {

    var $ci, $userHandler, $collaboratoracessHandler,$messagetemplateHandler;


    public function __construct() {
        parent::__construct();
        $this->ci = parent::$CI;
        $this->ci->load->model('Collaborator_model');
        $this->eventHandler = new Event_handler();
        $this->userHandler = new User_handler();
        $this->collaboratoracessHandler = new Collaboratoraccess_handler();
    }

    public function add($inputArray) {
        $this->ci->form_validation->pass_array($inputArray);
        //Checking validation using Group Validation (signup)
        if ($this->ci->form_validation->run('collaborator') === FALSE) {
            $errorMsg = $this->ci->form_validation->get_errors();
            $output = parent::createResponse(FALSE, $errorMsg['message'], STATUS_BAD_REQUEST);
            return $output;
        }
        $userEmail = $this->ci->customsession->getData('userEmail');
        if ($userEmail == $inputArray['email']) {
            $output = parent::createResponse(FALSE, ERROR_ADD_COLLABORATOR, STATUS_BAD_REQUEST);
            return $output;
        }
        $eventId = $inputArray['eventid'];
        $name = $inputArray['name'];
        $email = $inputArray['email'];
        if(isset($inputArray['mobile'])){
            $mobile = $inputArray['mobile'];
        }
        $orgInput['userId']=  getUserId();
        $orgInput['eventId']=  $eventId;
        $orgForEventRes=$this->eventHandler->isOrganizerForEvent($orgInput);
        if($orgForEventRes['status'] && $orgForEventRes['response']['totalCount']==0){
            $output = parent::createResponse(FALSE, ERROR_USERID, STATUS_BAD_REQUEST);
            return $output;
        }
        $modules = array('manage' => 0, 'promote' => 0, 'report' => 0);
        if (isset($inputArray['manage'])) {
            $modules['manage'] = $inputArray['manage'];
        }
        if (isset($inputArray['promote'])) {
            $modules['promote'] = $inputArray['promote'];
        }
        if (isset($inputArray['report'])) {
            $modules['report'] = $inputArray['report'];
        }
        foreach ($modules as $type=>$bool) {
            if($bool==1){
                break;
            }
            if($type=='report'){
                $output = parent::createResponse(FALSE, ERROR_COLLABORATOR_MODULES, STATUS_BAD_REQUEST);
                return $output;
            }
        }
        //Checking collaborator is already there or not
        $this->ci->Collaborator_model->resetVariable();
        $select['id'] = $this->ci->Collaborator_model->id;
        $this->ci->Collaborator_model->setSelect($select);
        $where[$this->ci->Collaborator_model->eventid] = $eventId;
        $where[$this->ci->Collaborator_model->email] = $email;
        $where[$this->ci->Collaborator_model->deleted] = 0;
        $this->ci->Collaborator_model->setWhere($where);
        $result = $this->ci->Collaborator_model->get();
        if (count($result) > 0) {
            $output = parent::createResponse(FALSE, ERROR_DUPLICATE_COLLABORATOR, STATUS_BAD_REQUEST);
            return $output;
        }

        $inputUser['email'] = $email;
        $userDetails = $this->userHandler->getUserData($inputUser);
        $isNewUser = false;
        if ($userDetails['status']) {
            if ($userDetails['response']['total'] == 0) {//insert into user table and get email
                $isNewUser = TRUE;
                $inputUser['email'] = $email;
                if(isset($inputArray['mobile'])){                
                    $inputUser['mobile'] = $mobile;
                }
                $inputUser['name'] = $name;
                $inputUser['password'] = $password = random_password();
                $addUserResponse = $this->userHandler->add($inputUser);
                if ($addUserResponse['status']) {
                    $userId = $addUserResponse['response']['userId'];
                } else {
                    return $addUserResponse;
                }
            }
            if (!$isNewUser) {
                $userId = $userDetails['response']['userData']['id'];
            }
            $insertCollaborator[$this->ci->Collaborator_model->eventid] = $eventId;
            $insertCollaborator[$this->ci->Collaborator_model->userid] = $userId;
            $insertCollaborator[$this->ci->Collaborator_model->name] = $name;
            $insertCollaborator[$this->ci->Collaborator_model->email] = $email;
            if(isset($inputArray['mobile'])){   
                $insertCollaborator[$this->ci->Collaborator_model->mobile] = $mobile;
            }
            $this->ci->Collaborator_model->setInsertUpdateData($insertCollaborator);
            $insertId = $this->ci->Collaborator_model->insert_data();
            if ($insertId > 0) {
                $insertAccess['collaboratorid'] = $insertId;
                $insertAccess['modules'] = $modules;
                $accessResponse = $this->collaboratoracessHandler->insert($insertAccess);
            }
            if ($accessResponse['status'] && $accessResponse['response']['total'] > 0) {
                $inputEmail['eventid'] = $eventId;
                $inputEmail['email'] = $email;
                $inputEmail['userid'] = $userId;
                $inputEmail['name'] = $name;
                if ($isNewUser===TRUE) {
                    $inputEmail['password'] = $password;
                }
                $emailResponse = $this->sendCollaboratorEmail($inputEmail);
            } else {
                return $accessResponse;
            }
            $output['status'] = TRUE;
            $output['response']['total'] = 1;
            $output['response']['collaboratorData'] = array('addedStatus' => TRUE);
            $output['response']['messages'] = [];
            $output['statusCode'] = STATUS_CREATED;
            return $output;
        } else {
            return $userDetails;
        }
    }

    public function sendCollaboratorEmail($inputArray) {
        $this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('eventid', 'eventid', 'required_strict|is_natural_no_zero');
        $this->ci->form_validation->set_rules('email', 'email', 'required_strict|valid_email');
        //Checking validation using Group Validation (signup)
        if ($this->ci->form_validation->run() === FALSE) {
            $errorMsg = $this->ci->form_validation->get_errors();
            $output = parent::createResponse(FALSE, $errorMsg['message'], STATUS_BAD_REQUEST);
            return $output;
        }
        $eventId = $inputArray['eventid'];
        $email = $inputArray['email'];
        $password = isset($inputArray['password']) ? $inputArray['password'] : '';
        $userId = $inputArray['userid'];
        $this->ci->load->library('parser');
        $this->emailHandler = new Email_handler();
        $templateInputs['type'] = TYPE_COLLABORATOR_INVITE;
        $templateInputs['mode'] = 'email';
        $inputEvent['eventId'] = $eventId;
        $eventDetails = $this->eventHandler->getEventDetails($inputEvent);
        //Sending collaborator invitation mail
        $this->messagetemplateHandler = new Messagetemplate_handler();
        $promoterTemplate = $this->messagetemplateHandler->getTemplateDetail($templateInputs);
        $templateId = $promoterTemplate['response']['templateDetail']['id'];
        $from = $promoterTemplate['response']['templateDetail']['fromemailid'];
        $to = $email;
        $templateMessage = $promoterTemplate['response']['templateDetail']['template'];
        $subject = SUBJECT_COLLABORATOR_INVITE;

        //$data['invitetype'] = 'Collaborator';
        $data['passwordLabel'] = "";
        $data['password'] = "";
        $data['address'] = "";
        $data['webinar'] = '';
        $data['userName'] = ucfirst($inputArray['name']);
        //$data['iFrameDisplay'] = 'none';
        if (!empty($password)) {
            $data['passwordLabel'] = "Your password: ";
            $data['password'] = $password;
        }
        $data['date'] = allTimeFormats($eventDetails['response']['details']['startDate'],7);
        $data['date'].='   -   ';
        $data['date'].=allTimeFormats($eventDetails['response']['details']['endDate'],7);

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
        // $data['pricingTabUrl'] = commonHelperGetPageUrl('dashboard-ticketwidget-pricing-tab', $inputArray['eventId'] . '&' . $inputArray['code']);
        $data['year'] = allTimeFormats(' ',17);
        $data['loginLink'] = commonHelperGetPageUrl('user-login');
        $data['email'] = $inputArray['email'];
        $data['title'] = $eventDetails['response']['details']['title'];
        $data['eventUrl'] = $eventDetails['response']['details']['eventUrl'];
        $data['eventMode'] = $eventDetails['response']['details']['eventMode'];
        $data['siteUrl'] = site_url();
        $data['supportLink'] = commonHelperGetPageUrl('contactUs');
        $data['meraeventLogoPath'] = $this->ci->config->item('images_static_path') . 'me-logo.png';
        $message = $this->ci->parser->parse_string($templateMessage, $data, TRUE);
        $sentmessageInputs['messageid'] = $templateId;
        $email = $this->emailHandler->sendEmail($from, $to, $subject, $message, '', '', '', $sentmessageInputs);
        return $email;
    }

    public function getList($inputArray) {
        $this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('eventid', 'eventid', 'required_strict|is_natural_no_zero');
        $this->ci->form_validation->set_rules('collaboratorid', 'collaboratorid', 'is_natural_no_zero');
        if ($this->ci->form_validation->run() == FALSE) {
            $response = $this->ci->form_validation->get_errors('message');
            $output['status'] = FALSE;
            $output['response']['messages'] = $response['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $eventId = $inputArray['eventid'];
        $collaboratorId = isset($inputArray['collaboratorid']) ? $inputArray['collaboratorid'] : 0;
        $this->ci->Collaborator_model->resetVariable();
        $select['id'] = $this->ci->Collaborator_model->id;
        $select['name'] = $this->ci->Collaborator_model->name;
        $select['email'] = $this->ci->Collaborator_model->email;
        $select['mobile'] = $this->ci->Collaborator_model->mobile;
        $select['status'] = $this->ci->Collaborator_model->status;
        $where[$this->ci->Collaborator_model->eventid] = $eventId;
        $where[$this->ci->Collaborator_model->deleted] = 0;
        if ($collaboratorId > 0) {
            $where[$this->ci->Collaborator_model->id] = $collaboratorId;
        }
        $this->ci->Collaborator_model->setSelect($select);
        $this->ci->Collaborator_model->setWhere($where);
        $collaboratorList = $this->ci->Collaborator_model->get();
        if (count($collaboratorList) == 0) {
            $output['status'] = TRUE;
            $output['response']['total'] = 0;
            $output['response']['messages'][] = ERROR_NO_COLLABORATORS;
            return $output;
        }
        $indexCollaborators = commonHelperGetIdArray($collaboratorList);
        $collaboratorIds = array_keys($indexCollaborators);
        $inputAccess['collaboratorids'] = $collaboratorIds;
        $collaboratorAcessList = $this->collaboratoracessHandler->get($inputAccess);
        if ($collaboratorAcessList['status'] && $collaboratorAcessList['response']['total'] > 0) {
            foreach ($collaboratorAcessList['response']['collaboratorAcessList'] as $value) {
                $indexCollaborators[$value['collaboratorid']]['modules'] = $value['modules'];
            }
        }
        if (count($indexCollaborators) > 0) {
            $output['status'] = TRUE;
            $output['response']['total'] = count($indexCollaborators);
            $output['response']['collaboratorList'] = $indexCollaborators;
            $output['response']['messages'] = [];
            return $output;
        }
        return $collaboratorAcessList;
    }
    //get Access Permission by event of given user Id 
    public function getEventByUserIds($inputArray) {
        $this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('userids', 'userids', 'required_strict|is_array');
        $this->ci->form_validation->set_rules('eventId', 'eventId', 'required_strict|is_natural_no_zero');
        if ($this->ci->form_validation->run() == FALSE) {
            $response = $this->ci->form_validation->get_errors('message');
            $output['status'] = FALSE;
            $output['response']['messages'] = $response['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $userIds = $inputArray['userids'];
        $getacesslevel = isset($inputArray['getacesslevel']) ? $inputArray['getacesslevel'] : false;
        $this->ci->Collaborator_model->resetVariable();
        $select['id'] = $this->ci->Collaborator_model->id;
        $select['eventid'] = $this->ci->Collaborator_model->eventid;
        $whereIn[$this->ci->Collaborator_model->userid] = $userIds;
        $where[$this->ci->Collaborator_model->eventid] = $inputArray['eventId'];
        $where[$this->ci->Collaborator_model->deleted] = 0;
        $where[$this->ci->Collaborator_model->status] = 1;

        $this->ci->Collaborator_model->setSelect($select);
        $this->ci->Collaborator_model->setWhere($where);
        $this->ci->Collaborator_model->setWhereIns($whereIn);
        $collaboratorList = $this->ci->Collaborator_model->get();
        if (count($collaboratorList) == 0) {
            $output['status'] = TRUE;
            $output['response']['total'] = 0;
            $output['response']['messages'][] = ERROR_NO_COLLABORATORS;
            $output['statusCode'] = STATUS_OK;
            return $output;
        }
        $collaboratorIds = array();
        if ($getacesslevel) {
//            foreach ($collaboratorList as $value) {
//                $collaboratorIds[] = $value['id'];
//            }
            $indexedcollaboratorList = commonHelperGetIdArray($collaboratorList);
        }$inputAccess['collaboratorids'] = array_keys($indexedcollaboratorList);
        $collaboratorAcessList = $this->collaboratoracessHandler->get($inputAccess);
        
        if ($collaboratorAcessList['status'] && $collaboratorAcessList['response']['total'] > 0) {
            /*foreach ($collaboratorAcessList['response']['collaboratorAcessList'] as $value) {*/
                /*if (strpos($collaboratorAcessList['response']['collaboratorAcessList'][0]['modules'], 'manage') !== FALSE) {
                    $collaboratorList[0]['module'] = 'manage';
                } elseif (strpos($collaboratorAcessList['response']['collaboratorAcessList'][0]['modules'], 'promote') !== FALSE) {
                    $collaboratorList[0]['module'] = 'promote';
                }else{*/
                    $collaboratorList[0]['module'] = $collaboratorAcessList['response']['collaboratorAcessList'][0]['modules'];//'report';
                //}
            //}
        }
        
        $output['status'] = TRUE;
        $output['response']['total'] = count($collaboratorList);
        $output['response']['collaboratorDetail'] = $collaboratorList[0];
        $output['response']['messages'] = [];
        $output['statusCode'] = STATUS_OK;
        return $output;
    }
    public function getListByUserIds($inputArray) {
        $this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('userids', 'userids', 'required_strict|is_array');
        if ($this->ci->form_validation->run() == FALSE) {
            $response = $this->ci->form_validation->get_errors('message');
            $output['status'] = FALSE;
            $output['response']['messages'] = $response['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $userIds = $inputArray['userids'];
        $getacesslevel = isset($inputArray['getacesslevel']) ? $inputArray['getacesslevel'] : false;
        $this->ci->Collaborator_model->resetVariable();
        $select['id'] = $this->ci->Collaborator_model->id;
        $select['eventid'] = $this->ci->Collaborator_model->eventid;
        $whereIn[$this->ci->Collaborator_model->userid] = $userIds;
        $where[$this->ci->Collaborator_model->deleted] = 0;
        $where[$this->ci->Collaborator_model->status] = 1;

        $this->ci->Collaborator_model->setSelect($select);
        $this->ci->Collaborator_model->setWhere($where);
        $this->ci->Collaborator_model->setWhereIns($whereIn);
        $collaboratorList = $this->ci->Collaborator_model->get();
        if (count($collaboratorList) == 0) {
            $output['status'] = TRUE;
            $output['response']['total'] = 0;
            $output['response']['messages'][] = ERROR_NO_COLLABORATORS;
            $output['statusCode'] = STATUS_OK;
            return $output;
        }
        $collaboratorIds = array();
        if ($getacesslevel) {
//            foreach ($collaboratorList as $value) {
//                $collaboratorIds[] = $value['id'];
//            }
            $indexedcollaboratorList = commonHelperGetIdArray($collaboratorList);
        }
        $inputAccess['collaboratorids'] = array_keys($indexedcollaboratorList);
        $collaboratorAcessList = $this->collaboratoracessHandler->get($inputAccess);
        if ($collaboratorAcessList['status'] && $collaboratorAcessList['response']['total'] > 0) {
            foreach ($collaboratorAcessList['response']['collaboratorAcessList'] as $value) {
                if (strpos($value['modules'], 'manage') !== FALSE) {
                    $indexedcollaboratorList[$value['collaboratorid']]['module'] = 'manage';
                } elseif (strpos($value['modules'], 'promote') !== FALSE) {
                    $indexedcollaboratorList[$value['collaboratorid']]['module'] = 'promote';
                }else{
                    $indexedcollaboratorList[$value['collaboratorid']]['module'] = 'report';
                }
            }
        }
        $output['status'] = TRUE;
        $output['response']['total'] = count($indexedcollaboratorList);
        $output['response']['collaboratorList'] = $indexedcollaboratorList;
        $output['response']['messages'] = [];
        $output['statusCode'] = STATUS_OK;
        return $output;
    }

    public function update($inputArray) {
        $this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('eventid', 'eventid', 'required_strict|is_natural_no_zero');
        $this->ci->form_validation->set_rules('collaboratorid', 'collaboratorid', 'is_natural_no_zero');
        $this->ci->form_validation->set_rules('mobile', 'mobile', 'phone');
        $this->ci->form_validation->set_rules('manage', 'manage', 'enable');
        $this->ci->form_validation->set_rules('promote', 'promote', 'enable');
        $this->ci->form_validation->set_rules('report', 'report', 'enable');
        if ($this->ci->form_validation->run() == FALSE) {
            $response = $this->ci->form_validation->get_errors('message');
            $output['status'] = FALSE;
            $output['response']['messages'] = $response['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $eventId = $inputArray['eventid'];
        $collaboratorId = $inputArray['collaboratorid'];
        if(isset($inputArray['mobile'])){   
            $this->ci->Collaborator_model->resetVariable();
            $updateData[$this->ci->Collaborator_model->mobile] =  $inputArray['mobile'];
            $this->ci->Collaborator_model->setInsertUpdateData($updateData);  
            $where[$this->ci->Collaborator_model->id] = $collaboratorId;
            $where[$this->ci->Collaborator_model->eventid] = $eventId;
            $this->ci->Collaborator_model->setWhere($where);
            $this->ci->Collaborator_model->update_data();
            
        }
        $modules = array('manage' => 0, 'promote' => 0, 'report' => 0);
        if (isset($inputArray['manage'])) {
            $modules['manage'] = $inputArray['manage'];
        }
        if (isset($inputArray['promote'])) {
            $modules['promote'] = $inputArray['promote'];
        }
        if (isset($inputArray['report'])) {
            $modules['report'] = $inputArray['report'];
        }
        $insertAccess['collaboratorid'] = $collaboratorId;
        $insertAccess['modules'] = $modules;
        $accessResponse = $this->collaboratoracessHandler->update($insertAccess);
        return $accessResponse;
    }

    public function changeFieldValue($data) {
        $this->ci->form_validation->pass_array($data);
        $this->ci->form_validation->set_rules('eventid', 'Event ID', 'is_natural_no_zero|required_strict');
        $this->ci->form_validation->set_rules('collaboratorid', 'collaboratorid', 'is_natural_no_zero');
        $this->ci->form_validation->set_rules('value', 'value', 'is_natural_no_zero');
        $this->ci->form_validation->set_rules('field', 'field type', 'required_strict');
        if ($this->ci->form_validation->run() === FALSE) {
            $output['status'] = FALSE;
            $output['response']['messages'] = $this->ci->form_validation->get_errors();
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $eventId = $data['eventid'];
        $collaboratorId = $data['collaboratorid'];
        $field = $data['field'];
        $status = isset($data['value']) ? $data['value'] : 0;
        $updateArray = array();
        $inputCollaborator['eventid'] = $eventId;
        $inputCollaborator['collaboratorid'] = $collaboratorId;
        $collaboratorResponse = $this->getList($inputCollaborator);
        if ($collaboratorResponse['status'] && $collaboratorResponse['response']['total'] > 0) {
            $collaboratorData = $collaboratorResponse['response']['collaboratorList'];
        } else {
            return $collaboratorResponse;
        }
        $this->ci->Collaborator_model->resetVariable();
        foreach ($collaboratorData as $value) {
            if ($data['field'] == 'status') {
                $status = 1;
                if ($value['status'] == 1) {
                    $status = 0;
                }
                $updateArray[$this->ci->Collaborator_model->status] = $status;
            }
        }
        if (count($updateArray) > 0) {
            $where[$this->ci->Collaborator_model->eventid] = $eventId;
            $where[$this->ci->Collaborator_model->id] = $collaboratorId;
            $this->ci->Collaborator_model->setWhere($where);

            $this->ci->Collaborator_model->setInsertUpdateData($updateArray);
            $updateStatus = $this->ci->Collaborator_model->update_data();
            $response = array('status' => $updateStatus, 'field' => $field, 'value' => $status, 'collaboratorid' => $collaboratorId);
            $output['status'] = TRUE;
            $output["response"]["updatecollaboratorResponse"] = $response;
            $output["response"]["messages"][] = SUCCESS_UPDATED_COLLABORATOR;
            $output["response"]["total"] = 1;
            $output['statusCode'] = STATUS_UPDATED;
            return $output;
        }
        $output['status'] = FALSE;
        $output['response']['messages'][] = ERROR_INVALID_DATA;
        $output['statusCode'] = STATUS_SERVER_ERROR;
        return $output;
    }
    
    // To check whether the user in the session is collaborator or not.
    public function checkCollaboratorAccess() {
        $this->ci->customsession->loginCheck();
        $userId = getUserId();
        
        $this->ci->Collaborator_model->resetVariable();
        $selectInput['numRows'] = "COUNT('".$this->ci->Collaborator_model->id."')";
        $this->ci->Collaborator_model->setSelect($selectInput);
        
        $where[$this->ci->Collaborator_model->userid] = $userId;
        $where[$this->ci->Collaborator_model->deleted] = 0;
        $this->ci->Collaborator_model->setWhere($where);
        $result = $this->ci->Collaborator_model->get();
        $numRows = $result[0]['numRows'];
        if ($numRows > 0) {
            $output = parent::createResponse(TRUE, '', STATUS_OK, '', 'isCollaborator', 1);
        } else {
            $output = parent::createResponse(TRUE, '', STATUS_OK, '', 'isCollaborator', 0);
        }
        return $output;
    }
    // to check whether the user in the session has the permissions to the given event id
    public function checkCollaboratorEventAccess($inputArray) {
        $this->ci->customsession->loginCheck();
        $userId = getUserId();
        $eventId = $inputArray['eventId'];

        /*$select['id'] = $this->ci->Collaborator_model->id;*/
        //checking count
        $this->ci->Collaborator_model->resetVariable();
        $where[$this->ci->Collaborator_model->userid] = $userId;
        $where[$this->ci->Collaborator_model->eventid] = $eventId;
        $where[$this->ci->Collaborator_model->deleted] = 0;
        $this->ci->Collaborator_model->setWhere($where);
        $result = $this->ci->Collaborator_model->getCount();
        if ($result != false && $result > 0) {
            $output = parent::createResponse(TRUE, '', STATUS_OK, '', 'isEventCollaborator', 1);
        } else {
            $output = parent::createResponse(TRUE, '', STATUS_OK, '', 'isEventCollaborator', 0);
        }
        return $output;

    }

}

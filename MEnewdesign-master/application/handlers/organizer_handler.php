<?php

/**
 * Promoter related business logic will be defined in this class
 *
 * @package		CodeIgniter
 * @author		Qison  Dev Team
 * @copyright	Copyright (c) 2015, MeraEvents.
 * @Version		Version 1.0
 * @Since       Class available since Release Version 1.0 
 * @Created     18-08-2015
 * @Last Modified 18-08-2015
 */
require_once (APPPATH . 'handlers/handler.php');
require_once(APPPATH . 'handlers/file_handler.php');

class Organizer_handler extends Handler {

    var $ci;
    var $fileHandler;

    public function __construct() {
        parent::__construct();
        $this->ci = parent::$CI;
        $this->ci->load->model('Organizer_model');
        $this->userHandler = new User_handler();
        $this->fileHandler = new File_handler();
    }

	public function checkOrganizerAccess() {     
		$this->ci->customsession->loginCheck();      
		$userId = getUserId();
                $this->ci->Organizer_model->resetVariable();
        //Checking promoter code is already there or not
        $where[$this->ci->Organizer_model->userid] = $userId;
        $where[$this->ci->Organizer_model->deleted] = 0;        
        $this->ci->Organizer_model->setWhere($where);
        $result = $this->ci->Organizer_model->getCount();
		if ($result != false && $result > 0) {
            $output = parent::createResponse(TRUE,'',STATUS_OK,'','isOrganizer', 1);
        }
		else
		{
			$output = parent::createResponse(TRUE,'',STATUS_OK,'','isOrganizer', 0);
		}
        return $output;
		
    }
 public function getCompanyDetails($inputArray) {

        parent::$CI->form_validation->pass_array($inputArray);
        parent::$CI->form_validation->set_rules('userId', 'User Id', 'required_strict|is_natural_no_zero');
        if (parent::$CI->form_validation->run() === FALSE) {
            $errorMessages = $this->ci->form_validation->get_errors();
            $output['status'] = FALSE;
            $output['response']['messages'] = $errorMessages['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $this->ci->Organizer_model->resetVariable();
        $selectInput['id'] = $this->ci->Organizer_model->id;
        $selectInput['userid'] = $this->ci->Organizer_model->userid;
        $selectInput['companyname'] = $this->ci->Organizer_model->companyname;
        $selectInput['designation'] = $this->ci->Organizer_model->designation;
        $selectInput['description'] = $this->ci->Organizer_model->description;
        $selectInput['countryid'] = $this->ci->Organizer_model->countryid;
        $selectInput['stateid'] = $this->ci->Organizer_model->stateid;
        $selectInput['cityid'] = $this->ci->Organizer_model->cityid;
        $selectInput['pincode'] = $this->ci->Organizer_model->pincode;
        $selectInput['phone'] = $this->ci->Organizer_model->phone;
        $selectInput['email'] = $this->ci->Organizer_model->email;
        $selectInput['url'] = $this->ci->Organizer_model->url;
        $selectInput['logofileid'] = $this->ci->Organizer_model->logofileid;
        $selectInput['facebooklink'] = $this->ci->Organizer_model->facebooklink;
        $selectInput['twitterlink'] = $this->ci->Organizer_model->twitterlink;
        $selectInput['googlepluslink'] = $this->ci->Organizer_model->googlepluslink;
        $selectInput['linkedinlink'] = $this->ci->Organizer_model->linkedinlink;
        $this->ci->Organizer_model->setSelect($selectInput);
        $where[$this->ci->Organizer_model->userid] = $inputArray['userId'];
        $this->ci->Organizer_model->setWhere($where);
        $companyData = $this->ci->Organizer_model->get();
        if ($companyData) {
            $companyDetails = $companyData['0'];
            $fileId = $companyDetails['logofileid'];
            $comanyFileData = array('id', $fileId);
            $fileData = $this->fileHandler->getFileData($comanyFileData);
            if ($fileData['status'] && $fileData['response']['total'] > 0) {
                $fileData = commonHelperGetIdArray($fileData['response']['fileData']);
                $companyDetails['logoPath'] = $this->ci->config->item('images_content_cloud_path') . $fileData[$companyDetails['logofileid']]['path'];
            } else {
                $companyDetails['logoPath'] = "";
            }

            if ($companyDetails) {
                $output['status'] = TRUE;
                $output['response']['companyDetails'] = $companyDetails;
                $output['statusCode'] = STATUS_OK;
                $output['response']['total'] = 1;
                return $output;
            } else {
                $output['status'] = FALSE;
                $output["response"]["messages"][] = ERROR_NO_DATA;
                $output['statusCode'] = STATUS_OK;
                $output['response']['total'] = 0;
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
    
    public function get($inputArray) {
        $this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('userId', 'User Id', 'required_strict|is_natural_no_zero');
        if ($this->ci->form_validation->run() === FALSE) {
            $errorMessages = $this->ci->form_validation->get_errors();
            $output['status'] = FALSE;
            $output['response']['messages'] = $errorMessages['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $this->ci->Organizer_model->resetVariable();
        $selectInput['id'] = $this->ci->Organizer_model->id;
        $selectInput['userid'] = $this->ci->Organizer_model->userid;
        $selectInput['designation'] = $this->ci->Organizer_model->designation;
        $this->ci->Organizer_model->setSelect($selectInput);
        $where[$this->ci->Organizer_model->userid] = $inputArray['userId'];
        $this->ci->Organizer_model->setWhere($where);
        $this->ci->Organizer_model->setRecords(1);
        $companyData = $this->ci->Organizer_model->get();
        if ($companyData) {
            $output['status'] = TRUE;
            $output['response']['organizerDetails'] = $companyData[0];
            $output['statusCode'] = STATUS_OK;
            $output['response']['total'] = 1;
            return $output;
        } else {
            $output['status'] = FALSE;
            $output["response"]["messages"][] = ERROR_NO_DATA;
            $output['statusCode'] = STATUS_OK;
            $output['response']['total'] = 0;
            return $output;
        }
        
    }
    public function insertCompanyDetails($inputArray) { 
        $this->ci->form_validation->pass_array($inputArray);
        if ($this->ci->form_validation->run('companyDetails') === FALSE) {
            $error_messages = $this->ci->form_validation->get_errors('message');
            $output = parent::createResponse(FALSE, $error_messages, STATUS_BAD_REQUEST);
            return $output;
        }
        $this->ci->Organizer_model->resetVariable();
        $image = $_FILES['picture']['name'];
        if ($image) {
            $companyLogoId = $this->imageInsert($inputArray);
            if ($companyLogoId['status'] === FALSE) {
                return $companyLogoId;
            }
            $companyFileId = $companyLogoId['response']['imageFileId'];
            $companyFilePath = $companyLogoId['response']['imageFilePath'];
            $companyInfo['logofileid'] = $companyFileId;
        }
        $companyInfo['userid'] = $inputArray['userId'];
        $companyInfo['companyname'] = $inputArray['companyname'];
        $companyInfo['designation'] = $inputArray['designation'];
        $companyInfo['description'] = $inputArray['description'];
        $companyInfo['countryid'] = $inputArray['countryid'];
        $companyInfo['stateid'] = $inputArray['stateid'];
        $companyInfo['cityid'] = $inputArray['cityid'];
        $companyInfo['phone'] = $inputArray['phone'];
        $companyInfo['email'] = $inputArray['email'];
        $companyInfo['url'] = $inputArray['url'];
        $this->ci->Organizer_model->setInsertUpdateData($companyInfo);
        $response = $this->ci->Organizer_model->insert_data();
        if ($response) {
            $output['status'] = TRUE;
            $output["response"]["messages"][] = UPDATE_COMPANY_DETAILS;
            $output['statusCode'] = STATUS_CREATED;
            return $output;
        }
        $output['status'] = FALSE;
        $output["response"]["messages"][] = SOMETHING_WENT_WRONG;
        $output['statusCode'] = STATUS_SERVER_ERROR;
        return $output;
    }

    public function updateCompanyDetails($inputArray) { 
        $this->ci->form_validation->reset_form_rules();
        parent::$CI->form_validation->pass_array($inputArray);
        parent::$CI->form_validation->set_rules('userId', 'User Id', 'required_strict|is_natural_no_zero');
        parent::$CI->form_validation->set_rules('companyname', 'companyname', 'required_strict');
        if($inputArray['countryId'] == '' || $inputArray['countryId'] == 0){
            parent::$CI->form_validation->set_rules('countryId', 'Locality', 'required_strict|is_natural_no_zero');
        }
        //parent::$CI->form_validation->set_rules('countryId', 'Country Id', 'required_strict|is_natural_no_zero');
        //parent::$CI->form_validation->set_rules('stateId', 'State Id', 'required_strict|is_natural_no_zero');
        //parent::$CI->form_validation->set_rules('cityId', 'City Id', 'required_strict|is_natural_no_zero');
        parent::$CI->form_validation->set_rules('email', 'Email', 'enail|valid_email');
        parent::$CI->form_validation->set_rules('url', 'url');
        parent::$CI->form_validation->set_rules('phone', 'phone', 'numeric|max_length[10]');
        if (parent::$CI->form_validation->run() === FALSE) {
            $errorMessages = $this->ci->form_validation->get_errors();
            $output['status'] = FALSE;
            $output['response']['messages'] = $errorMessages['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $this->ci->Organizer_model->resetVariable();
        $image = $_FILES['picture']['name'];
        if ($image) {
            $companyLogoId = $this->imageInsert($inputArray);
            if ($companyLogoId['status'] === FALSE) {
                return $companyLogoId;
            }
            $companyFileId = $companyLogoId['response']['imageFileId'];
            $companyFilePath = $companyLogoId['response']['imageFilePath'];
            $companyInfo['logofileid'] = $companyFileId;
        }
        $companyInfo['companyname'] = $inputArray['companyname'];
        $companyInfo['designation'] = $inputArray['designation'];
        $companyInfo['description'] = $inputArray['description'];
        $companyInfo['countryid'] = $inputArray['countryId'];
        $companyInfo['stateid'] = $inputArray['stateId'];
        $companyInfo['cityid'] = $inputArray['cityId'];
        $companyInfo['phone'] = $inputArray['phone'];
        $companyInfo['email'] = $inputArray['email'];
        $companyInfo['url'] = $inputArray['url'];
        $where['id'] = $inputArray['id'];
        $where['userid'] = $inputArray['userId'];
        $this->ci->Organizer_model->setInsertUpdateData($companyInfo);
        $this->ci->Organizer_model->setWhere($where);
        $response = $this->ci->Organizer_model->update_data();
        if ($response) {
            $output['status'] = TRUE;
            $output["response"]["messages"][] = UPDATE_COMPANY_DETAILS;
            $output['statusCode'] = STATUS_UPDATED;
            return $output;
        }
        $output['status'] = FALSE;
        $output["response"]["messages"][] = SOMETHING_WENT_WRONG;
        $output['statusCode'] = STATUS_SERVER_ERROR;
        return $output;
    }
      public function ImageInsert($inputArray) {
        $imageFileConfig['fieldName'] = 'picture';     
        /* validating the uploaded file is image ot not */
        $filename = $_FILES[$imageFileConfig['fieldName']]['name'];
        $fileExtension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        $extensionArray = explode('|',IMAGE_EXTENTIONS);
        if(!in_array($fileExtension,$extensionArray)) {
            $output['status'] = FALSE;
            $output["response"]["messages"][] = ERROR_INVALID_IMAGE;
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }   
        $imagePath = $this->ci->config->item('user_profile_path') . $inputArray['userId'];
        $imageFileConfig['upload_path'] = $this->ci->config->item('file_upload_path') . $imagePath;
        $imageFileConfig['allowed_types'] = IMAGE_EXTENTIONS;
        $imageFileConfig['dbFilePath'] = $imagePath . "/";
        $imageFileConfig['dbFileType'] = FILE_TYPE_USERPROFILE;
        $imageFileConfig['folderId'] = $inputArray['userId'];
        $imageResponse = $this->fileHandler->doUpload($imageFileConfig);
        if ($imageResponse['status'] === FALSE) {
            return $imageResponse;
        }
        $output['response']['imageFileId'] = $imageResponse['response']['fileId'];
        $output['response']['imageFilePath'] = $imageResponse['response']['filePath'];
        $output['status'] = TRUE;
        $output['statusCode'] = STATUS_CREATED;
        return $output;
    }
       public function updateOrgCompanyDetails($inputArray) {
        $this->ci->form_validation->reset_form_rules();
        parent::$CI->form_validation->pass_array($inputArray);
        parent::$CI->form_validation->set_rules('userId', 'User Id', 'required_strict|is_natural_no_zero');
        if (parent::$CI->form_validation->run() === FALSE) {
            $errorMessages = $this->ci->form_validation->get_errors();
            $output['status'] = FALSE;
            $output['response']['messages'] = $errorMessages['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $this->ci->Organizer_model->resetVariable();
        if(isset($inputArray['companyname'])){
        $companyInfo['companyname'] = $inputArray['companyname'];
        }
         if(isset($inputArray['designation'])){
             $companyInfo['designation'] = $inputArray['designation'];  
         }
       if(isset($inputArray['facebooklink'])){
             $companyInfo['facebooklink'] = $inputArray['facebooklink'];
         }
        if(isset($inputArray['twitterlink'])){
              $companyInfo['twitterlink'] = $inputArray['twitterlink'];
         }
       if(isset($inputArray['googlepluslink'])){
             $companyInfo['googlepluslink'] = $inputArray['googlepluslink'];
         }
        if(isset($inputArray['linkedinlink'])){
             $companyInfo['linkedinlink'] = $inputArray['linkedinlink'];
         }      
        $where['userid'] = $inputArray['userId'];
        $where['id'] = $inputArray['id'];
        $this->ci->Organizer_model->setInsertUpdateData($companyInfo);
        $this->ci->Organizer_model->setWhere($where);
        $response = $this->ci->Organizer_model->update_data();
        if ($response) {
            $output['status'] = TRUE;
            $output["response"]["messages"][] = UPDATE_COMPANY_DETAILS;
            $output['statusCode'] = STATUS_OK;
            return $output;
        }
        $output['status'] = FALSE;
        $output["response"]["messages"][] = SOMETHING_WENT_WRONG;
        $output['statusCode'] = STATUS_SERVER_ERROR;
        return $output;
    }
      public function insertOrgCompanyDetails($inputArray) {
       $this->ci->form_validation->reset_form_rules();
        parent::$CI->form_validation->pass_array($inputArray);
        parent::$CI->form_validation->set_rules('userId', 'User Id', 'required_strict|is_natural_no_zero');
        if (parent::$CI->form_validation->run() === FALSE) {
            $errorMessages = $this->ci->form_validation->get_errors();
            $output['status'] = FALSE;
            $output['response']['messages'] = $errorMessages['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $this->ci->Organizer_model->resetVariable();
        $companyInfo['companyname'] = $inputArray['companyname'];
        $companyInfo['designation'] = $inputArray['designation'];
        $companyInfo['facebooklink'] = $inputArray['facebooklink'];
        $companyInfo['twitterlink'] = $inputArray['twitterlink'];
        $companyInfo['googlepluslink'] = $inputArray['googlepluslink'];
        $companyInfo['linkedinlink'] = $inputArray['linkedinlink'];
        $this->ci->Organizer_model->setInsertUpdateData($companyInfo);
        $response = $this->ci->Organizer_model->insert_data();
        if ($response) {
            $output['status'] = TRUE;
            $output["response"]["messages"][] = UPDATE_COMPANY_DETAILS;
            $output['statusCode'] = STATUS_OK;
            return $output;
        }
        $output['status'] = FALSE;
        $output["response"]["messages"][] = SOMETHING_WENT_WRONG;
        $output['statusCode'] = STATUS_SERVER_ERROR;
        return $output;
    }
    
    
    /*
     *  user have created events, then changing the organizer table
     */

    public function changeOrganizerStatus() {
        $orgStatus = $this->ci->customsession->getData('isOrganizer');
        if ($orgStatus === 0) {

            $organizerData = array();
            $this->ci->Organizer_model->resetVariable();
            $organizerData['userid'] = $this->ci->customsession->getUserId();

            $this->ci->Organizer_model->setInsertUpdateData($organizerData);
            $response = $this->ci->Organizer_model->insert_data();
            //change the session status
            $this->ci->customsession->setData('isOrganizer', 1);
            $output['status'] = TRUE;
            $output['response']['affectedRows'] = $response;
            $output["response"]["messages"] = array();
            $output['statusCode'] = STATUS_CREATED;
            return $output;
        }
    }
    
    public function getOrganizerContactData($inputArray) {
        parent::$CI->form_validation->pass_array($inputArray);
        parent::$CI->form_validation->set_rules('userids', 'User Ids', 'required_strict|is_array');
        if (parent::$CI->form_validation->run() === FALSE) {
            $errorMessages = $this->ci->form_validation->get_errors();
            $output['status'] = FALSE;
            $output['response']['messages'] = $errorMessages['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $this->ci->Organizer_model->resetVariable();
        $selectInput['userid'] = $this->ci->Organizer_model->userid;
        $selectInput['id'] = $this->ci->Organizer_model->id;
        $selectInput['email'] = $this->ci->Organizer_model->email;
        $this->ci->Organizer_model->setSelect($selectInput);
        $where_in[$this->ci->Organizer_model->userid] = $inputArray['userids'];
        $where[$this->ci->Organizer_model->deleted] = 0;
        $this->ci->Organizer_model->setWhereIns($where_in);
        $this->ci->Organizer_model->setWhere($where);
        $contactData = $this->ci->Organizer_model->get();
        //echo $this->ci->db->last_query();exit;
        if ($contactData) {
                $output['status'] = TRUE;
                $output['response']['cotactDetails'] = $contactData;
                $output['statusCode'] = STATUS_OK;
                $output['response']['total'] = count($contactData);
                return $output;
            } else {
                $output['status'] = FALSE;
                $output["response"]["messages"][] = ERROR_NO_DATA;
                $output['statusCode'] = STATUS_OK;
                $output['response']['total'] = 0;
                return $output;
            }
    }

}

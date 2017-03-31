<?php

/**
 * alerts related business logic will be defined in this class
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
require_once(APPPATH . 'handlers/file_handler.php');

class Oauth_clients_handler extends Handler {

    var $ci;

    public function __construct() {
        parent::__construct();
        $this->ci = parent::$CI;
        $this->ci->load->model('Oauth_clients_model');
    }

    public function insertAppDetails($inputs) {
        $validationStatus = $this->insertAppDetailsValidation($inputs);
        if ($validationStatus['error'] == TRUE) {
            $output['status'] = FALSE;
            $output['response']['messages'] = $validationStatus['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $inputs['client_id'] = random_strings_for_client();
//        do {
//
//            $inputs['client_id'] = random_strings_for_client();
//        } while ($this->getTokenDetails($inputs));
        //20 digits random string  

        $inputs['client_secret'] = commonHelpergenerateRandomString(20);
        $inputs['user_id'] = $this->ci->customsession->getUserId(); //user id
        $fileId = "";
        if (isset($_FILES['appImage'])) {
            $fileResponse = $this->appImageUpload($inputs);
            $fileId = $fileResponse['response']['fileId'];
        }
        $inputs['app_image_id'] = $fileId;

        $insertOauthArray = array();
        $this->ci->Oauth_clients_model->resetVariable();
        $insertOauthArray[$this->ci->Oauth_clients_model->client_id] = $inputs['client_id'];
        $insertOauthArray[$this->ci->Oauth_clients_model->client_secret] = $inputs['client_secret'];
        $insertOauthArray[$this->ci->Oauth_clients_model->created_date] = onlyCurrentDate();
        $insertOauthArray[$this->ci->Oauth_clients_model->redirect_uri] = $inputs['callbackUrl'];
        $insertOauthArray[$this->ci->Oauth_clients_model->user_id] = $inputs['user_id'];
        $insertOauthArray[$this->ci->Oauth_clients_model->app_name] = $inputs['appName'];
        if($fileId > 0){
            $insertOauthArray[$this->ci->Oauth_clients_model->app_image] = $inputs['app_image_id'];
        }
        $insertOauthArray[$this->ci->Oauth_clients_model->access_level] = $inputs['access_level'];
        $this->ci->Oauth_clients_model->setInsertUpdateData($insertOauthArray);
        $othId = $this->ci->Oauth_clients_model->insert_data();
        $output['status'] = TRUE;
        $output['response']['othid'] = $othId;
        $output['response']['messages'] = array();
        $output['statusCode'] = STATUS_UPDATED;
        return $output;
    }

    public function updateAppDetails($inputs) {
        $validationStatus = $this->insertAppDetailsValidation($inputs);
        if ($validationStatus['error'] == TRUE) {
            $output['status'] = FALSE;
            $output['response']['messages'] = $validationStatus['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $updateOauthArray[$this->ci->Oauth_clients_model->redirect_uri] = $inputs['callbackUrl'];
        $updateOauthArray[$this->ci->Oauth_clients_model->app_name] = $inputs['appName'];
        $fileId = "";
        if (isset($_FILES['appImage'])) {
            $fileResponse = $this->appImageUpload($inputs);
            $fileId = $fileResponse['response']['fileId'];
        }
        if($fileId > 0){
            $updateOauthArray[$this->ci->Oauth_clients_model->app_image] =$fileId;
        }
        $updateOauthArray[$this->ci->Oauth_clients_model->access_level] = $inputs['access_level'];
        $this->ci->Oauth_clients_model->setInsertUpdateData($updateOauthArray);
        $where[$this->ci->Oauth_clients_model->id] = $inputs['id'];

        $this->ci->Oauth_clients_model->setWhere($where);
        $updateId = $this->ci->Oauth_clients_model->update_data();
        $output['status'] = TRUE;
        $output['response']['updatedId'] = $updateId;
        $output['response']['messages'] = array();
        $output['statusCode'] = STATUS_UPDATED;
        return $output;
    }

    //validate the input data
    public function insertAppDetailsValidation($inputs) {
        $errorMessages = array();
        $this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($inputs);
        $this->ci->form_validation->set_rules('appName', 'App Name', 'required_strict');

        if ($this->ci->form_validation->run() === FALSE) {
            $errorMessages = $this->ci->form_validation->get_errors();
            return $errorMessages;
        }

        $errorMessages['error'] = FALSE;
        return $errorMessages;
    }

    public function getClientAppDetails($inputArray) {
        parent::$CI->form_validation->pass_array($inputArray);
        if (isset($inputArray['app_id'])) {
            parent::$CI->form_validation->set_rules('app_id', 'App Id', 'required_strict');
        } else {
            parent::$CI->form_validation->set_rules('user_id', 'User Id', 'required_strict');
        }
        if (parent::$CI->form_validation->run() === FALSE) {
            $errorMessages = $this->ci->form_validation->get_errors();
            $output['status'] = FALSE;
            $output['response']['messages'] = $errorMessages['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $this->ci->Oauth_clients_model->resetVariable();
        $selectInput['id'] = $this->ci->Oauth_clients_model->id;
        $selectInput['client_id'] = $this->ci->Oauth_clients_model->client_id;
        $selectInput['client_secret'] = $this->ci->Oauth_clients_model->client_secret;
        $selectInput['redirect_uri'] = $this->ci->Oauth_clients_model->redirect_uri;
        $selectInput['user_id'] = $this->ci->Oauth_clients_model->user_id;
        $selectInput['app_name'] = $this->ci->Oauth_clients_model->app_name;
        $selectInput['app_image'] = $this->ci->Oauth_clients_model->app_image;
        $selectInput['access_level'] = $this->ci->Oauth_clients_model->access_level;
        $this->ci->Oauth_clients_model->setSelect($selectInput);

        if (isset($inputArray['app_id'])) {
            $where[$this->ci->Oauth_clients_model->id] = $inputArray['app_id'];
            $where[$this->ci->Oauth_clients_model->user_id] = $this->ci->customsession->getUserId();
        } else {
            $where[$this->ci->Oauth_clients_model->user_id] = $inputArray['user_id'];
        }

        $this->ci->Oauth_clients_model->setWhere($where);

        $clientDetails = $this->ci->Oauth_clients_model->get();
        if ($clientDetails) {
            $output['status'] = TRUE;
            $output['response']['total'] = count($clientDetails);
            $output['response']['appDetails'] = $clientDetails;
            $output['statusCode'] = STATUS_OK;
            return $output;
        }
        $output['status'] = TRUE;
        $output['response']['total'] = 0;
        $output["response"]["messages"][] = ERROR_NO_DATA;
        $output['statusCode'] = STATUS_OK;
        return $output;
    }

    public function appImageUpload($inputArray) {
        $fileHandler = new File_handler();
        $appFileConfig['fieldName'] = "appImage";
        $appPath = $this->ci->config->item('app_images_path') . $inputArray['client_id'];
        $appFileConfig['upload_path'] = $this->ci->config->item('file_upload_path') . $appPath;
        $appFileConfig['allowed_types'] = IMAGE_EXTENTIONS;
        $appFileConfig['dbFilePath'] = $appPath . "/";
        $appFileConfig['dbFileType'] = FILE_TYPE_APPIMAGE;
        $appFileConfig['folderId'] = $inputArray['client_id'];
        $appFileConfig['fileName'] = $_FILES['appImage']["name"];
        $appFileConfig['sourcePath'] = $_FILES['appImage']["tmp_name"];
        $appFileConfig['imageResize'] = FALSE;
        return $fileHandler->doUpload($appFileConfig);
    }

}

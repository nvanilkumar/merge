<?php

/**
 * All images path details will be fetched from this class
 *
 * @package		CodeIgniter
 * @author		Qison  Dev Team
 * @copyright	Copyright (c) 2015, MeraEvents.
 * @Version		Version 1.0
 * @Since       Class available since Release Version 1.0 
 * @Created     11-06-2015
 * @Last Modified 11-06-2015
 */
require_once(APPPATH . 'handlers/handler.php');
//require_once APPPATH . 'libraries/aws-sdk-for-php/sdk.class.php';

class File_handler extends Handler {

    var $ci;

    public function __construct() {
        parent::__construct();
        $this->ci = parent::$CI;
        $this->ci->load->model('File_model');
    }

    public function getFileData($input) {
        if (count($input) == 0) {
            $data['status'] = FALSE;
            $data['response']['messages'][] = ERROR_INVALID_INPUT;
            $data['statusCode'] = STATUS_INVALID_INPUTS;
            return $data;
        }
        $selectData['id'] = $this->ci->File_model->id;
        $selectData['path'] = $this->ci->File_model->filePath;
        $this->ci->File_model->setSelect($selectData);
        $where[$this->ci->File_model->deleted] = 0;
        //print_r($input);
        $this->ci->File_model->setWhereIn($input);
        $this->ci->File_model->setWhere($where);
        $fileDataArray = $this->ci->File_model->get();
        if (count($fileDataArray) == 0) {
            $output['status'] = TRUE;
            $output['response']['messages'][] = ERROR_NO_IMAGE;
            $output['response']['total'] = 0;
            $output['statusCode'] = STATUS_OK;
            return $output;
        }

        $fileDataOutput = array();
        foreach ($fileDataArray as $fileObj) {

            $fileDataOutput[] = (array) $fileObj;
        }


        $output['status'] = TRUE;
        $output['response']['messages'] = array();
        $output['response']['total'] = count($fileDataOutput);
        $output['response']['fileData'] = $fileDataOutput;
        $output['statusCode'] = STATUS_OK;
        return $output;
    }

    public function getData($inputArray) {
        $this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('fileids', 'fileids', 'required_strict|is_array');
        //$this->ci->form_validation->set_rules('destinationpath', 'destinationpath', 'required_strict');
        //$this->ci->form_validation->set_rules('filename', 'filename', 'required_strict');
        //$this->ci->form_validation->set_rules('filetype', 'filetype', 'required_strict');
        if ($this->ci->form_validation->run() == FALSE) {
            $response = $this->ci->form_validation->get_errors();
            $output['status'] = FALSE;
            $output['response']['messages'] = $response['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $fileIds = $inputArray['fileids'];
		$this->ci->File_model->resetVariable();
        $selectData['id'] = $this->ci->File_model->id;
        $selectData['path'] = $this->ci->File_model->filePath;
        $this->ci->File_model->setSelect($selectData);
        $where[$this->ci->File_model->deleted] = 0;
        $whereIns['id'] = $fileIds;
        $this->ci->File_model->setWhereIns($whereIns);
        $this->ci->File_model->setWhere($where);
        $fileDataArray = $this->ci->File_model->get();
        //echo $this->ci->db->last_query();exit;
        if (count($fileDataArray) == 0) {
            $output['status'] = TRUE;
            $output['response']['messages'][] = ERROR_NO_IMAGE;
            $output['response']['total'] = 0;
            $output['statusCode'] = STATUS_OK;
            return $output;
        }

        $fileDataOutput = array();
        foreach ($fileDataArray as $fileObj) {

            $fileDataOutput[] = (array) $fileObj;
        }


        $output['status'] = TRUE;
        $output['response']['messages'] = array();
        $output['response']['total'] = count($fileDataOutput);
        $output['response']['fileData'] = $fileDataOutput;
        $output['statusCode'] = STATUS_OK;
        return $output;
    }

    public function uploadToS3($inputArray) {
        $this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('sourcepath', 'sourcepath', 'required_strict');
        $this->ci->form_validation->set_rules('destinationpath', 'destinationpath', 'required_strict');
        $this->ci->form_validation->set_rules('filename', 'filename', 'required_strict');
        $this->ci->form_validation->set_rules('filetype', 'filetype', 'required_strict');
        if ($this->ci->form_validation->run() == FALSE) {
            $response = $this->ci->form_validation->get_errors();
            $output['status'] = FALSE;
            $output['response']['messages'] = $response['message'];
            $output['statusCode'] = STATUS_BAD_REQUESTs;
            return $output;
        }
        $fileName = $inputArray['filename'];
        $sourcepath = $inputArray['sourcepath'];
        $destinationPath = $inputArray['destinationpath'];
        $fileType = $inputArray['filetype'];
        $awsAccessKey = $this->ci->config->item('awsAccessKey');
        $awsSecretKey = $this->ci->config->item('awsSecretKey');
        require_once(APPPATH . 'libraries/S3.php');
        $s3 = new S3($awsAccessKey, $awsSecretKey);
        $bucketName = $this->ci->config->item('bucketName');
        if (!is_null($fileName)) {
            $uri = 'content/' . $destinationPath . '/' . $fileName;
            $upStatus = $s3->putObjectFile($sourcepath, $bucketName, $uri, S3::ACL_PUBLIC_READ, array(), $fileType);
            if ($upStatus) {
                //To unlink image in local after creating in cloud                   
                if(isset($inputArray['unlink'])&&$inputArray['unlink']==TRUE){
                    unlink($sourcepath);
                }
                $data['status'] = TRUE;
                $data['response']['messages'][] = SUCCESS_FILE_UPLOAD;
                $data['response']['uploadPath'] = $this->ci->config->item('images_cloud_path') . $uri;
                $data['statusCode'] = STATUS_OK;
                return $data;
            } else {
                $data['status'] = FALSE;
                $data['response']['messages'][] = ERROR_FILE_UPLOAD;
                $data['statusCode'] = STATUS_SERVER_ERROR;
                return $data;
            }
        } else {
            $data['status'] = FALSE;
            $data['response']['messages'][] = ERROR_INVALID_INPUT;
            $data['statusCode'] = STATUS_INVALID_INPUTS;
            return $data;
        }
    }

    /**
     * To Upload the files and insert into file table 
     * @param type $config
     *          $config['fieldName'] = 'eventBanner';//html file element name
     *          $config['upload_path'] =FCPATH.$path;
      $config['allowed_types'] ='jpg|jpeg|gif|png';
      $config['dbFilePath']--db stored path
      $config['dbFileType']--file type('userprofile','banner',
     *                      'thumbnail','countrythumb','categorythumb')
     * 
     * @return type
     */
    function doUpload($config) {
        $this->ci->load->library('upload');
        //checking the folder name and not exist creating it
        //$folderName = $config['upload_path'];
//        if (!file_exists($folderName)) {
//            mkdir($folderName, 0755, true);
//        }

        //append timestamp to file name
        if (isset($config['fileName']) && ((isset($config['multiUpload']) && $config['multiUpload'] == TRUE) || (isset($config['imageResize']) && $config['imageResize'] == TRUE))) {
            $config['file_name'] = appendTimeStamp($config['fileName']);
        } elseif(isset($config['delegateFileName']) && $config['delegateFileName'] != '') {
            $config['file_name'] = appendTimeStamp($_FILES[$config['delegateFileName']]['name']);
        } else {
            $config['file_name'] = appendTimeStamp($_FILES[$config['fieldName']]['name']);
        }


        $this->ci->upload->initialize($config);


//        if (!$this->ci->upload->do_upload($config['fieldName'])) {
//            // file upload failed
//
//            $output['status'] = FALSE;
//            $output['response']['messages'][] = $config['fieldName'] . "-->" . $this->ci->upload->display_errors();
//            $output['statusCode'] = 406;
//            return $output;
//        }
        if (!isset($config['folderId'])) {
            $output['status'] = FALSE;
            $output['response']['messages'][] = ERROR_FOLDER_ID;
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        //for s3 upload
        $folderId = $config['folderId'];
        if (isset($config['sourcePath']) && ((isset($config['multiUpload']) && $config['multiUpload'] == TRUE) || (isset($config['imageResize']) && $config['imageResize'] == TRUE))) {
            $inputArray['sourcepath'] = $config['sourcePath'];
        } elseif(isset($config['delegateFileName']) && $config['delegateFileName'] != '') {
            $inputArray['sourcepath'] = $_FILES[$config['delegateFileName']]['tmp_name'];
        }else {
            $inputArray['sourcepath'] = $_FILES[$config['fieldName']]['tmp_name'];
        }
        $inputArray['destinationpath'] = $this->ci->config->item('s3_' . $config['fieldName'] . '_path') . $folderId;
        $inputArray['filename'] = $config['file_name'];
        $inputArray['filetype'] = $this->getFileType($config['file_name']);
        $saveToS3Response = $this->uploadToS3($inputArray);
        if ($saveToS3Response['status']) {
            //   success,return file ID
            $fileUploadData = $this->ci->upload->data();
            $fileInsertArray['filePath'] = $config['dbFilePath'] . $fileUploadData['file_name'];
//            $fileInsertArray['filePath'] = $saveToS3Response['response']['uploadPath'];
            $fileInsertArray['fileType'] = $config['dbFileType'];
            $insertFileId = $this->fileInsert($fileInsertArray);
            $output['status'] = TRUE;
            $output['response']['fileId'] = $insertFileId;
            $output['response']['filePath'] = $fileInsertArray['filePath'];
            $output['response']['messages'] = array();
            $output['statusCode'] = STATUS_CREATED;
        }
        return $output;
    }

    //Used to save images of source http and https
    public function save($data) {
        $this->ci->form_validation->pass_array($data);
        $this->ci->form_validation->set_rules('type', 'type', 'required_strict|is_valid_type[file]');
        $this->ci->form_validation->set_rules('source', 'source', 'required_strict');
        //$this->ci->form_validation->set_rules('keyWord', 'KeyWord', 'keyWordRule');
        //$this->ci->form_validation->set_rules('limit', 'Limit', 'is_natural_no_zero');
        if ($this->ci->form_validation->run() == FALSE) {
            $response = $this->ci->form_validation->get_errors();
            $output['status'] = FALSE;
            $output['response']['messages'] = $response['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $fileType = $data['type'];
        $eventIdReqired = array('thumbImage', 'bannerImage');
        $userIdReqired = array('userprofile');
        if (in_array($fileType, $eventIdReqired)) {
            $this->ci->form_validation->pass_array($data);
            $this->ci->form_validation->set_rules('eventId', 'eventId', 'required_strict');
        }
        if ($this->ci->form_validation->run() == FALSE) {
            $response = $this->ci->form_validation->get_errors();
            $output['status'] = FALSE;
            $output['response']['messages'] = $response['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $eventId = isset($data['eventId']) ? $data['eventId'] : '';
        $userId = isset($data['userid']) ? $data['userid'] : '';
        $src_path = $data['source'];
        $output = array('status' => FALSE, 'statusCode' => 500);
        $folderId = $eventId;
        //changing path based on file type
        $folderName = $this->ci->config->item('file_upload_temp_path');            
        $path_array = explode('/', $src_path);
        $fileName = appendTimeStamp($path_array[count($path_array) - 1]);
        if ($fileType == 'thumbImage') {
            $insertPath = 'eventlogo';
        } elseif ($fileType == 'bannerImage') {
            $insertPath = 'eventbanner';
        } elseif ($fileType == 'userprofile') {
            $insertPath = 'logos';
            $fileName = appendTimeStamp($userId . '.jpg');
            $folderId = $userId;
        }
        //create new directory if not available
//        $statusDir = true;
//        if (!file_exists($folderName)) {
//            $statusDir = mkdir($folderName);
//        }
//        if (!$statusDir) {
//            $output['messages'][] = ERROR_SOMETHING_WENT_WRONG;
//            return $output;
//        }
        //preparing destination path
        $dst_path=$this->ci->config->item('file_upload_temp_path').$fileName;
        $result=strpos($src_path,'https');      
        if ($result === false) {
            //reading source contents
            $contents = url_get_contents($src_path);
            if (!$contents) {
                $output['messages'][] = ERROR_SOMETHING_WENT_WRONG;
                return $output;
            }
            //copying content to destination
            $status = file_put_contents($dst_path, $contents);
        } else {
            $saveResponse = $this->save_https_image($src_path, $dst_path);
            $status = $saveResponse['status'];
        }
        if (!$status) {
            $output['messages'][] = ERROR_SOMETHING_WENT_WRONG;
            return $output;
        }
        $inputArray['sourcepath'] = $dst_path;
        $inputArray['destinationpath'] = $this->ci->config->item('s3_' . $fileType . '_path') . $folderId;
        $inputArray['filename'] = $fileName;
        $inputArray['filetype'] = $this->getFileType($fileName);
        //To unlink image in local after creating in cloud        
        $inputArray['unlink'] = TRUE;
        $saveToS3Response = $this->uploadToS3($inputArray);
        //var_dump($saveToS3Response);
        if ($saveToS3Response['status']) {
            //saving to database
            $fileInsertArray['filePath'] = $insertPath . '/' . $folderId . '/' . $fileName;
            $fileInsertArray['fileType'] = $fileType;
            $insertFileId = $this->fileInsert($fileInsertArray);
            $output['status'] = TRUE;
            $output['response']['fileId'] = $insertFileId;
            $output['response']['filePath'] = $fileInsertArray['filePath'];
            $output['response']['messages'] = array();
            $output['statusCode'] = STATUS_CREATED;
            return $output;
        } else {
            return $saveToS3Response;
        }
    }

    /**
     * To Insert the file  details
     * @param $inputs array[ filePath, fileType]
     * @filetype:('userprofile','banner','thumbnail','countrythumb','categorythumb')
     * @param type $inputs
     * @return int
     */
    public function fileInsert($inputs) {
        $validationStatus = $this->fileInsertValidation($inputs);
        if ($validationStatus['error'] == TRUE) {
            $output['status'] = FALSE;
            $output['response']['messages'] = $validationStatus['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }

        $insertFileArray = array();
        $insertFileArray[$this->ci->File_model->filePath] = $inputs['filePath'];
        $insertFileArray[$this->ci->File_model->fileType] = $inputs['fileType'];
        $insertFileArray[$this->ci->File_model->deleted] = 0;
        $this->ci->File_model->setInsertUpdateData($insertFileArray);
        return $this->ci->File_model->insert_data();
    }

    /**
     * validate the filinsert related data
     * 
     * @param type $inputs
     * @return boolean
     */
    public function fileInsertValidation($inputs) {
        $errorMessages = array();

        $this->ci->form_validation->pass_array($inputs);
        $this->ci->form_validation->set_rules('filePath', 'File Path', 'required_strict');
        $this->ci->form_validation->set_rules('fileType', 'File Type', 'required_strict');

        if ($this->ci->form_validation->run() === FALSE) {
            $errorMessages = $this->ci->form_validation->get_errors();
            return $errorMessages;
        }
        $errorMessages['error'] = FALSE;
        return $errorMessages;
    }

    function save_https_image($url, $saveto) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
        $raw = curl_exec($ch);
        if (!$raw) {
            $output['status'] = FALSE;
            $output['response']['messages'] = curl_error($ch);
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        curl_close($ch);
        $fp = fopen($saveto, 'x');
        fwrite($fp, $raw);
        fclose($fp);
        $output['status'] = TRUE;
        $output['response']['messages'] = array();
        $output['statusCode'] = STATUS_OK;
        return $output;
    }

    private function getFileType($fileName) {
        $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        switch ($ext) {
            case 'jpg':
            case 'jpeg':
                $contentType = "image/jpeg";
                break;
            case 'png':
                $contentType = "image/png";
                break;
            case 'gif':
                $contentType = "image/gif";
                break;
            case 'js':
                $contentType = "text/javascript";
                break;
            case 'css':
                $contentType = "text/css";
                break;
            case 'csv':
                $contentType = "text/csv";
                break;
            default:$contentType = '';
                break;
        }
        return $contentType;
    }
    /* guest listbooking upload csv file */
    public function uploadGuestListFile($eventId){
        $userId = $this->ci->customsession->getUserId();
        $currentTime = strtotime("now");
        $imagePath = $this->ci->config->item('file_upload_temp_path');
        $config['upload_path'] = $imagePath;
        $config['allowed_types'] = 'text/csv|csv| application/vnd.ms-excel';
        $config['max_size'] = '1000';
        $config['max_width'] = '1024';
        $config['max_height'] = '768';
        $config['file_name'] = 'GuestList_'.$userId._.$eventId._.$currentTime.".csv";
        $this->ci->load->library('upload', $config);
        $fileupload = $this->ci->upload->do_upload('csvfile');
        if (!$fileupload) {
            $output['status'] = FALSE;
            $output['response']['messages'][] = ERROR_GUEST_FILE_UPLOAD;
            $output['statusCode'] = STATUS_INVALID_INPUTS;
            return $output;
        }
        $upload = array('upload_data' => $this->ci->upload->data());
        /* // uplosd csv flie to s3
         *  //   add config path in config.php $config['s3_guestlist_path'] = 'guestlist';
        $inputArray['sourcepath'] =$imagePath.$config['file_name'];
        $inputArray['destinationpath'] = $this->ci->config->item('s3_guestlist_path');
        $inputArray['filename'] = $config['file_name'];
        $inputArray['filetype'] = $this->getFileType($config['file_name']);
        $saveToS3Response = $this->uploadToS3($inputArray);
        if($saveToS3Response['status'] == TRUE){
            $csvFilePath=$saveToS3Response['response']['uploadPath'];
        }else{
            return $saveToS3Response;
        }
         * */
        /* upload  file reading  */
        $this->ci->load->helper('file');
        $string = read_file($imagePath . $config['file_name']);       
        if(!$string){
            $output['status'] = FALSE;
            $output['response']['messages'] = 'The filename ' .  $config['file_name'] . ' is not readable';
            $output['statusCode'] = STATUS_INVALID_INPUTS;
            return $output;
        }
        $this->ci->load->library('excel');
       // $objPHPExcel = PHPExcel_IOFactory::load($csvFilePath);
       $objPHPExcel = PHPExcel_IOFactory::load($imagePath . $config['file_name']);
        $cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();
        $header = $arr_data = $guestdata = array();
        foreach ($cell_collection as $cell) {
            $column = $objPHPExcel->getActiveSheet()->getCell($cell)->getColumn();
            $row = $objPHPExcel->getActiveSheet()->getCell($cell)->getRow();
            $data_value = $objPHPExcel->getActiveSheet()->getCell($cell)->getValue();
            if ($row == 1) {
                $header[$column] = $data_value;
            } else {
                $arr_data[$row][$column] = $data_value;
            }
        }
        foreach ($arr_data as $key => $value) {
            foreach ($value as $k => $v) {
                $guestdata[$key][$header[$k]] = $v;
            }
        }
        if(count($guestdata) > 0){
                $output['status'] = TRUE;
                $output['response']['guestUserData'] = $guestdata;
                $output['response']['total'] = count($guestdata);
                $output['statusCode'] = STATUS_OK;
                return $output;
        }else{
                $output['status'] = TRUE;
                $output['response']['messages'][] = ERROR_NO_GUEST_DATA;
                $output['statusCode'] = STATUS_OK;
                $output['response']['total'] = 0;
                return $output;
        }
    }
}

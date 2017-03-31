<?php

/**
 * Event Gallery Data will be defined in this class
 * Getting Banners Related data
 * @package		CodeIgniter
 * @author		Qison  Dev Team
 * @param		eventId - required
 * @copyright	Copyright (c) 2015, MeraEvents.
 * @Version		Version 1.0
 * @Since       Class available since Release Version 1.0
 * @Created     21-07-2015
 * @Last Modified 21-06-2015
 */
require_once (APPPATH . 'handlers/handler.php');
require_once (APPPATH . 'handlers/file_handler.php');

class Gallery_handler extends Handler {

    var $ci;
    var $galleryHandler;
    var $fileHandler;

    public function __construct() {
        parent::__construct();
        $this->ci = parent::$CI;
        $this->ci->load->library('form_validation');
        $this->ci->load->model('Gallery_model');
        $this->fileHandler = new File_handler();
    }

    public function getEventGalleryList($request) {
        $output = array();
        if (!isset($request['eventId'])) {
            $output['status'] = FALSE;
            $output['response']['messages'][] = ERROR_INVALID_INPUT;
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($request);
        $this->ci->form_validation->set_rules('eventId', 'EventId', 'is_natural_no_zero|required_strict');
        if (!empty($request) && $this->ci->form_validation->run() == FALSE) {
            $validationStatus = $this->ci->form_validation->get_errors();
            $output['status'] = FALSE;
            $output['response']['messages'] = $validationStatus['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $selectInput['id'] = $this->ci->Gallery_model->id;
        $selectInput['imagefileid'] = $this->ci->Gallery_model->imagefileid;
        $selectInput['eventId'] = $this->ci->Gallery_model->eventid;
        $selectInput['thumbnailfileid'] = $this->ci->Gallery_model->thumbnailfileid;
        $selectInput['order'] = $this->ci->Gallery_model->order;
        $this->ci->Gallery_model->setSelect($selectInput);
        //fetching Galley Images & not deleted
        $where[$this->ci->Gallery_model->deleted] = 0;
        $where[$this->ci->Gallery_model->eventid] = $request['eventId'];
        $this->ci->Gallery_model->setWhere($where);
        //Order by array
        $orderBy = array();
        $orderBy[] = $this->ci->Gallery_model->order;
        $this->ci->Gallery_model->setOrderBy($orderBy);
        $tempGalleryDetails = $this->ci->Gallery_model->get();
            
        if (is_array($tempGalleryDetails) && count($tempGalleryDetails) > 0) {
            $galleryImageIdArray = array();
            foreach ($tempGalleryDetails as $gallerykey => $gallery) {
                $galleryImageIdArray[] = $gallery['imagefileid'];
                $galleryImageIdArray[] = $gallery['thumbnailfileid'];
                $tempGalleryDetails[$gallerykey]['imageId'] = $gallery['imagefileid'];
                $tempGalleryDetails[$gallerykey]['thumbnailId'] = $gallery['thumbnailfileid'];
                unset($tempGalleryDetails[$gallerykey]['thumbnailfileid']);
                unset($tempGalleryDetails[$gallerykey]['imagefileid']);
            }
            // getting file path for Image and thumbnail from file table
            $fileData = $this->fileHandler->getFileData(array('id', $galleryImageIdArray));
            $fileDataTemp = array();
            if ($fileData['status'] && $fileData['response']['total'] > 0) {
                $fileDataTemp = commonHelperGetIdArray($fileData['response']['fileData']);
            }
            $response = array();
            foreach ($tempGalleryDetails as $gallerykey => $gallery) {
                $imagepath = $fileDataTemp[$gallery['imageId']]['path'];
                $thumbnailpath = $fileDataTemp[$gallery['thumbnailId']]['path'];
                if (isset($imagepath)) {
                    $imagepath = $this->ci->config->item('images_content_cloud_path') . $imagepath;
                } else {
                    $imagepath = '';
                }
                $tempGalleryDetails[$gallerykey]['imagePath'] = $imagepath;
                if (isset($thumbnailpath)) {
                    $thumbnailpath = $this->ci->config->item('images_content_cloud_path') . $thumbnailpath;
                } else {
                    $thumbnailpath = '';
                }
                $tempGalleryDetails[$gallerykey]['thumbnailPath'] = $thumbnailpath;
            }
            $output['status'] = TRUE;
            $output['response']['galleryList'] = $tempGalleryDetails;
            $output['response']['total'] = count($tempGalleryDetails);
            $output['statusCode'] = STATUS_OK;
            return $output;
        }elseif(count($tempGalleryDetails)==0) {		
               $output['status'] = TRUE;		
               $output['response']['messages'][] = ERROR_NO_DATA;		
               $output['response']['total'] = 0;                    		
               $output['statusCode'] = STATUS_OK;		
               return $output;		
        }

        $output['status'] = FALSE;
        $output['response']['messages'][] = ERROR_INTERNAL_DB_ERROR;
        $output['statusCode'] = STATUS_SERVER_ERROR;
        return $output;
    }
    
     public function insertGallery($inputArray) {
        //Inserting into the cloud and inserting in the file table
        $imagesCount = count($_FILES['eventGallery']['name']);
        for ($key = 0; $key < $imagesCount; $key++) {//Looping for multiple files insertion
            $inputArray['key'] = $key;
            $output = $this->insertIntoGalleryCloud($inputArray);
            if ($output['status'] == FALSE) {
                return $output;
            }            
            $inputArray['imageFileId'] = $output['response']['imageFileId'];
            $inputArray['thumbnailFileId'] = $output['response']['thumbnailImageFileId'];
            $validationStatus = $this->galleryInsertValidation($inputArray);
            if ($validationStatus['error'] == TRUE) {
                $output['status'] = FALSE;
                $output['response']['messages'] = $validationStatus['message'];
                $output['statusCode'] = STATUS_BAD_REQUEST;
                return $output;
            }

            //Inserting into the gallery table
            $insertArray = array();
            $insertArray[$this->ci->Gallery_model->eventid] = $inputArray['eventId'];
            $insertArray[$this->ci->Gallery_model->imagefileid] = $inputArray['imageFileId'];
            $insertArray[$this->ci->Gallery_model->thumbnailfileid] = $inputArray['thumbnailFileId'];
            $insertArray[$this->ci->Gallery_model->status] = 1;
            $this->ci->Gallery_model->setInsertUpdateData($insertArray);
            $galleryInsertion= $this->ci->Gallery_model->insert_data();
            if(!$galleryInsertion){
                $galleryInserted=FALSE;
            }else{
                $galleryInserted=TRUE;
            }            
        }
        return $galleryInserted;
    }

    public function galleryInsertValidation($inputs) {
        $errorMessages = array();
        $this->ci->form_validation->pass_array($inputs);
        $this->ci->form_validation->set_rules('imageFileId', 'Image file id', 'required_strict|is_natural_no_zero');
        $this->ci->form_validation->set_rules('thumbnailFileId', 'Thumbnail file id', 'required_strict|is_natural_no_zero');
        $this->ci->form_validation->set_rules('eventId', 'event Id', 'required_strict|is_natural_no_zero');

        if ($this->ci->form_validation->run() === FALSE) {
            $errorMessages = $this->ci->form_validation->get_errors();
            return $errorMessages;
        }
        $errorMessages['error'] = FALSE;
        return $errorMessages;
    }

    public function insertIntoGalleryCloud($inputArray) {
        //Inserting file into cloud 
        $imagePath = $this->ci->config->item('eventGallery__path') . $inputArray['eventId'];
        $imageFileConfig['allowed_types'] = IMAGE_EXTENTIONS;
        $imageFileConfig['dbFilePath'] = $imagePath . "/";
        $imageFileConfig['dbFileType'] = FILE_TYPE_EVENT_GALLERY;
        $imageFileConfig['folderId'] = $inputArray['eventId'];
        $imageFileConfig['fieldName'] = 'eventGallery';
        $imageFileConfig['multiUpload'] = TRUE;
        $imageFileConfig['fileName'] = $_FILES['eventGallery']['name'][$inputArray['key']];
        $imageFileConfig['sourcePath'] = $_FILES['eventGallery']['tmp_name'][$inputArray['key']];

        $imageResponse = $this->fileHandler->doUpload($imageFileConfig);
        if ($imageResponse['status'] === FALSE) {
            return $imageResponse;
        } elseif ($imageResponse['status'] === TRUE) {
            $output['response']['imageFileId'] = $imageResponse['response']['fileId'];
            $output['response']['imageFilePath'] = $imageResponse['response']['filePath'];

            //Getting the thumbnail image with the 260x180px from the center of the image
            $fileExtention = getExtension($_FILES['eventGallery']['name'][$inputArray['key']]);
            $srcImageResponse = $this->createImage($_FILES['eventGallery']['tmp_name'][$inputArray['key']], $fileExtention);           
            
            if ($srcImageResponse['status'] == FALSE) {
                return $srcImageResponse;
            }
            $srcImage = $srcImageResponse['response']['image'];

            //Finding start and end points where to start craping
            $srcWidth = imagesx($srcImage);
            $srcHeight = imagesy($srcImage);
            $thumbnailWidth = 260;
            $thumbnailHeight = 180;
            //Create new image with the 260 and 180 dimentions
            $destImage = imagecreatetruecolor($thumbnailWidth, $thumbnailHeight);
            //Resizing the image into the new image acc to given dimentions
            imagecopyresampled($destImage, $srcImage, 0, 0, 0, 0, $thumbnailWidth, $thumbnailHeight, $srcWidth,$srcHeight);                      
            $thumbnailName = preg_replace('/(\.gif|\.jpeg|\.jpg|\.png)/', '_thumb$1', $_FILES['eventGallery']['name'][$inputArray['key']]);
            $sourcePath=$imageFileConfig['sourcePath'] = $this->ci->config->item('file_upload_temp_path').$thumbnailName;             
            $createImageResponse = $this->createImageType($destImage, $sourcePath,$fileExtention);

            if (!$createImageResponse['status']) {
                return $createImageResponse;
            }
            $imageFileConfig['fileName'] = $thumbnailName;
            $imageFileConfig['dbFileType'] = FILE_TYPE_EVENT_GALLERY_THUMBNAIL;
            $thumbnailResponse = $this->fileHandler->doUpload($imageFileConfig);
            unlink($sourcePath);//Deleting the thumbnail file generated in the temp folder
            if ($thumbnailResponse['status'] === FALSE) {
                return $thumbnailResponse;
            } elseif ($thumbnailResponse['status'] === TRUE) {
                $output['response']['thumbnailImageFileId'] = $thumbnailResponse['response']['fileId'];
                $output['response']['thumbnailImageFilePath'] = $thumbnailResponse['response']['filePath'];
            }
            $output['status'] = TRUE;
            $output['statusCode'] = STATUS_CREATED;
            return $output;
        }
    }
    
    public function createImage($filePath, $type) {
        $errorMessage = true;
        $typeArr = array('gif','jpeg','jpg','png');
        if(!in_array($type, $typeArr)){
        	$errorMessage = FALSE;
        	$message = ERROR_FILETYPE;
        }else{
        	$message = ERROR_FILEUPLOADING;
	        switch ($type) {
	            case 'gif' :
	                $image = imagecreatefromgif($filePath);
	        			if($image === false){
				    		$errorMessage = FALSE;
						}
	                break;
	            case 'jpeg' :
	                $image = imagecreatefromjpeg($filePath);
	      			  if($image === false){
				    		$errorMessage = FALSE;
						}
	                break;
	            case 'jpg' :
	                $image = imagecreatefromjpeg($filePath);
	       			 if($image === false){
				    		$errorMessage = FALSE;
						}
	                break;
	            case 'png' :
	                $image = imagecreatefrompng($filePath);
	              if($image === false){
				    		$errorMessage = FALSE;
						}
	                break;
	            default :
	               break;
	        }
        }
        if (!$errorMessage) {
            $output['status'] = FALSE;
            $output['response']['messages'][] = $message;
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        } else {
            $output['status'] = TRUE;
            $output['response']['image'] = $image;
            $output['statusCode'] = STATUS_OK;
            return $output;
        }
    }

    public function createImageType($destImage, $sourcePath,$type) {
        $errorMessage = FALSE;
        switch ($type) {
            case 'gif' :
                $imageStaus = imagegif($destImage,$sourcePath);
                break;
            case 'jpeg' :
                $imageStaus = imagejpeg($destImage,$sourcePath);
                break;
            case 'jpg' :
                $imageStaus = imagejpeg($destImage,$sourcePath);
                break;
            case 'png' :
                $imageStaus = imagepng($destImage,$sourcePath);
                break;
            default :
                $errorMessage = TRUE;
        }
        if ($errorMessage) {
            $output['status'] = FALSE;
            $output['response']['messages'][] = "Only jpg|jpeg|gif|png are allowed";
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        } else {
            $output['status'] = TRUE;
            $output['response']['imageStaus'] = $imageStaus;
            $output['statusCode'] = STATUS_OK;
            return $output;
        }
    }
    
    public function deleteGallery($imageFileId){
        $updateArray[$this->ci->Gallery_model->deleted]=1;
        $where[$this->ci->Gallery_model->imagefileid]=$imageFileId;
        $this->ci->Gallery_model->setWhere($where);
        $this->ci->Gallery_model->setInsertUpdateData($updateArray);
        $response=$this->ci->Gallery_model->update_data();
        if($response){
            $output['status'] = TRUE;
            $output['response']['messages'][] = "Successfully deleted the record";
            $output['statusCode'] = STATUS_UPDATED;
            return $output;
        } else {
            $output['status'] = FALSE;
            $output['response']['messages'][] = ERROR_SOMETHING_WENT_WRONG;
            $output['statusCode'] = STATUS_SERVER_ERROR;
            return $output;
        }       
}
}
?>
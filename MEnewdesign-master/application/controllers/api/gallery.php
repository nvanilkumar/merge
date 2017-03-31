<?php

/**
 * Maintaing Gallery related data
 * @package		CodeIgniter
 * @author		Qison  Dev Team
 * @param		eventId - required
 * 
 */
/*
 * Place includes, constant defines and $_GLOBAL settings here.
 */

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require(APPPATH . 'libraries/REST_Controller.php');
require(APPPATH . 'handlers/gallery_handler.php');

class Gallery extends REST_Controller {

    var $galleryHandler;

    public function __construct() {
        parent::__construct();
        $this->galleryHandler = new gallery_handler();
    }

    public function list_get() {
        $inputArray = $this->get();
        $galleryList = $this->galleryHandler->getEventGalleryList($inputArray);
        $resultArray = array('response' => $galleryList['response']);
        $this->response($resultArray, $galleryList['statusCode']);
    }

}
